<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
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
     * @Route("/mon-compte/mes-commandes", name="account_order")
     */
    public function index(): Response
    {

        $orders = $this->entityManager->getRepository(Order::class)->findBySuccessOrders($this->getUser());


        return $this->render('account/account_order.html.twig', [
            'ordersSuccess' => $orders
        ]);
    }
}
