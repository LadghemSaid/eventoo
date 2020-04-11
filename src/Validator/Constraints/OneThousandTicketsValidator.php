<?php


namespace App\Validator\Constraints;

use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class OneThousandTicketsValidator
 * @package App\Validator\Constraints
 */
class OneThousandTicketsValidator extends ConstraintValidator
{
    private $em;

    /**
     * OneThousandTicketsValidator constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function validate($object, Constraint $constraint)
    {

        if(!$constraint instanceof OneThousandTickets){
            return ;
        }

        if(!$object instanceof Visit){
            return ;
        }


        /** @var $visitDate */
        $visitDate = $object->getVisitDate();

        $total = $this->em->getRepository(Visit::class)->countNbTicketsOnThisDate($visitDate);

        if( $total + $object->getNbTicket() > $constraint->nbTicketsByDay) {
            $this->context->buildViolation($constraint->getMessage())
                ->atPath('nbTicket')
                ->addViolation();
        }
    }

}
