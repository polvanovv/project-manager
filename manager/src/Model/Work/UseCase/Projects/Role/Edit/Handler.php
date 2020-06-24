<?php


namespace App\Model\Work\UseCase\Projects\Role\Edit;


use App\Model\Flusher;
use App\Model\Work\Entity\Projects\Role\Id;
use App\Model\Work\Entity\Projects\Role\RoleRepository;

/**
 * Class Handler
 * @package App\Model\Work\UseCase\Projects\Role\Edit
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
     */
    public function handle(Command $command)
    {
        $role = $this->roleRepository->get(new Id($command->id));

        $role->edit($command->name, $command->permissions);

        $this->roleRepository->add($role);
        $this->flusher->flush();
    }
}