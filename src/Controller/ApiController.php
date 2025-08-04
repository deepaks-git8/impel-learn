<?php

namespace App\Controller;

use App\DTO\ApiResponseDto;
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
use App\DTO\CourseDtoApi;
use App\DTO\CourseDetailDto;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;


class ApiController extends AbstractController
{
    #[Route('/api/view-course', name: 'app_api_view_course', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(CourseRepository $courseRepo): JsonResponse
    {
        $courses = $courseRepo->findAllActive();
        $dtoResponse = array_map(fn(Course $course) => new CourseDtoApi($course), $courses);
        return new JsonResponse(new ApiResponseDto($dtoResponse));
    }

    #[Route('/testing', name:'app_test')]
    public function testing(CourseRepository $courseRepo): JsonResponse
    {
        $courses = $courseRepo->findAll();
        $dtoResult = array_map(fn(Course $course) => new CourseDtoApi($course), $courses);
        return new JsonResponse(new ApiResponseDto($dtoResult));
    }

    #[Route('/api/get-users/{course_id}', name: 'app_get_user', requirements: ['course_id' => '\d+'], methods: ['GET'])]
    public function getUsers(int $course_id, EnrollmentRepository $enrollmentRepo): JsonResponse
    {
        $enrollments = $enrollmentRepo->findBy(['course'=> $course_id]);
        $dtoResponse = array_map(fn(Enrollment $enrollment) => new UserDto($enrollment), $enrollments);
        return new JsonResponse(new ApiResponseDto($dtoResponse));
    }

    #[Route('/api/users-with-courses', name: 'api_users_active_courses')]
    public function getUsersWithActiveCourses(UserRepository $repo): JsonResponse
    {
        $users = $repo->findUsersWithActiveCourses();

        $data = array_map(fn(User $user) => new UserCourseDto($user), $users);

        return $this->json($data);
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

        return new JsonResponse(new ApiResponseDto($response));
    }

    #[Route('/api/courses-detailed', name: 'app_api_courses_detailed', methods: ['GET'])]
    public function getCoursesDetailed(CourseRepository $courseRepo): JsonResponse
    {
        $courses = $courseRepo->findAllActive();
        $dtoList = array_map(fn(Course $course) => new CourseDetailDto($course), $courses);
        return new JsonResponse(new ApiResponseDto($dtoList));
    }

    #[Route('/api/view-sorted-course', name: 'app_api_view_sorted_course', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function viewSortedCourse(CourseRepository $courseRepo, Request $request): JsonResponse
    {
        $sort = $request->query->get('sort', 'name');
        $order = $request->query->get('order', 'asc');

        $courses = $courseRepo->findAllSorted($sort, $order);

        $dtoResponse = array_map(fn(Course $course) => new CourseDtoApi($course), $courses);

        return new JsonResponse(new ApiResponseDto($dtoResponse));
    }

    #[Route('/api/view-sorted-course/{sort}/{order}', name: 'app_api_view_sorted_course', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function viewSortedCourseRoute(
        CourseRepository $courseRepo,
        string $sort = 'name',
        string $order = 'asc'
    ): JsonResponse {
        $courses = $courseRepo->findAllSorted($sort, $order);

        $dtoResponse = array_map(fn(Course $course) => new CourseDtoApi($course), $courses);

        return new JsonResponse(new ApiResponseDto($dtoResponse));
    }

    #[Route('/api/view-course-hashed', name: 'app_api_view_course_hashed', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index2(CourseRepository $courseRepo): JsonResponse
    {
        $courses = $courseRepo->findAllActive();


        $dtoResponse = array_map(function(Course $course) {
            $dto = new CourseDtoApi($course);
            return [
                'id' => $dto->getId(),
                'name' => $dto->name,
            ];
        }, $courses);

        return new JsonResponse(new ApiResponseDto($dtoResponse));
    }

}
