<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class ToLateForToday
 * @package App\Validator\Constraints
 *
 * @Annotation
 */
class ToLateForToday extends Constraint
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
        return 'constraint.to_late_for_today';
    }


}
