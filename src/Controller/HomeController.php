<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Adverts;
use App\Form\AdvertsType;
use App\Repository\AdvertsRepository;
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
public function show(Request $request, PaginatorInterface $paginator, AdvertsRepository $advertRepository): Response
{
    $queryBuilder = null;

    if (!empty($_POST)) {
        $category = $_POST['categories'] ?? null;
        $brand = $_POST['brands'] ?? null;
        $description = $_POST['mot-cles'] ?? null;
        $region = $_POST['region'] ?? null;
        $useCondition = $_POST['etat'] ?? null;

        // Récupération de la QueryBuilder avec des filtres
        $queryBuilder = $advertRepository->findBySomeField($category, $brand, $description, $region, $useCondition);
    } else {
        // QueryBuilder par défaut pour récupérer tous les enregistrements
        $queryBuilder = $advertRepository->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC');
    }

    // Utiliser le paginator pour paginer le QueryBuilder
    $adverts = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        9
    );

    return $this->render('adverts/index.html.twig', [
        'adverts' => $adverts,
    ]);
}

}
