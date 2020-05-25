<?php

declare(strict_types = 1);

namespace App\Tests\Functional;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeTest extends WebTestCase
{
    public function testGuest()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/login', $client->getResponse()->headers->get('Location'));
    }
}