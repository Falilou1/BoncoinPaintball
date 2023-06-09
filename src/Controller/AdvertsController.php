<?php

namespace App\Controller;

use App\Entity\Adverts;
use App\Form\AdvertsType;
use App\Repository\AdvertsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adverts")
 */
class AdvertsController extends AbstractController
{
    /**
     * @Route("/", name="app_adverts_index", methods={"GET"})
     */
    public function index(AdvertsRepository $advertsRepository): Response
    {
        return $this->render('adverts/index.html.twig', [
            'adverts' => $advertsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_adverts_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AdvertsRepository $advertsRepository): Response
    {
        $advert = new Adverts();
        $form = $this->createForm(AdvertsType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advertsRepository->add($advert, true);

            return $this->redirectToRoute('app_adverts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adverts/new.html.twig', [
            'advert' => $advert,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_adverts_show", methods={"GET"})
     */
    public function show(Adverts $advert): Response
    {
        return $this->render('adverts/show.html.twig', [
            'advert' => $advert,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_adverts_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Adverts $advert, AdvertsRepository $advertsRepository): Response
    {
        $form = $this->createForm(AdvertsType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advertsRepository->add($advert, true);

            return $this->redirectToRoute('app_adverts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adverts/edit.html.twig', [
            'advert' => $advert,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_adverts_delete", methods={"POST"})
     */
    public function delete(Request $request, Adverts $advert, AdvertsRepository $advertsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advert->getId(), $request->request->get('_token'))) {
            $advertsRepository->remove($advert, true);
        }

        return $this->redirectToRoute('app_adverts_index', [], Response::HTTP_SEE_OTHER);
    }
}
