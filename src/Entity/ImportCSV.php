<?php


namespace App\Entity;

use App\Repository\CampusRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ImportCSV
{

    /**
     * @var
     */
    private $csvFileName;

    /**
     * @var ContainerInterface
     */
    private $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function doSomething()
    {
        $this->container->getParameter('%kernel.project_dir%'); // <- Access your param
    }

    public function importCSV(CampusRepository $campusRepository): array
    {

        $lecture = fopen(
            $this->container->getParameter('kernel.project_dir') . '/public/uploads/fichierCSV/' . $this->csvFileName, 'r');
        $listCampus = $campusRepository->findAll();
        dump($listCampus);
        while(!feof($lecture))
        {
            $ligne = fgets($lecture);
            dump($ligne);
            $separation = explode(';', $ligne);
            $user = new User();

            if($ligne != false)
            {
                if(isset($separation[5])){
                    $user->setPrenom($separation[5]);
                }
                if(isset($separation[6])){
                    $user->setTelephone($separation[6]);
                }
                if(isset($separation[2]))
                {
                    $user->setEmail($separation[2]);
                }
                if(isset($separation[10]))
                {
                    $user->setImageFilename($separation[10]);
                }
                if(isset($separation[8]))
                {
                    $user->setPseudo($separation[8]);
                }
                if(isset($separation[9]))
                {
                    $user->setNom($separation[9]);
                }

                if(isset($separation[7]))
                {
                    $user->setActif($separation[7]);
                }
                if(isset($separation[4]))
                {
                    $user->setPassword($separation[4]);
                }
                if(isset($separation[1]))
                {
                    foreach($listCampus as $campus)
                    {
                        if($campus->getNom() == strtoupper($separation[2]))
                        {
                            $user->setCampus($campus);
                        }
                    }
                }
                if(isset($separation[3]))
                {
                    if(str_starts_with($separation[3], "oui")){
                        $user->setRoles((array)true);
                    }
                    else
                    {
                        $user->setRoles((array)false);
                    }
                }
                $users[] = $user;
            }
    }
        return $users;
}

    public function getCsvFileName()
    {
        return $this->csvFileName;
    }

    public function setCsvFileName($csvFileName): self
    {
        $this->csvFileName = $csvFileName;
        return $this;
    }

}