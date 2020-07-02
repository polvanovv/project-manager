<?php


namespace App\Controller;


use Psr\Log\LoggerInterface;

class ErrorHandler
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \DomainException $e
     */
    public function handle(\DomainException $e): void
    {
        $this->logger->warning($e->getMessage(), ['exception' => $e]);
    }

}