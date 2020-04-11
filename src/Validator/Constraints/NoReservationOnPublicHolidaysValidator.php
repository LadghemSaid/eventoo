<?php

namespace App\Validator\Constraints;

use App\Manager\VisitManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Service\PublicHolidaysService;

class NoReservationOnPublicHolidaysValidator extends ConstraintValidator
{
    private $publicHolidaysService;

    public function __construct(PublicHolidaysService $publicHolidaysService)
    {
        $this->publicHolidaysService = $publicHolidaysService;

    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->publicHolidaysService->checkIsHoliday($value) == true) {

            $this->context->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }
}
