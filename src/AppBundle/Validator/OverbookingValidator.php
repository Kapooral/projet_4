<?php 

// src/AppBundle/Validator/OverbookingValidator.php

namespace AppBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OverbookingValidator extends ConstraintValidator
{
	private $em;
	private $limit;

	public function __construct(EntityManagerInterface $em, $limit)
	{
		$this->em = $em;
		$this->limit = $limit;
	}

	public function validate($value, Constraint $constraint)
	{
		$nbTickets = $this->em->getRepository('AppBundle:Order')->countTickets($value);

		if($nbTickets >= $this->limit)
		{
			$this->context->addViolation($constraint->message);	
		}
	}
}