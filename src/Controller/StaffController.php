<?php

namespace App\Controller;

use App\Repository\StaffRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaffController extends AbstractController
{
    #[Route('/staff', name: 'app_staff')]
    public function index(StaffRepository $staffRepository): Response
    {
        $staff = $staffRepository->findAll();

        return $this->render('staff/index.html.twig', [
            'staff' => $staff
        ]);
    }
}
