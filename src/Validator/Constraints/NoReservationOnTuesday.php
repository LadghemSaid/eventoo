<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * Class NoReservationOnTuesday
 * @package App\Validator\Constraints
 *
 * @Annotation
 */
class NoReservationOnTuesday extends Constraint
{
    public $day;

    public function __construct($options = null)
    {
        parent::__construct($options);

    }

    public function getRequiredOptions()
    {
        return array('day');
    }


    public function getMessage()
    {
        return 'constraint.no_reservation_on_tuesday';
    }
}
