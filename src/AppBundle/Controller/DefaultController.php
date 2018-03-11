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
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig');
    }

    public function bookingAction(Request $request)
    {
    	$dateService = $this->container->get('appbundle.dateservice');
    	$order = new Order();

    	if(!$dateService->isFullDay())
    	{
    		$order->setFullDay(false);
    	}

    	$form = $this->createForm(OrderType::class, $order);

    	if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
    	{
    		$quantity = $form->get('quantity')->getData();

    		for($i = 0; $i < $quantity; $i++)
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
    	$form = $this->createForm(OrderChildType::class, $request->getSession()->get('order'));

    	if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
    	{
    		return $this->render('summary.html.twig', array('order' => $request->getSession()->get('order')));
    	}

    	return $this->render('info.html.twig', array('form' => $form->createView()));
    }

    public function summaryAction(Request $request)
    {
    	
    	$dateService = new DateService();

    	$tickets = $request->getSession()->get('order')->getTickets();

    	foreach($tickets as $ticket)
    	{
    		/*


			-----------------------------------------------------------------------------

			ICI SE TROUVE LE SERVICE DE CALCUL DU PRIX DU BILLET

			-----------------------------------------------------------------------------


    		*/
    	}

    	return $this->render('summary.html.twig', array('interval' => $hours));
    }
}