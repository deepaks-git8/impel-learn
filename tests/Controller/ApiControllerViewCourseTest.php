<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerViewCourseTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        // Always call createClient() here — do not bootKernel() separately
        $this->client = static::createClient();
    }

    public function testGetUsersWithValidCourseId(): void
    {
        $this->client->request('GET', '/api/get-users/1');

        $this->assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('requested_at', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertIsArray($responseData['data']);
    }

    public function testGetUsersWithZeroCourseId(): void
    {
        $this->client->request('GET', '/api/get-users/0');

        $this->assertResponseIsSuccessful(); // Or adjust to your API logic
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEmpty($responseData['data']);
    }

    public function testGetUsersWithNegativeCourseId(): void
    {
        $this->client->request('GET', '/api/get-users/-1');

        $this->assertResponseStatusCodeSame(404); // Route param constraint fails
    }

    public function testGetUsersWithNonExistentCourseId(): void
    {
        $this->client->request('GET', '/api/get-users/99999');

        $this->assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData['data']);
        $this->assertEmpty($responseData['data']);
    }

    public function testGetUsersWithStringCourseId(): void
    {
        $this->client->request('GET', '/api/get-users/abc');

        $this->assertResponseStatusCodeSame(404); // Invalid route param
    }

    public function testGetUsersWithSpecialCharacterCourseId(): void
    {
        $this->client->request('GET', '/api/get-users/%40%23%24'); // URL-encoded @#$

        $this->assertResponseStatusCodeSame(404); // Invalid route
    }
}
