<?php


namespace App\Controller\Work\Members;


use App\Model\Work\Entity\Members\Group\Group;
use App\Model\Work\UseCase\Members\Group\Create;
use App\Model\Work\UseCase\Members\Group\Edit;
use App\Model\Work\UseCase\Members\Group\Remove;
use App\ReadModel\Work\Members\GroupFetcher;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GroupController
 * @package App\Controller\Work\Members
 *
 * @Route("/work/members/group", name="work_members_groups")
 * @IsGranted("ROLE_WORK_MANAGE_MEMBERS")
 */
class GroupController extends AbstractController
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
     * @Route("/", name="")
     *
     * @param GroupFetcher $fetcher
     * @return Response
     */
    public function index(GroupFetcher $fetcher): Response
    {
        $groups = $fetcher->all();

        return $this->render('app/work/members/groups/index.html.twig', [
            'groups' => $groups,
        ]);
    }

    /**
     * @Route("/create", name="_create")
     *
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
                return $this->redirectToRoute('work_members_groups');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }
        return $this->render('app/work/members/groups/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="_edit")
     *
     * @param Group $group
     * @param Request $request
     * @param Edit\Handler $handler
     * @return RedirectResponse|Response
     */
    public function edit(Group $group, Request $request, Edit\Handler $handler): Response
    {
        $command = new Edit\Command($group->getId());

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('work_members_groups_edit', ['id' => $group->getId()]);
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }
        return $this->render('app/work/members/groups/edit.html.twig', [
            'form' => $form->createView(),
            'group' => $group
        ]);
    }

    /**
     * @Route("{id}/delete", name="_delete")
     *
     * @param Group $group
     * @param Request $request
     * @param Remove\Handler $handler
     * @return RedirectResponse
     */
    public function delete(Group $group,Request $request, Remove\Handler $handler)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('work_members_groups');
        }

        $command = new Remove\Command($group->getId());

        try {
            $handler->handle($command);
            $this->redirectToRoute('work_members_groups');
        } catch (\DomainException $e) {
            $this->logger->error($e->getMessage(), ['exception'=>$e]);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('work_members_groups');
    }
}