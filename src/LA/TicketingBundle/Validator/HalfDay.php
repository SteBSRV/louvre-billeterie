<?php
// srr/LA/TicketingBundle/Validator/HalfDay.php

namespace LA\TicketingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HalfDay extends Constraint
{
	public $message = "Vous ne pouvez plus réserver de billet Journée pour le jour même une fois 14h passé, vous pouvez cependant toujours réserver un billet Demi-journée";
}