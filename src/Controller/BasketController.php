<?php

namespace App\Controller;

use App\Classes\Basket;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
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
     * @Route("/mon-panier", name="basket")
     */
    public function index(Basket $basket): Response
    {
        $basketComplete = [];

        foreach ($basket->get() as $id => $quantity)
        {
            $basketComplete[] = [
                'product' => $this->entityManager->getRepository(Product::class)->findOneById($id),
                'quantity' => $quantity
            ];
        }

        return $this->render('basket/index.html.twig', [
            'basket' => $basketComplete
        ]);
    }

    /**
     * @Route("/mon-panier/ajouter/{id}", name="add_to_basket")
     */
    public function add(Basket $basket,int $id): Response
    {

        $basket->add($id);

        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("/mon-panier/supprimer", name="remove_my_basket")
     */
    public function remove(Basket $basket): Response
    {

        $basket->remove();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/mon-panier/supprimer-produit/{id}", name="delete_from_basket")
     */
    public function delete(Basket $basket, int $id): Response
    {

        $basket->delete($id);

        return $this->redirectToRoute('basket');
    }
}
