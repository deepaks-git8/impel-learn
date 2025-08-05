<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $utils, Security $security): Response
    {
        if ($security->getUser()) {
            return $this->redirectToRoute('user_dashboard'); // or wherever logged-in users should go
        }
        $lastUsername = $utils->getLastUsername();
        $error = $utils->getLastAuthenticationError();
        return $this->render('login/index.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(){


    }
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }

}
