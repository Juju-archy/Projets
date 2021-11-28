<?php

namespace App\Controller;

use App\Classe\cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference)
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:8080';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];

        //dd($product_for_stripe);

        Stripe::setApiKey('pk_test_51Jvi6QCJXZiU3n5ADlJ8hKYWIvWD4XiHxSTOomg4Tfy42NQZeu2EbPwKaFe50z6qZUy13s0qeLkLPBWWNjPmzyuB00Gh03Cdfb');

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [
                $product_for_stripe
            ],
            'payment_method_types' => [
                'card',
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/command/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/command/error/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripSessionID($checkout_session->id);
        $entityManager->flush();

        /*$response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;*/
        return $this->redirect($checkout_session->url);
    }
}
