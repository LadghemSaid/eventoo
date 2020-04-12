<?php

namespace App\Form;

use App\Entity\Visit;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;


class VisitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('type', ChoiceType::class, array(
                'choices' => $this->getDuration($options['data']->getEvent()->getDuration()),
                'label' => 'label.visit.type',
                'expanded' => false,
                'multiple' => false,

            ))
            ->add('visitDate', TextType::class, array(
                    'label' => 'label.visit.date',
                    'attr' => [
                        'class' => 'datepicker',
                        'data-startdate' => $options['data']->getEvent()->getStartDateToString(),
                        'data-price' => $options['data']->getEvent()->getprice()
                    ],
                    'row_attr' => ['class' => ''],
                    'required' => true,
                )
            )
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

    private function getDuration($duration)
    {
        $arr = [];
        for ($i = 1; $i <= $duration; $i++) {
            array_push($arr, "Pass $i jours");
        }
        return array_flip($arr);
    }


}

