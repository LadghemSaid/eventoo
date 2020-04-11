<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NoReservationOnPublicHolidays
 * @package App\Validator\Constraints
 *
 * @Annotation
 */
class NoReservationOnPublicHolidays extends Constraint
{
    public $publicHolidays;

    public function __construct($options = null)
    {
        parent::__construct($options);

    }

    public function getRequiredOptions()
    {
        return array('publicHolidays');
    }


    public function getMessage()
    {
        return 'constraint.no_reservation_on_public_holidays';

    }


}
