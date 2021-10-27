<?php

namespace App\Controller;

use App\Classes\Basket;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
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
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);

            $productsStripe = [];
            $YOUR_DOMAIN = 'http://127.0.0.1:8000';

            foreach ($basket->getFull() as $product)
            {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);

                $productsStripe[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $product['product']->getPrice(),
                        'product_data' => [
                            'name' => $product['product']->getName(),
                            'images' => [$YOUR_DOMAIN.'/assets/img/products/'.$product['product']->getIllustration()]
                        ]
                    ],
                    'quantity' => $product['quantity'],
                ];

            }
            $productsStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => ($carriers->getPrice())*100,
                    'product_data' => [
                        'name' => $carriers->getName(),
                    ]
                ],
                'quantity' => 1,
            ];

            //$this->entityManager->flush();

            Stripe::setApiKey('sk_test_51JooPjHLeiEOI7bdV7JLIEojGYOLCEGdsEKz3w6vWM65xSOYzvbVIdEJbk2MRvOY433qS0Rd94wEyqrGmoebHNv600dpntcIaR');


            $checkout_session = Session::create([
                'customer_email' => $this->getUser()->getEmail(),
                'line_items' => [[
                    $productsStripe
                ]],
                'payment_method_types' => [
                    'card',
                    'bancontact',
                ],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/paiement-accepte/{CHECKOUT_SESSION_ID}',
                'cancel_url' => $YOUR_DOMAIN . '/paiement-refuse/{CHECKOUT_SESSION_ID}',
            ]);

            $order->setStripeSessionId($checkout_session->id);
            $this->entityManager->flush();

            return $this->render('order/add.html.twig', [
                'basket' => $basket->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'stripe_checkout' => $checkout_session->url
            ]);
        }

        return $this->redirectToRoute('basket');


    }
}
