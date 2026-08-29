<?php

declare(strict_types=1);

namespace App\Domain\Identity\SecondFactor\Totp;

use App\Enums\AuthFactor;
use App\Models\TwoFactorMethod;
use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use PragmaRX\Google2FA\Google2FA;

/**
 * Starts TOTP enrolment (DR-022).
 *
 * The secret is generated and stored unconfirmed. It becomes usable only once
 * ConfirmTotp sees a code derived from it, which proves the authenticator
 * actually holds it -- enrolling on the strength of a displayed QR code alone
 * locks out every user whose scan silently failed.
 *
 * pragmarx/google2fa and bacon/bacon-qr-code are reached only from this
 * namespace, and tests/Unit/Arch/ArchitectureTest asserts it.
 */
final class EnrolTotp
{
    public function __construct(private readonly Google2FA $google2fa) {}

    public function handle(User $user): TwoFactorMethod
    {
        $secret = $this->google2fa->generateSecretKey(32);

        /** @var TwoFactorMethod $method */
        $method = $user->twoFactorMethods()->updateOrCreate(
            ['type' => AuthFactor::Totp],
            ['secret' => $secret, 'confirmed_at' => null],
        );

        return $method;
    }

    /**
     * The provisioning URI, for an authenticator that cannot scan.
     */
    public function provisioningUri(User $user, TwoFactorMethod $method): string
    {
        return $this->google2fa->getQRCodeUrl(
            (string) config('app.name'),
            $user->email,
            (string) $method->secret,
        );
    }

    /**
     * Rendered locally as an inline SVG rather than fetched from a chart
     * service: sending the secret to a third party to have it drawn would
     * hand the second factor to that third party.
     */
    public function qrCodeSvg(User $user, TwoFactorMethod $method): string
    {
        $writer = new Writer(new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd));

        return $writer->writeString($this->provisioningUri($user, $method));
    }
}
