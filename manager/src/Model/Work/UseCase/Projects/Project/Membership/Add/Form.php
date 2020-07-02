<?php


namespace App\Model\Work\UseCase\Projects\Project\Membership\Add;


use App\ReadModel\Work\Members\Member\MemberFetcher;
use App\ReadModel\Work\Projects\Project\DepartmentFetcher;
use App\ReadModel\Work\Projects\RoleFetcher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class Form
 * @package App\Model\Work\UseCase\Projects\Project\Membership\Add
 */
class Form extends AbstractType
{
    /**
     * @var MemberFetcher
     */
    private $members;
    /**
     * @var DepartmentFetcher
     */
    private $departments;
    /**
     * @var RoleFetcher
     */
    private $roles;

    public function __construct(MemberFetcher $members, DepartmentFetcher $departments, RoleFetcher $roles)
    {
        $this->members = $members;
        $this->departments = $departments;
        $this->roles = $roles;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $members = [];
        foreach ($this->members->activeGroupedList() as $item) {
            $members[$item['group']][$item['name']] = $item['id'];
        }

        $builder
            ->add('member', Type\ChoiceType::class, [
                'choices' => $members,
            ])
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