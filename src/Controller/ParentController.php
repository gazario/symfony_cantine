<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ParentType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/parent')]
class ParentController extends AbstractController
{
    #[Route('/parent', name: 'app_parent')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('parent/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/add', name: 'app_parent_add')]
    public function add(Request $request, UserRepository $userRepository): Response
    {
        //definition de l'objet user qui sera remplis
        $user = new User();
        //appel de l'objet formulaire pour l'affichage
        $form = $this->createForm(ParentType::class, $user);
        //appel de la fonction qui doit faire matcher les informations en provenance du formulaire avec les attributs de l'objet

        $form->handleRequest($request);

        //si le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()){
            //on enregistre les données du user en BDD
            //dump($user);
            //TODO : mettre en place le hashage du mdp
            $userRepository->save($user,true);

            //on redirige l'utilisateur
            return $this->redirectToRoute('app_parent');
        }
        //::appel static, pas d'instanciation necessaire
        //dump($form);
        return $this->render('parent/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
