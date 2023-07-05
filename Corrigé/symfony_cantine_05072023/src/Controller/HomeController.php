<?php

namespace App\Controller;

use App\Repository\ChildRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(
        RequestStack $requestStack,
        ChildRepository $childRepository,
        UserRepository $userRepository): Response
    {
        //definition de la session
        $session = $requestStack->getSession();
        $session->set('toto', 'vous dit bonjour');

        $nbChild = $childRepository->countChild();
        $nbAdmin = $userRepository->countUser('ADMIN');
        $nbParent = $userRepository->countUser('PARENT');

        return $this->render('home/index.html.twig', [
            'nbChild' => $nbChild,
            'nbAdmin' => $nbAdmin,
            'nbParent' => $nbParent,
        ]);
    }
}
