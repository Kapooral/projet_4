<?php 

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;

class LoadOrder implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$date = new \DateTime();
		$order = new Order();
		$order->setBookingDate($date);
		$order->setOrderCode(bin2hex(random_bytes(5)));
		$order->setEmail('mbenguia.husseini@ive.fr');

		for($i=0; $i < 5; $i++)
		{
			$ticket = new Ticket();
			$ticket->setName('Husseini');
			$ticket->setLastName('Mbenguia');
			$ticket->setBirthDate($date);
			$ticket->setCountry('FR');
			$ticket->setType('Billet normal');
			$ticket->setPrice(16);
			$order->addTicket($ticket);
		}

		$manager->persist($order);
		$manager->flush();
	}
}