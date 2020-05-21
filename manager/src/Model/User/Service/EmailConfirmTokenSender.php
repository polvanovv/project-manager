<?php

declare(strict_types = 1);

namespace App\Model\User\Service;


use App\Model\User\Entity\User\Email;
use Twig\Environment;

/**
 * Class EmailConfirmTokenSender
 *
 * @package App\Model\User\Service
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class EmailConfirmTokenSender
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * EmailConfirmTokenSender constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param Email $email
     * @param string $token
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function send(Email $email, string $token)
    {
        $message = (new \Swift_Message('Email Confirmation'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('mail/user/email.html.twig',[
                'token' => $token,
            ]), 'text/html');

        if (!$this->mailer->send($message)) {
            throw new \RuntimeException('Unable to send message.');
        }


    }
}