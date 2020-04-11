<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class OneThousandTickets
 * @package App\Validator\Constraints
 *
 * @Annotation
 */
class OneThousandTickets extends Constraint
{
    public $nbTicketsByDay;

    public function __construct($options = null)
    {
        parent::__construct($options);

    }

    public function getRequiredOptions()
    {
        return array('nbTicketsByDay');
    }


    public function getMessage()
    {
        return 'constraint.one_thousand_tickets';
    }


    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
