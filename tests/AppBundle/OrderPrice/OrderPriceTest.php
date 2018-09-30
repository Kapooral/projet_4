<?php

namespace Tests\AppBundle\OrderPrice;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Order;

class OrderPriceTest extends WebTestCase
{
	private $orderprice = null;

	public function setUp()
	{
		$request = $this->getMockBuilder('Symfony\Component\HttpFoundation\RequestStack')
			->disableOriginalConstructor()
			->getMock();

		$this->orderprice = new \AppBundle\OrderPrice\OrderPrice($request, 0, 8, 16, 12, 10);
	}

	public function testYearsOld()
	{
		$ticket = new Ticket();
		$ticket->setBirthDate(new \DateTime('1994-05-22'));
		
		$yearsOld = $this->orderprice->getYearsOld($ticket);
		$this->assertSame(24, $yearsOld);
	}

	public function testTotalPrice()
	{
		// For testing reduce price always equals 10
		$ticket1 = new Ticket();
		$ticket1->setReducePrice(true);
		$ticket1->setBirthDate(new \DateTime('1985-10-02'));

		// For testing reduce price is ignored under 12 years old
		$ticket2 = new Ticket();
		$ticket2->setReducePrice(true);
		$ticket2->setBirthDate(new \DateTime('2010-04-12'));

		// For testing regular ticket equals 16
		$ticket3 = new Ticket();
		$ticket3->setBirthDate(new \DateTime('1993-05-22'));

		// For testing baby ticket is free
		$ticket4 = new Ticket();
		$ticket4->setBirthDate(new \DateTime('2017-08-19'));

		// For testing senior ticket equals 12
		$ticket5 = new Ticket();
		$ticket5->setBirthDate(new \DateTime('1950-01-20'));

		$order = new Order();
		$order->addTicket($ticket1);
		$order->addTicket($ticket2);
		$order->addTicket($ticket3);
		$order->addTicket($ticket4);
		$order->addTicket($ticket5);

		$totalPrice = $this->orderprice->setTotalPrice($order);
		$this->assertEquals(46, $totalPrice);
		$this->assertSame('Billet normal réduit', $order->getTickets()->get(0)->getType());
		$this->assertSame('Billet enfant', $order->getTickets()->get(1)->getType());
		$this->assertSame('Billet normal', $order->getTickets()->get(2)->getType());
		$this->assertSame('Billet gratuit', $order->getTickets()->get(3)->getType());
		$this->assertSame('Billet senior', $order->getTickets()->get(4)->getType());
	}

	public function tearDown()
	{
		$this->orderprice = null;
	}
}