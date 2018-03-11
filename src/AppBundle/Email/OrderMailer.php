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

	public function __construct(\Swift_Mailer $mailer)
	{
		$this->mailer = $mailer;
	}

	public function sendNewEmail(Order $order)
	{
		$tickets = $order->getTickets();
		$message = new \Swift_Mailer('Billet(s) Musée du Louvre');

		foreach($tickets as $ticket)
		{
			$message->addPart('Nom : ' . $ticket->getLastName() . '\r' .
							  'Prénom : ' . $ticket->getName() . '\r\r');
		}
		
		$message->setTo($order->getemail());
		$message->setFrom('kapooral.b@gmail.com');

		$this->mailer->send($message);
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if(!entity instanceof Order)
		{
			return;
		}

		$this->sendNewEmail($entity);
	}
}