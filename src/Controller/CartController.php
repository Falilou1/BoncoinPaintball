<?php

namespace App\Controller;

use App\Entity\Adverts;
use App\Repository\AdvertsRepository;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;





/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, AdvertsRepository $advertsRepository): Response
    {
        $cart = $session->get('cart', []);
        // Initialisation du panier
        $dataCart = [];
        $total = 0;
        // Parcours du panier et calcul du total
        foreach ($cart as $id => $quantity) {
            $product = $advertsRepository->find($id);

            $dataCart[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }
        return $this->render('cart/index.html.twig', compact('dataCart', 'total'));
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(Adverts $advert, SessionInterface $session, Request $request): Response
    {
        // $session->set('cart.'.$id, $session->get('cart.'.$id, 0) + 1);
        // On recupere le panier actuel
        $cart = $session->get('cart', []);
        $id = $advert->getId();

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        // On stocke le nouveau panier dans la session
        $session->set('cart', $cart);

        // Récupérer la route actuelle et rediriger vers celle-ci (reste sur la même page)
        $referer = $request->headers->get('referer');
        $this->addFlash('message', 'L\'élément a bien été ajouté à votre panier.');
        return $this->redirect($referer);
    }
    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Adverts  $advert, SessionInterface $session)
    {

        // On recupere le panier actuel
        $cart = $session->get('cart', []);
        $id = $advert->getId();

        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        // On stocke le nouveau panier dans la session
        $session->set('cart', $cart);

        return $this->redirect('/cart');
    }
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Adverts  $advert, SessionInterface $session)
    {

        // On recupere le panier actuel
        $cart = $session->get('cart', []);
        $id = $advert->getId();

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        // On stocke le nouveau panier dans la session
        $session->set('cart', $cart);

        return $this->redirect('/cart');
    }
    /**
     * @Route("/delete", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {


        $session->remove('cart');


        return $this->redirect('/cart');
    }
}
