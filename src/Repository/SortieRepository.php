<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function getSorties($campus, $query, $dateDebut, $dateFin, $isOrganisateur,
                               $isInscrit, $isNotInscrit, $passee, $user): array
    {

        $req = $this->createQueryBuilder('s')
            ->innerJoin('s.etat', 'etat')
            ->innerJoin('s.organisateur', 'user')
            ->innerJoin('s.campus', 'campus')
            ->innerJoin('s.lieu', 'lieu')
            ->innerJoin('lieu.ville', 'ville')
            ->leftJoin('s.inscriptions', 'inscriptions')
            ->leftJoin('inscriptions.user', 'user2')
            ->addSelect('inscriptions')
            ->addSelect('user2')
            ->addSelect('etat')
            ->addOrderBy('s.dateHeureDebut', 'ASC')
            ->andWhere("NOT etat.libelle = 'Annulée'")
            ->andWhere('NOT (NOT user.id = :userid and etat.id = 1)')->setParameter('userid', $user->getId());


        if (!empty($query)) {
            $req->andWhere('s.nom like :name')->setParameter('name', '%' . $query . '%');
        }
        if (!empty($campus)) {
            $req->andWhere('campus.id = :camp')->setParameter('camp', $campus);
        }
        if (!empty($dateDebut) && !empty($dateFin)) {
            $req->andWhere('s.dateHeureDebut BETWEEN :datedebut AND :dateFin')
                ->setParameter('datedebut', $dateDebut)
                ->setParameter('dateFin', $dateFin);
        }
        if (!empty($isOrganisateur)) {
            $req->andWhere('user.id = :organisateur')->setParameter('organisateur', $user);
        }

        if (!empty($isInscrit) && $isInscrit === true) {
            $req->andWhere(':inscrit = inscriptions.user')->setParameter('inscrit', $user->getId());
        }

        if (!empty($passee)) {
            $req->andWhere("etat.libelle = 'Passee'");
        } else {
            $req->andWhere("NOT etat.libelle = 'Passee'");
        }

        $sorties = $req->getQuery()->getResult();

        if (!empty($isNotInscrit)) {
            $sortiesFiltrees = [];
            foreach ($sorties as $sortiefiltre) {
                $ok = false;
                $inscriptions = $sortiefiltre->getInscriptions();
                foreach ($inscriptions as $inscriptionfiltre) {
                    if ($inscriptionfiltre->getUser()->getId() === $user->getId()) {
                        $ok = true;
                    }
                }
                if (!$ok) {
                    $sortiesFiltrees[] = $sortiefiltre;
                }
            }
            $sorties = $sortiesFiltrees;
        }
        return $sorties;
    }

}
