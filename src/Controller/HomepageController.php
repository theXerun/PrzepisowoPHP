<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(AuthenticationUtils $authenticationUtils, RecipeRepository $recipeRepository, UserRepository $userRepository): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findOneBy([
            'username' => $this->getUser()->getUserIdentifier()
        ]);

        $recipes = $recipeRepository->getDoableRecipes($user);
        return $this->render('index.html.twig', [
            'username' => $this->getUser()->getUserIdentifier(),
            'recipes' => $recipes,
        ]);
    }
}
