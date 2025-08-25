<?php
namespace App\Controller;

use App\Service\Payment\PaymentGatewayInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    private PaymentGatewayInterface $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(): Response
    {
        $transactionId = $this->paymentGateway->charge('cust_001', 99.99, 'USD');

            return new Response("Payment successful! Transaction ID: " . $transactionId);
    }
}
