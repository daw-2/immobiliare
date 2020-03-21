<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Form\ProprietaireType;
use App\Repository\ProprietaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProprietaireController extends AbstractController
{
    /**
     * @Route("/proprietaires", name="proprietaires_liste")
     */
    public function index(ProprietaireRepository $proprietaireRepository)
    {
        return $this->render('proprietaire/index.html.twig', [
            'proprietaires' => $proprietaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/proprietaires/creer", name="proprietaires_creer")
     */
    public function creer(Request $request, EntityManagerInterface $entityManager)
    {
        $proprietaire = new Proprietaire();
        $form = $this->createForm(ProprietaireType::class, $proprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Upload de l'avatar
            $image = $form->get('avatar')->getData();

            if ($image !== null) {
                // Je récupère le dossier où j'upload mes images
                $uploadDir = __DIR__.'/../../public/uploads';
                // Je fais l'upload en générant un nom pour l'image comme aerf1234.jpg
                $fileName = uniqid().'.'.$image->guessExtension();
                $image->move($uploadDir, $fileName);

                // Je mets à jour l'entité pour la BDD
                $proprietaire->setAvatar($fileName);
            }


            // On fait la requête SQL
            $entityManager->persist($proprietaire);
            $entityManager->flush();
        }

        return $this->render('proprietaire/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/proprietaires/{id}", name="proprietaires_voir")
     */
    public function voir(Proprietaire $proprietaire)
    {
        // Calcul de l'âge
        $age = (new \DateTime('now'))->diff($proprietaire->getNaissance())->y;

        return $this->render('proprietaire/voir.html.twig', [
            'proprietaire' => $proprietaire,
            'age' => $age,
        ]);
    }

    /**
     * @Route("/proprietaires/modifier/{id}", name="proprietaires_modifier")
     */
    public function modifier(Request $request, Proprietaire $proprietaire)
    {
        $form = $this->createForm(ProprietaireType::class, $proprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // Ici, pas besoin de faire le persist
            // Car Doctrine sait qu'on a modifié l'objet
            $entityManager->flush(); // UPDATE...
        }

        return $this->render('proprietaire/modifier.html.twig', [
            'proprietaire' => $proprietaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/proprietaires/supprimer/{id}", name="proprietaires_supprimer")
     */
    public function supprimer(Proprietaire $proprietaire, EntityManagerInterface $entityManager)
    {
        // On supprime en BDD
        $entityManager->remove($proprietaire);
        $entityManager->flush();

        // On redirige
        return $this->redirectToRoute('proprietaires_liste');
    }
}
