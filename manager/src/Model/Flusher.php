<?php

declare(strict_types = 1);

namespace App\Model;


use Doctrine\ORM\EntityManagerInterface;

/**
 * Interface Flusher
 * @package App\Model
 */
class Flusher
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcher $dispatcher)
    {

        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function flush(): void
    {
        $this->em->flush();
    }

}