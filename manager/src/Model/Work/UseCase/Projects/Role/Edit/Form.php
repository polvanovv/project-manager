<?php


namespace App\Model\Work\UseCase\Projects\Role\Edit;


use App\Model\Work\Entity\Projects\Role\Permission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class Form
 * @package App\Model\Work\UseCase\Projects\Role\Edit
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
            ->add('name', Type\TextType::class)
            ->add('permissions', Type\ChoiceType::class, [
                'choices' => array_combine(Permission::names(), Permission::names()),
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'translation_domain' => 'work_permissions',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Command::class
        ]);
    }

}