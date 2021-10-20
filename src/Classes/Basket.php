<?php

namespace App\Classes;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Basket
{
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param SessionInterface $session
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }


    /**
     * @param $id
     */
    public function add($id)
    {
        $basket = $this->session->get('basket', []);

        if(!empty($basket[$id]))
        {
            $basket[$id]++;
        }else{
            $basket[$id] = 1;
        }
        return $this->session->set('basket', $basket);
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->session->get('basket');
    }

    /**
     * @return mixed
     */
    public function remove()
    {
        return $this->session->remove('basket');
    }

    public function delete(int $id)
    {
        $basket = $this->session->get('basket', []);
        unset($basket[$id]);
        return $this->session->set('basket', $basket);
    }

    /**
     * @param $id
     */
    public function substract($id)
    {
        $basket = $this->session->get('basket', []);

        if($basket[$id]>1)
        {
            $basket[$id]--;
        }else{
            unset($basket[$id]);
        }
        return $this->session->set('basket', $basket);
    }

    /**
     * @return mixed
     */
    public function getFull()
    {

        $basketComplete = [];

        if($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $product = $this->entityManager->getRepository(Product::class)->findOneById($id);
                if(!$product){
                    $this->delete($id);
                    continue;
                }
                $basketComplete[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
    }
        return $basketComplete;
    }
}