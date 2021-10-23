<?php

namespace App\Controller;

use App\Classes\Basket;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{

    /**
     * @Route("/mon-panier", name="basket")
     */
    public function index(Basket $basket): Response
    {
        return $this->render('basket/index.html.twig', [
            'basket' => $basket->getFull()
        ]);
    }

    /**
     * @Route("/mon-panier/ajouter/{id}", name="add_to_basket")
     */
    public function add(Basket $basket,int $id, Request $request): Response
    {

        $basket->add($id);

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/mon-panier/ajouter-panier/{id}", name="add_and_stay_basket")
     */
    public function addAndStayBasket(Basket $basket,int $id): Response
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

    /**
     * @Route("/mon-panier/soustraire/{id}", name="substract_to_basket")
     */
    public function substract(Basket $basket,int $id): Response
    {

        $basket->substract($id);

        return $this->redirectToRoute('basket');
    }
}
