<?php

namespace App\Tests\Controller;

use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    private function loginUser(): void
    {
        // Replace with actual logic to fetch a test user
        $user = static::getContainer()
            ->get('doctrine')
            ->getRepository(\App\Entity\User::class)
            ->findOneBy([]);

        $this->client->loginUser($user);
    }

    public function testAuthenticationIsRequired(): void
    {
        $this->client->request('GET', '/api/view-course-hashed');

        $statusCode = $this->client->getResponse()->getStatusCode();
        $this->assertContains(
            $statusCode,
            [401, 403],
            'Expected 401 or 403 when not authenticated.'
        );
    }

    public function testReturnsCourseListInExpectedFormat(): void
    {
        $this->loginUser();

        $this->client->request('GET', '/api/view-course-hashed');

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $json = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $json, 'Response should contain a "data" key.');
        $this->assertIsArray($json['data'], 'The "data" key should be an array.');

        foreach ($json['data'] as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('name', $item);
        }
    }
}
