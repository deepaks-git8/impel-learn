<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BaseWebTestCase extends WebTestCase
{
    protected $client;
    protected EntityManagerInterface $entityManager;
    protected UserPasswordHasherInterface $passwordHasher;
    // Error message constants
    public static string $emptyEmailError = 'Email is required';
    public static string $invalidEmailError = 'Please enter a valid email address.';
    public static string $mismatchedPasswordError = 'The password fields must match.';
    public static string $weakPasswordError = 'Password must include at least one uppercase letter';
    public static string $shortPasswordError = 'Your password should be at least 8 characters';
    public static string $duplicateEmailError = 'This email address is already registered.';
    public static string $missingSpecialCharacterError = 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.';
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);
    }

    /**
     * Submits the registration form.
     */
    protected function submitRegisterForm(array $formData): void
    {
        $crawler = $this->client->request('GET', '/register');
        $form = $crawler->selectButton('Register')->form($formData);
        $this->client->submit($form);
    }

    /**
     * Generates a unique email for test users.
     */
    protected function generateUniqueEmail(string $prefix = 'user_'): string
    {
        return sprintf('%s%s@example.com', $prefix, uniqid('', true));
    }

    /**
     * Returns a valid password for tests.
     */
    protected function validPassword(): string
    {
        return 'ValidPass123!';
    }

    /**
     * Registers a new user through the form.
     */
    protected function registerUser(string $email, string $password): void
    {
        $this->submitRegisterForm([
            'registration_form[email]' => $email,
            'registration_form[plainPassword][first]' => $password,
            'registration_form[plainPassword][second]' => $password,
        ]);
    }

    /**
     * Asserts that a form error contains a given message.
     */
    protected function assertFormErrorContains(string $message): void
    {
        $this->assertSelectorTextContains('.form-error-message', $message);
    }

    /**
     * Creates a standard user in the database.
     */
    protected function createTestUser(): User
    {
        $user = new User();
        $user->setEmail($this->generateUniqueEmail('testuser_'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $this->validPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * Creates an admin user in the database.
     */
    protected function createAdminUser(): User
    {
        $user = new User();
        $user->setEmail($this->generateUniqueEmail('admin_'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $this->validPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * Creates and persists a test course.
     */
    /**
     * Creates and persists a test course with valid Data.
     */
    protected function createCourse(
        string $name = 'Test Course',
        string $description = 'This is a valid course description with more than fifty characters in length.',
        string $instructor = 'John Doe',
        string $duration = '6'
    ): Course {
        $course = new Course();
        $course->setName($name);
        $course->setDescription($description);
        $course->setInstructor($instructor);
        $course->setDuration($duration); // Matches entity's string duration field

        $this->entityManager->persist($course);
        $this->entityManager->flush();

        return $course;
    }
}
