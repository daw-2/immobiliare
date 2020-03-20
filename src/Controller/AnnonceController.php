<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/annonces/creer", name="annonces_creer")
     */
    public function creer(Request $request)
    {
        $annonce = new Annonce();
        // Ici je dois afficher mon formulaire
        $form = $this->createForm(AnnonceType::class, $annonce);

        // On va traiter le formulaire
        $form->handleRequest($request);

        // Vérifier les données du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() est équivalent à $_POST
            // dump($form->getData());
            // dump($annonce);
            // Ajouter l'annonce en BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce); // On insère l'objet dans la BDD
            $entityManager->flush(); // On exécute la requête

            // Pourquoi pas faire une petite redirection vers la page des annonces ?
            return $this->redirectToRoute('annonces_liste');
        }

        return $this->render('annonce/creer.html.twig', [
            'form' => $form->createView(),
        ]);
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
