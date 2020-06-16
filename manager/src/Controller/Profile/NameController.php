<?php

declare(strict_types = 1);

namespace App\Controller\Profile;


use App\ReadModel\User\UserFetcher;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User\UseCase\Name;

/**
 * Class NameController
 *
 * @package App\Controller\Profile
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 * @Route("/profile/name")
 */
class NameController extends AbstractController
{

    /**
     * @var UserFetcher
     */
    private $fetcher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(UserFetcher $fetcher, LoggerInterface $logger)
    {
        $this->fetcher = $fetcher;
        $this->logger = $logger;
    }


    /**
     * @Route("/", name="profile_name_edit")
     *
     * @param Request $request
     * @param Name\Handler $handler
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Name\Handler $handler)
    {
        $user = $this->fetcher->get($this->getUser()->getId());

        $command = new Name\Command($user->getId()->getValue());
        $command->firstName = $user->getName()->getFirst();
        $command->lastName = $user->getName()->getLast();

        $form = $this->createForm(Name\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                    $handler->handle($command);

                return $this->redirectToRoute('profile');
            } catch (\DomainException $e) {
                $this->logger->warning($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/profile/name.html.twig',[
            'form' => $form->createView()
        ]);
    }
}