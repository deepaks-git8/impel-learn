<?php
namespace App\Service\Payment;

interface PaymentGatewayInterface
{
    /**
     * Charge a customer for a given amount.
     */
    public function charge(string $customerId, float $amount, string $currency): string;

    /**
     * Refund a charge.
     */
    public function refund(string $transactionId, float $amount): bool;

    /**
     * Check payment status.
     */
    public function getStatus(string $transactionId): string;
}
