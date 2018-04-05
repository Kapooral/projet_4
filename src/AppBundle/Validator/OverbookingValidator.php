<?php 

// src/AppBundle/Validator/Overbooking.php

namespace AppBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OverbookingValidator extends ConstraintValidator
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function validate($value, Constraint $constraint)
	{
		$isOverbooking = $this->em->getRepository('AppBundle:Order')->isOverbooking($value);

		if($isOverbooking)
		{
			$this->context->addViolation($constraint->message);	
		}
	}
}