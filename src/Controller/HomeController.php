<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Adverts;
use App\Repository\AdvertsRepository;

/**
    * @Route("/home", name="home_")
    */
class HomeController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(AdvertsRepository $advertRepository): Response
    {
        $lastAdverts = $advertRepository->findLastAdverts();
        return $this->render('home/index.html.twig', [
            'lastAdverts' => $lastAdverts,
            'categories' => Adverts::$CATEGORIES,
            'regions' => Adverts::$REGIONS,
            'brands' => Adverts::$BRANDS,
            'useCondition' => Adverts::$USECONDITIONS,
        ]);
    }
    /**
     * @Route("/show", name="show")
     */
    public function show(AdvertsRepository $advertRepository): Response
    {
        $adverts = [];
        if (!empty($_POST)) {
            $category = $_POST['categories'];
            $brand = $_POST['brands'];
            $description = $_POST['mot-cles'];
            $region = $_POST['region'];
            $useCondition = $_POST['etat'];
            $adverts = $advertRepository->findBySomeField($category, $brand, $description, $region, $useCondition);
        }
        return $this->render('adverts/list.html.twig', [
            'adverts' => $adverts
        ]);
    }
}
