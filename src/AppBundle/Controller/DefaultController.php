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

    		$order->setOrderCode(bin2hex(random_bytes(5)));
			$request->getSession()->set('order', $order);

    		return $this->redirectToRoute('app_info');
    	}

        return $this->render('booking.html.twig', array('form' => $form->createView()));
    }

    public function infoAction(Request $request)
    {
    	if(!$request->getSession()->has('order') || $request->getSession()->get('order')->getOrderCode() == null)
    	{
    		$request->getSession()->clear();
    		return $this->redirectToRoute('app_booking');
    	}

    	$form = $this->createForm(OrderChildType::class, $request->getSession()->get('order'));

    	if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
    	{
    		if(!$form->get('cgv')->getData())
    		{
    			$request->getSession()->clear();
    			return $this->redirectToRoute('app_booking');
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
    	if(!$request->getSession()->has('order') || $request->getSession()->get('order')->getOrderCode() == null)
    	{
    		$request->getSession()->clear();
    		return $this->redirectToRoute('app_booking');
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
	    		\Stripe\Charge::create(array(
	    			"amount" => $totalPrice,
	    			"currency" => 'eur',
	    			"description" => 'Billet(s) Musée du Louvre',
	    			"source" => $request->request->get('stripeToken')
	    		));
    		} 
    		catch (\Stripe\Error\Card $e)
    		{
    			return $this->redirectToRoute('app_summary');
    		}

    		return $this->redirectToRoute('app_confirmation');
    	}

    	return $this->render('summary.html.twig', array('order' => $request->getSession()->get('order')));
    }

    public function confirmationAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$em->persist($request->getSession()->get('order'));
    	$em->flush();

    	$request->getSession()->clear();
    	return $this->render('confirmation.html.twig');
    }

}