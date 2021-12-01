<?php

namespace App\Controller;

use App\Entity\Campus;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/admin/campus/", name="campus_")
 */

class CampusController extends AbstractController
{
    /**
     * @Route(path="", name="list", methods={"GET", "POST"})
     */

    public function addCampus(Request $req, EntityManagerInterface $em){

        $campus = new Campus();

        $formCampus = $this->createForm('App\Form\CampusType', $campus);
        $formCampus->handleRequest($req);

        $formSearchCampus = $this->createForm('App\Form\SearchType');
        $formSearchCampus->handleRequest($req);

        if($formCampus->isSubmitted() && $formCampus->isValid()){
            try {
                $em->persist($campus);
                $em->flush();
                $this->addFlash('success', 'Campus ajouté avec succès');
            }catch (UniqueConstraintViolationException $exception){
                $this->addFlash('danger', 'Ce campus existe déja!');
                return $this->redirectToRoute('campus_list');
            }
        }

        $camps = $formSearchCampus->isEmpty()
                ? $em->getRepository('App:Campus')->findAll()
                : $em->getRepository('App:Campus')->getCampus($formSearchCampus->get('query')->getData());

        sort($camps, SORT_STRING);

        return $this->render('admin/campus.html.twig', ['form' => $formCampus->createView(), 'formSearchCampus' =>$formSearchCampus->createView(), 'camps' => $camps]);
    }

    /**
     * @Route(path="{id}/remove", requirements={"id" : "\d+"}, name="remove", methods={"GET"})
     */

    public function removeCampus(int $id, EntityManagerInterface $em){

        $campus = $em->getRepository('App:Campus')->find($id);

        $em->remove($campus);
        $em->flush();

        return $this->redirectToRoute('campus_list');
    }

    /**
     * @Route(path="{id}/edit", requirements={"id" : "\d+"}, name="edit", methods={"GET", "POST"})
     */

    public function editCampus(int $id, Request $req, EntityManagerInterface $em){

        $campus = $em->getRepository('App:Campus')->find($id);

        $formCampus = $this->createForm('App\Form\CampusType', $campus);
        $formCampus->handleRequest($req);

        $formSearchCampus = $this->createForm('App\Form\SearchType');
        $formSearchCampus->handleRequest($req);

        if($formCampus->isSubmitted() && $formCampus->isValid()){
            try{
                $em->persist($campus);
                $em->flush();
                $this->addFlash('success', 'Campus modifié avec succès');
            }catch(UniqueConstraintViolationException $exception){
                $this->addFlash('danger', 'Ce campus existe déja!');
                return $this->redirectToRoute('campus_list');
            }

            return $this->redirectToRoute('campus_list');
        }

        $camps = $em->getRepository('App:Campus')->findAll();

        sort($camps, SORT_STRING);

        return $this->render('admin/campusEdit.html.twig', ['form' => $formCampus->createView(), 'formSearchCampus' => $formSearchCampus->createView(), 'camps' => $camps, 'id' => $id]);

    }
}