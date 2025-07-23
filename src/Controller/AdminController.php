<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/add', name: 'app_add_course')]
    public function addCourse(EntityManagerInterface $em, Request $request) : Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($course);
            $em->flush();

            $this->addFlash('success', 'Course added successfully!');
            return $this->redirectToRoute('app_course_view');
        }

        return $this->render('course_view/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/admin/edit/{id}', name: 'app_edit_course')]
    public function editCourse(int $id, EntityManagerInterface $em, Request $request, CourseRepository $courseRepo) : Response
    {
        $course = $courseRepo->find(['id'=>$id]);
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($course);
            $em->flush();

            $this->addFlash('success', 'Course updated successfully!');
            return $this->redirectToRoute('app_course_view');
        }

        return $this->render('course_view/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/admin/delete/{id}', name: 'app_delete_course')]
    public function deleteCourse(int $id, CourseRepository $courseRepo, EntityManagerInterface $em): RedirectResponse
    {
        $course = $courseRepo->find($id);

        if (!$course) {
            throw $this->createNotFoundException('Course not found.');
        }

        $em->remove($course);
        $em->flush();

        $this->addFlash('success', 'Course deleted successfully!');
        return $this->redirectToRoute('app_course_view');
    }


}
