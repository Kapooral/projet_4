<?php 

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\TicketType;

class OrderChildType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tickets', CollectionType::class, array(
                	'entry_type' => TicketType::class,
                    'label' => 'Billet(s)'))
                ->add('email', EmailType::class, array(
                    'label' => 'Adresse e-mail de réception de billet(s)'))
                ->add('cgv', CheckboxType::class, array(
                    'label' => 'J\'accepte les conditions générales de ventes.',
                	'mapped' => false,
                    'constraints' => new IsTrue(array(
                        'message' => 'Veuillez accepter les conditions générales de ventes.'))));
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
