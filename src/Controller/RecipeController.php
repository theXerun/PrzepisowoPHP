<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Form\RecipeFormType;
use App\Repository\IngredientTypeRepository;
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

    #[Route('/recipe/', name: 'all_recipes')]
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
    public function recipe(Recipe $recipe): Response
    {
        if ($recipe->isIsPublic() || $recipe->getAuthor()->getUsername() == $this->getUser()->getUserIdentifier()) {
            return $this->render('recipe/index.html.twig', [
                'recipe' => $recipe,
            ]);
        }
        return $this->render('recipe/index.html.twig', [
            'recipe' => new Recipe(),
        ]);
    }

    #[Route('/recipe/add', name: 'add_recipe')]
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
            return $this->redirect('/recipe/'.$recipe->getId());
        }
        return $this->render('/recipe/add.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/recipe/edit/{id}', name: 'edit_recipe')]
    public function edit(Request $request, EntityManagerInterface $entityManager, IngredientTypeRepository $ingredientTypeRepository, Recipe $recipe): Response
    {

        if (!$recipe->getAuthor()->getUsername() == $this->getUser()->getUserIdentifier()) {
            return new Response('Brak dostępu', 403);
        }

        $form = $this->createForm(RecipeFormType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $entityManager->persist($recipe);
            $entityManager->flush();
            return $this->redirect('/recipe/'.$recipe->getId());
        }
        return $this->render('/recipe/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

}
