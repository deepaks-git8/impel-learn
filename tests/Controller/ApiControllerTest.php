<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

class ApiControllerTest extends WebTestCase
{
    private $client;
    private $user;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        // Replace with an actual user in your test DB
        $this->user = $container
            ->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['email' => 'test1@test.com']);

        $this->assertNotNull($this->user, 'User not found in test DB');
        $this->client->loginUser($this->user);
    }

    public function testViewCourse(): void
    {
        $this->client->request('GET', '/api/view-course');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testCoursesWithUsers(): void
    {
        $this->client->request('GET', '/api/courses-with-users');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testViewSortedCourse(): void
    {
        $this->client->request('GET', '/api/view-sorted-course');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testViewSortedCourseWithParams(): void
    {
        $this->client->request('GET', '/api/view-sorted-course/name/asc');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertGreaterThan(1, count($data));

        // Assert that courses are sorted by name ASC
        $names = array_column($data, 'name');
        $sorted = $names;
        sort($sorted);

        $this->assertEquals($sorted, $names, 'Courses are not sorted by name ASC');
    }


    public function testViewCourseHashed(): void
    {
        $this->client->request('GET', '/api/view-course-hashed');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }
}
