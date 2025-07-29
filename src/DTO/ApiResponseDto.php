<?php

namespace App\DTO;

class ApiResponseDto
{
    public string $requested_at;
    public mixed $data;

    public function __construct(mixed $data)
    {
        $this->requested_at = (new \DateTime())->format(\DateTime::ATOM);
        $this->data = $data;
    }
}
