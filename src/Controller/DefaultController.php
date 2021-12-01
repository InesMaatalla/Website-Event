<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/", name="default_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route(path="", name="", methods={"GET", "POST"})
     * @Route(path="home", name="home", methods={"GET", "POST"})
     */
    public function home(Request $request, EntityManagerInterface $entityManager)
    {
        //Récupération de l'utilisateur connectée
        /** @var User $user */
        $user = $this->getUser();
        $idUser = null;
        if ($user) {
            $idUser = $user->getId();
        } else {
            //Si l'utilisateur n'est pas connecté, on le redirige vers la page d'inscription+
            return $this->redirectToRoute('app_login');
        }
        // Création du formulaire
        $formSearch = $this->createForm('App\Form\SortieSearchFormType');

        // Récupérer les données envoyées par le navigateur et les transmettre au formulaire
        $formSearch->handleRequest($request);
        $query = null;

        //Récupération de la liste initiale à afficher (sans filtre)
        $campus = null;
        $query = null;
        $dateDebut = null;
        $dateFin = null;
        $isOrganisateur = null;
        $isInscrit = null;
        $isNotInscrit = null;
        $passee = null;
        $sorties = $entityManager->getRepository('App:Sortie')
            ->getSorties($campus, $query, $dateDebut, $dateFin, $isOrganisateur,
                $isInscrit, $isNotInscrit, $passee, $user);

        // Vérifier les données du formulaire
        if ($formSearch->isSubmitted()) {
            if ($formSearch->get('submit')->isClicked()) {
                $campus = $formSearch->get('campus')->getData();
                $query = $formSearch->get('query')->getData();
                $dateDebut = $formSearch->get('dateDebut')->getData();
                $dateFin = $formSearch->get('dateFin')->getData();
                $isOrganisateur = $formSearch->get('isOrganisateur')->getData();
                $isInscrit = $formSearch->get('isInscrit')->getData();
                $isNotInscrit = $formSearch->get('isNotInscrit')->getData();
                $passee = $formSearch->get('passee')->getData();
                if ($dateDebut > $dateFin) {
                    $this->addFlash('danger', 'Les dates saisies sont invalides!');
                    return $this->redirectToRoute('default_home');
                }
                $sorties = $entityManager->getRepository('App:Sortie')
                    ->getSorties($campus, $query, $dateDebut, $dateFin, $isOrganisateur,
                        $isInscrit, $isNotInscrit, $passee, $user);

            }
            if ($formSearch->get('reset')->isClicked()) {
                return $this->redirectToRoute('default_home');
            }
//            return $this->render('default/home.html.twig', ['formSearch' => $formSearch->createView(), 'sorties' => $sorties]);

        }

        return $this->render('default/home.html.twig', ['formSearch' => $formSearch->createView(), 'sorties' => $sorties]);

    }
}
