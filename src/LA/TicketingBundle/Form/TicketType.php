<?php

namespace LA\TicketingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',      TextType::class, array('label'     => 'Nom'))
            ->add('firstName', TextType::class, array('label'     => 'Prénom'))
            ->add('birthDate', BirthdayType::class, array('label' => 'Date de naissance'))
            ->add('country',   CountryType::class, array('label'  => 'Nationalité'))
            ->add('type',      ChoiceType::class, array(
                'choices'  => array(
                    'half' => false,
                    'day'  => true,
                    ),
                'label'    => 'Durée'
                ))
            ->add('reduced',   CheckboxType::class, array('required' => false,'label' => 'Tarif réduit'))
            ->add('visitDate', DateType::class, array('label' => 'Date de visite'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LA\TicketingBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'la_ticketingbundle_ticket';
    }


}
