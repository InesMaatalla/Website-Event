<?php

namespace App\Repository;

use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }

    /**
    * @return Ville[]
    */

    public function getVille(?string $query){

        $villes = $this->createQueryBuilder('ville')
            ->where('ville.nom Like :query')
            ->setParameter('query', '%'.$query.'%')
            ->getQuery()->getResult();

        return $villes;
    }
    public function findVilleByName(string $nom){
        $req = $this->createQueryBuilder('ville')
            ->select('ville')
            ->where('ville.nom = :nom')->setParameter('nom', $nom);

        return $req->getQuery()->getResult();
    }
}
