<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\User\UseCase\Create;
use App\Model\User\UseCase\Edit;
use App\Model\User\UseCase\Role;
use App\Model\User\UseCase\SingUp\Confirm\Manual;
use App\Model\User\UseCase\Activate;
use App\Model\User\UseCase\Block;
use App\Model\User\Entity\User\User;
use App\ReadModel\User\Filter;
use App\ReadModel\User\UserFetcher;
use App\ReadModel\Work\Members\Member\MemberFetcher;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class UsersController
 *
 * @package App\Controller
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 * @Route("/users", name="users")
 * @IsGranted("ROLE_MANAGE_USERS")
 */
class UsersController extends AbstractController
{

    const PER_PAGE = 10;
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
     * @param Request $request
     * @param UserFetcher $userFetcher
     * @return Response
     */
    public function index(Request $request, UserFetcher $userFetcher): Response
    {
        $filter = new Filter\Filter();


        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $userFetcher->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'created_at'),
            $request->query->get('direction', 'desc')
        );

        return $this->render('app/users/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
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
                $this->addFlash('success', 'User successfully created');

                return $this->redirectToRoute('users');
            } catch (\DomainException $e) {
                $this->logger->warning($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/users/create.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{id}/edit", name="_edit")
     *
     * @param User $user
     * @param Request $request
     * @param Edit\Handler $handler
     * @return Response
     */
    public function edit(User $user, Request $request, Edit\Handler $handler): Response
    {
        if ($user->getId()->getValue() === $this->getUser()->getId()) {
            $this->addFlash('error', 'Unable to edit yourself.');
            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
        }

        $command = Edit\Command::fromUser($user);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
            } catch (\DomainException $e) {
                $this->logger->warning($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/users/edit.html.twig', [
           'user' => $user,
           'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/role", name="_role")
     *
     * @param User $user
     * @param Request $request
     * @param Role\Handler $handler
     * @return Response
     */
    public function role(User $user, Request $request, Role\Handler $handler): Response
    {
        if ($user->getId()->getValue() === $this->getUser()->getId()) {
            $this->addFlash('error', 'Unable to change role for yourself.');
            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
        }

        $command = Role\Command::fromUser($user);

        $form = $this->createForm(Role\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
            } catch (\DomainException $e) {
                $this->logger->warning($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/users/role.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/confirm", name="_confirm")
     *
     * @param User $user
     * @param Request $request
     * @param Manual\Handler $handler
     * @return RedirectResponse
     */
    public function confirm(User $user, Request $request, Manual\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('confirm', $request->request->get('token'))) {
            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
        }

        $command = new Manual\Command($user->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->logger->warning($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
    }

    /**
     * @Route("/{id}/block", name="_block")
     *
     * @param User $user
     * @param Request $request
     * @param Block\Handler $handler
     * @return RedirectResponse
     */
    public function block(User $user, Request $request, Block\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('block', $request->request->get('token'))) {
            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
        }

        $command = new Block\Command($user->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->logger->warning($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
    }

    /**
     * @Route("/{id}/activate", name="_activate")
     *
     * @param User $user
     * @param Request $request
     * @param Activate\Handler $handler
     * @return RedirectResponse
     */
    public function activate(User $user, Request $request, Activate\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('activate', $request->request->get('token'))) {
            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
        }

        $command = new Activate\Command($user->getId()->getValue());

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->logger->warning($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
    }

    /**
     * @Route("/{id}", name="_show")
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user, MemberFetcher $memberFetcher): Response
    {
        $member = $memberFetcher->find($user->getId()->getValue());

        return $this->render('app/users/show.html.twig', [
            'user' => $user,
            'member' => $member
        ]);
    }


}