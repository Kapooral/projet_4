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
                  'label' => 'Date de réservation',
                  'widget' => 'single_text', 
                  'html5' => false, 
                  'model_timezone' => 'Europe/Paris',
                  'format' => 'dd-MM-yyyy', 
                  'attr' => array('class' => 'picker', 'onFocus' => 'this.blur()')))
                ->add('wholeDay', ChoiceType::class, array(
                  'label' => 'Type de billet',
                  'choices' => array('Journée entière' => true, 'Demi-journée' => false), 
                  'expanded' => true))
                ->add('quantity',ChoiceType::class, array(
                  'label' => 'Quantité',
                  'choices' => array_combine(range(1,10), range(1,10))))
                ->add('Continuer', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
          $order = $event->getData();

          if($order === null)
          {
            return;
          }

          if(!$order->getWholeDay())
          {
            $event->getForm()->add('wholeDay', ChoiceType::class, array('choices' => array('Demi-journée' => false), 'expanded' => true));
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
