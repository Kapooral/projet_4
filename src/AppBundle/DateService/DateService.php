<?php 

namespace AppBundle\DateService;

use AppBundle\Entity\Ticket;

class DateService
{
	public function getYearsOld(Ticket $ticket)
	{
		$today = new \DateTime();
		$yearsOld = $ticket->getBirthDate()->diff($today)->y;

		return $yearsOld;
	}

	public function isFullDay()
	{
		$date = getdate();
		$currentHour = $date['hours'];

		if($currentHour < 14 || $currentHour > 20)
		{
			return true;
		}
	}
}