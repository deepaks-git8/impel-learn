<?php

namespace App\Controller;

use App\Form\CourseType;
use App\Form\RegistrationFormType;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseViewController extends AbstractController
{
    #[Route('/view', name: 'app_course_view')]
    public function index(CourseRepository $course): Response
    {
        $courses = $course->findAll();
        return $this->render('course_view/index.html.twig', [
            'controller_name' => 'CourseViewController',
            'courses' => $courses
        ]);
    }

    #[Route('/dashboard', name: 'user_dashboard')]
    public function dashboard(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('course_view/dashboard.html.twig', [
            'enrollments' => $user->getEnrollments(),
        ]);
    }

    #[Route('/course/content/{id}', name: 'app_course_content')]
    public function content(int $id, CourseRepository $courseRepo): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }
        $course = $courseRepo->Find($id);

        return $this->render('course_view/content.html.twig', [
            'course' => $course,
        ]);
    }




}
