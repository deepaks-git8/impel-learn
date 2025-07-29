<?php

namespace App\DTO;

use App\Entity\Course;

class CourseDetailDto
{
    public string $id;
    public string $title;
    public string $instructor;
    public string $duration;

    public function __construct(Course $course)
    {
        $this->id = $course->getId();
        $this->title = $course->getName();
        $this->instructor = $course->getInstructor();
        $this->duration = $course->getDuration();
    }
}
