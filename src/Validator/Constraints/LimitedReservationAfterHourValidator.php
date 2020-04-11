<?php

namespace App\Validator\Constraints;


use App\Entity\Visit;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class LimitedReservationAfterTwoHoursValidator
 * @package App\Validator\Constraints
 *
 */
class LimitedReservationAfterHourValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($object, Constraint $constraint)
    {
        $hour = date("H");

        if(!$object instanceof Visit)
        {
            return;
        }

        if($object->getType() == Visit::TYPE_FULL_DAY &&
            $hour >= $constraint->hour &&
            $object->getVisitDate()->format('dmY') === date('dmY')
        )
        {
            $this->context->buildViolation($constraint->getMessage())
                ->setParameter('%hour%',$constraint->hour)
                ->atPath('type')
                ->addViolation();
        }
    }


}
