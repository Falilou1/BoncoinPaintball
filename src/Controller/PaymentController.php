<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    /**
     * @Route("/stripe", name="app_stripe_")
     */
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }

    /**
     * @Route("/create-checkout-session/{id}", name="app_stripe_create_checkout_session")
     */
    public function createCheckoutSession(int $id, OrdersRepository $ordersRepository): Response
    {
        // Récupérer la commande
        $order = $ordersRepository->find($id);

        if (!$order) {
            throw $this->createNotFoundException('La commande n\'existe pas.');
        }
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);


        $lineItems = [];
        foreach ($order->getOrdersDetails() as $detail) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $detail->getAdverts()->getTitle(),
                    ],
                    'unit_amount' => $detail->getPrice() * 100, // Stripe attend un montant en centimes
                ],
                'quantity' => $detail->getQuantity(),
            ];
        }
        // Créer la Session Stripe
        $checkoutSession = \Stripe\Checkout\Session::create([
            'customer_email' => $this->getUser()->getUserIdentifier(),
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_stripe_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_stripe_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($checkoutSession->url);
    }

    /**
     * @Route("/success", name="app_stripe_success")
     */
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    /**
     * @Route("/cancel", name="app_stripe_cancel")
     */
    public function cancel(): Response
    {
        return $this->render('payment/index.html.twig');
    }
}
