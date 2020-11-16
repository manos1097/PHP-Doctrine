<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestController extends AbstractController
{
    /**
     * @Route("/teachers", name="teachers", methods={"GET"})
     */
    public function teachers(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Teacher::class);
        $teachers = $repository->findAll();

        return $this->json(["teachers" => $teachers]);
    }

    /**
     * @Route("/teachers", name="addTeacher", methods={"PUT"})
     * @param Request $request
     * @return Response
     */
    public function add_teacher(Request $request): Response
    {
        // Get request data
        $data = json_decode($request->getContent());
        $name = $data->name;
        $email = $data->email;
        $address = $data->address;

        // Create new teacher
        $teacher = new Teacher();
        $teacher->setName($name);
        $teacher->setEmail($email);
        $teacher->setAddress($address);

        // Save the new teacher
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($teacher);
        $entityManager->flush();

        return $this->json(["message" => "Successfully created a new teacher"]);
    }

    /**
     * @Route("/teachers/{id}", name="teacher", requirements={"id"="\d+"}, methods={"GET"})
     * @param $id
     * @return Response
     */
    public function teacher($id): Response
    {
        // Get teacher by id
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);

        if (!$teacher) {
            return $this->json(["message" => "Teacher with ID: " . $id . " doesnt exist"]);
        }

        $students = $this->getDoctrine()->getRepository(Student::class)->getStudentsByTeacherId($teacher->getId());

        return $this->json(['teacher' => $teacher, 'students' => $students]);
    }

    /**
     * @Route("/teachers/{id}", name="updateTeacher", requirements={"id"="\d+"}, methods={"POST"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function update_teacher($id, Request $request): Response
    {
        // Get request data
        $data = json_decode($request->getContent());
        $name = $data->name;
        $email = $data->email;
        $address = $data->address;

        $entityManager = $this->getDoctrine()->getManager();
        $teacher = $entityManager->getRepository(Teacher::class)->find($id);

        if (!$teacher) {
            return $this->json(["message" => "Teacher with ID: " . $id . " doesnt exist"]);
        }

        // Update teacher and save
        $teacher->setName($name);
        $teacher->setEmail($email);
        $teacher->setAddress($address);
        $entityManager->flush();

        return $this->json(["message" => "Teacher with ID: " . $id . " saved successfully"]);
    }

    /**
     * @Route("/teachers/{id}", name="deleteTeacher", requirements={"id"="\d+"}, methods={"DELETE"})
     * @param $id
     * @return Response
     */
    public function delete_teacher($id): Response
    {
        // Get requested teacher by id
        $entityManager = $this->getDoctrine()->getManager();
        $teacher = $entityManager->getRepository(Teacher::class)->find($id);

        // If not found inform the client that the teacher doesnt exist
        if (!$teacher) {
            return $this->json(["message" => "Teacher with ID: " . $id . " doesnt exist"]);
        }

        // Delete him and save
        $entityManager->remove($teacher);
        $entityManager->flush();

        return $this->json(["message" => "Deleted teacher with ID: " . $id . " successfully"]);
    }

    /**
     * @Route("/students/{id}", name="student", requirements={"id"="\d+"}, methods={"GET"})
     * @param $id
     * @return Response
     */
    public function student($id): Response
    {
        // Get student by id
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);

        // If not found inform the client that the teacher doesnt exist
        if (!$student) {
            return $this->json(["message" => "Student with ID: " . $id . " doesnt exist"]);
        }

        return $this->json(['student' => $student]);
    }

    /**
     * @Route("/students", name="students", methods={"GET"})
     */
    public function students(): Response
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->json(["students" => $students]);
    }

    /**
     * @Route("/students", name="addStudent", methods={"PUT"})
     * @param Request $request
     * @return Response
     */
    public function add_student(Request $request): Response
    {
        // Get request data
        $data = json_decode($request->getContent());
        $firstName = $data->firstName;
        $lastName = $data->lastName;
        $email = $data->email;
        $address = $data->address;

        // Create new student
        $student = new Student();
        $student->setFirstName($firstName);
        $student->setLastName($lastName);
        $student->setEmail($email);
        $student->setAddress($address);

        // Save the new teacher
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($student);
        $entityManager->flush();

        return $this->json(["message" => "Successfully created a new student"]);
    }

    /**
     * @Route("/students/{id}", name="updateStudent", requirements={"id"="\d+"}, methods={"POST"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function update_student($id, Request $request): Response
    {
        // Get request data
        $data = json_decode($request->getContent());
        $firstName = $data->firstName;
        $lastName = $data->lastName;
        $email = $data->email;
        $address = $data->address;

        $entityManager = $this->getDoctrine()->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        if (!$student) {
            return $this->json(["message" => "Student with ID: " . $id . " doesnt exist"]);
        }

        // Update student and save
        $student->setFirstName($firstName);
        $student->setLastName($lastName);
        $student->setEmail($email);
        $student->setAddress($address);
        $entityManager->flush();

        return $this->json(["message" => "Student with ID: " . $id . " saved successfully"]);
    }

    /**
     * @Route("/students/{id}", name="deleteStudent", requirements={"id"="\d+"}, methods={"DELETE"})
     * @param $id
     * @return Response
     */
    public function delete_student($id): Response
    {
        // Get requested student by id
        $entityManager = $this->getDoctrine()->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);

        // If not found inform the client that the student doesnt exist
        if (!$student) {
            return $this->json(["message" => "Student with ID: " . $id . " doesnt exist"]);
        }

        // Delete him and save
        $entityManager->remove($student);
        $entityManager->flush();

        return $this->json(["message" => "Deleted student with ID: " . $id . " successfully"]);
    }

    /**
     * @Route("/link-teacher/{id}", name="linkTeacher", requirements={"id"="\d+"}, methods={"POST"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function add_teacher_to_student($id, Request $request): Response
    {
        // Get request data
        $data = json_decode($request->getContent());
        $studentId = $data->studentId;

        $entityManager = $this->getDoctrine()->getManager();
        $teacher = $entityManager->getRepository(Teacher::class)->find($id);
        $student = $entityManager->getRepository(Student::class)->find($studentId);

        if (!$teacher) {
            return $this->json(["message" => "Teacher with ID: " . $id . " doesnt exist"]);
        }

        if (!$student) {
            return $this->json(["message" => "Student with ID: " . $studentId . " doesnt exist"]);
        }

        // Link student to teacher and save
        $student->setTeacher($teacher);
        $entityManager->flush();

        return $this->json(["message" => "Success"]);
    }
}
