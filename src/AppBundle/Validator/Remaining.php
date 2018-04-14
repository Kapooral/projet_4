<?php 

// src/AppBundle/Validator/Remaining.php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Remaining extends Constraint
{
	public $message = "Veuillez choisir une quantité inférieure pour cette date.";

	public function validateBy()
	{
		return 'appbundle.validator.remaining';
	}
}