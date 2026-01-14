<?php

namespace App\Controller;

use App\Entity\Pet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/', name: 'app')]
    public function index(): Response
    {
        return $this->render('pages/registration.html.twig');
    }

    #[Route('/confirmation/{id}', name: 'app_confirmation')]
    public function confirmation(int $id, EntityManagerInterface $em): Response
    {
        $pet = $em->getRepository(Pet::class)->find($id);

        if (!$pet) {
            return $this->redirectToRoute('app');
        }

        return $this->render('pages/confirmation.html.twig', [
            'pet' => $pet,
        ]);
    }
}
