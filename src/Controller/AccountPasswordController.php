<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/moncompte/modifier-mot-de-passe", name="account_password")
     */
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $notification = null;
        $user = $this->getUser();
        $changePasswordForm = $this->createForm(ChangePasswordType::class, $user);

        $changePasswordForm-> handleRequest($request);
        if($changePasswordForm->isSubmitted() && $changePasswordForm->isValid())
        {
            $old_password = $changePasswordForm->get('old_password')->getData();
            if($passwordHasher->isPasswordValid($user, $old_password))
            {
                $new_password = $changePasswordForm->get('new_password')->getData();
                $password = $passwordHasher->hashPassword($user, $new_password);

                $user->setPassword($password);
                $this->entityManager->flush();
                $notification = "Votre mot de passe a bien été mis à jour.";
            } else {
                $notification = "Votre mot de passe actuel n'est pas le bon.";
            }
        }

        return $this->render('account/password.html.twig', [
            'changePasswordForm' => $changePasswordForm->createView(),
            'notification' => $notification
        ]);
    }
}
