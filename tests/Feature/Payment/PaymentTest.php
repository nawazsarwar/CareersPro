<?php

declare(strict_types=1);

use App\Domain\Payment\ComputeFee;
use App\Domain\Payment\CreateOrder;
use App\Domain\Payment\Gateways\MockGateway;
use App\Domain\Payment\HandleCallback;
use App\Domain\Payment\Money;
use App\Domain\Payment\ReconcileMisFile;
use App\Enums\OrderStatus;
use App\Models\Application;
use App\Models\FeeRule;
use App\Models\Order;
use App\Models\Profile;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\MasterDataSeeder::class);

    config(['payment.default_gateway' => 'mock']);
    MockGateway::reset();

    $this->application = Application::factory()->create();
    $this->application->post->advertisement->forceFill(['payment_gateway' => 'mock', 'default_fee' => 500])->save();
    $this->application->refresh();
});

// The double-deduction fix.

it('returns the same order when creation is repeated', function (): void {
    $first = app(CreateOrder::class)->handle($this->application);
    $second = app(CreateOrder::class)->handle($this->application);

    // CU-Chayan's users report being charged twice at deadline hours, when an
    // impatient candidate double-submits or a slow response prompts a retry.
    expect($second->getKey())->toBe($first->getKey())
        ->and(Order::query()->count())->toBe(1);
});

it('creates a distinct order for a genuinely new attempt', function (): void {
    $first = app(CreateOrder::class)->handle($this->application, attempt: 1);
    $second = app(CreateOrder::class)->handle($this->application, attempt: 2);

    expect($second->getKey())->not->toBe($first->getKey());
});

it('derives the idempotency key from user, post and attempt', function (): void {
    $key = app(CreateOrder::class)->idempotencyKey($this->application, 1);

    expect($key)->toBe(hash('sha256', sprintf(
        '%d|%d|%d',
        $this->application->user_id,
        $this->application->post_id,
        1,
    )));
});

// The callback is never trusted alone.

it('refuses a callback whose signature does not verify', function (): void {
    MockGateway::willRejectSignature();

    $order = app(CreateOrder::class)->handle($this->application);

    expect(fn () => app(HandleCallback::class)->handle($order, ['txn_id' => 'forged']))
        ->toThrow(RuntimeException::class, 'could not be verified');

    expect($order->refresh()->status)->toBe(OrderStatus::Created);
});

it('does not mark an order paid when the gateway says it failed', function (): void {
    // A valid signature over a claim the gateway itself does not support.
    MockGateway::willReport(OrderStatus::Failed);

    $order = app(CreateOrder::class)->handle($this->application);
    app(HandleCallback::class)->handle($order, ['txn_id' => 'T1']);

    expect($order->refresh()->status)->toBe(OrderStatus::Failed)
        ->and($this->application->refresh()->paid)->toBeFalse();
});

it('marks an order paid when the gateway confirms it', function (): void {
    $order = app(CreateOrder::class)->handle($this->application);
    app(HandleCallback::class)->handle($order, ['txn_id' => 'T1']);

    expect($order->refresh()->status)->toBe(OrderStatus::Paid)
        ->and($this->application->refresh()->paid)->toBeTrue()
        ->and(Transaction::query()->count())->toBe(1);
});

// double_payment is a status, not an exception path.

it('records a second settlement as a double payment', function (): void {
    $order = app(CreateOrder::class)->handle($this->application);

    app(HandleCallback::class)->handle($order, ['txn_id' => 'T1']);
    app(HandleCallback::class)->handle($order->refresh(), ['txn_id' => 'T2']);

    // Finance can filter, count and refund a status. An exception path is a
    // support ticket.
    expect($order->refresh()->status)->toBe(OrderStatus::DoublePayment)
        ->and($order->status->grantsAccess())->toBeTrue();
});

it('never stores card data', function (): void {
    $order = app(CreateOrder::class)->handle($this->application);
    app(HandleCallback::class)->handle($order, ['txn_id' => 'T1', 'card_number' => '4111111111111111']);

    $stored = (string) json_encode(Transaction::query()->first()?->gateway_payload);

    expect($stored)->not->toContain('4111')
        ->and($stored)->not->toContain('card_number');
});

it('never touches the application snapshot', function (): void {
    $order = app(CreateOrder::class)->handle($this->application);
    $before = $this->application->snapshots()->count();

    app(HandleCallback::class)->handle($order, ['txn_id' => 'T1']);

    // What was scored must not change because money moved.
    expect($this->application->refresh()->snapshots()->count())->toBe($before);
});

// The fee.

it('exempts a candidate with a benchmark disability certificate', function (): void {
    Profile::query()->updateOrCreate(
        ['user_id' => $this->application->user_id],
        ['is_pwd' => true, 'disability_certificate_authority' => 'CMO, Aligarh'],
    );

    $fee = app(ComputeFee::class)->for($this->application->user->refresh(), $this->application->post);

    expect($fee->isZero())->toBeTrue();
});

it('does not exempt a disability claim without a certificate', function (): void {
    // The relaxation depends on the document, and scrutiny verifies it later.
    Profile::query()->updateOrCreate(
        ['user_id' => $this->application->user_id],
        ['is_pwd' => true, 'disability_certificate_authority' => null],
    );

    expect(app(ComputeFee::class)->for($this->application->user->refresh(), $this->application->post)->isZero())
        ->toBeFalse();
});

