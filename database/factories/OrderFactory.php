<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Application;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $application = Application::factory()->create();

        return [
            'order_uid' => (string) Str::uuid(),
            'application_id' => $application->getKey(),
            'user_id' => $application->user_id,
            'idempotency_key' => hash('sha256', (string) Str::uuid()),
            'amount_paise' => 50000,
            'currency' => 'INR',
            'gateway' => 'mock',
            'status' => OrderStatus::Created,
        ];
    }
}
