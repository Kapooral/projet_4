<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
                  'label' => 'Prénom'))
                ->add('lastName', TextType::class, array(
                  'label' => 'Nom de famille'))
                ->add('country', CountryType::class, array(
                  'label'=> 'Pays de résidence',
                  'preferred_choices' => array(
                    'FR')))
                ->add('birthDate', BirthdayType::class, array(
                  'label' => 'Date de naissance',
                  'widget' => 'single_text',  
                  'model_timezone' => 'Europe/Paris',
                  'format' => 'dd/MM/yyyy',
                  'attr' => array('placeholder' => 'jj/mm/AAAA')))
                ->add('reducePrice', CheckboxType::class, array(
                  'label' => 'Prix réduit',
                  'required' => false));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ticket';
    }


}
