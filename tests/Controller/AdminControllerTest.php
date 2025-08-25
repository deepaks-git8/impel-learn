<?php

namespace App\Tests\Controller;

use App\Entity\Course;
use App\Tests\Data\TestCourseData;

class AdminControllerTest extends BaseWebTestCase
{
    public function testAddCourseSuccessfully(): void
    {
        $admin = $this->createAdminUser();
        $this->client->loginUser($admin);

        $crawler = $this->client->request('GET', '/admin/add');

        $form = $crawler->selectButton('Submit')->form([
            'course[name]'        => TestCourseData::validCourse()['name'],
            'course[description]' => TestCourseData::validCourse()['description'],
            'course[instructor]'  => TestCourseData::validCourse()['instructor'],
            'course[duration]'    => TestCourseData::validCourse()['duration'],
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/view');
        $this->client->followRedirect();

        $this->assertSelectorExists('.flash-success');
    }

    public function testEditCourse(): void
    {
        $admin = $this->createAdminUser();
        $this->client->loginUser($admin);

        $course = $this->createCourse(TestCourseData::editableCourseName());

        $crawler = $this->client->request('GET', '/admin/edit/' . $course->getId());
        $form = $crawler->selectButton('Submit')->form([
            'course[name]'        => TestCourseData::updatedCourse()['name'],
            'course[description]' => TestCourseData::updatedCourse()['description'],
            'course[instructor]'  => TestCourseData::updatedCourse()['instructor'],
            'course[duration]'    => TestCourseData::updatedCourse()['duration'],
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/view');
        $this->client->followRedirect();

        $updatedCourse = $this->entityManager->getRepository(Course::class)->find($course->getId());
        $this->assertSame(TestCourseData::updatedCourse()['name'], $updatedCourse->getName());
    }

    public function testDeleteCourseWithoutEnrollments(): void
    {
        $admin = $this->createAdminUser();
        $this->client->loginUser($admin);

        $course = $this->createCourse();

        $this->client->request('GET', '/admin/delete/' . $course->getId());

        $this->assertResponseRedirects('/view');
        $deletedCourse = $this->entityManager->getRepository(Course::class)->find($course->getId());
        $this->assertNotNull($deletedCourse->getDeletedAt());
    }




    public function testNonAdminCannotAccessAddCourse(): void
    {
        $user = $this->createTestUser();
        $this->client->loginUser($user);

        $this->client->request('GET', '/admin/add');

        $this->assertResponseStatusCodeSame(403);
    }



    public function testNonAdminCannotEditCourse(): void
    {
        $user = $this->createTestUser();
        $this->client->loginUser($user);

        $course = $this->createCourse();

        $this->client->request('GET', '/admin/edit/' . $course->getId());

        $this->assertResponseStatusCodeSame(403);
    }

    //  DELETE COURSE TESTS

    public function testDeleteNonExistentCourse(): void
    {
        $admin = $this->createAdminUser();
        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/delete/99999');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testNonAdminCannotDeleteCourse(): void
    {
        $user = $this->createTestUser();
        $this->client->loginUser($user);

        $course = $this->createCourse();

        $this->client->request('GET', '/admin/delete/' . $course->getId());

        $this->assertResponseStatusCodeSame(403);
    }

    // ACCESS CONTROL

    public function testUnauthenticatedUserRedirectedFromAdminAdd(): void
    {
        $this->client->request('GET', '/admin/add');
        $this->assertResponseRedirects('http://localhost/login');
    }
}
