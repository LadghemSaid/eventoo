<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\Visit;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class VisitTicketsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tickets',CollectionType::class, array(
            'entry_type' => TicketType::class,
            'allow_add' => true
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Visit::class,
            'validation_groups' => array('identification_registration')
        ));
    }


    /**
     * {@inheritdoc}
    public function getBlockPrefix()
    {
    return 'appbundle_visit';
    } */


}
