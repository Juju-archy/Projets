<?php

namespace App\Controller;

use App\Classe\cart;
use App\Classe\mail;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/command/success/{stripSessionId}", name="order_validate")
     */
    public function index(Cart $cart, $stripSessionId)
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripSessionId($stripSessionId);

        if(!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if($order->getState() == 0) {
            //Modify the statut to 1 'Payed'
            $order->setState(1);

            foreach($cart->getFull() as $e) {
                $productQt = $this->entityManager->getRepository(Product::class)->findOneById($e['product']->getId());
                $productQt->setQuantity(($e['product']->getquantity()) - ($e['quantity']));
            }

            //delete the cart
            $cart->remove();

            $this->entityManager->flush();

            //Send a confirmation email to the customer
            $email = new mail();
            $content = "Welcome ".$order->getUser()->getFirstname().".</br><br>Thanks for your order n°".$order->getReference().".</br>";
            $email->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Order success - Daki Suki', $content);
        }

        //Display informations about the order
        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
