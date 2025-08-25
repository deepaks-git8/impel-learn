<?php
namespace App\Service\Payment;

class PaypalPaymentGateway implements PaymentGatewayInterface
{
    public function charge(string $customerId, float $amount, string $currency): string
    {
        return 'txn_paypal_' . uniqid();
    }

    public function refund(string $transactionId, float $amount): bool
    {
        return true;
    }

    public function getStatus(string $transactionId): string
    {
        return 'pending';
    }
}
