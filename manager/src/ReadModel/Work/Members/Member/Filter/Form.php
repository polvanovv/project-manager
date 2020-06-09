<?php


namespace App\ReadModel\Work\Members\Member\Filter;


use App\Model\Work\Entity\Members\Member\Status;
use App\ReadModel\Work\Members\GroupFetcher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Type\EmailType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Name',
                    'onchange' => 'this.form.submit()'
                ]])
            ->add('email', Type\TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Email',
                    'onchange' => 'this.form.submit()'
                ]])
            ->add('group', Type\ChoiceType::class, [
                    'choices' => array_flip($this->groupFetcher->assoc()),
                    'required' => false,
                    'placeholder' => 'All groups',
                    'attr' => [
                        'onchange' => 'this.form.submit()'
                    ]
                ])
            ->add('status', Type\ChoiceType::class, [
                'choices' => [
                    'Active' => Status::ACTIVE,
                    'Archived' => Status::ARCHIVER,
                ],
                'required' => false,
                'placeholder' => 'All statuses',
                'attr' => [
                'onchange' => 'this.form.submit()'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => 'GET',
            'csrf_token' => false,
        ]);
    }
}