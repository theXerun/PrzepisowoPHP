<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Form\RecipeFormType;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{

    #[Route('/recipes', name: 'all_recipes')]
    public function allRecipes(RecipeRepository $recipeRepository, UserRepository $userRepository): Response
    {

        $allRecipes = $recipeRepository
            ->getAvailableRecipes($userRepository
                ->findOneBy(['username' => $this->getUser()->getUserIdentifier()]));

        return $this->render('recipe/recipes.html.twig', [
            'all_recipes' => $allRecipes,
        ]);
    }
    #[Route('/recipe/{id}', name: 'app_recipe')]
    public function recipe(int $id, RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
        ]);
    }

    #[Route('/add-recipe', name: 'add_recipe')]
    public function add(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $recipe = new Recipe();
        $user = $userRepository
            ->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);

        $form = $this->createForm(RecipeFormType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $recipe->setAuthor($user);
            $entityManager->persist($recipe);
            $entityManager->flush();
            return $this->redirectToRoute('/recipe/'.$recipe->getId());
        }
        return $this->render('/recipe/add.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
