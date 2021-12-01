<?php

namespace App\Controller;

use App\Entity\Ville;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/admin/ville/", name="ville_")
 */

class VilleController extends AbstractController
{
    /**
     * @Route(path="", name="list", methods={"GET", "POST"})
     */

    public function addVille(Request $req, EntityManagerInterface $em){

        $ville = New Ville();

        $formVille = $this->createForm('App\Form\VilleType', $ville);
        $formVille->handleRequest($req);

        $formSearchVille = $this->createForm('App\Form\SearchType');
        $formSearchVille->handleRequest($req);

        if($formVille->isSubmitted() && $formVille->isValid()){
            try {
                $em->persist($ville);
                $em->flush();
                $this->addFlash('success', 'Ville ajoutée avec succès!');
            }catch(UniqueConstraintViolationException $exception){
                $this->addFlash('danger', 'Ce code postal existe déjà!');
                return $this->redirectToRoute('ville_list');
            }
        }

        $cities = $formSearchVille->isEmpty()
            ? $em->getRepository('App:Ville')->findAll()
            : $em->getRepository('App:Ville')->getVille($formSearchVille->get('query')->getData());

        uasort($cities, function (Ville $a, Ville $b) {
            if($a->getNom() == $b->getNom()){
                if($a->getCodePostal() == $b->getCodePostal()){
                    return 0;
                }
                return $a->getCodePostal() < $b->getCodePostal()
                    ? -1
                    : 1;
            }
            return $a->getNom() < $b->getNom()? -1 : 1;
        });

        return $this->render('admin/villes.html.twig', ['form' => $formVille->createView(), 'formSearchVille' => $formSearchVille->createView(), 'cities' => $cities]);

    }

    /**
     * @Route(path="{id}/remove", requirements={"id" : "\d+"}, name="remove", methods={"GET"})
     */

    public function removeVille(int $id, EntityManagerInterface $em){

        $ville = $em->getRepository('App:Ville')->find($id);

        $em->remove($ville);
        $em->flush();

        return $this->redirectToRoute('ville_list');
    }

    /**
     * @Route(path="{id}/edit", requirements={"id" : "\d+"}, name="edit", methods={"GET", "POST"})
     */

    public function editVille(int $id, Request $req, EntityManagerInterface $em){

        $ville = $em->getRepository('App:Ville')->find($id);

        $formVille = $this->createForm('App\Form\VilleType', $ville);
        $formVille->handleRequest($req);

        $formSearchVille = $this->createForm('App\Form\SearchType');
        $formSearchVille->handleRequest($req);

        try {
            $em->persist($ville);
            $em->flush();
        }catch (UniqueConstraintViolationException $exception){
            $this->addFlash('danger', 'Ce code postal existe déjà!');
            return $this->redirectToRoute('ville_list');
        }

        $cities = $em->getRepository('App:Ville')->findAll();
        uasort($cities, function (Ville $a, Ville $b) {
            if($a->getNom() == $b->getNom()){
                if($a->getCodePostal() == $b->getCodePostal()){
                    return 0;
                }
                return $a->getCodePostal() < $b->getCodePostal()
                    ? -1
                    : 1;
            }
            return $a->getNom() < $b->getNom()? -1 : 1;
        });

        return $this->render('admin/villeEdit.html.twig', ['form' =>$formVille->createView(), 'formSearchVille' => $formSearchVille->createView(), 'cities' => $cities, 'id' => $id]);

    }


}