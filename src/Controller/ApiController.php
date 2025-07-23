<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\EnrollmentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/view-course', name: 'app_api_view_course', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(CourseRepository $courseRepo): JsonResponse
    {
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $results = $courseRepo->findAll();
        $responseInJson = [];

        foreach ($results as $course) {
            $responseInJson[] = [
                'id' => $course->getId(),
                'name' => $course->getName(),
            ];
        }

        return new JsonResponse($responseInJson);
    }

    #[Route('/api/get-users/{course_id}', name:'app_get_user', methods: ['GET'])]
    public function getUsers(int $course_id, EnrollmentRepository $enrollmentRepo) :JsonResponse {
        $enrollments = $enrollmentRepo->findBy(['course'=> $course_id]);
        $responseInJson = [];
        foreach($enrollments as $enrollment){
        $user = $enrollment->getUser();
            $responseInJson [] = [
                'user_id'=> $user->getId(),
                'email'=> $user->getEmail()
            ];
        }
        return new JsonResponse($responseInJson);
    }
}
