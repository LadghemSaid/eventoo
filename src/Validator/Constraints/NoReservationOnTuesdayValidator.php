<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class NoReservationOnTuesdayValidator extends ConstraintValidator
{
    /**
     * @param $day
     * @return bool
     */
    public function check($day) {
        if ($day->format('w') == 2) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {

        if ($this->check($value) == true) {

            $this->context->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }
}
