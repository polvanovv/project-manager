<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\Role;


use App\Model\User\Entity\User\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class Form
 *
 * @package App\Model\User\UseCase\Role
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Form extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'role',
                Type\ChoiceType::class,
                ['label' => 'Choice Role', 'choices' => [
                    'User'  => Role::USER,
                    'Admin' => Role::ADMIN,
                ]]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Command::class,
            ]
        );
    }
}