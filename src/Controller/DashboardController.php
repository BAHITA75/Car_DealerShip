<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Category;
use App\Form\CarType;
use App\Form\EditCarType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class DashboardController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/dashboard", name="dashboard")
     * @return Response
     */
    public function dashboard(PaginatorInterface $paginator, Request $request): Response
    { 
        $data = $this->entityManager->getRepository(Car::class)->findAll();
        $cars = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        $categories = $this->entityManager->getRepository(Category::class)->findAll();


        return $this->render('dashboard/dashboard.html.twig',[
                'cars' => $cars,
                'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/create_car", name="create_car")
     */
    public function createCar(Request $request, SluggerInterface $slugger): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        
        if($form->isSubmitted() && $form->isValid()){

            $car = $form->getData();

            $file = $form->get('picture')->getData(); 
        
            if($file){
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = '.' . $file->guessExtension();
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '_' . uniqid() . $extension;

                    try {
                        $file->move($this->getParameter('uploads_dir'), $newFilename);

                        $car->setPicture($newFilename);
                    } catch (FileException $exception) {
                    }
            }
            $this->entityManager->persist($car);
            $this->entityManager->flush();

            $this->addFlash('success','Vehicule ajouté!');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('dashboard/form_create_car.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/edit/car/{id}", name="edit_car")
     */
    public function editCar(Car $car, Request $request): Response
    {
        $form = $this->createForm(EditCarType::class, $car)
                ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($car);
            $this->entityManager->flush();

            $this->addFlash('success','Vehicule modifié!');
            return $this->redirectToRoute('dashboard');

        }
        return $this->render('dashboard/form_edit_car.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/supprimer/car/{id}", name="delete_car")
     */
    public function deleteCar(Car $car): Response
    {
          $this->entityManager->remove($car);
          $this->entityManager->flush();

          $this->addFlash('success','vehicule supprimé !');

          return $this->redirectToRoute('dashboard');
    }

}
