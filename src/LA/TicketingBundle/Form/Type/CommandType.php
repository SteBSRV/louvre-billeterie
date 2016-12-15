<?php
// src/LA/TicketingBundle/Form/Type/CommandType.php

namespace LA\TicketingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use LA\TicketingBundle\Form\Type\TicketType;


class CommandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',         TextType::class, array('label'  => 'Nom'))
            ->add('firstName',    TextType::class, array('label'  => 'Prénom'))
            ->add('mail',         EmailType::class, array('label' => 'Adresse mail'))
            ->add('tickets',      CollectionType::class, array(
                'entry_type'   => TicketType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'label'        => 'Liste des tickets'
            ))
            ->add('save',       SubmitType::class, array('label' => 'Valider'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LA\TicketingBundle\Entity\Command'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'la_ticketingbundle_command';
    }


}
