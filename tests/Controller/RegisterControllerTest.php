<?php

namespace App\Tests\Controller;

use App\Tests\Data\TestUserData;

class RegisterControllerTest extends BaseWebTestCase
{
    public function testRegisterWithEmptyFields(): void
    {
        $this->submitRegisterForm([
            'registration_form[email]' => '',
            'registration_form[plainPassword][first]' => '',
            'registration_form[plainPassword][second]' => '',
        ]);
        $this->assertFormErrorContains(self::$emptyEmailError);
    }

    public function testRegisterWithInvalidEmail(): void
    {
        $this->registerUser(TestUserData::invalidEmail(), TestUserData::validPassword());
        $this->assertFormErrorContains(self::$invalidEmailError);
    }

    public function testRegisterWithMismatchedPasswords(): void
    {
        $this->submitRegisterForm([
            'registration_form[email]' => TestUserData::validEmail(),
            'registration_form[plainPassword][first]' => TestUserData::validPassword(),
            'registration_form[plainPassword][second]' => TestUserData::mismatchedPassword(),
        ]);
        $this->assertFormErrorContains(self::$mismatchedPasswordError);
    }

    public function testRegisterWithWeakPassword(): void
    {
        $this->registerUser(TestUserData::validEmail(), TestUserData::weakPassword());
        $this->assertFormErrorContains(self::$weakPasswordError);
    }

    public function testSuccessfulRegistration(): void
    {
        $this->registerUser(TestUserData::validEmail(), TestUserData::validPassword());
        $this->assertResponseRedirects('/login');
    }

    public function testRegisterWithShortPassword(): void
    {
        $this->registerUser(TestUserData::validEmail(), TestUserData::shortPassword());
        $this->assertFormErrorContains(self::$shortPasswordError);
    }

    public function testRegisterWithDuplicateEmail(): void
    {
        $email = TestUserData::duplicateEmail();
        $this->registerUser($email, TestUserData::validPassword());
        $this->registerUser($email, TestUserData::validPassword());
        $this->assertFormErrorContains(self::$duplicateEmailError);
    }

    public function testPasswordWithoutSpecialCharacter(): void
    {
        $this->registerUser(TestUserData::validEmail(), TestUserData::passwordWithoutSpecialCharacter());
        $this->assertFormErrorContains(self::$missingSpecialCharacterError);
    }

    public function testDuplicateEmailCaseInsensitive(): void
    {
        $email = TestUserData::caseInsensitiveEmail();
        $this->registerUser($email, TestUserData::validPassword());
        $this->registerUser(strtoupper($email), TestUserData::validPassword());
        $this->assertFormErrorContains(self::$duplicateEmailError);
    }

    public function testLoggedInUserRedirectedFromRegister(): void
    {
        $user = $this->createTestUser();
        $this->client->loginUser($user);
        $this->client->request('GET', '/register');
        $this->assertResponseRedirects('/dashboard');
    }
}
