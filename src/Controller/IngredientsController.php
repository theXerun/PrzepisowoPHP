<?php

namespace App\Controller;

use App\Entity\IngredientType;
use App\Form\IngredientTypeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientsController extends AbstractController
{
    #[Route('/add-ingredient', name: 'app_ingredients')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $route = $request->headers->get('referer');
        $ingredientType = new IngredientType();
        $form = $this->createForm(IngredientTypeType::class, $ingredientType);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientType = $form->getData();
            $entityManager->persist($ingredientType);
            $entityManager->flush();
            return $this->redirectToRoute($route);
        }
        return $this->render('ingredients/index.html.twig', [
            'form' => $form,
        ]);
    }
}
