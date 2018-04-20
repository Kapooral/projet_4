<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;
use AppBundle\Form\OrderChildType;
use AppBundle\Entity\Ticket;
use AppBundle\Form\TicketType;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $order = new Order();
    	$dateService = $this->container->get('appbundle.date_service');

    	if(!$dateService->isFullDay())
    	{
    		$order->setWholeDay(false);
    	}

    	$form = $this->createForm(OrderType::class, $order);

    	if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
    	{
    		for($i = 0; $i < $order->getQuantity(); $i++)
    		{
    			$ticket = new Ticket();
    			$order->addTicket($ticket);
    		}

			$request->getSession()->set('order', $order);

    		return $this->redirectToRoute('app_info');
    	}

        return $this->render('booking.html.twig', array('form' => $form->createView()));
    }

    public function infoAction(Request $request)
    {
    	if(!$request->getSession()->has('order'))
    	{
    		return $this->redirectToRoute('app_index');
    	}

    	$form = $this->createForm(OrderChildType::class, $request->getSession()->get('order'));

    	if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
    	{
    		if(!$form->get('cgv')->getData())
    		{
    			$request->getSession()->clear();
    			return $this->redirectToRoute('app_index');
    		}
    		else
    		{
    			return $this->redirectToRoute('app_summary');
    		}
    	}

    	return $this->render('info.html.twig', array('form' => $form->createView()));
    }

    public function summaryAction(Request $request)
    {
    	if(!$request->getSession()->has('order'))
    	{
    		return $this->redirectToRoute('app_index');
    	}
    	elseif($request->getSession()->get('order')->getEmail() == null)
    	{
    		return $this->redirectToRoute('app_info');
    	}
    	elseif($request->isMethod('POST'))
    	{
    		$orderPrice = $this->container->get('appbundle.order_price');
    		$totalPrice = $orderPrice->setTotalPrice($request->getSession()->get('order')) * 100;
    		\Stripe\Stripe::setApiKey('sk_test_Ce6DBxnu8smTNyzF2TokYRIO');

    		try
    		{
	    		$charge = \Stripe\Charge::create(array(
	    			"amount" => $totalPrice,
	    			"currency" => 'eur',
	    			"description" => 'Billet(s) Musée du Louvre',
	    			"source" => $request->request->get('stripeToken')
	    		));
    		} 
    		catch (\Stripe\Error\Card | \Stripe\Error\RateLimit | \Stripe\Error\InvalidRequest | \Stripe\Error\Authentication | \Stripe\Error\ApiConnection | \Stripe\Error\Base | Exception $e)
    		{
                $message = 'Une erreur est survenue. Votre paiement n\'a pas été effectué, veuillez recommencer.';
    			return $this->redirectToRoute('app_summary');
    		}

            $request->getSession()->get('order')->setOrderCode($charge->id);
    		return $this->redirectToRoute('app_confirmation');
    	}

    	return $this->render('summary.html.twig', array('order' => $request->getSession()->get('order')));
    }

    public function confirmationAction(Request $request)
    {
        if(!$request->getSession()->has('order'))
        {
            return $this->redirectToRoute('app_index');
        }
        elseif($request->getSession()->get('order')->getOrderCode() == null)
        {
            return $this->redirectToRoute('app_summary');
        }

        try
        {
            \Stripe\Stripe::setApiKey('sk_test_Ce6DBxnu8smTNyzF2TokYRIO');
            $charge = \Stripe\Charge::retrieve($request->getSession()->get('order')->getOrderCode());
        }
        catch(\Stripe\Error\InvalidRequest $e)
        {
            $message = 'Une erreur est survenue. Votre paiement n\'a pas été effectué, veuillez recommencer.';
            return $this->redirectToRoute('app_summary');
        }
        
        if(!$charge->status == "succeeded")
        {
            $message = 'Une erreur est survenue. Votre paiement n\'a pas été effectué, veuillez recommencer.';
            return $this->redirectToRoute('app_summary');
        }

    	$em = $this->getDoctrine()->getManager();
    	$em->persist($request->getSession()->get('order'));
    	$em->flush();

    	$request->getSession()->clear();
    	return $this->render('confirmation.html.twig');
    }

}