<?php

namespace App\Tests\Data;

class TestCourseData
{
    public static function validCourse(): array
    {
        return [
            'name'        => 'New Course Name',
            'description' => str_repeat('This is a valid course description. ', 3),
            'instructor'  => 'John Doe',
            'duration'    => 4,
        ];
    }

    public static function updatedCourse(): array
    {
        return [
            'name'        => 'Updated Course Name',
            'description' => str_repeat('Updated course description. ', 3),
            'instructor'  => 'Jane Doe',
            'duration'    => 6,
        ];
    }

    public static function editableCourseName(): string
    {
        return 'Editable Course';
    }

    public static function courseName(): string
    {
        return 'Advanced PHP';
    }

    public static function courseDescription(): string
    {
        return 'A detailed PHP course.';
    }

    public static function instructorName(): string
    {
        return 'John Doe';
    }

    public static function courseDuration(): string
    {
        return '10 weeks';
    }

    public static function deletedAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
