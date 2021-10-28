<?php

namespace App\Controller;

use App\Classes\Basket;
use App\Classes\Mail;
use App\Entity\Order;
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

        if(!$myOrder || $myOrder->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }
        if (!$myOrder->getisPaid())
        {
            $basket->remove();
            $myOrder->setIsPaid(1);
            $this->entityManager->flush();
            $mail = new Mail();
            $mail->send();
        }

        //email pour success

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

        //email pour failed

        return $this->render('after_payment/failed.html.twig', [
            'failedOrder' => $myOrder
        ]);
    }
}
