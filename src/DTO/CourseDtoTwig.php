<?php

namespace App\DTO;

use App\Entity\Course;

class CourseDtoTwig{
    public int $id;
    public string $name;
    public ?\DateTimeInterface $deletedAt;

    public function __construct(Course $course)
    {
        $this->id = $course->getId();
        $this->name = $course->getName();
        $this->deletedAt = $course->getDeletedAt();

    }

}