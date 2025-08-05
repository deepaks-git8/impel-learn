<?php
// src/Controller/TestController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test-flash', name: 'test_flash')]
    public function testFlash(Request $request): Response
    {
        // Debug session
        $session = $request->getSession();
        dump('Session ID: ' . $session->getId());
        dump('Session started: ' . ($session->isStarted() ? 'Yes' : 'No'));

        // Add flash messages
        $this->addFlash('success', 'This is a SUCCESS flash message!');
        $this->addFlash('error', 'This is an ERROR flash message!');
        $this->addFlash('warning', 'This is a WARNING flash message!');
        $this->addFlash('info', 'This is an INFO flash message!');

        // Debug flash bag
        $flashBag = $session->getFlashBag();
        dump('Flash messages in bag:', $flashBag->peekAll());

        return $this->render('test/flash.html.twig', [
            'sessionId' => $session->getId(),
            'sessionStarted' => $session->isStarted(),
            'flashMessages' => $flashBag->peekAll()
        ]);
    }

    #[Route('/test-flash-and-redirect', name: 'test_flash_redirect')]
    public function testFlashAndRedirect(): Response
    {
        $this->addFlash('success', 'Flash message before redirect!');
        return $this->redirectToRoute('test_flash_show');
    }

    #[Route('/test-flash-show', name: 'test_flash_show')]
    public function showFlash(): Response
    {
        return $this->render('test/flash.html.twig', [
            'redirectTest' => true
        ]);
    }
}