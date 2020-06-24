<?php


namespace App\Model\Work\UseCase\Projects\Role\Copy;


use App\Model\Flusher;
use App\Model\Work\Entity\Projects\Role\Id;
use App\Model\Work\Entity\Projects\Role\RoleRepository;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Projects\Role\Copy
 */
class Handler
{

    /**
     * @var RoleRepository
     */
    private $roleRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     * @param RoleRepository $roleRepository
     * @param Flusher $flusher
     */
    public function __construct(RoleRepository $roleRepository, Flusher $flusher)
    {
        $this->roleRepository = $roleRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function handle(Command $command)
    {
        $current = $this->roleRepository->get(new Id($command->id));

        if ($this->roleRepository->hasByName($command->name)) {
            throw new \DomainException('Role already exists.');
        }

        $role = $current->clone(
            Id::next(),
            $command->name
        );

        $this->roleRepository->add($role);

        $this->flusher->flush();
    }
}