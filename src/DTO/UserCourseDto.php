<?php

namespace App\DTO;

class UserCourseDto
{
    public int $userId;
    public string $email;
    public array $courses;

    public function __construct($user)
    {
        $this->userId = $user->getId();
        $this->email = $user->getEmail();
        $this->courses = [];

        foreach ($user->getEnrollments() as $enrollment) {
            $this->courses[] = [
                'course_id' => $enrollment->getCourse()->getId(),
                'course_name' => $enrollment->getCourse()->getName(),
            ];
        }
    }
}
