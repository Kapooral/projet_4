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

	public function __construct(\Swift_Mailer $mailer, $from)
	{
		$this->mailer = $mailer;
		$this->from = $from;
	}

	public function sendNewEmail(Order $order)
	{
		$message = new \Swift_Message('Billet(s) Musée du Louvre', 'Nouvelle réservation.');
		$message->setFrom($this->from)->setTo($order->getEmail());
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