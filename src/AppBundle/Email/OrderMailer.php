<?php 

namespace AppBundle\Email;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Entity\Order;

class OrderMailer
{
	/**
	 * @var \Swift_Mailer
	 */
	private $mailer;
	private $from;

	public function __construct(\Swift_Mailer $mailer, $from, \Twig_Environment $templating)
	{
		$this->mailer = $mailer;
		$this->from = $from;
		$this->templating = $templating;
	}

	public function sendNewEmail(Order $order)
	{
		$price = 0;
		$tickets = $order->getTickets();

		foreach($tickets as $ticket)
		{
			$price += $ticket->getPrice();
		}

		$message = new \Swift_Message('Billet Musée du Louvre');
		$message->setBody($this->templating->render('email.html.twig', array('order' => $order, 'price' => $price)), 'text/html');
		$message->setFrom([$this->from => 'Musée du Louvre'])->setTo($order->getEmail());
		$this->mailer->send($message);
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if(!$entity instanceof Order)
		{
			return;
		}

		$this->sendNewEmail($entity);
	}
}