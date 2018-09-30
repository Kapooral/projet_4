<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// For Requests
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// For Entities
use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;
use AppBundle\Form\OrderChildType;
use AppBundle\Entity\Ticket;
use AppBundle\Form\TicketType;
// Services
use AppBundle\OrderPrice\OrderPrice;

class DefaultController extends Controller
{
    /**
     * @Route("/index", name="app_index")
     */
	public function indexAction()
	{
		return $this->render('index.html.twig');
	}

    /**
     * @Route("/reservation", name="app_booking")
     */
    public function bookingAction(Request $request)
    {
        $order = new Order();
    	$form = $this->createForm(OrderType::class, $order);
    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
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

    /**
     * @Route("/informations", name="app_info")
     */
    public function infoAction(Request $request, OrderPrice $orderPrice)
    {
    	if (!$request->getSession()->has('order')) {
    		return $this->redirectToRoute('app_booking');
    	}
    	$form = $this->createForm(OrderChildType::class, $request->getSession()->get('order'));
    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
            $totalPrice = $orderPrice->setTotalPrice($request->getSession()->get('order'));
            $request->getSession()->set('totalPrice', $totalPrice);
    		return $this->redirectToRoute('app_summary');
    	}
    	return $this->render('infos.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/recapitulatif", name="app_summary")
     */
    public function summaryAction(Request $request, OrderPrice $orderPrice)
    {
    	if (!$request->getSession()->has('order') || !$request->getSession()->has('totalPrice') || $request->getSession()->get('order')->getEmail() == null) {
    		return $this->redirectToRoute('app_info');
    	}
    	elseif ($request->isMethod('POST')) {
    		if ($orderPrice->payment($request->getSession()->get('totalPrice'), $this->getParameter('stripe_api'))) {
                return $this->redirectToRoute('app_confirmation');
            }
            $request->getSession()->getFlashBag()->add('error', 'Votre paiement n\'a pas été effectué, veuillez reessayer.');
    	}

    	return $this->render('summary.html.twig', array('order' => $request->getSession()->get('order'), 'price' => $request->getSession()->get('totalPrice')));
    }

    /**
     * @Route("/confirmation", name="app_confirmation")
     */
    public function confirmationAction(Request $request)
    {
        if (!$request->getSession()->has('order') || $request->getSession()->get('order')->getOrderCode() === null) {
        	return $this->redirectToRoute('app_summary');
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($request->getSession()->get('order'));
        $em->flush();
        
        $request->getSession()->clear();
        return $this->render('confirmation.html.twig');
    }

}