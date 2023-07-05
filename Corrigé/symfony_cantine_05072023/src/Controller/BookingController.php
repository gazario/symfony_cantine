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
        //obtention du premier jour du mois
        $firstDay = 1;
        //obtention du dernier jour du mois
        $lastDay = date("t", strtotime($firstDay));
       
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
            //initialisation du prix pour un enfant
            $totalChild = 0;
            //je recupere l'id de l'enfant dans la chaine date_x
            $id = str_replace("date_", "", $key);
            //je recupere l'enfant par rapport a l'id
            $tabChild[] = $child = $childRepository->findOneById($id);

            //boucle pour cahque jour réservé
            foreach($val as $row){
                //prix du menu lié a l'enfant
                $totalFacture += $child->getMenu()->getPrice();
                $totalChild += $child->getMenu()->getPrice();
            }
            $totalByChild[$id] = $totalChild;
        }

        return $this->render('booking/recap.html.twig', [
            'tabChild' => $tabChild,
            'totalByChild' => $totalByChild,
            'totalFacture' => $totalFacture,
            'tabDay' => $request->request
        ]);

    }
}
