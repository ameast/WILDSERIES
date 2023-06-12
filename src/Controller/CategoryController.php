<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;


#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{


    #[Route('/', name: 'index')]
    public  function index(CategoryRepository  $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();


        return $this->render(
            'category/index.html.twig',
            ['categories' => $categories]
        );
    }
    #[Route('/{categoryName}', name: 'show')]
    public  function show(string $categoryName,CategoryRepository $categoryRepository,ProgramRepository $programRepository): Response
    {


        $category= $categoryRepository->findOneByName($categoryName);

        $programsByCategory= $programRepository->findByCategory($category,['id'=>'DESC']);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with named '. $categoryName .' found in categories table.'
            );
        }

            return $this->render('category/show.html.twig', [
                'programsByCategory' => $programsByCategory,
                'category'=> $category
            ]);
    }

}