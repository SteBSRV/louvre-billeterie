<?php
// src/LA/TicketingBundle/Validator/HalfDayValidator.php

namespace LA\TicketingBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HalfDayValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		$now = new \DateTime('now');
		$hour = $now->format('H');

		if ($hour >= 14) {
			$this->context->addViolation($constraint->message);
		}
	}
}