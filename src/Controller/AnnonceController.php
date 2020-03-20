<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
