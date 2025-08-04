<?php

namespace App\DTO;

use App\Entity\Course;

class CourseDtoApi
{
    public string $id;
    public string $name;

    public function __construct(Course $course)
    {
        $this->id = base64_encode((string) $course->getId());
        $this->name = $course->getName();
    }

    public function getId(): string
    {
        return $this->id;
    }
}
