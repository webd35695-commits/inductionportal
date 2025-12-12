<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Str;

class OneLinkService
{
    public function initiate(Payment $payment): array
    {
        // TODO: Replace with real 1LINK request building using credentials from config/services.php
        $reference = 'OL-' . Str::upper(Str::random(10));
        // Simulate a redirect URL (in real case, build and sign request to 1LINK and get redirect)
        $redirectUrl = route('payments.mock.redirect', ['ref' => $reference, 'pid' => $payment->id]);

        return [
            'reference' => $reference,
            'redirect_url' => $redirectUrl,
        ];
    }

    public function verify(array $payload): array
    {
        // TODO: Implement verification with 1LINK callback payload/signature
        return [
            'status' => $payload['status'] ?? 'paid',
            'transaction_id' => $payload['txn_id'] ?? ('TXN-' . Str::upper(Str::random(12))),
            'reference_no' => $payload['ref'] ?? null,
            'meta' => $payload,
        ];
    }
}
