<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Form\StaffType;
use App\Repository\StaffRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/staff')]
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
    #[Route('/add', name: 'app_staff_add')]
    public function add(Request $request, StaffRepository $staffRepository): Response
    {
        //definition de l'objet user qui sera remplis
        $staff = new Staff();
        //appel de l'objet formulaire pour l'affichage
        $form = $this->createForm(StaffType::class, $staff);
        //appel de la fonction qui doit faire matcher les informations en provenance du formulaire avec les attributs de l'objet

        $form->handleRequest($request);

        //si le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()){
            //on enregistre les données du user en BDD
            //dump($user);
            //TODO : mettre en place le hashage du mdp
            $staffRepository->save($staff,true);

            //on redirige l'utilisateur
            return $this->redirectToRoute('app_staff');
        }
        //::appel static, pas d'instanciation necessaire
        //dump($form);
        return $this->render('staff/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/edit/{id}', name: 'app_staff_edit')]
    public function edit(Staff $staff, Request $request, StaffRepository $staffRepository): Response
    {   
        // appel de l'objet formulaire pour affichage
       $form = $this->createForm(StaffType::class, $staff);
       // appel de la fonction qui doit matcher les informations 
       // en provenance du formulaire avec les attributs du l'objet
       $form->handleRequest($request);
       // dump($form);

       // Si le formulaire est soumis et valid
       if($form->isSubmitted() && $form->isValid()) {
       // On enregistre les données du user en BDD
       // TODO : mettre en place de hashage de mot de passe
       // dump($user); -> Le dump s'enclenche avant donc si on laisse la redirection ne marche pas
       $staffRepository->save($staff, true);

       // on redirige l'utilisateur 
       return $this->redirectToRoute('app_staff');
       }

       //passage des infos vers la vue
       return $this->render('staff/add.html.twig', [
           'form' => $form->createView()
       ]);
    }
    #[Route('/delete/{id}', name: 'app_staff_delete')]
    public function delete(Staff $staff, Request $request, StaffRepository $staffRepository): Response
    {
        $staffRepository->remove($staff, true);
        return $this->redirectToRoute('app_staff');
    }

    #[Route('/detail/{id}', name: 'app_staff_detail')]
    public function detail(Staff $staff, Request $request, StaffRepository $staffRepository): Response
    {
        //envoyer les information de user vers la vue
            return $this->render('staff/detail.html.twig', [
            'staff' => $staff
        ]);
    }
}
