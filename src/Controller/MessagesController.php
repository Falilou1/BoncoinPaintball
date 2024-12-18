<?php

namespace App\Controller;

use App\Entity\Adverts;
use App\Entity\Messages;
use App\Form\MessagesType;
use App\Repository\AdvertsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function index(): Response
    {
       
       
       
        return $this->render('messages/index.html.twig', [
            
        ]);
    }


    /**
     * @Route("/send/{id}", name="send")
     */
    public function send(Request  $request, AdvertsRepository $advertsRepository, int $id): Response
    {
        if (!$this->getUser()) {
            throw $this->createAccessDeniedException("Vous devez être connecté pour envoyer un message.");
        }

        $advert = $advertsRepository->find($id);

        if (!$advert) {
            throw $this->createNotFoundException('L\'annonce n\'existe plus.');
        }
        $message = new Messages();
        $message->setAdverts($advert);
        $message->setRecipient($advert->getOwner());
        
        $form = $this->createForm(MessagesType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message->setSender($this->getUser());
            

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message envoyé avec succès.");
            return $this->redirectToRoute("messages");
        }

        return $this->render('messages/send.html.twig', [
            "form" => $form->createView(),
            "advert" => $advert
        ]);
    }

    /**
     * @Route("/received", name="received")
     
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }
*/
     /**
     * @Route("/sent", name="sent")
     
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig');
    }
*/
    /**
     * @Route("/read/{id}", name="read")
     */
    public function read(Messages $message): Response
    {
        $message->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('messages/read.html.twig', compact("message"));
    }

 /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Messages $message): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("messages");
    }














}
