<?php

namespace App\Controller;

use App\DTO\UserCourseDto;
use App\DTO\UserDto;
use App\Entity\Course;
use App\Entity\Enrollment;
use App\Entity\User;
use App\Repository\CourseRepository;
use App\Repository\EnrollmentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\DTO\CourseDto;

class ApiController extends AbstractController
{
    #[Route('/api/view-course', name: 'app_api_view_course', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(CourseRepository $courseRepo): JsonResponse
    {
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $courses = $courseRepo->findAllActive();
//        $responseInJson = [];
//
//        foreach ($results as $course) {
//            $responseInJson[] = [
//                'id' => $course->getId(),
//                'name' => $course->getName(),
//            ];
//        }
        $dtoResponse = array_map(function(Course $course){
            return new CourseDto($course);
        }, $courses);
        return new JsonResponse($dtoResponse);
    }

    #[Route('/testing' , name:'app_test')]
    public function testing(CourseRepository $courseRepo){
        $courses = $courseRepo->findAll();
        $dtoResult = array_map(function(Course $course) {
//            return new CourseDto($c->getId(), $c->getName());
            return new CourseDto($course );
        }
            , $courses);

        return new JsonResponse($dtoResult);
    }

    #[Route('/api/get-users/{course_id}', name:'app_get_user', methods: ['GET'])]
    public function getUsers(int $course_id, EnrollmentRepository $enrollmentRepo) :JsonResponse {
        $enrollments = $enrollmentRepo->findBy(['course'=> $course_id]);
//        $responseInJson = [];
//        foreach($enrollments as $enrollment){
//        $user = $enrollment->getUser();
//            $responseInJson [] = [
//                'user_id'=> $user->getId(),
//                'email'=> $user->getEmail()
//            ];
//        }
        $dtoResponse = array_map(function(Enrollment $enrollment){
            return new UserDto($enrollment);
        }, $enrollments);

        return new JsonResponse($dtoResponse);
    }

//    $responseInDTO = array_map(function ($course) {
//        return new CourseDTO(
//            $course->getId(),
//            $course->getName()
//        );
//    }, $courses);



    #[Route('/api/users-with-courses', name: 'app_users_with_courses', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function getUsersWithCourses(UserRepository $userRepo): JsonResponse
    {
        $users = $userRepo->findAll();
        $response = [];

        foreach ($users as $user) {
            $response[] = new UserCourseDto($user);
        }

        return $this->json($response);
    }

    #[Route('/courses-with-users', name:'app_courses_with_users_test')]
    public function getCoursesWithUsersTest(CourseRepository $courseRepo)
    {
        $courses = $courseRepo->findAll();

        foreach ($courses as $course){
            dd($course->getEnrollments(), $course->getName(), $course->getEnrollments());
        }
    }

    #[Route('/api/courses-with-users', name: 'app_courses_with_users', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function getCoursesWithUsers(CourseRepository $courseRepo): JsonResponse
    {
        $courses = $courseRepo->findAll();
        $response = [];

        foreach ($courses as $course) {

            if ($course->getDeletedAt() !== null) {
                continue;
            }

            $courseData = [
                'course_id' => $course->getId(),
                'course_name' => $course->getName(),
                'users' => [],
            ];

            foreach ($course->getEnrollments() as $enrollment) {
                $user = $enrollment->getUser();
                $courseData['users'][] = [
                    'user_id' => $user->getId(),
                    'email' => $user->getEmail(),
                ];
            }

            $response[] = $courseData;
        }

        return $this->json($response);
    }

}
