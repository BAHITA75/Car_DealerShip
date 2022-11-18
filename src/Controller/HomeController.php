<?php

namespace App\Controller;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Car;
use App\Form\SearchForm;
use App\Service\MeteoApi;
use App\Service\SearchData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(
        EntityManagerInterface $entityManager, 
        PaginatorInterface $paginator, 
        Request $request, 
        MeteoApi $meteoService
        ): Response {

        $dataSearch = new SearchData();
        $formSearch = $this->createForm(SearchForm::class, $dataSearch);
        $formSearch->handleRequest($request);

        $data = $entityManager->getRepository(Car::class)->findSearch($dataSearch);

        // dd($data);

        $dataMeteo = $meteoService->getMeteoData();

        $cars = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('home/home.html.twig', [
            'form' => $formSearch->createView(),
            'cars' => $cars,
            'dataMeteo' => $dataMeteo
        ]);
    }
}
