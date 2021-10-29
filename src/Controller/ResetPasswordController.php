<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
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
     * @Route("/mot-de-passe-oublie", name="reset_password")
     */
    public function index(Request $request): Response
    {
        $notification = null;
        if($this->getUser())
        {
            return $this->redirectToRoute('home');
        }
        if($request->get('notification'))
        {
            $notification = 'Une erreur est intervenue lors de la réinitialisation de votre mot de passe';
        }
        if($request->get('email'))
        {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if ($user)
            {
                $newPassword = new ResetPassword();
                $newPassword->setUser($user);
                $newPassword->setToken(str_replace('/', '', password_hash(uniqid(), PASSWORD_BCRYPT)));
                $newPassword->setCreatedAt(new \DateTime());
                $this->entityManager->persist($newPassword);
                $this->entityManager->flush();

                $mail = new Mail();
                $mail->send(
                    $user->getEmail(),
                    $user->getFullName(),
                    'Mot de passe oublié',
                    'Réinitialisation du mot de passe<br/><br/>',
                    'Bonjour'.$user->getFirstname().',<br/>Veuillez cliquer sur ce lien pour modifier votre mot de passe,<br/><br/>
                             Attention: Ce lien est valide pendant 1h<br/><br/>',
                    'modifier-mot-de-passe/'.urlencode($newPassword->getToken()),
                    'Modifier votre mot de passe'
                );
                $notification = "Un email vous a été envoyé pour modifier votre mot de passe.";
            }
        }else {
            $notification = "Cet email est inconnue.";
        }

        return $this->render('reset_password/index.html.twig',[
            'notification' => $notification
        ]);
    }


    /**
     * @Route("/modifier-mot-de-passe/{token}", name="modify_password")
     */
    public function update($token, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if(!$password)
        {
            $notification = 'Une erreur est intervenue lors de la réinitialisation de votre mot de passe.';
            return $this->redirectToRoute('reset_password', ['notification' => $notification]);
        }
        $now = new \DateTime();
        if ($now > $password->getCreatedAt()->modify('+1 hour'))
        {
            $notification = 'Votre demande de mot de passe a expiré. Merci de la renouveller.';
            return $this->redirectToRoute('reset_password');
        }

        $changePassword = $this->createForm(ResetPasswordType::class);

        $changePassword->handleRequest($request);

        if ($changePassword->isSubmitted() && $changePassword->isValid())
        {
                $new_password = $changePassword->get('new_password')->getData();
                $passwordModify = $passwordHasher->hashPassword($password->getUser(), $new_password);

                $password->getUser()->setPassword($passwordModify);
                $this->entityManager->flush();
                $notification = "Votre mot de passe a bien été mis à jour.";
                return $this->redirectToRoute('app_login', [
                    'notification' => $notification
                ]);

        }

        return $this->render('reset_password/reset.html.twig',[
            'changePasswordForm' => $changePassword->createView()
        ]);
    }
}
