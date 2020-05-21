<?php

declare(strict_types = 1);

namespace App\Controller;


use App\ReadModel\User\UserFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 *
 * @package App\Controller
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class ShowController extends AbstractController
{
    /**
     * @var UserFetcher
     */
    private $fetcher;

    /**
     * ProfileController constructor.
     * @param UserFetcher $fetcher
     */
    public function __construct(UserFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    /**
     * @Route("/profile", name="profile")
     * @return Response
     */
    public function show(): Response
    {
        $user = $this->fetcher->findDetail($this->getUser()->getId());

        return $this->render('app/profile/show.html.twig', compact('user'));
    }
}