it('refuses to create an order for a zero fee', function (): void {
    Profile::query()->updateOrCreate(
        ['user_id' => $this->application->user_id],
        ['is_pwd' => true, 'disability_certificate_authority' => 'CMO, Aligarh'],
    );

    // An order for zero would have to be settled and then reconciled against a
    // payment that never happens.
    expect(fn () => app(CreateOrder::class)->handle($this->application->refresh()))
        ->toThrow(RuntimeException::class, 'No fee is payable');
});

it('prefers a category fee rule over the advertisement default', function (): void {
    FeeRule::query()->create([
        'advertisement_id' => $this->application->advertisement_id,
        'category' => null,
        'amount_paise' => 20000,
    ]);

    expect(app(ComputeFee::class)->for($this->application->user, $this->application->post)->paise)
        ->toBe(20000);
});

it('holds money in paise as an integer', function (): void {
    // A fee schedule summed in floats drifts, and money out by a paisa over
    // 78,232 applications is money somebody reconciles by hand.
    expect(Money::rupees(500)->paise)->toBe(50000)
        ->and(Money::rupees(0.1)->paise)->toBe(10)
        ->and(fn () => new Money(-1))->toThrow(InvalidArgumentException::class);
});

// Reconciliation.

it('lets the gateway record win over local state', function (): void {
    $order = app(CreateOrder::class)->handle($this->application);

    // A dropped callback: the candidate paid and we never heard.
    $csv = "order,txn,status,amount\n{$order->order_uid},T9,paid,{$order->amount_paise}\n";
    $file = UploadedFile::fake()->createWithContent('mis.csv', $csv);

    $report = app(ReconcileMisFile::class)->handle($file, 'mock');

    expect($order->refresh()->status)->toBe(OrderStatus::Paid)
        ->and($this->application->refresh()->paid)->toBeTrue()
        ->and($report->rows_discrepant)->toBe(1);
});

it('keeps the disagreement rather than silently correcting it', function (): void {
    $order = app(CreateOrder::class)->handle($this->application);

    $csv = "order,txn,status,amount\n{$order->order_uid},T9,paid,{$order->amount_paise}\n";
    app(ReconcileMisFile::class)->handle(UploadedFile::fake()->createWithContent('mis.csv', $csv), 'mock');

    // A reconciliation that corrected local state without a record would
    // destroy the evidence that it was ever wrong.
    $row = App\Models\ReconciliationRowRecord::query()->firstOrFail();

    expect($row->outcome)->toBe('discrepant')
        ->and($row->local_status)->toBe('created')
        ->and($row->gateway_status)->toBe('paid')
        ->and($row->note)->toContain('Status differs');
});

it('records a payment against an order it does not recognise', function (): void {
    $csv = "order,txn,status,amount\nunknown-uid,T9,paid,50000\n";

    $report = app(ReconcileMisFile::class)->handle(
        UploadedFile::fake()->createWithContent('mis.csv', $csv),
        'mock',
    );

    // Never silently dropped: it is money that moved.
    expect(App\Models\ReconciliationRowRecord::query()->where('outcome', 'unknown_order')->count())->toBe(1)
        ->and($report->rows_discrepant)->toBe(1);
});

it('counts a matching row as matched and changes nothing', function (): void {
    $order = app(CreateOrder::class)->handle($this->application);
    app(HandleCallback::class)->handle($order, ['txn_id' => 'T1']);

    $csv = "order,txn,status,amount\n{$order->order_uid},T1,paid,{$order->amount_paise}\n";
    $report = app(ReconcileMisFile::class)->handle(UploadedFile::fake()->createWithContent('mis.csv', $csv), 'mock');

    expect($report->rows_matched)->toBe(1)
        ->and($report->rows_discrepant)->toBe(0);
});

// DR-018 — the domain never names a gateway.

it('keeps every vendor name inside the Gateways namespace', function (): void {
    $offenders = [];

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(app_path())) as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $path = str_replace(base_path().'/', '', $file->getPathname());

        if (str_contains($path, 'Payment/Gateways')) {
            continue;
        }

        if (preg_match('/razorpay|billdesk/i', (string) file_get_contents($file->getPathname()))) {
            $offenders[] = $path;
        }
    }

    expect($offenders)->toBe([]);
});

it('maps both gateway file formats to the same row shape', function (): void {
    // The two v1 providers publish different columns in different orders with
    // different status vocabularies. The reconciler reads one shape.
    $razorpay = app(App\Domain\Payment\Gateways\RazorpayGateway::class)->parseReconciliation(
        UploadedFile::fake()->createWithContent('rp.csv', "receipt,payment_id,status,amount\nA-1,pay_1,captured,500.00\n"),
    );

    $billdesk = app(App\Domain\Payment\Gateways\BilldeskGateway::class)->parseReconciliation(
        UploadedFile::fake()->createWithContent('bd.csv', "a,b,c,d,e\nX,A-1,txn_1,500.00,SUCCESS\n"),
    );

    expect($razorpay->first()->orderUid)->toBe('A-1')
        ->and($razorpay->first()->status)->toBe(OrderStatus::Paid)
        ->and($razorpay->first()->amountPaise)->toBe(50000)
        ->and($billdesk->first()->orderUid)->toBe('A-1')
        ->and($billdesk->first()->status)->toBe(OrderStatus::Paid)
        ->and($billdesk->first()->amountPaise)->toBe(50000);
});

it('treats an unreachable gateway as unknown, not failed', function (): void {
    $order = app(CreateOrder::class)->handle($this->application);
    $order->forceFill(['status' => OrderStatus::Initiated])->save();

    // Marking an order failed because the network was unavailable would refund
    // a candidate who paid.
    $result = app(App\Domain\Payment\Gateways\RazorpayGateway::class)->status($order);

    expect($result->status)->toBe(OrderStatus::Initiated);
});
