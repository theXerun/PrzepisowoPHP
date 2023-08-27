<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FridgeFormType;
use App\Repository\FridgeRepository;
use App\Repository\UserRepository;
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
        $form = $this->createForm(FridgeFormType::class, $fridge);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fridge = $form->getData();
            $entityManager->persist($fridge);
            $entityManager->flush();
            return $this->redirectToRoute('app_fridge');
        }
        return $this->render('fridge/index.html.twig', [
            'form' => $form,
        ]);
    }
}
