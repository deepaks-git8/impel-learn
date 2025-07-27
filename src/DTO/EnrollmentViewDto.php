<?php

// src/DTO/EnrollmentViewDto.php
namespace App\DTO;

use App\Entity\Enrollment;

class EnrollmentViewDto
{
    public int $courseId;
    public string $courseName;
    public ?\DateTimeInterface $courseDeletedAt;

    public function __construct(Enrollment $enrollment)
    {
        $course = $enrollment->getCourse();
        $this->courseId = $course->getId();
        $this->courseName = $course->getName();
        $this->courseDeletedAt = $course->getDeletedAt();
    }
}
