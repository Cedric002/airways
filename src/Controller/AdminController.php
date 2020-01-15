<?php

namespace App\Controller;

use App\Entity\Flight;
use App\Repository\FlightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function list(FlightRepository $repo)
    {
        $flights  = $repo->findAll();
        return $this->render('admin/list.html.twig', [
            'flights' => $flights
        ]);
    }

    /**
     * @Route("/admin/new", name="admin_new")
     */
    public function new(Request $request)
    { 
        // instance de l'entité Flight à alimenter avec le formulaire

        $flight = new Flight();
        // on crée un formulaire basé sur la classe FlightType qui configure les champs
        $form = $this->createForm(FlightType::class, $flight);
        // pour gérer la soumission
        $form->handleRequest($request);

        /* Dans le controller on vérifie que les villes sont différentes ici j'utilise
        un générateur de message d'erreur
        Mais ce test est opérationnel dans l'entité flight 
        */

        /*if($flight->getDeparture() == $flight->getArrival() ) {
            $error = new FormError("Le départ et l'arrivée doivent être différents");
            $form->get("arrival")->addError($error);
        } */
            // on donne un numéro de vol au hasard
        $flight->setNumber($this->getFlightNumber() );

        // On renregistre les données du formulaire (aussi l'objet Flight)
        if($form->isSubmitted() && $form->isValid() ) {
            $flight->setNumber($this->getFlightNumber() );
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($flight);
            $manager->flush();

            // redirection page d'acueil de l'admin
            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/update.html.twig',[
            'numerovol' => $flight->getNumber(),
            'myform' => $form->createView()
            
        ]);
        
    }

    /**
     * @Route("/admin/edit/{id}", name="admin_edit")
     */
    public function edit(Request $request, $id) 
    { 
        // je récupère de la base de donnée le vol relatif à l'identifiant $id
        $flight = $this->getDoctrine()->getRepository(Flight::class)->find($id);
        // je crée un formulaire alimenté par l'objet Flight
        $form = $this->createForm(FlightType::class, $flight);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() )
            $flight->setNumber($this->getFlightNumber() );
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($flight);
            $manager->flush();

            // redirection page d'acueil de l'admin
            return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/admin/delete/{id}", name="admin_delete")
     */
    public function delete(Flight $flight) 
    {
        $manager = $this->getDoctrine()->getManager();        
        $manager->remove($flight);
        $manager->flush();
        return $this->redirectToRoute('admin_home');
    }
    /**
     * get a random flight number
     *
     * @return string
     */
    public function getFlightNumber():string {
        // tableau de lettres màj
        $lettres = range('A', 'Z');
        // mélange
        shuffle($lettres);
        // extraire le premier item du tableau
        $lettre = array_shift($lettres);
        // je recommence
        shuffle($lettres);
        $lettre .= array_shift($lettres);
        // nombre sur 4 digit au hasard
        $nombre = mt_rand(1000, 9999);
        return $lettre.$nombre;
    }
}
