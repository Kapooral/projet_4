<?php 

// src/AppBundle/Validator/Overbooking.php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Overbooking extends Constraint
{
	public $message = "Cette date est indisponible. Le nombre maximum de visiteurs est atteint.";

	public function validateBy()
	{
		return 'appbundle.validator.overbooking';
	}
}