<?php

namespace App\Controller;

use App\Classe\cart;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/", name="stripe_create_session")
     */
    public function index(Cart $cart)
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:8080';

        foreach ($cart->getFull() as $product) {
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()],
                    ],
                ],
                'quantity' => $product['quantity'],
            ];
        }

        Stripe::setApiKey('sk_test_51Jvi6QCJXZiU3n5Amx7XJ2A8v55a5dIKqBTwnX3dW0c4ScRNsy4QygvmgSMc2Wd0Edk2QaRE5iBBIfznah7hjqOC009hc0nVur');

        $checkout_session = Session::create([
            'line_items' => [
                $product_for_stripe
            ],
            'payment_method_types' => [
                'card',
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        /*$response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;*/
        return $this->redirect($checkout_session->url);
    }
}
