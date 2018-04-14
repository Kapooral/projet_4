<?php 

// src/AppBundle/Validator/RemainingValidator.php

namespace AppBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RemainingValidator extends ConstraintValidator
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
		$order = $this->context->getObject();
		$value += $this->em->getRepository('AppBundle:Order')->countTickets($order->getBookingDate());

		if($value > $this->limit)
		{
			$this->context->addViolation($constraint->message);
		}
	}
}