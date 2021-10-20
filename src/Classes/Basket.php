<?php

namespace App\Classes;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Basket
{
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
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
        $this->session->set('basket', $basket);
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
}