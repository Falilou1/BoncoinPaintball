<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Adverts;
use App\Form\AdvertsType;
use App\Repository\AdvertsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

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
    public function show(AdvertsRepository $advertRepository, PaginatorInterface $paginator, Request $request): Response
{
    // Récupérer les critères de recherche (POST ou GET)
    $category = $request->get('categories');
    $brand = $request->get('brands');
    $description = $request->get('mot-cles');
    $region = $request->get('region');
    $useCondition = $request->get('etat');

    // Appel de la méthode du repository qui retourne une Query
    $query = $advertRepository->findBySomeField($category, $brand, $description, $region, $useCondition);

    // Utilisation du paginator pour paginer les résultats
    $adverts = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), // Numéro de la page
        9 // Nombre d'éléments par page
    );

    // Ajouter les paramètres à la pagination pour qu'ils soient conservés
    $adverts->setParam('categories', $category);
    $adverts->setParam('brands', $brand);
    $adverts->setParam('mot-cles', $description);
    $adverts->setParam('region', $region);
    $adverts->setParam('etat', $useCondition);

    return $this->render('home/show.html.twig', [
        'adverts' => $adverts,
    ]);
}
}