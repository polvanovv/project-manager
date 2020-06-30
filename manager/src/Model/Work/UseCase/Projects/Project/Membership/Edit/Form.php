<?php


namespace App\Model\Work\UseCase\Projects\Project\Membership\Edit;

use App\ReadModel\Work\Projects\Project\DepartmentFetcher;
use App\ReadModel\Work\Projects\RoleFetcher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class Form extends AbstractType
{
    /**
     * @var DepartmentFetcher
     */
    private $departments;
    /**
     * @var RoleFetcher
     */
    private $roles;

    public function __construct( DepartmentFetcher $departments, RoleFetcher $roles)
    {
        $this->departments = $departments;
        $this->roles = $roles;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('departments',Type\ChoiceType::class,[
                'choices' => [ array_flip($this->departments->listOfProject($options['project']))],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('roles', Type\ChoiceType::class, [
                'choices' => [array_flip($this->roles->allList())],
                'expanded' => true,
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Command::class
        ]);
        $resolver->setRequired(['project']);
    }
}