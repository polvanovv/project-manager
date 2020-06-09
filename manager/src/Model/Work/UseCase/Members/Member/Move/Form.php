<?php


namespace App\Model\Work\UseCase\Members\Member\Move;


use App\ReadModel\Work\Members\GroupFetcher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Form
 * @package App\Model\Work\UseCase\Members\Member\Move
 */
class Form extends AbstractType
{
    /**
     * @var GroupFetcher
     */
    private $groupFetcher;

    /**
     * Form constructor.
     * @param GroupFetcher $groupFetcher
     */
    public function __construct(GroupFetcher $groupFetcher)
    {
        $this->groupFetcher = $groupFetcher;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('group', Type\ChoiceType::class, ['choices' => array_flip($this->groupFetcher->assoc())]);
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