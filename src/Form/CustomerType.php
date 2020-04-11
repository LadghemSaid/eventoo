<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, array(
            'label' => 'label.lastname.customer',
            'required' => true))

            ->add('firstname', TextType::class, array(
                'label' => 'label.firstname.customer',
                'required' => true))

            ->add('email', EmailType::class, array(
                'label' => 'label.email.customer',
                'required' => true))

            ->add('adress', TextType::class, array(
                'label' => 'label.adress.customer',
                'required' => true))

            ->add('postCode', TextType::class, array(
                'label' => 'label.postcode.customer',
                'required' => true))

            ->add('city', TextType::class, array(
                'label' => 'label.city.customer',
                'required' => true))

            ->add('country', CountryType::class, array(
                'label' => 'label.country.customer',
                'preferred_choices' => array('FR')));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Customer::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_customer';
    }


}
