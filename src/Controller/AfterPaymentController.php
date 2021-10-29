<?php

namespace App\Controller;

use App\Classes\Basket;
use App\Classes\Mail;
use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AfterPaymentController extends AbstractController
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
     * @Route("/paiement-accepte/{stripeSessionId}", name="success_payment")
     */
    public function success($stripeSessionId, Basket $basket): Response
    {
        $myOrder = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        $orderDetail = $this->entityManager->getRepository(OrderDetails::class)->findOneByMyOrder($myOrder);
        if(!$myOrder || $myOrder->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }
        if (!$myOrder->getisPaid())
        {
            $basket->remove();
            $myOrder->setIsPaid(1);
            $this->entityManager->flush();
            $mail = new Mail();
            $mail->send(
                $this->getUser()->getEmail(),
                $this->getUser()->getFullName(),
                'Confirmation de paiement',
                'Merci pour votre commande',
                'Merci d\'avoir choisi Glaira pour la décoration de votre maison.<br/><br/>
                         Votre commande n°'.$myOrder->getReference().' est en cours de préparation.<br/><br/>
                         <br/><br/>Si vous voulez suivre votre commande, veuillez cliquer sur ce lien.<br/><br/>',
                'mon-compte/mes-commandes',
                'Suivre mes commandes'
            );
        }


        return $this->render('after_payment/success.html.twig', [
            'orderValidated' => $myOrder
        ]);
    }

    /**
     * @Route("/paiement-refuse/{stripeSessionId}", name="failed_payment")
     */
    public function failed($stripeSessionId): Response
    {
        $myOrder = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$myOrder || $myOrder->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }
        $mail = new Mail();
        $mail->send(
            $this->getUser()->getEmail(),
            $this->getUser()->getFullName(),
            'Erreur de paiement',
            'Nous sommes désolé',
            'Merci d\'avoir choisi Glaira, mais malheureusement il semble qu\'il y ait eu une erreur lors du paiement<br/><br/>
                         Votre commande n°'.$myOrder->getReference().' n\'a pas pu etre confirmé.<br/><br/>
                         <br/><br/>Si vous voulez réessayer le paiement, veuillez cliquer sur ce lien.<br/><br/>',
            'commande',
            'Réessayer le paiement'
        );

        return $this->render('after_payment/failed.html.twig', [
            'failedOrder' => $myOrder
        ]);
    }
}
