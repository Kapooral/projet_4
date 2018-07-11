<?php 

// src/AppBundle/Validator/ValideDate.php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValideDate extends Constraint
{
	public $message = "Cette date est indisponible.";

	public function validateBy()
	{
		return 'appbundle.validator.valide_date';
	}
}