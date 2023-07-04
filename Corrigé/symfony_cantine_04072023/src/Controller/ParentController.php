<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ParentType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/parent')]
class ParentController extends AbstractController
{
    #[Route('/', name: 'app_parent')]
    public function index(UserRepository $userRepository): Response
    {
        //recuperation des informations en bdd
        $users = $userRepository->getUserByRole('PARENT');

        //passage des informations vers la vue
        return $this->render('parent/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/add', name: 'app_parent_add')]
    public function add(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        //definition de l'objet user qui sera remplis
        $user = new User();
        //appel de l'objet formulaire pour affichage
        $form = $this->createForm(ParentType::class, $user);
        //appel de la fonction qui doit faire matcher les informations
        //en provenance du formulaire avec le attributs de l'objet
        $form->handleRequest($request);

        //si le formulaire est soumis et valid
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre les données du user en BDD

            $user->setRoles(['ROLE_PARENT']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $userRepository->save($user, true);

            //on redirige l'utilisateur
            return $this->redirectToRoute('app_parent');
        }

        //passage des informations vers la vue
        return $this->render('parent/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'app_parent_edit')]
    public function edit(User $user, Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        //appel de l'objet formulaire pour affichage
        $form = $this->createForm(ParentType::class, $user);
        //appel de la fonction qui doit faire matcher les informations
        //en provenance du formulaire avec le attributs de l'objet
        $form->handleRequest($request);

        //si le formulaire est soumis et valid
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre les données du user en BDD
           
            $user->setRoles(['ROLE_PARENT']);
            if($form->get('password')->getData() != ''){
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            }

            $userRepository->save($user, true);

            //on redirige l'utilisateur
            return $this->redirectToRoute('app_parent');
        }

        //passage des informations vers la vue
        return $this->render('parent/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'app_parent_delete')]
    public function delete(User $user, UserRepository $userRepository): Response
    {
        //on appel la method de suppression
        $userRepository->remove($user, true);
        //on redirige l'utilisateur sur la liste
        return $this->redirectToRoute('app_parent');
    }

    #[Route('/detail/{id}', name: 'app_parent_detail')]
    public function detail(User $user): Response
    {
        //je dois envoyer les informations du user vers la vue
        return $this->render('parent/detail.html.twig', [
            'user' => $user
        ]);
    }
}
