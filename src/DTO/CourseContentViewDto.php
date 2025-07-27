<?php

// src/DTO/CourseContentViewDto.php
namespace App\DTO;

use App\Entity\Course;

class CourseContentViewDto
{
    public int $id;
    public string $name;
    public string $description;

    public function __construct(Course $course)
    {
        $this->id = $course->getId();
        $this->name = $course->getName();
        $this->description = $course->getDescription();
    }
}
