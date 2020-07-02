<?php

declare(strict_types=1);

namespace App\Controller\Auth;


use App\Controller\ErrorHandler;
use App\Model\User\UseCase\SingUp;
use App\ReadModel\User\UserFetcher;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class SignUpController extends AbstractController
{

    /**
     * @var UserFetcher
     */
    private $fetcher;
    /**
     * @var ErrorHandler
     */
    private $errorHandler;

    public function __construct(ErrorHandler $errorHandler, UserFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
        $this->errorHandler = $errorHandler;
    }

    /**
     * @Route("/signup", name="auth_signup")
     *
     * @param Request $request
     * @param SingUp\Request\Handler $handler
     * @return Response
     */
    public function sigUp(Request $request, SingUp\Request\Handler $handler): Response
    {
        $command = new SingUp\Request\Command();

        $form = $this->createForm(SingUp\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Check your email.');

                return $this->redirectToRoute('home');

            } catch (\DomainException $e) {
                $this->errorHandler->handle($e);
                $this->addFlash('error', $e->getMessage());
            }

        }

        return $this->render('app/auth/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/signup/{token}", name="auth_signup_confirm")
     *
     * @param string $token
     * @param SingUp\Confirm\ByToken\Handler $handler
     * @param Request $request
     * @param UserProviderInterface $userProvider
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $formAuthenticator
     * @return Response
     */
    public function confirm(
        string $token,
        SingUp\Confirm\ByToken\Handler $handler,
        Request $request,
        UserProviderInterface $userProvider,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator
    ): Response
    {

        if (!$user = $this->fetcher->findBySignUpConfirmToken($token)) {
            $this->addFlash('error', 'Incorrect or already confirmed token.');
            return $this->redirectToRoute('auth_signup');
        }


        $command = new SingUp\Confirm\ByToken\Command($token);

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Email is successfully confirmed.');

            return $guardHandler->authenticateUserAndHandleSuccess(
                $userProvider->loadUserByUsername($user->email),
                $request,
                $authenticator,
                'main'
            );
        } catch (\DomainException $e) {
            $this->errorHandler->handle($e);
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('home');

        }
    }
}