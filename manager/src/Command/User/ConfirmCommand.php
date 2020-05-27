<?php

declare(strict_types = 1);

namespace App\Command\User;


use App\ReadModel\User\UserFetcher;
use Symfony\Component\Console\Command\Command;
use App\Model\User\UseCase\SingUp\Confirm;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ConfirmCommand extends Command
{

    /**
     * @var UserFetcher
     */
    private $fetcher;

    /**
     * @var Confirm\ByToken\Handler
     */
    private $handler;

    public function __construct(UserFetcher $fetcher, Confirm\ByToken\Handler $handler )
    {
        $this->fetcher = $fetcher;
        $this->handler = $handler;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('user:confirm')
            ->setDescription('Confirms signed up user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $email = $helper->ask($input, $output, new Question('Email: '));

        if (!$user = $this->fetcher->findByEmail($email)) {
            throw new \LogicException('User is not found');
        }

        $command =  new Confirm\ByToken\Command($user->id);
        $this->handler->handle($command);

        $output->writeln('<info>Done!</info>');
    }

}