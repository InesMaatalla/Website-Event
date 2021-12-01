<?php

namespace App\Controller;

use App\Entity\ImportCSV;
use App\Entity\User;
use App\Form\CSVFormType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
class CSVController extends AbstractController
{
    /**
     * @Route("/ajoutUser", name="_ajoutListeParticipant")
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @param CampusRepository $campusRepository
     * @param SluggerInterface $slugger
     * @param ContainerInterface $container
     * @return Response
     */
    public function ajoutParticipant(Request                     $request,
                                     UserPasswordHasherInterface $passwordEncoder,
                                     EntityManagerInterface      $entityManager,
                                     CampusRepository            $campusRepository,
                                     SluggerInterface            $slugger,
                                     ContainerInterface $container): Response
    {


        $import = new ImportCSV($container);
        $form = $this->createForm(CSVFormType::class, $import);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('csvFileName')->getData();

            if ($csvFile) {
                $originalFilename = pathinfo($csvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.csv';
            }
            try {
                $csvFile->move(
                 $this->getParameter('csv_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $import->setCsvFileName( $newFilename);
            $users = $import->importCSV($campusRepository);
            dump($users);
            foreach ($users as $user) {
                $user = new User();
                $user->setEmail();
                if ($user->getRoles()) {
                    $user->setRoles(["ROLE_ADMIN"]);
                } else {
                    $user->setRoles(["ROLE_USER"]);
                }
                $user->setPassword(
                    $passwordEncoder->hashPassword(
                        $user,
                        '1234'
                    )
                );
                $user->setPrenom();
                $user->setTelephone();
                $user->setActif(true);
                $user->setPseudo();
                $user->setNom();
                $user->setImageFilename('logo2.png');
                $user->setCampus()->getId();

                $entityManager->persist($user);
            }
            $entityManager->flush();

            return $this->redirectToRoute('default_home');
        }

        return $this->render('upload_file/CSV.html.twig', [
            'formCSV' => $form->createView()
        ]);
    }
}