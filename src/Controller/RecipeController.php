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
        $isAuthor = false;
        if ($recipe->getAuthor()->getUsername() == $this->getUser()->getUserIdentifier()) {
            $isAuthor = true;
        }
        if($recipe->isIsPublic() || $isAuthor) {
            return $this->render('recipe/index.html.twig', [
                'recipe' => $recipe,
                'is_author' => $isAuthor,
            ]);
        }

        return new Response('Nie ma takiego przepisu', 404);
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
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $entityManager): Response
    {

        if (!($recipe->getAuthor()->getUsername() == $this->getUser()->getUserIdentifier())) {
            return new Response('Brak dostępu', 403);
        }

        $originalIngredients = new ArrayCollection();

        foreach ($recipe->getIngredients() as $ingredient) {
            $originalIngredients->add($ingredient);
        }

        $form = $this->createForm(RecipeFormType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            foreach ($originalIngredients as $ingredient) {
                if (false === $recipe->getIngredients()->contains($ingredient)) {
                    $recipe->removeIngredient($ingredient);
                    $entityManager->remove($ingredient);
                }
            }
            $entityManager->persist($recipe);
            $entityManager->flush();
            return $this->redirectToRoute('app_recipe', $recipe->getId());
        }
        return $this->render('/recipe/edit.html.twig',[
            'form' => $form->createView(),
            'id' => $recipe->getId(),
        ]);
    }

    #[Route('/recipe/delete/{id}', name: 'delete_recipe')]
    public function delete(Request $request, EntityManagerInterface $entityManager, Recipe $recipe): Response
    {
        if (!($recipe->getAuthor()->getUsername() == $this->getUser()->getUserIdentifier())) {
            return new Response('Brak dostępu', 403);
        }

        $entityManager->remove($recipe);
        $entityManager->flush();

        return $this->redirectToRoute('all_recipes');
    }

}
