<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
                ->add('fullDay', ChoiceType::class, array(
                  'choices' => array('Journée entière' => true, 'Demi-journée' => false), 
                  'expanded' => true))
                ->add('quantity',ChoiceType::class, array(
                  'choices' => range(0, 5),
                  'mapped' => false))
                ->add('Continuer', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
          $order = $event->getData();

          if($order === null)
          {
            return;
          }

          if(!$order->getFullDay())
          {
            $event->getForm()->add('fullDay', ChoiceType::class, array('choices' => array('Demi-journée' => false), 'expanded' => true));
          }
        });
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
