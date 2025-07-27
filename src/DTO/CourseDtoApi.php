<?php

namespace App\DTO;

use App\Entity\Course;

class CourseDtoApi{
    public int $id;
    public string $name;


    public function __construct(Course $course)
    {
        $this->id = $course->getId();
        $this->name = $course->getName();


    }

}