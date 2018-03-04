<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('bookingDate', DateType::class, array(
                  'widget' => 'single_text', 
                  'html5' => false, 
                  'model_timezone' => 'Europe/Paris',
                  'format' => 'dd-MM-yyyy', 
                  'attr' => array('class' => 'picker')))
                ->add('type', ChoiceType::class, array(
                  'choices' => array('Journée entière' => 'Journée Entière', 'Demi-journée' => 'Demi-Journée'), 
                  'expanded' => true))
                ->add('quantity',ChoiceType::class, array(
                  'choices' => range(0, 5)));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Order'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_order';
    }


}
