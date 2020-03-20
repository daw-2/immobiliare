<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces_liste")
     */
    public function index()
    {
        return $this->render('annonce/index.html.twig');
    }

    /**
     * @Route("/annonces/{id}", name="annonces_voir")
     */
    public function voir($id)
    {
        return $this->render('annonce/voir.html.twig', [
            'id' => $id,
        ]);
    }

    /**
     * @Route("/annonces/creer", name="annonces_creer")
     */
    public function creer()
    {
        return $this->render('annonce/creer.html.twig');
    }

    /**
     * @Route("/annonces/modifier/{id}", name="annonces_modifier")
     */
    public function modifier($id)
    {
        return $this->render('annonce/modifier.html.twig', [
            'id' => $id,
        ]);
    }

    /**
     * @Route("/annonces/supprimer/{id}", name="annonces_supprimer")
     */
    public function supprimer($id)
    {
        return new Response('<body>On supprime '.$id.' et on redirige vers la liste des annonces</body>');
    }
}
