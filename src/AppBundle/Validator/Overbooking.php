<?php 

// src/AppBundle/Validator/Overbooking.php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Overbooking extends Constraint
{
	public $message = "Le nombre maximum de visiteurs est atteint pour ce jour.";

	public function validateBy()
	{
		return 'appbundle.validator.overbooking';
	}
}