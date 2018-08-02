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
		$invalideDates = array('05-01', '11-01', '12-25');
		$today = new \DateTime();
		$interval = $today->diff($value);

		if($today->format('m-d-Y') !== $value->format('m-d-Y') && $interval->invert > 0)
		{
			$this->context->addViolation($constraint->message_unavailable);
		}

		elseif(in_array($value->format('m-d'), $invalideDates) || $value->format('D') == 'Tue')
		{
			$this->context->addViolation($constraint->message_unavailable);
		}

		$nbTickets = $this->em->getRepository('AppBundle:Order')->countTickets($value);

		if($nbTickets >= $this->limit)
		{
			$this->context->addViolation($constraint->message);	
		}
	}
}