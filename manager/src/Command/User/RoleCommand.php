<?php

declare(strict_types = 1);

namespace App\Command\User;

use App\Model\User\Entity\User\Role as RoleValue;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\Console\Command\Command;
use App\Model\User\UseCase\Role;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RoleCommand extends Command
{
    /**
     * @var UserFetcher
     */
    private $fetcher;

    /**
     * @var Role\Handler
     */
    private $handler;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(UserFetcher $fetcher, Role\Handler $handler, ValidatorInterface $validator)
    {
        parent::__construct();
        $this->fetcher = $fetcher;
        $this->handler = $handler;
        $this->validator = $validator;
    }

    protected function configure()
    {
        $this
            ->setName('user:role')
            ->setDescription('Change user role');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $email = $helper->ask($input, $output, new Question('Email: '));

        if ($user = $this->fetcher->findByEmail($email)) {
            throw new \LogicException('User not found.');
        }

        $command = new Role\Command($user->id);

        $roles = [RoleValue::USER, RoleValue::ADMIN];

        $command->role = $helper->ask($input, $output, new ChoiceQuestion('Role: ', $roles, 0));

        $violations = $this->validator->validate($command);
        if ($violations->count()) {
            foreach ($violations as $violation) {
                $output->writeln('<error>' . $violation->getPropertyPath() . ': ' . $violation->getMessage() . '</error>');
            }
            return;
        }

        $this->handler->handle($command);

        $output->writeln('<info>Done</info>');
    }

}