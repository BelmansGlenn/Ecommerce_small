<?php

namespace App\Controller;

use App\Classes\Basket;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Extension\CoreExtension;

class OrderController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/commande", name="order")
     */
    public function index(Basket $basket, Request $request, Environment $environment): Response
    {
        if(!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('account_address_add');
        }


        $ValidationForm = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'ValidationForm' => $ValidationForm->createView(),
            'basket' => $basket->getFull()
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="order_recap", methods={"POST"})
     */
    public function add(Basket $basket, Request $request): Response
    {

        $ValidationForm = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $ValidationForm->handleRequest($request);

        if ($ValidationForm->isSubmitted() && $ValidationForm->isValid())
        {
            $date = new \DateTime();
            $carriers = $ValidationForm->get('carriers')->getData();
            $delivery = $ValidationForm->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname().'<br>';
            if ($delivery->getCompany())
            {
                $delivery_content .= $delivery->getCompany();
            }
            $delivery_content .= $delivery->getAddress().'<br>';
            $delivery_content .= $delivery->getPostal().' '.$delivery->getCity().'<br>';
            $delivery_content .= $delivery->getCountry().'<br>';
            $delivery_content .= $delivery->getPhone().'<br>';
            //Order
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);


            foreach ($basket->getFull() as $product)
            {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);
            }
            $this->entityManager->flush();

            return $this->render('order/add.html.twig', [
                'basket' => $basket->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content
            ]);
        }

        return $this->redirectToRoute('basket');


    }
}
