<?php

namespace App\Controller;

use App\DTO\CourseContentViewDto;
use App\DTO\CourseDtoApi;
use App\DTO\CourseDtoTwig;
use App\DTO\EnrollmentViewDto;
use App\Entity\Course;
use App\Entity\Enrollment;
use App\Form\CourseType;
use App\Form\RegistrationFormType;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseViewController extends AbstractController
{
    #[Route('/view', name: 'app_course_view')]
    public function index(CourseRepository $course): Response
    {
        $courses = $course->findAll();

//        return $this->render('course_view/index.html.twig', [
//            'controller_name' => 'CourseViewController',
//            'courses' => $courses
//        ]);

        $courseDtos = array_map(fn(Course $course) => new CourseDtoTwig($course), $courses);

        return $this->render('course_view/index.html.twig', [
            'controller_name' => 'CourseViewController',
            'courses' => $courseDtos,
        ]);
    }

    #[Route('/dashboard', name: 'user_dashboard')]
    public function dashboard(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $dtoEnrollments = array_map(fn(Enrollment $e) => new EnrollmentViewDto($e), $user->getEnrollments()->toArray());

        return $this->render('course_view/dashboard.html.twig', [
            'enrollments' => $dtoEnrollments,
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
        if(!$course){
            return new JsonResponse(["404"=>"Invalid Course Id"]);
        }


        $courseDto = new CourseContentViewDto($course);

        return $this->render('course_view/content.html.twig', [
            'course' => $courseDto,
        ]);
    }




}
