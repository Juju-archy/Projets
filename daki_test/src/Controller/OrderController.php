<?php

namespace App\Controller;

use App\Entity\CarriersInformation;
use App\Entity\Product;
use App\Entity\User;
use App\Classe\cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/command", name="order")
     */
    public function index(cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('add_address');
        }

        //To display the total weight
        $weightTotal = 50; //50g = weight of parcel
        foreach ($cart->getFull() as $product){
            $weightTotal = $weightTotal + ($product['product']->getWeight() * $product['quantity']);
        }

        $address = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $address->createView(),
            'cart' => $cart->getFull(),
            'totalWeight' => $weightTotal,
        ]);
    }

    /**
     * @Route("/command/summary", name="order_summary",  methods={"POST"})
     */
    public function add(cart $cart, Request $request)
    {
        /*
         * First, we get all carrier (getAll)
         * After, we get the country code of the shipping address (2 digits). We call 'countryCarrier' to define the
         * country (France, EU or World)
         * Calculate the total weight
         * Save Order and Order details
         * On calcule le poids total
         *
         */
        $carriersInfo = $this->entityManager->getRepository(CarriersInformation::class)->findAll();
        $carrierPrice = -1;

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();

            if ($delivery->getCompany()) {
                $delivery_content .= '<br>'.$delivery->getCompany();
            }

            $delivery_content .= '<br>'.$delivery->getAddress();
            $delivery_content .= '<br>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '<br>'.$delivery->getCountry();

            //Enregistrer ma commande Order()
            //Calculate the total weight
            $weightTotal = 50; //50g = weight of parcel
            foreach ($cart->getFull() as $product){
                $weightTotal = $weightTotal + ($product['product']->getWeight() * $product['quantity']);
            }

            //Define the country (France, EU or world)
            $country = countryCarrier($delivery->getCountry());

            foreach ($carriersInfo as $c)
            {
                if($c->getCarrierName() == $carriers->getName()){
                    if($c->getCountry() == $country){
                        if($weightTotal > $c->getWeightMin() && $weightTotal < $c->getWeightMax()){
                            $carrierPrice = $c->getPrice();
                        }
                    }
                }
            }

            //Redirect if no carrier found
            if($carrierPrice < 0){
                return $this->redirectToRoute('order');
            }

            //Save order
            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carrierPrice);
            $order->setDelivery($delivery_content);
            $order->setState(0);
            $order->setTotalWeight($weightTotal);

            $this->entityManager->persist($order);

            //Save order details
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setSlug($product['product']->getSlug());  //ajouter le Slug du produits dans l'entity orderDetails
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $orderDetails->setWeigthTotal($weightTotal);
                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();

            if(!$order){
                return $this->redirectToRoute('order');
            }

            return $this->render('order/add.html.twig', [
                'cart'=>$cart->getFull(),
                'carrier'=>$carriers,
                'delivery'=>$delivery_content,
                'reference' => $order->getReference(),
                'weight' => $weightTotal,
                'carrierPrice' => $carrierPrice,
            ]);
        }
        return $this->redirectToRoute('cart');
    }

}

function countryCarrier($getCountry)
{
    //Select the country for the shipping carrier
    switch ($getCountry) {
        case "FR":
            $country = "France";
            break;
        case "DE":
            $country = "EU";
            break;
        case "AT":
            $country = "EU";
            break;
        case "BE":
            $country = "EU";
            break;
        case "BG":
            $country = "EU";
            break;
        case "CY":
            $country = "EU";
            break;
        case "HR":
            $country = "EU";
            break;
        case "DK":
            $country = "EU";
            break;
        case "ES":
            $country = "EU";
            break;
        case "EE":
            $country = "EU";
            break;
        case "FI":
            $country = "EU";
            break;
        case "GR":
            $country = "EU";
            break;
        case "HU":
            $country = "EU";
            break;
        case "IE":
            $country = "EU";
            break;
        case "IT":
            $country = "EU";
            break;
        case "LV":
            $country = "EU";
            break;
        case "LT":
            $country = "EU";
            break;
        case "MT":
            $country = "EU";
            break;
        case "LU":
            $country = "EU";
            break;
        case "NL":
            $country = "EU";
            break;
        case "PL":
            $country = "EU";
            break;
        case "PT":
            $country = "EU";
            break;
        case "CZ":
            $country = "EU";
            break;
        case "RO":
            $country = "EU";
            break;
        case "SK":
            $country = "EU";
            break;
        case "SI":
            $country = "EU";
            break;
        case "SE":
            $country = "EU";
            break;
        default:
            $country = "OTHER";
    }

    return $country;
}
