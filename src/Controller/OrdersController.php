<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Repository\AdvertsRepository;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/orders", name="app_orders_")
 */
class OrdersController extends AbstractController
{
    /**
     * @Route("/add", name="add")
     */
    public function add(SessionInterface $session,Request $request, AdvertsRepository $advertsRepository, EntityManagerInterface $em): Response
    {

        $panier = $session->get('cart', []);
       
        if (empty($panier)){
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('home_show');
        }

        //Le panier n'est pas vide, on cree la commande
        $order = new Orders();

        //On recupere le proprietaire de la commande
        if ($this->getUser()) {
            $order->setUsers($this->getUser());
        } else {
            $guestEmail = $request->request->get('guest_email');
            if (!$guestEmail || !filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
                $this->addFlash('error', 'Veuillez fournir une adresse email valide.');
                return $this->redirectToRoute('cart_index');
            }
            $order->setGuestEmail($guestEmail);
        }

        //On remplit la commande
        $order->setReference('REF'.uniqid());
        $order->setCreatedAt(new \DateTime());

        //On parcourt le panier pour creer les details de la commande
        foreach ($panier as $item => $quantity) {

            
            $orderDetails = new OrdersDetails();

            //On va chercher l'annonce
            $advert = $advertsRepository->find($item);
          
            $price = $advert->getPrice();

            //On cree les details de la commande
            $orderDetails->setQuantity($quantity);
            $orderDetails->setPrice($price);
            $orderDetails->setAdverts($advert);
           


            $order->addOrdersDetail($orderDetails);
           
        }
        
        //On persiste la commande detaillee
        $em->persist($order);
        $em->flush();

        //On vide le panier
        $session->remove('cart');


        return $this->redirectToRoute('app_orders_pay', ['id' => $order->getId()]);
    }

    /**
     * @Route("/order/{id}/pay", name="pay")
     */
    public function pay(Orders $order): Response
    {
        
        // Configuration de Stripe
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        // Préparer les articles pour Stripe
        $lineItems = [];
        foreach ($order->getOrdersDetails() as $details) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $details->getAdverts()->getTitle(),
                    ],
                    'unit_amount' => $details->getPrice() * 100, // Convertir en centimes
                ],
                'quantity' => $details->getQuantity(),
            ];
        }

        // Créer une session Stripe
        $checkoutSession = Session::create([
            'customer_email' => $order->getUsers() ? $order->getUsers()->getUserIdentifier() : $order->getGuestEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_orders_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_orders_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($checkoutSession->url);
    }

       /**
     * @Route("/success", name="success")
     */
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    /**
     * @Route("/cancel", name="cancel")
     */
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }

}
