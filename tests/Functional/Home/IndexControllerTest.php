<?php

declare(strict_types=1);

namespace App\Tests\Functional\Home;

use App\Service\HttpClientDecorator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndexController(): void
    {
        HttpClientDecorator::setExpectedResponseCode(500);

        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Welcome on Symfony Demos!');
        self::assertSelectorTextContains('p', 'Current public IP is: 0.0.0.0');
    }
}
