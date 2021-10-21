<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
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
     * @Route("/mon-compte/adresses", name="account_address")
     */
    public function index(): Response
    {

        return $this->render('account/address.html.twig');
    }

    /**
     * @Route("/mon-compte/adresses/ajouter", name="account_address_add")
     */
    public function add(Request $request): Response
    {
        $address = new Address();

        $addressForm = $this->createForm(AddressType::class, $address);

        $addressForm->handleRequest($request);

        if($addressForm->isSubmitted() && $addressForm->isValid())
        {
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'addressForm' => $addressForm->createView()
        ]);
    }


    /**
     * @Route("/mon-compte/modifier-une-adresse/{id}", name="account_address_edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);



        if(!$address || $address->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('account_address');
        }

        $addressFormEdit = $this->createForm(AddressType::class, $address);
        $addressFormEdit->handleRequest($request);

        if($addressFormEdit->isSubmitted() && $addressFormEdit->isValid())
        {
            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'addressForm' => $addressFormEdit->createView()
        ]);
    }

    /**
     * @Route("/mon-compte/supprimer-une-adresse/{id}", name="account_address_remove")
     */
    public function remove(int $id)
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if($address && $address->getUser() == $this->getUser())
        {
            $this->entityManager->remove($address);
            $this->entityManager->flush();

        }
        return $this->redirectToRoute('account_address');

    }
}
