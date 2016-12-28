<?php
// src/LA/TicketingBundle/Form/Type/OrderType.php

namespace LA\TicketingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use LA\TicketingBundle\Form\Type\TicketType;


class OrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitDate',         DateType::class, array(
                'label' => 'Date de visite',
                'data'  => new \Datetime(),
                'years' => range(date('Y'), date('Y') + 2),
                )
            )
            ->add('ticketsType',       ChoiceType::class, array(
                'choices'  => array(
                    'journée'      => 'journée',
                    'demi-journée' => 'demi-journée',
                    ),
                'label'    => 'Type de billets'
                )
            )
            ->add('nbTickets',         ChoiceType::class, array(
                'placeholder' => 'Choisir une quantité',
                'choices'     => array_combine(range(1,10),range(1,10)),
                'label'       => 'Nomre de ticket(s)',
                'mapped'      => false,
                )
            )
            ->add('tickets',           CollectionType::class, array(
                'entry_type'   => TicketType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'label'        => 'Liste des tickets :'
                )
            )
            ->add('Valider',              SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LA\TicketingBundle\Entity\Order'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'la_ticketingbundle_order';
    }
}
