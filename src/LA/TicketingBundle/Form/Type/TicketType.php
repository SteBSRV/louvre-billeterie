<?php
// src/LA/TicketingBundle/Form/Type/TicketType.php

namespace LA\TicketingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('name',      TextType::class, ['label'        => 'Nom'])
            ->add('firstName', TextType::class, ['label'        => 'Prénom'])
            ->add('country',   CountryType::class, [
                'label'             => 'Pays',
                'preferred_choices' => ['FR'],
            ])
            ->add('birthDate', BirthdayType::class, ['label'    => 'Date de naissance'])
            ->add('reduced',   CheckboxType::class, [
                'required' => false,
                'label'    => 'Tarif réduit',
                'attr'     => ['class' => 'reduced-info'],
            ])
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
