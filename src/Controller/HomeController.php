<?php

namespace App\Controller;

use App\Repository\ChildRepository;
use App\Repository\MenuRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(RequestStack $requestStack, ChildRepository $childRepository, MenuRepository $menuRepository, UserRepository $userRepository): Response
    {
        //Création de la session
        $session = $requestStack->getSession();
        $session->set('toto', 'vous dit bonjour');
        //session utilise memoire tampon du server

        //Récupération du countChild()
        $nbChild = $childRepository->countChild();
        $nbMenu = $menuRepository->countMenu();
        $nbUser = $userRepository->countUser();
        $nbParent = $userRepository->countRole('PARENT');
        $nbAdmin = $userRepository->countRole('ADMIN');


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'nbChild' => $nbChild,
            'nbMenu' => $nbMenu,
            'nbUser' => $nbUser,
            'nbParent' => $nbParent,
            'nbAdmin' => $nbAdmin
        ]);
    }
}
