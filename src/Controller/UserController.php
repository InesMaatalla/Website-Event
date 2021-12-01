<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;



class UserController extends AbstractController
{
    /**
     * @Route("/afficherProfil/{id}", name="afficherProfil", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function afficherProfil(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');
        $user = $userRepository->find($id);
        return $this->render('participant/afficherProfil.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/monProfil", name="monProfil", methods={"GET", "POST"})
     */
    public function monProfil(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userForm = $this->createForm(UserFormType::class, $user, [
            'validation_groups' => ['monProfil'],
        ]);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $user->setPassword($encoder->hashPassword($user, $user->getPlainPassword()));

            $uploadedFile = $userForm['imageFilename']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/user_image';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $user->setImageFilename($newFilename);
            }
//            $users=$em->getRepository('App:User')->findAll();
//            foreach ($users as $userbase){
//                if ($userbase->getId() != $user->getId() && $userbase->getEmail() == $userForm->get('email')->getData()){
//                    $this->addFlash('danger', 'Cette adresse mail existe déjà');
//                    $userForm = $this->createForm(UserFormType::class, $user);
//                    return $this->render('participant/monProfil.html.twig', [
//                        'userForm' => $userForm->createView(),
//                    ]);
//                }
//                if ($userbase->getId() != $user->getId() && $userbase->getPseudo() == $userForm->get('pseudo')->getData()){
//                    $this->addFlash('danger', 'Ce pseudo existe déjà');
//                    return $this->render('participant/monProfil.html.twig', [
//                        'userForm' => $userForm->createView(),
//                    ]);
//                }

//            }

           if($userForm->isValid()){
//               try {
                   $user->setPassword($encoder->hashPassword($user, $user->getPlainPassword()));
                   $em->persist($user);
                   $em->flush();
                   $this->addFlash('success', 'Le profil a été enregistré');
                   return $this->redirectToRoute('monProfil');
//               } catch (UniqueConstraintViolationException $ex){
//                   $this->addFlash('danger', 'Le pseudo ou l\'email existent déjà');
//                   return $this->render('participant/monProfil.html.twig', [
//                        'userForm' => $userForm->createView(),
//                    ]);
//               }
            }
        }

        return $this->render('participant/monProfil.html.twig', [
            'userForm' => $userForm->createView(),
        ]);

    }
}
