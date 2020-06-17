<?php


namespace App\Controller\Work\Projects\Project;

use App\Annotations\Guid;
use App\Model\Work\Entity\Projects\Project\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/work/projects/{id}", name="work_projects_project")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("", name="_show", requirements={"id"=Guid::PATTERN})
     * @param Project $project
     * @return Response
     */
    public function show(Project $project): Response
    {
        return $this->render('app/work/projects/project/show.html.twig', compact('project'));
    }
}