<?php

declare(strict_types = 1);

namespace App\ReadModel\User\Filter;


use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class Form
 *
 * @package App\ReadModel\User\Fiter
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                Type\TextType::class,
                ['required' => false, 'attr' => ['placeholder' => 'Name', 'onchange' => 'this.form.submit()']]
            )
            ->add(
                'email',
                Type\TextType::class,
                ['required' => false, 'attr' => ['placeholder' => 'Email', 'onchange' => 'this.form.submit()']]
            )
            ->add(
                'role',
                Type\ChoiceType::class,
                ['choices'    => [
                    'User'  => Role::USER,
                    'Admin' => Role::ADMIN,
                ], 'required' => false, 'placeholder' => 'All roles', 'attr' => ['onchange' => 'this.form.submit()']]
            )
            ->add(
                'status',
                Type\ChoiceType::class,
                ['choices'    => [
                    'Active'  => User::STATUS_ACTIVE,
                    'Blocked' => User::STATUS_BLOCKED,
                    'Wait'    => User::STATUS_WAIT,
                ], 'required' => false, 'placeholder' => 'All statuses', 'attr' => ['onchange' => 'this.form.submit()']]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'      => Filter::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}