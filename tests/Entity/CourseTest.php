<?php

namespace App\Tests\Entity;

use App\Entity\Course;
use App\Entity\Enrollment;
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
        $course->setName('Advanced PHP');
        $this->assertSame('Advanced PHP', $course->getName());
    }

    public function testSetAndGetDescription(): void
    {
        $course = new Course();
        $course->setDescription('A detailed PHP course.');
        $this->assertSame('A detailed PHP course.', $course->getDescription());
    }

    public function testSetAndGetInstructor(): void
    {
        $course = new Course();
        $course->setInstructor('John Doe');
        $this->assertSame('John Doe', $course->getInstructor());
    }

    public function testSetAndGetDuration(): void
    {
        $course = new Course();
        $course->setDuration('10 weeks');
        $this->assertSame('10 weeks', $course->getDuration());
    }

    public function testSetAndGetDeletedAt(): void
    {
        $course = new Course();
        $now = new \DateTimeImmutable();
        $course->setDeletedAt($now);
        $this->assertSame($now, $course->getDeletedAt());
    }
}
