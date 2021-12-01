<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\User;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inscription/", name="inscription_")
 */
class InscriptionController extends AbstractController
{

    /**
     * @Route("inscrire/{id}", name="inscrire", methods={"GET"})
     * @param SortieRepository $sortieRepository
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @return Response
     */
    public function inscription(Request                $request,
                                SortieRepository       $sortieRepository,
                                EntityManagerInterface $entityManager): Response
    {
        //Récupération de l'utilisateur connectée
        /** @var User $user */
        $user = $this->getUser();

        if ($user) {
            $idUser = $user->getId();
        } else {
            //Si l'utilisateur n'est pas connecté, on le redirige vers la page d'inscription+
            return $this->redirectToRoute('app_login');
        }
        $idSortie = $request->get('id');
        $sortie = $sortieRepository->find($idSortie);

        $dateJour = new DateTime('now');
        if ($dateJour > $sortie->getDateLimiteInscription()) {
            $this->addFlash('danger', 'Vous n\'êtes pas inscrit, les inscriptions sont déjà closes');
            return $this->redirectToRoute('default_home');
        }

        $tabInscription = $sortie->getInscriptions();
        foreach ($tabInscription as $inscription) {
            if ($inscription->getUser()->getId() == $user->getId()) {
                $this->addFlash('danger', 'Vous êtes déjà inscrit.');
                return $this->redirectToRoute('default_home');
            }
        }

        //Vérification du nombre d'inscrit
        $nbInscritMax = $sortie->getNbInscriptionsMax();
        $nbInscrit = $sortie->getInscriptions()->count();

        $inscription = new Inscription();
        $inscription->setDateInscription(new DateTime('now'));
        $inscription->setSortie($sortie);
        $inscription->setUser($user);
        $user->addInscription($inscription);
        $sortie->addInscription($inscription);

        //Si encore de la place, on ajoute l'user dans la sortie
        if ($nbInscrit < $nbInscritMax) {
            $sortie->addInscription($inscription);
            $entityManager->persist($sortie);
            $entityManager->persist($inscription);
            $this->addFlash('success', 'Vous êtes inscrit à cette sortie.');
            $entityManager->flush();
        } else {
            $this->addFlash('danger', 'Complet, impossible de s\'inscrire');
            return $this->redirectToRoute('default_home');
        }
        return $this->redirectToRoute('default_home');

    }


    /**
     * @Route("desinscrire/{id}", name="desinscrire", methods={"GET"})
     * @param SortieRepository $sortieRepository
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function desinscription(Request                $request,
                                   SortieRepository       $sortieRepository,
                                   EntityManagerInterface $entityManager)
    {
        //Récupération de l'utilisateur connectée
        /** @var User $user */
        $user = $this->getUser();
        if ($user) {
            $idUser = $user->getId();
        } else {
            //Si l'utilisateur n'est pas connecté, on le redirige vers la page d'inscription
            return $this->redirectToRoute('app_login');
        }

        $idSortie = $request->get('id');
        $sortie = $sortieRepository->find($idSortie);

        $tabInscription = $sortie->getInscriptions();
        foreach ($tabInscription as $inscription) {
            if ($inscription->getSortie()->getId() == $sortie->getId()) {
                $desinscription = $inscription;
            }
        }

        $sortie->removeInscription($desinscription);
        $user->removeInscription($desinscription);
        $entityManager->persist($sortie);
        $entityManager->persist($user);
        $entityManager->remove($desinscription);
        $entityManager->flush();
        $this->addFlash('warning', 'Vous êtes désinscrit.');
        return $this->redirectToRoute('default_home');
    }
}
