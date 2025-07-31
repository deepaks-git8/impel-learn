<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiControllerTest extends WebTestCase
{
    public function testAuthenticatedCourseView(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Ensure user exists in the test DB
        $userRepo = $em->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => 'test1@test.com']);

        if (!$user) {
            $user = new User();
            $user->setEmail('test1@test.com');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword(
                $passwordHasher->hashPassword($user, 'adminpass')
            );
            $em->persist($user);
            $em->flush();
        }

        // Ensure user was created
        $this->assertNotNull($user, 'User not found in test DB');

        // Login and test
        $client->loginUser($user);
        $client->request('GET', '/api/view-course');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);

        $this->assertArrayHasKey('data', $data);
    }
}
