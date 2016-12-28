<?php
// src/LA/TicketingBundle/Validator/Constraints/OrderCheck.php

namespace LA\TicketingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class OrderCheck extends Constraint
{
	public $messageNoTickets = "Aucun ticket ajouté.";
	public $messageClosedMuseum = "Impossible de réserver ce jour, le musée étant fermé.";
	public $messageClosedOrder  = "Impossible de réserver ce jour, les résevations en ligne étant fermées pour les jours fériés et les dimanches";
	public $messageTooLateForFullDay = "Vous ne pouvez plus réserver de billet Journée pour le jour même une fois 14h passé, vous pouvez cependant toujours réserver un billet Demi-journée.";
	public $messageNoMoreTickets = "Il ne reste plus assez de tickets à la vente pour votre terminer votre commande.";

	public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'la.order_check';
    }
}
