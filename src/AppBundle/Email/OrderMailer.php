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
		$message = (new \Swift_Message('Billet(s) Musée du Louvre'))
		    ->setFrom('kapooral.b@gmail.com')
		    ->setTo($order->getEmail())
		    ->setBody(
		    	$this->renderView(
		    		'app/Resources/views/Emails/template.html.twig', array('order' => $order)
		    	), 'text/html');

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