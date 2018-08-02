<?php

namespace AppBundle\OrderPrice;

use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use Symfony\Component\HttpFoundation\RequestStack;

class OrderPrice
{
	private $request;
	private $baby;
	private $child;
	private $normal;
	private $senior;
	private $reduce;

	public function __construct(RequestStack $request, $baby, $child, $normal, $senior, $reduce)
	{
		$this->request = $request->getCurrentRequest();
		$this->baby = $baby;
		$this->child = $child;
		$this->normal = $normal;
		$this->senior = $senior;
		$this->reduce = $reduce;
	}

	public function getYearsOld(Ticket $ticket)
	{
		$today = new \DateTime();
		$yearsOld = $ticket->getBirthDate()->diff($today)->y;

		return $yearsOld;
	}

	public function setTotalPrice(Order $order)
	{
		$totalPrice = 0;
		$tickets = $order->getTickets();

		foreach($tickets as $ticket)
		{
			$age = $this->getYearsOld($ticket);

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

	public function payment($price, $skey)
	{
		\Stripe\Stripe::setApiKey($skey);
		$price = $price * 100;

		try
		{
    		$charge = \Stripe\Charge::create(array(
    			"amount" => $price,
    			"currency" => 'eur',
    			"description" => 'Billet(s) Musée du Louvre',
    			"source" => $this->request->request->get('stripeToken')
    		));

    		if ($charge->id === null)
    		{
    			return false;
    		}

    		$this->request->getSession()->get('order')->setOrderCode($charge->id);
    		return true;
		}
		catch (\Stripe\Error\Card $e)
		{
			return false;
		}
		catch (\Stripe\Error\RateLimit $e)
		{
			return false;
		}
		catch (\Stripe\Error\InvalidRequest $e)
		{
			return false;
		}
		catch (\Stripe\Error\Authentication $e)
		{
			return false;
		}
		catch (\Stripe\Error\ApiConnection $e)
		{
			return false;
		}
		catch (\Stripe\Error\Base $e)
		{
			return false;
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}