<?php

namespace App\DTO;

class UserCourseDto
{
    public int $id;
    public string $email;
    public array $courses = [];

    public function __construct(\App\Entity\User $user)
    {
        $this->id = $user->getId();
        $this->email = $user->getEmail();

        foreach ($user->getEnrollments() as $enrollment) {
            $course = $enrollment->getCourse();
            if ($course->getDeletedAt() === null) {
                $this->courses[] = [
                    'id' => $course->getId(),
                    'name' => $course->getName(),
                ];
            }
        }
    }
}
