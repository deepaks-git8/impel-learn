<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        Security $security,
        UserRepository $userRepository
    ): Response {
        if ($security->getUser()) {
            return $this->redirectToRoute('user_dashboard');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            // Check if user already exists
            $existingUser = $userRepository->findOneBy(['email' => $email]);
            if ($existingUser) {
                $this->addFlash('error', 'Email already exists.');
                return $this->redirectToRoute('app_register');
            }

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Registration successful!');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
