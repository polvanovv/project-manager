<?php

declare(strict_types=1);

namespace App\Controller\Profile\OAuth;


use App\Controller\ErrorHandler;
use App\Model\User\UseCase\Network\Attach\Command;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User\UseCase\Network\Attach\Handler;

/**
 * @Route("/profile/oauth/facebook")
 * Class FacebookController
 *
 * @package App\Controller\Profile\OAuth
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class FacebookController extends AbstractController
{
    /**
     * @var ErrorHandler
     */
    private $errorHandler;

    /**
     * FacebookController constructor.
     * @param ErrorHandler $errorHandler
     */
    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * @Route("/attache", name="profile_oauth_facebook")
     *
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function connect(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('facebook_attach')
            ->redirect(['public_profile']);
    }

    /**
     * @Route("/check", name="profile_oauth_facebook_check")
     *
     * @param ClientRegistry $clientRegistry
     * @param Handler $hander
     * @return Response
     */
    public function check(ClientRegistry $clientRegistry, Handler $hander): Response
    {
        $client = $clientRegistry->getClient('facebook_attach');

        $command = new Command(
            $this->getUser()->getId(),
            'facebook',
            $client->fetchUser()->getId()
        );

        try {
            $hander->handle($command);
            $this->addFlash('success', 'Facebook is successfully attached.');

            return $this->redirectToRoute('profile');
        } catch (\DomainException $e) {
            $this->errorHandler->handle($e);
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('profile');
        }
    }
}