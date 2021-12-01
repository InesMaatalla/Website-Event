<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lieu/", name="lieu_")
 */
class LieuController extends AbstractController
{
    /**
     * @Route("create", name="create", methods={"GET", "POST"})
     */
    public function addLieu(Request $req, EntityManagerInterface $em): JsonResponse
    {
//        dump($req);
//        exit();
        $lieuAjoute = new Lieu();
        $nom = (string)$req->query->get('nom');
        $rue = (string)$req->query->get('rue');
        $latitude = (float)$req->query->get('latitude');
        $longitude = (float)$req->query->get('longitude');
        $ville = $em->getRepository(Ville::class)->find((int)$req->query->get('villeId'));
//dump($nom);
//dump($rue);
//dump($latitude);
//dump($longitude);
//dump($ville);
//exit();
        $lieuAjoute->setNom('lieu');
        $lieuAjoute->setRue($rue);
        $lieuAjoute->setLatitude($latitude);
        $lieuAjoute->setLongitude($longitude);
        $lieuAjoute->setVille($ville);

        $em->persist($lieuAjoute);
        $em->flush();
        return new JsonResponse($lieuAjoute);
    }

}
