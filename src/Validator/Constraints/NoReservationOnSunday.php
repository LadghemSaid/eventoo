<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class NoReservationOnSunday
 * @package App\Validator\Constraints
 *
 * @Annotation
 */
class NoReservationOnSunday extends Constraint
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
        return 'constraint.no_reservation_on_sunday';

        //$message = $this->get('translator')->trans('En ligne, il n\'est pas possible de réserver une visite pour la journée du dimanche.');

        //return new Response($message);
    }
}
