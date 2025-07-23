<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Enrollment;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EnrollmentController extends AbstractController
{
    #[Route('/courses/enroll/{id}', name: 'app_enroll_course', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function enroll(
        int $id,
        CourseRepository $courseRepo,
        EntityManagerInterface $em
    ): RedirectResponse
    {
        $user = $this->getUser();

        $course = $courseRepo->find($id);
        if (!$course) {
            $this->addFlash('error', 'Course not found!');
            return $this->redirectToRoute('app_course_view');
        }

        // Check if already enrolled
        foreach ($user->getEnrollments() as $enrollment) {
            if ($enrollment->getCourse()->getId() === $course->getId()) {
                $this->addFlash('warning', 'You are already enrolled in this course.');
                return $this->redirectToRoute('app_course_view');
            }
        }

        $enrollment = new Enrollment();
        $enrollment->setUser($user);
        $enrollment->setCourse($course);
        $em->persist($enrollment);
        $em->flush();

        $this->addFlash('success', 'Successfully enrolled in course!');
        return $this->redirectToRoute('app_course_view');
    }
}
