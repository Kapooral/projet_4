<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;
use AppBundle\Form\OrderChildType;
use AppBundle\Entity\Ticket;
use AppBundle\Form\TicketType;

class DefaultController extends Controller
{
	public function indexAction()
	{
		return $this->render('index.html.twig');
	}

    public function bookingAction(Request $request)
    {
        $order = new Order();
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
            $orderPrice = $this->get('appbundle.order_price');
            $totalPrice = $orderPrice->setTotalPrice($request->getSession()->get('order'));
            $request->getSession()->set('totalPrice', $totalPrice);
    		return $this->redirectToRoute('app_summary');
    	}

    	return $this->render('infos.html.twig', array('form' => $form->createView()));
    }

    public function summaryAction(Request $request)
    {
    	if(!$request->getSession()->has('order') || !$request->getSession()->has('totalPrice') || $request->getSession()->get('order')->getEmail() == null)
    	{
    		return $this->redirectToRoute('app_info');
    	}
    	elseif($request->isMethod('POST'))
    	{
    		$orderPrice = $this->get('appbundle.order_price');
    		if ($orderPrice->payment($request->getSession()->get('totalPrice'), $this->getParameter('stripe_api')))
            {
                return $this->redirectToRoute('app_confirmation');
            }
            $request->getSession()->getFlashBag()->add('error', 'Votre paiement n\'a pas été effectué, veuillez reessayer.');
    	}

    	return $this->render('summary.html.twig', array('order' => $request->getSession()->get('order'), 'price' => $request->getSession()->get('totalPrice')));
    }

    public function confirmationAction(Request $request)
    {
        if (!$request->getSession()->has('order') || $request->getSession()->get('order')->getOrderCode() === null)
        {
        	return $this->redirectToRoute('app_summary');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($request->getSession()->get('order'));
        $em->flush();
        
        $request->getSession()->clear();
        return $this->render('confirmation.html.twig');
    }

}