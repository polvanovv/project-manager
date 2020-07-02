<?php

namespace App\Controller\Work\Projects\Project\Settings;

use App\Annotations\Guid;
use App\Controller\ErrorHandler;
use App\Model\Work\Entity\Projects\Project\Department\Id;
use App\Model\Work\Entity\Projects\Project\Project;
use App\ReadModel\Work\Projects\Project\DepartmentFetcher;
use App\Security\Voter\Work\ProjectAccess;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Work\UseCase\Projects\Project\Department\Create;
use App\Model\Work\UseCase\Projects\Project\Department\Edit;
use App\Model\Work\UseCase\Projects\Project\Department\Remove;

/**
 * Class DepartmentsController
 * @package App\Controller\Work\Projects\Project\Settings
 *
 * @Route("/work/projects/{project_id}/settings/departments", name="work_projects_project_settings_departments")
 * @ParamConverter("project", options={"id" = "project_id"})
 */
class DepartmentsController extends AbstractController
{
    /**
     * @var ErrorHandler
     */
    private $errorHandler;

    /**
     * DepartmentsController constructor.
     * @param ErrorHandler $errorHandler
     */
    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * @Route("/", name="")
     *
     * @param Project $project
     * @param DepartmentFetcher $departments
     * @return Response
     */
    public function index(Project $project, DepartmentFetcher $departments): Response
    {
        $this->denyAccessUnlessGranted(ProjectAccess::MANAGE_MEMBERS, $project);

        return $this->render('app/work/projects/project/settings/departments/index.html.twig', [
            'project' => $project,
            'departments' => $departments->allOfProject($project->getId()->getValue()),
        ]);
    }

    /**
     * @Route("/create", name="_create")
     *
     * @param Project $project
     * @param Request $request
     * @param Create\Handler $handler
     * @return Response
     */
    public function create(Project $project, Request $request, Create\Handler $handler): Response
    {

        $this->denyAccessUnlessGranted(ProjectAccess::MANAGE_MEMBERS, $project);

        $command = new Create\Command($project->getId()->getValue());

        $form = $this->createForm(Create\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('work_projects_project_settings_departments', ['project_id' => $project->getId()]);
            } catch (\DomainException $e) {
                $this->errorHandler->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }
        return $this->render('app/work/projects/project/settings/departments/create.html.twig', [
            'project' => $project,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("{id}/edit", name="_edit")
     * @param Project $project
     * @param string $id
     * @param Request $request
     * @param Edit\Handler $handler
     * @return Response
     */
    public function edit(Project $project, string $id, Request $request, Edit\Handler $handler): Response
    {
        $this->denyAccessUnlessGranted(ProjectAccess::MANAGE_MEMBERS, $project);

        $department = $project->getDepartment(new Id($id));
        $command = Edit\Command::fromDepartment($project, $department);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('work_projects_project_settings_departments', ['project_id' => $project->getId(), 'id' => $id]);
            } catch (\DomainException $e) {
                $this->errorHandler->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }
        return $this->render('app/work/projects/project/settings/departments/edit.html.twig', [
            'project' => $project,
            'department' => $department,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("{id}/delete", name="_delete")
     *
     * @param Project $project
     * @param string $id
     * @param Request $request
     * @param Remove\Handler $handler
     * @return Response
     */
    public function delete(Project $project, string $id, Request $request, Remove\Handler $handler): Response
    {
        $this->denyAccessUnlessGranted(ProjectAccess::MANAGE_MEMBERS, $project);

        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('work_projects_project_settings_departments', ['project_id' => $project->getId()]);
        }

        $department = $project->getDepartment(new Id($id));
        $command = new Remove\Command($project->getId()->getValue(), $department->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errorHandler->handle($e);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('work_projects_project_settings_departments', ['project_id' => $project->getId()]);
    }

    /**
     * @Route("/{id}", name="_show", requirements={"id"=Guid::PATTERN})
     * @param Project $project
     * @return Response
     */
    public function show(Project $project): Response
    {
        return $this->redirectToRoute('work_projects_project_settings_departments', ['project_id' => $project->getId()]);
    }
}