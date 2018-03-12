<?php

namespace AppBundle\OrderPrice;

use AppBundle\Entity\Order;
use AppBundle\DateService\Dateservice;

class OrderPrice
{
	private $dateService;
	private $baby;
	private $child;
	private $normal;
	private $senior;
	private $reduce;

	public function __construct(DateService $dateService, $baby, $child, $normal, $senior, $reduce)
	{
		$this->dateService = $dateService;
		$this->baby = $baby;
		$this->child = $child;
		$this->normal = $normal;
		$this->senior = $senior;
		$this->reduce = $reduce;
	}
	public function setTotalPrice(Order $order)
	{
		$totalPrice = 0;
		$tickets = $order->getTickets();

		foreach($tickets as $ticket)
		{
			$age = $this->dateService->getYearsOld($ticket);

			if($age < 4)
			{
				$ticket->setType('Billet gratuit');
				$ticket->setPrice($this->baby);
			}
			elseif($age < 12)
			{
				$ticket->setType('Billet enfant');
				$ticket->setPrice($this->child);
				$totalPrice += $this->child;
			}
			elseif($age < 60)
			{
				if($ticket->getReducePrice())
				{
					$ticket->setType('Billet normal réduit');
					$ticket->setPrice($this->reduce);
					$totalPrice += $this->reduce;
				}
				else
				{
					$ticket->setType('Billet normal');
					$ticket->setPrice($this->normal);
					$totalPrice += $this->normal;
				}
			}
			else
			{
				if($ticket->getReducePrice())
				{
					$ticket->setType('Billet sénior réduit');
					$ticket->setPrice($this->reduce);
					$totalPrice += $this->reduce;
				}
				else
				{
					$ticket->setType('Billet senior');
					$ticket->setPrice($this->senior);
					$totalPrice += $this->senior;
				}
			}
		}

		return $totalPrice;
	}
}