<?php


namespace App\Model\Work\UseCase\Members\Member\Create;


use App\ReadModel\Work\Members\GroupFetcher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Form
 * @package App\Model\Work\UseCase\Members\Member\Create
 */
class Form extends AbstractType
{

    /**
     * @var GroupFetcher
     */
    private $groupFetcher;

    public function __construct(GroupFetcher $groupFetcher)
    {
        $this->groupFetcher = $groupFetcher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('group', Type\ChoiceType::class, ['choices' => array_flip($this->groupFetcher->assoc())])
            ->add('firstName', Type\TextType::class)
            ->add('lastName', Type\TextType::class)
            ->add('email', Type\EmailType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class
        ]);
    }
}