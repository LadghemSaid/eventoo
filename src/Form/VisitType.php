<?php

namespace App\Form;

use App\Entity\Visit;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;


class VisitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('visitDate', DateType::class, array(
                'label' => 'label.visit.date',
                'widget' => 'single_text',
                'attr' => ['class' => 'datepicker'],
                'required' => true
            )
        )
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'label.full.day.ticket' => Visit::TYPE_FULL_DAY,
                    'label.half.day.ticket' => Visit::TYPE_HALF_DAY
                ),
                'label' => 'label.visit.type',
                'expanded' => true,
                'multiple' => false,

            ))

            ->add('nbTicket', ChoiceType::class, array(
                'choices' => array(
                    '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10,
                    '11' => 11, '12' => 12, '13' => 13, '14' => 14, '15' => 15, '16' => 16, '17' => 17, '18' => 18,
                    '19' => 19, '20' => 20
                ),
                'label' => 'label.visit.nb.tickets',
                'required' => true
            ));
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Visit::class,
            'validation_groups' => array('order_registration')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_visit';
    }


}
