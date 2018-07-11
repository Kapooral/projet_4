<?php 
// src/AppBundle/Validator/ValideDateValidator.php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValideDateValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		$invalideDates = array('05-01', '11-01', '12-25');
		$order = $this->context->getObject();
		$date = $order->getBookingDate();

		if(in_array($date->format('m-d'), $invalideDates) || $date->format('D') == 'Tue')
		{
			$this->context->addViolation($constraint->message);
		}
	}
}