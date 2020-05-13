<?php

declare(strict_types = 1);

namespace App\Controller\Auth;


use App\Model\User\UseCase\SingUp;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractController
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
                $this->logger->error($e->getMessage(),['exception' => $e]);
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
     * @param SingUp\Confirm\Handler $handler
     * @return Response
     */
    public function confirm(string $token, SingUp\Confirm\Handler $handler): Response
    {
        $command = new SingUp\Confirm\Command($token);

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Email is successfully confirmed.');
            return $this->redirectToRoute('home');
        } catch (\DomainException $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('home');

        }
    }
}