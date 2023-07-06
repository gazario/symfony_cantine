<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use App\Repository\ChildRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
    public function recap(Request $request, ChildRepository $childRepository, RequestStack $requestStack): Response
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
        
        //création d'une session
        $session = $requestStack->getSession();
        $session->set('tabChild', $tabChild);
        $session->set('tabDay', $request->request);

       return $this->render('booking/recap.html.twig',[
            'tabChild' => $tabChild,
            'totalByChild' => $totalByChild,
            'totalFacture' => $totalFacture,
            'tabDay' => $request->request
       ]);
    }

    #[Route('/bookingsave', name: 'app_booking_save')]
    public function save(
        ChildRepository $childRepository,
        BookingRepository $bookingRepository,
        RequestStack $requestStack,
    ): Response
    {
        /*$totalFacture = 0;
        $totalByChild = [];
        $tabChild = [];*/
        $session = $requestStack->getSession();
        $tabChild = $session->get('tabChild');
        $tabDay = $session->get('tabDay');

        //dump($tabChild);
        //dump($tabDay);

        foreach($tabChild as $rowChild){
            //dump($rowChild);
            //boucle sur les dates selectionnées
            foreach($tabDay as $key => $rowDay){
                //condition pour n etraiter que les dates de l'enfant concerné par l'itération de la boucle
                if(str_replace("date_", "", $key) == $rowChild->getId()){
                    //dump(str_replace("date_", "", $key));
                    //dump($rowDay);
                    //boucle sur les jours du tableau des jours
                    foreach($rowDay as $day){

                        $date = explode('/', $day);
                        $newDate = new DateTime($date[2].'-'.$date[1].'-'.$date[0]);

                        //on teste pour savoir si la date existe deja
                        $booking = $bookingRepository->findOneByDate($newDate);
                        //si elle n'existe pas on la créée
                        if(empty($booking)){

                            //mise en place de l'objet booking
                            $booking = new Booking();
                            $booking->setDate($newDate);
                            //sauvegarde en BDD de l'objet

                            $bookingRepository->save($booking, true);
                            
                        }
                        //je reprends les objets enfant depuis l'information qui provient de la session
                        $child = $childRepository->findOneById($rowChild->getId());
                        $child->addBooking($booking);
                        $childRepository->save($child, true);
                       
                    }

                }

            }
               
        }

        //dd('ok');
        $this->addFlash('success', 'votre commande a bien été prise en compte');

        return $this->redirectToRoute('app_home');
    }

}
