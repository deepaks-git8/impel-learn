<?php

namespace App\Tests\DTO;

use App\DTO\ApiResponseDto;
use PHPUnit\Framework\TestCase;

class ApiResponseDtoTest extends TestCase
{
    public function testRequestedAtIsValidIso8601(): void
    {
        $dto = new ApiResponseDto(['test' => 'value']);

        // Check if requested_at is a valid ISO 8601 string
        $timestamp = \DateTime::createFromFormat(\DateTime::ATOM, $dto->requested_at);
        $this->assertInstanceOf(\DateTime::class, $timestamp);
    }

    public function testDataAssignmentWithEdgeCases(): void
    {
        $edgeCaseData = [
            'id' => 0,
            'name' => '',
            'description' => null,
            'active' => false,
            'created_at' => '0000-00-00 00:00:00',
            'students' => [],
            'metadata' => ['key' => 'value', 'nested' => ['sub' => null]],
            'unicode' => '𝓣𝓮𝓼𝓽 ✓',
            'long_string' => str_repeat('x', 5000),
        ];

        $dto = new ApiResponseDto($edgeCaseData);

        $this->assertSame($edgeCaseData, $dto->data);
    }

    public function testDataAssignmentWithString(): void
    {
        $dto = new ApiResponseDto("simple string");
        $this->assertSame("simple string", $dto->data);
    }

    public function testDataAssignmentWithInteger(): void
    {
        $dto = new ApiResponseDto(42);
        $this->assertSame(42, $dto->data);
    }


}
