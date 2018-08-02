<?php 
// src/AppBundle/Validator/WholeDayValidator.php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WholeDayValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		$today = new \DateTime();
		$date = $today->format('m-d-Y');
		$order = $this->context->getObject();

		if ($order->getBookingDate()->format('m-d-Y') == $date)
		{
			$hour = $today->format('H');

			if ($hour >= 14 && $hour < 19)
			{
				if ($order->getWholeDay() == true)
				{
					$this->context->addViolation($constraint->message);
				}
			}
		}
	}
}