<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FridgeFormType;
use App\Repository\FridgeRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FridgeController extends AbstractController
{
    #[Route('/fridge', name: 'app_fridge')]
    public function index(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $fridge = $userRepository->getByIdentifier($user->getUserIdentifier())->getFridge();
        $originalIngredients = new ArrayCollection();

        foreach ($fridge->getIngredients() as $ingredient) {
            $originalIngredients->add($ingredient);
        }
        $form = $this->createForm(FridgeFormType::class, $fridge);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fridge = $form->getData();
            foreach ($originalIngredients as $ingredient) {
                if (false === $fridge->getIngredients()->contains($ingredient)) {
                    $fridge->removeIngredient($ingredient);
                    $entityManager->remove($ingredient);
                }
            }
            $entityManager->persist($fridge);
            $entityManager->flush();
            return $this->redirectToRoute('app_fridge');
        }
        return $this->render('fridge/index.html.twig', [
            'form' => $form,
        ]);
    }
}
