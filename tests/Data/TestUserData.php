<?php

namespace App\Tests\Data;

class TestUserData
{
    public static function validEmail(string $prefix = 'user_'): string
    {
        return $prefix . uniqid() . '@example.com';
    }

    public static function invalidEmail(): string
    {
        return 'invalid-email';
    }

    public static function validPassword(): string
    {
        return 'ValidPass123!';
    }

    public static function mismatchedPassword(): string
    {
        return 'OtherPass123!';
    }

    public static function weakPassword(): string
    {
        return 'Test@';
    }

    public static function shortPassword(): string
    {
        return 'Aa1@';
    }

    public static function passwordWithoutSpecialCharacter(): string
    {
        return 'StrongPass123';
    }

    public static function duplicateEmail(): string
    {
        return 'duplicate_' . uniqid() . '@example.com';
    }

    public static function caseInsensitiveEmail(): string
    {
        return 'case_' . uniqid() . '@example.com';
    }
}
