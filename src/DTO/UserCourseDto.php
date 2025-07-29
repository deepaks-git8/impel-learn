<?php

namespace App\DTO;

use App\Entity\User;

class UserCourseDto
{
    public int $id;
    public string $email;
    public array $courses = [];

    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->email = $user->getEmail();

        foreach ($user->getEnrollments() as $enrollment) {
            $course = $enrollment->getCourse();
            $this->courses[] = [
                'id' => $course->getId(),
                'name' => $course->getName(),
            ];
        }
    }
}
