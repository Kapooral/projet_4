<?php 

// src/AppBundle/Validator/WholeDay.php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class WholeDay extends Constraint
{
	public $message = "À partir de 14h seuls les billets demi-journée sont sélectionnables.";

	public function validateBy()
	{
		return 'appbundle.validator.valide_date';
	}
}