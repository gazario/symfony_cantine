<?php

namespace App\Controller;

use App\Repository\ChildRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    #[Route('/booking', name: 'app_booking')]
    public function index(): Response
    {
        //calendrier - affichage des jours du mois en cours
        //1er jour du mois en cours
        $firstDay = 1;
        //dernier jour du mois en cours
        $lastDay = date('t', strtotime($firstDay));
        


        return $this->render('booking/index.html.twig', [
            'child' => $this->getUser()->getChild(),
            'firstDay' => $firstDay,
            'lastDay' => $lastDay,
        ]);
    }

    #[Route('/bookingrecap', name: 'app_booking_recap')]
    public function recap(Request $request, ChildRepository $childRepository): Response
    {
        $totalFacture = 0;
        $totalByChild = [];
        $tabChild = [];

        foreach($request->request as $key => $val)
        {
            $totalChild = 0;

            //récupère l'id de l'enfant dans la chaine date_x
            $id = str_replace("date_", "", $key);

            $tabChild[] = $child = $childRepository->findOneById($id);

            foreach($val as $row){
                //prix du menu lié à l'enfant
                $totalFacture += $child->getMenu()->getPrice();
                $totalChild += $child->getMenu()->getPrice();
                
            }
            $totalByChild[$id] = $totalChild;
        }
        
       return $this->render('booking/recap.html.twig',[
            'tabChild' => $tabChild,
            'totalByChild' => $totalByChild,
            'totalFacture' => $totalFacture,
            'tabDay' => $request->request
       ]);
    }

}
