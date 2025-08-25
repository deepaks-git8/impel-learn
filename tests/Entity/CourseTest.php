<?php

namespace App\Tests\Entity;

use App\Entity\Course;
use App\Entity\Enrollment;
use App\Tests\Data\TestCourseData;
use PHPUnit\Framework\TestCase;

class CourseTest extends TestCase
{
    public function testAddEnrollment(): void
    {
        $course = new Course();
        $enrollment = new Enrollment();

        $this->assertCount(0, $course->getEnrollments());

        $course->addEnrollment($enrollment);

        $this->assertCount(1, $course->getEnrollments());
        $this->assertTrue($course->getEnrollments()->contains($enrollment));
        $this->assertSame($course, $enrollment->getCourse());
    }

    public function testRemoveEnrollment(): void
    {
        $course = new Course();
        $enrollment = new Enrollment();

        $course->addEnrollment($enrollment);
        $this->assertCount(1, $course->getEnrollments());

        $course->removeEnrollment($enrollment);

        $this->assertCount(0, $course->getEnrollments());
        $this->assertNull($enrollment->getCourse());
    }

    public function testSetAndGetName(): void
    {
        $course = new Course();
        $course->setName(TestCourseData::courseName());
        $this->assertSame(TestCourseData::courseName(), $course->getName());
    }

    public function testSetAndGetDescription(): void
    {
        $course = new Course();
        $course->setDescription(TestCourseData::courseDescription());
        $this->assertSame(TestCourseData::courseDescription(), $course->getDescription());
    }

    public function testSetAndGetInstructor(): void
    {
        $course = new Course();
        $course->setInstructor(TestCourseData::instructorName());
        $this->assertSame(TestCourseData::instructorName(), $course->getInstructor());
    }

    public function testSetAndGetDuration(): void
    {
        $course = new Course();
        $course->setDuration(TestCourseData::courseDuration());
        $this->assertSame(TestCourseData::courseDuration(), $course->getDuration());
    }

    public function testSetAndGetDeletedAt(): void
    {
        $course = new Course();
        $now = TestCourseData::deletedAt();
        $course->setDeletedAt($now);
        $this->assertSame($now, $course->getDeletedAt());
    }
}
