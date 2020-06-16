<?php

declare(strict_types = 1);

namespace App\Controller\Profile\OAuth;


use App\Model\User\UseCase\Network\Detach\Command;
use App\Model\User\UseCase\Network\Detach\Handler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/profile/oauth")
 *
 * Class DetachController
 *
 * @package App\Controller\Profile\OAuth
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class DetachController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DetachController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/detach/{network}/{identity}", name="profile_oauth_detach", methods={"DELETE"})
     * @param Request $request
     * @param string $network
     * @param string $identity
     * @param Handler $handler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detach(Request $request, string $network, string $identity, Handler $handler)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('profile');
        }

        $command = new Command(
            $this->getUser()->getId(),
            $network,
            $identity
        );

        try {
            $handler->handle($command);
            $this->addFlash('success', 'The network has been detached');

            return $this->redirectToRoute('profile');
        } catch (\DomainException $e) {
            $this->logger->warning($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('profile');
        }
    }
}