<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class AllFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //        CAMPUS
        $campus1 = new Campus();
        $campus1->setNom('Saint-Herblain');
        $campus2 = new Campus();
        $campus2->setNom('Chartres de Bretagne');
        $campus3 = new Campus();
        $campus3->setNom('La Roche sur Yon');



        //          ETAT

        $etat1 = new Etat();
        $etat1->setLibelle('Créée');
        $etat2 = new Etat();
        $etat2->setLibelle('Ouverte');
        $etat3 = new Etat();
        $etat3->setLibelle('Clôturée');
        $etat4 = new Etat();
        $etat4->setLibelle('Activité en cours');
        $etat5 = new Etat();
        $etat5->setLibelle('Passée');
        $etat6 = new Etat();
        $etat6->setLibelle('Annulée');



        //          LIEU

        $ville1 = new Ville();
        $ville1->setNom('Orvault')->setCodePostal('44700');
        $lieu1 = new Lieu();
        $lieu1->setNom('Lieu 1')
            ->setVille($ville1) ->setRue('Rue du pain')->setLatitude( 47.270)->setLongitude( -1.616);
        $ville2 = new Ville();
        $ville2->setNom('St-Nazaire')->setCodePostal('44600');
        $lieu2 = new Lieu();
        $lieu2->setNom('Lieu 2')
            ->setVille($ville2) ->setRue('Rue des chantiers')->setLatitude(47.279)->setLongitude(2.219);

        $ville3 = new Ville();
        $ville3->setNom('Nantes')->setCodePostal('44000');
        $lieu3 = new Lieu();
        $lieu3->setNom('Lieu 3')
            ->setVille($ville3) ->setRue('Rue benjamin franklin')->setLatitude(47.218)->setLongitude(-1.553);



        //        USER
        $user1 = new User();
        $user1->setCampus($campus1);
        $user1->setEmail('john@smith.fr');
        $user1->setRoles((array)'ROLE_USER');
        $user1->setPassword(password_hash('123456789', PASSWORD_DEFAULT));
        $user1->setPrenom('John');
        $user1->setTelephone('0656595233');
        $user1->setActif('1');
        $user1->setPseudo('Jojo');
        $user1->setNom('Smith');
        $user1->setImageFilename('smith-61278c80396ed.jpg');

        $user2 = new User();
        $user2->setCampus($campus3);
        $user2->setEmail('emma@durand.fr');
        $user2->setRoles((array)'ROLE_USER');
        $user2->setPassword(password_hash('123456789', PASSWORD_DEFAULT));
        $user2->setPrenom('Emma');
        $user2->setTelephone('0656595233');
        $user2->setActif('1');
        $user2->setPseudo('Em');
        $user2->setNom('Durand');
        $user2->setImageFilename('femme2-61278bf83cdce.jpg');

        $user3 = new User();
        $user3->setCampus($campus2);
        $user3->setEmail('leo@medina.fr');
        $user3->setRoles((array)'ROLE_USER');
        $user3->setPassword(password_hash('123456789', PASSWORD_DEFAULT));
        $user3->setPrenom('Leo');
        $user3->setTelephone('0641216597');
        $user3->setActif('1');
        $user3->setPseudo('Lolo');
        $user3->setNom('Medina');
        $user3->setImageFilename('neymar-61278d06692a6.jpg');

        $user4 = new User();
        $user4->setCampus($campus3);
        $user4->setEmail('jade@bonnet.fr');
        $user4->setRoles((array)'ROLE_USER');
        $user4->setPassword(password_hash('123456789', PASSWORD_DEFAULT));
        $user4->setPrenom('Jade');
        $user4->setTelephone('0623265499');
        $user4->setActif('1');
        $user4->setPseudo('Jaja');
        $user4->setNom('Bonnet');
        $user4->setImageFilename('femmejpg-61278a867c2ba.jpg');

        $user5 = new User();
        $user5->setCampus($campus1);
        $user5->setEmail('admin44@admin.fr');
        $user5->setRoles((array)'ROLE_ADMIN');
        $user5->setPassword(password_hash('123456789', PASSWORD_DEFAULT));
        $user5->setPrenom('Admin');
        $user5->setTelephone('0645487895');
        $user5->setActif('1');
        $user5->setPseudo('Admin44');
        $user5->setNom('Administrateur');
        $user5->setImageFilename('admin-61278dce435ce.png');



        //      SORTIE


        // Activité en cours
        $sortie1 = new Sortie();
        $sortie1->setEtat($etat4);
        $sortie1->setCampus($campus1);
        $sortie1->setLieu($lieu1);
        $sortie1->setOrganisateur($user1);
        $sortie1->setNom('Match de Basketball');
        $sortie1->setDateHeureDebut(new DateTime('2021-08-27 12:20:00 PM'));
        $sortie1->setDuree(90);
        $sortie1->setDateLimiteInscription(new DateTime('2021-08-26 2:30:00 PM'));
        $sortie1->setNbInscriptionsMax(10);
        $sortie1->setInfosSortie('');


        // Activité passée
        $sortie2 = new Sortie();
        $sortie2->setEtat($etat5);
        $sortie2->setCampus($campus2);
        $sortie2->setLieu($lieu2);
        $sortie2->setOrganisateur($user2);
        $sortie2->setNom('Restaurant');
        $sortie2->setDateHeureDebut(new DateTime('2021-08-10 '));
        $sortie2->setDuree(90);
        $sortie2->setDateLimiteInscription(new DateTime('2021-08-05'));
        $sortie2->setNbInscriptionsMax(2);
        $sortie2->setInfosSortie('');

        // Activité annulée
        $sortie3 = new Sortie();
        $sortie3->setEtat($etat6);
        $sortie3->setCampus($campus3);
        $sortie3->setLieu($lieu2);
        $sortie3->setOrganisateur($user3);
        $sortie3->setNom('Paddle');
        $sortie3->setDateHeureDebut(new DateTime('2021-07-22 '));
        $sortie3->setDuree(120);
        $sortie3->setDateLimiteInscription(new DateTime('2021-08-21'));
        $sortie3->setNbInscriptionsMax(4);
        $sortie3->setInfosSortie('');


        // Activité clôturée
        $sortie4 = new Sortie();
        $sortie4->setEtat($etat3);
        $sortie4->setCampus($campus1);
        $sortie4->setLieu($lieu3);
        $sortie4->setOrganisateur($user4);
        $sortie4->setNom('Soirée jeux vidéo');
        $sortie4->setDateHeureDebut(new DateTime('2021-07-27 '));
        $sortie4->setDuree(960);
        $sortie4->setDateLimiteInscription(new DateTime('2021-08-27'));
        $sortie4->setNbInscriptionsMax(4);
        $sortie4->setInfosSortie('');

        // Activité Ouverte
        $sortie5 = new Sortie();
        $sortie5->setEtat($etat2);
        $sortie5->setCampus($campus2);
        $sortie5->setLieu($lieu1);
        $sortie5->setOrganisateur($user1);
        $sortie5->setNom('PaintBall');
        $sortie5->setDateHeureDebut(new DateTime('2021-10-10 '));
        $sortie5->setDuree(30);
        $sortie5->setDateLimiteInscription(new DateTime('2021-10-01'));
        $sortie5->setNbInscriptionsMax(10);
        $sortie5->setInfosSortie('');


        $manager->persist($campus1);
        $manager->persist($campus2);
        $manager->persist($campus3);
        $manager->flush();

        $manager->persist($etat1);
        $manager->persist($etat2);
        $manager->persist($etat3);
        $manager->persist($etat4);
        $manager->persist($etat5);
        $manager->persist($etat6);
        $manager->flush();

        $manager->persist($ville1);
        $manager->persist($ville2);
        $manager->persist($ville3);
        $manager->persist($lieu1);
        $manager->persist($lieu2);
        $manager->persist($lieu3);
        $manager->flush();

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);
        $manager->persist($user5);
        $manager->flush();

        $manager->persist($sortie1);
        $manager->persist($sortie2);
        $manager->persist($sortie3);
        $manager->persist($sortie4);
        $manager->persist($sortie5);
        $manager->flush();
    }
}