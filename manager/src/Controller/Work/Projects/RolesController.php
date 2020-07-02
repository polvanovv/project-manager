<?php


namespace App\Controller\Work\Projects;


use App\Controller\ErrorHandler;
use App\Model\Work\Entity\Projects\Role\Permission;
use App\Model\Work\Entity\Projects\Role\Role;
use App\Model\Work\UseCase\Projects\Role\Copy;
use App\Model\Work\UseCase\Projects\Role\Create;
use App\Model\Work\UseCase\Projects\Role\Edit;
use App\Model\Work\UseCase\Projects\Role\Remove;
use App\ReadModel\Work\Projects\RoleFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/work/projects/roles", name="work_projects_roles")
 * @IsGranted("ROLE_WORK_MANAGE_PROJECTS")
 */
class RolesController extends AbstractController
{
    /**
     * @var ErrorHandler
     */
    private $errorHandler;

    /**
     * RolesController constructor.
     * @param ErrorHandler $errorHandler
     */
    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * @Route("", name="")
     * @param RoleFetcher $fetcher
     * @return Response
     */
    public function index(RoleFetcher $fetcher): Response
    {
        $roles = $fetcher->all();
        $permissions = Permission::names();

        return $this->render('app/work/projects/roles/index.html.twig', compact('roles', 'permissions'));
    }

    /**
     * @Route("/create", name="_create")
     * @param Request $request
     * @param Create\Handler $handler
     * @return Response
     */
    public function create(Request $request, Create\Handler $handler): Response
    {
        $command = new Create\Command();

        $form = $this->createForm(Create\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('work_projects_roles');
            } catch (\DomainException $e) {
                $this->errorHandler->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/work/projects/roles/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="_edit")
     * @param Role $role
     * @param Request $request
     * @param Edit\Handler $handler
     * @return Response
     */
    public function edit(Role $role, Request $request, Edit\Handler $handler): Response
    {
        $command = Edit\Command::fromRole($role);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('work_projects_roles_show', ['id' => $role->getId()]);
            } catch (\DomainException $e) {
                $this->errorHandler->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/work/projects/roles/edit.html.twig', [
            'role' => $role,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/copy", name="_copy")
     * @param Role $role
     * @param Request $request
     * @param Copy\Handler $handler
     * @return Response
     */
    public function copy(Role $role, Request $request, Copy\Handler $handler): Response
    {
        $command = new Copy\Command($role->getId()->getValue());

        $form = $this->createForm(Copy\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('work_projects_roles');
            } catch (\DomainException $e) {
                $this->errorHandler->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/work/projects/roles/copy.html.twig', [
            'role' => $role,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="_delete", methods={"POST"})
     * @param Role $role
     * @param Request $request
     * @param Remove\Handler $handler
     * @return Response
     */
    public function delete(Role $role, Request $request, Remove\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('work_projects_roles_show', ['id' => $role->getId()]);
        }

        $command = new Remove\Command($role->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errorHandler->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('work_projects_roles');
    }

    /**
     * @Route("/{id}", name="_show")
     * @param Role $role
     * @return Response
     */
    public function show(Role $role): Response
    {
        return $this->render('app/work/projects/roles/show.html.twig', compact('role'));
    }
}