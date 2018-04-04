<?php 

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\TicketType;

class OrderChildType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('bookingDate')
                ->remove('wholeDay')
                ->remove('quantity')
                ->add('tickets', CollectionType::class, array(
                	'entry_type' => TicketType::class))
                ->add('email', EmailType::class)
                ->add('cgv', CheckboxType::class, array(
                	'mapped' => false));
    }

    public function getParent()
    {
        return OrderType::class;
    }
}
