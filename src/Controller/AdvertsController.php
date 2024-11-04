<?php

namespace App\Controller;

use App\Entity\Adverts;
use App\Entity\Images;
use App\Form\AdvertsType;
use App\Repository\AdvertsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/adverts", name="adverts_")
 */
class AdvertsController extends AbstractController
{
    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list(Request $request,PaginatorInterface $paginator, AdvertsRepository $advertsRepository): Response
    {
        $data = $advertsRepository->findAll();
        
        $adverts = $paginator->paginate($data, $request->query->getInt('page', 1), 9);


        return $this->render('adverts/index.html.twig', [
            'adverts' => $adverts
        ]);
    }

   
    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, AdvertsRepository $advertsRepository, EntityManagerInterface $entityManager): Response
    {
        $advert = new Adverts();
        $advert->setStatus('En cours');
        $advert->setCreatedAt(new DateTime());
        $advert->setUpdatedAt(new DateTime());


        $form = $this->createForm(AdvertsType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $advert->setOwner($user);

            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fichier);
            };

            $img = new Images();
            $img->setName($fichier);
            $advert->addImage($img);

            $entityManager->persist($advert);
            $entityManager->persist($advert);
            $entityManager->flush();
            // $advertsRepository->add($advert, true);

            return $this->redirectToRoute('adverts_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adverts/new.html.twig', [
            'advert' => $advert,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Adverts $advert): Response
    {
        return $this->render('adverts/show.html.twig', [
            'advert' => $advert,
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function advertSearch(): Response
    {
        return $this->render('adverts/index.html.twig', []);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Adverts $advert): Response
    {
        $form = $this->createForm(AdvertsType::class, $advert);
        $form->handleRequest($request);

      


        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On stocke l'image dans la base de données (son nom)
                $img = new Images();
                $img->setName($fichier);
                $advert->addImage($img);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_profile');
        }



        return $this->render('adverts/edit.html.twig', [
            'advert' => $advert,
            'form' => $form->createView(),
        ]);
    }



   



   /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Adverts $advert,  EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advert->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($advert);
            $entityManager->flush();
        }

        return $this->redirectToRoute("adverts_list");
    }


   



    /**
     * @Route("/delete/image/{id}", name="delete_image", methods={"DELETE"})
     */
    public function deleteImage(Images $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            $nom = $image->getName();
            unlink($this->getParameter('images_directory') . '/' . $nom);

            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalid'], 400);
        }
    }



 /**
     * @Route("/favoris/add/{id}", name="add_favoris")
     */
    public function addFavoris(Adverts $advert): Response
    {
        if(!$advert){
            throw new NotFoundHttpException('annonce indisponible');
        }
        $advert->addFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();
        return $this->redirectToRoute('adverts_list');
    }

/**
     * @Route("/favoris/remove/{id}", name="remove_favoris")
     */
    public function removeFavoris(Adverts $advert)
    {
        if(!$advert){
            throw new NotFoundHttpException('annonce indisponible');
        }
        $advert->removeFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();
        return $this->redirectToRoute('adverts_list');
    }



}
