<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces_liste")
     */
    public function index(AnnonceRepository $annonceRepository)
    {
        // On veut récupérer les annonces en BDD
        // $annonces = $this->getDoctrine()->getRepository(Annonce::class)->findAll();
        $annonces = $annonceRepository->findAll(); // $annonces nous renvoie un tableau avec des objets Annonce

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
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

            // Upload de la photo
            $image = $form->get('photo')->getData();

            if ($image !== null) {
                // Je récupère le dossier où j'upload mes images
                $uploadDir = __DIR__.'/../../public/uploads';
                // Je fais l'upload en générant un nom pour l'image comme aerf1234.jpg
                $fileName = uniqid().'.'.$image->guessExtension();
                $image->move($uploadDir, $fileName);

                // Je mets à jour l'entité pour la BDD
                $annonce->setPhoto($fileName);
            }

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
    public function voir($id, AnnonceRepository $annonceRepository)
    {
        // On va chercher l'annonce en BDD
        $annonce = $annonceRepository->find($id);

        // On renvoie une 404 si l'annonce n'existe pas
        if (!$annonce) {
            throw $this->createNotFoundException();
        }

        return $this->render('annonce/voir.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/annonces/modifier/{id}", name="annonces_modifier")
     */
    public function modifier(Request $request, Annonce $annonce)
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // Ici, pas besoin de faire le persist
            // Car Doctrine sait qu'on a modifié l'objet
            $entityManager->flush(); // UPDATE...
        }

        return $this->render('annonce/modifier.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/annonces/supprimer/{id}", name="annonces_supprimer")
     */
    public function supprimer($id, AnnonceRepository $annonceRepository, EntityManagerInterface $entityManager)
    {
        // On va chercher l'annonce en BDD
        $annonce = $annonceRepository->find($id);

        // On supprime en BDD
        $entityManager->remove($annonce);
        $entityManager->flush();

        // On redirige
        return $this->redirectToRoute('annonces_liste');
    }
}
