<?php

namespace App\DTO;

use App\Entity\Enrollment;
use App\Entity\User;

class UserDto
{
    public int $userId;
    public string $email;
    public function __construct(Enrollment $enrollment){
        $this->userId = $enrollment->getUser()->getId();
        $this->email = $enrollment->getUser()->getEmail();
    }
}