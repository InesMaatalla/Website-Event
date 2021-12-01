<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie/", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        // Création des entités

        //sortie
        $sortie = new Sortie();
        $sortie->setOrganisateur($user);

        //inscription
        $inscription = new Inscription();
        $inscription->setDateInscription(new DateTime('now'));
        $inscription->setSortie($sortie);
        $inscription->setUser($user);
        $sortie->addInscription($inscription);

        //lieu
        $lieu = new Lieu();

        // Création des formulaires de sortie et lieu
        $formSortie = $this->createForm('App\Form\SortieFormType', $sortie);
        $formLieu = $this->createForm('App\Form\LieuFormType', $lieu);

        // Récupérer les données
        $formSortie->handleRequest($request);
        $formLieu->handleRequest($request);

        // Vérifier les données du formulaire de sortie
        if ($formSortie->isSubmitted() && $formSortie->isValid()) {

            if ($formSortie->get('publier')->isClicked()) {
                $sortie->setEtat($entityManager->getRepository('App:Etat')->findOneBy(['libelle' => 'Ouverte']));
                $this->addFlash('success', 'La sortie a bien été publiée');
            } else {
                $sortie->setEtat($entityManager->getRepository('App:Etat')->findOneBy(['libelle' => 'Créée']));
                $this->addFlash('success', 'La sortie a bien été enregistrée');
            }
            $entityManager->persist($sortie);
            $entityManager->persist($inscription);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('default_home');

        }

        // Vérifier les données du formulaire de lieu
        if ($formLieu->isSubmitted() && $formLieu->isValid()) {

            $lieux = $entityManager->getRepository('App:Lieu')->findAll();
            foreach ($lieux as $lieuEnBase) {
                if ($lieu->getNom() == $lieuEnBase->getNom()
                    && $lieu->getRue() == $lieuEnBase->getRue()
                    && $lieu->getVille()->getNom() == $lieuEnBase->getVille()->getNom()) {
                    $this->addFlash('danger', 'Ce lieu existe déjà!');
                    return $this->redirectToRoute('sortie_create');
                }
            }
            $entityManager->persist($lieu);
            $entityManager->flush();
            $this->addFlash('success', 'Le lieu a bien été enregistré');
            $lieu2 = $lieu;

            //Vider le formulaire de lieu
            unset($formLieu);
            $lieu = new Lieu();
            $formLieu = $this->createForm('App\Form\LieuFormType', $lieu);
            return $this->render('sortie/create.html.twig', [
                'formSortie' => $formSortie->createView(), 'formLieu' => $formLieu->createView(), 'lieu2' => $lieu2,
            ]);

        }

        return $this->render('sortie/create.html.twig', [
            'formSortie' => $formSortie->createView(), 'formLieu' => $formLieu->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("edit/{id}", name="edit", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $idSortie = $request->get('id');
        $sortie = $entityManager->getRepository('App:Sortie')->find($idSortie);
        $ville = $sortie->getLieu()->getVille();
        try {
            if ($sortie->getOrganisateur()->getId() !== $user->getId()) {
                throw $this->createAccessDeniedException();
            }
        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('Sortie Not Found !');
        }
        $formSortie = $this->createForm('App\Form\SortieFormType', $sortie);
        $lieu = new Lieu();
        $formLieu = $this->createForm('App\Form\LieuFormType', $lieu);
        $lieu2 = $entityManager->getRepository('App:Lieu')->find($sortie->getLieu()->getId());

        $formSortie->handleRequest($request);
        $formLieu->handleRequest($request);

        if ($formSortie->isSubmitted() && $formSortie->isValid()) {


            if ($formSortie->get('publier')->isClicked()) {
                $sortie->setEtat($entityManager->getRepository('App:Etat')->findOneBy(['libelle' => 'Ouverte']));
                $this->addFlash('success', 'La sortie a bien été publié');
            } else {
                $sortie->setEtat($entityManager->getRepository('App:Etat')->findOneBy(['libelle' => 'Créée']));
                $this->addFlash('success', 'La sortie a bien été enregistrée');
            }
            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('default_home');
        }
        if ($formLieu->isSubmitted() && $formLieu->isValid()) {

            $lieux = $entityManager->getRepository('App:Lieu')->findAll();
            foreach ($lieux as $lieuEnBase) {
                if ($lieu->getNom() == $lieuEnBase->getNom()
                    && $lieu->getRue() == $lieuEnBase->getRue()
                    && $lieu->getVille()->getNom() == $lieuEnBase->getVille()->getNom()) {
                    $this->addFlash('danger', 'Ce lieu existe déjà!');
                    return $this->redirectToRoute('sortie_create');
                }
            }
            $entityManager->persist($lieu);
            $entityManager->flush();
            $this->addFlash('success', 'Le lieu a bien été enregistré');
            $lieu2 = $lieu;
            $lieu = new Lieu();

            $formLieu = $this->createForm('App\Form\LieuFormType', $lieu);
        }
        return $this->render('sortie/edit.html.twig', [
            'formSortie' => $formSortie->createView(), 'formLieu'=> $formLieu->createView(), 'lieu2' => $lieu2, 'sortie' => $sortie
        ]);

    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route ("lieuxVille", name="liste_lieux", methods={"GET", "POST"})
     */
    public function listeLieuxParVille(Request $request, EntityManagerInterface $entityManager)
    {
        //Récupération de l'id de la ville sélectionnée
        $idVille = $request->query->get('idVille');

        //Récupération de l'objet ville en base
        $ville = $entityManager->getRepository(Ville::class)->find($idVille);

        //Récupération de tous les lieux correspondant l'idVille
        $lieux = $entityManager->getRepository(Lieu::class)->findLieuByVille($idVille);

        //Récupération du CP
        $cp = $ville->getCodePostal();

        $tabLieux = [];

        // Ajout des données du lieu récupéré et du CP de la ville dans un tableau...
        foreach ($lieux as $lieu) {
            $tabLieux[] = [
                "id" => $lieu->getId(),
                "nom" => $lieu->getNom(),
                "cp" => $cp
            ];
        }
        //... que je renvoi au format JSON
        return new JsonResponse($tabLieux);
    }

    /**
     * @Route("lieuDetails", name="lieu_details")
     */
    public function lieuDetails(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $lieuId = $request->query->get('lieuId');
        $lieu = $entityManager->getRepository(Lieu::class)->find($lieuId);

        $tabreponse[] = [
            "id" => $lieu->getId(),
            "nom" => $lieu->getNom(),
            "Rue" => $lieu->getRue(),
            "villeId" => $lieu->getVille()->getId(),
            "Cp" => $lieu->getVille()->getCodePostal(),
            "xlatitude" => $lieu->getLatitude(),
            "xlongitude" => $lieu->getLongitude(),
        ];
        return new JsonResponse($tabreponse);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route("{id}/cancel", requirements={"id": "\d+"}, name="cancel", methods={"GET", "POST"})
     */
    public function cancel(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        //Récupération de l'entité à supprimer
        try {
            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));
            if ($sortie->getOrganisateur()->getId() !== $user->getId()) {
                throw $this->createAccessDeniedException();
            }
            $ok = true;
            if ($sortie->getDateHeureDebut() <= new DateTime('now')) {
                $this->addFlash('success', 'La sortie a déjà eu lieu, impossible de l\'annuler');
                $ok = false;
            }
            if ($sortie->getEtat()->getLibelle() === 'Créée') {
                $this->addFlash('success', 'La sortie n\'a pas été publiée, impossible de l\'annuler');
                $ok = false;
            }
        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('Sortie introuvable');
        }
        if ($ok === false) {
            return $this->redirectToRoute('default_home');
        }
        // Création du formulaire
        $formAnnuler = $this->createForm('App\Form\AnnulerFormType', $sortie);

        // Récupérer les données envoyées par le navigateur et les transmettre au formulaire
        $formAnnuler->handleRequest($request);

        if ((bool)$request->get('annuler') === true) {
            if ($sortie->getDateHeureDebut() < new \DateTime('now')) {
                $this->addFlash('danger', 'La sortie a déjà eu lieu ou est en cours! Impossible de l\'annuler');
                return $this->redirectToRoute('default_home');
            } else {
                $sortie->setEtat($entityManager->getRepository('App:Etat')->findOneBy(['libelle' => 'Annulée']));
                $this->addFlash('success', 'La sortie a bien été annulée');
                $entityManager->persist($sortie);
                $entityManager->flush();
                return $this->redirectToRoute('default_home');
            }
        }
        if ($formAnnuler->isSubmitted() && $formAnnuler->isValid()) {
            if ($sortie->getDateHeureDebut() < new \DateTime('now')) {
                $this->addFlash('danger', 'La sortie a déjà eu lieu ou est en cours! Impossible de l\'annuler');
                return $this->redirectToRoute('default_home');
            } else {
                $sortie->setEtat($entityManager->getRepository('App:Etat')->findOneBy(['libelle' => 'Annulée']));
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'La sortie a bien été annulée');
                return $this->redirectToRoute('default_home');
            }
        }
        return $this->render('sortie/cancel.html.twig', [
            'formAnnuler' => $formAnnuler->createView(),
        ]);

    }

    /**
     * @Route("afficherSortie/{id}", name="afficherSortie", requirements={"id"="\d+"}, methods={"GET", "POST"})
     * @param SortieRepository $sortieRepository
     * @param LieuRepository $lieuRepository
     * @return Response
     */
    public function afficherSortie(Request $request, SortieRepository $sortieRepository, LieuRepository $lieuRepository, UserRepository $userRepository): Response
    {
        $today = new DateTime('now');
        $monthAgo = $today->sub(new DateInterval('P1M'));
        $idSortie = $request->get('id');
        $sortie = $sortieRepository->find($idSortie);
        if ($sortie->getDateHeureDebut()<$monthAgo){
            $this->addFlash('danger', 'Cette sortie a eu lieu il y plus d\'1 mois, impossible de la consulter');
            return $this->redirectToRoute('default_home');
        }
        $lieu = $lieuRepository->find($idSortie);
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('sortie/afficherSortie.html.twig', [
            'sortie' => $sortie,
            'lieu' => $lieu,
            'user' => $user
        ]);
    }
}
