<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;

    /**
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $notification = null;
        $user = new User();
        $registerForm = $this->createForm(RegisterType::class, $user);

        $registerForm->handleRequest($request);

        if($registerForm->isSubmitted() && $registerForm->isValid())
        {
            $user = $registerForm->getData();

            $same_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if(!$same_email)
            {
                $password = $passwordHasher->hashPassword($user,$user->getPassword());
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $mail = new Mail();
                $content = 'Bonjour'.$user->getFirstname().',<br/>Nous sommes heureux de vous compter parmis nous.<br/>
                    Pour commencer, merci de vérifier votre adresse email.<br/>';
                $mail->send($user->getEmail(),
                    $user->getFullname(),
                    'Bienvenue sur Glaira',
                    'Vérification du compte',
                    $content,
                    'Vérifier' );
                $notification = "Un email vous a été envoyé pour vérifier votre compte.";
            }else{
                $notification = "Cet email a déjà été utilisé.";
            }


            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'registerForm' => $registerForm->createView(),
            'notification' => $notification
        ]);
    }
}
