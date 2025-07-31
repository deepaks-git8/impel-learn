<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testTestLogRoute(): void
    {
        $client = static::createClient();

        $client->request('GET', '/test-log');

        $this->assertResponseIsSuccessful(); // status code 200
        $this->assertResponseFormatSame('json');

        $responseContent = $client->getResponse()->getContent();
        $data = json_decode($responseContent, true);

        $this->assertArrayHasKey('status', $data);
        $this->assertSame('Logs written successfully', $data['status']);
    }
}
