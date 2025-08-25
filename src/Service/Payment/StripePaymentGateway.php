<?php
namespace App\Service\Payment;

class StripePaymentGateway implements PaymentGatewayInterface
{
    public function charge(string $customerId, float $amount, string $currency): string
    {
        // Simulate calling Stripe API
        return 'txn_stripe_' . uniqid();
    }

    public function refund(string $transactionId, float $amount): bool
    {
        // Simulate refund
        return true;
    }

    public function getStatus(string $transactionId): string
    {
        return 'completed';
    }
}
