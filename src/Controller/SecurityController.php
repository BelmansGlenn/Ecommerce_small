<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
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
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('account');
         }
         if($request->get('notification') === 'Votre compte a été vérifié')
         {
             $notification = 'Votre compte a été vérifié';
         }elseif ($request->get('notification') === 'Une erreur est intervenue lors de la vérification de votre compte')
         {
             $notification = 'Une erreur est intervenue lors de la vérification de votre compte';
         }else{
             $notification = null;
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'notification' =>$notification
        ]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/confirm/{confirmationToken}", name="confirm")
     */
    public function confirm($confirmationToken)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneByConfirmationToken($confirmationToken);
        if($user)
        {
            $user->setConfirmationToken(null);
            $this->entityManager->flush();
            $notification = 'Votre compte a été vérifié';
            return $this->redirectToRoute('app_login', ['notification' => $notification]);
        }else{
            $notification = 'Une erreur est intervenue lors de la vérification de votre compte';
            return $this->redirectToRoute('app_login', ['notification' => $notification]);
        }
    }
}
