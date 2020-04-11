<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LimitedReservationAfterTwoHours
 * @package App\Validator\Constraints
 *
 * @Annotation
 */
class LimitedReservationAfterHour extends Constraint
{
    public $hour;

    public function __construct($options = null)
    {
        parent::__construct($options);

    }

    public function getRequiredOptions()
    {
        return array('hour');
    }


    public function getMessage()
    {
        return 'constraint.limited_reservation_after_hour' ;


    }




    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
