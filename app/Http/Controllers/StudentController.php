<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Services\StudentService;
use App\Http\Requests\AddStudentRequest;
use App\Http\Requests\AddScheduleRequest;
use App\Http\Requests\AddAvailabilityRequest;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    private $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    // Add a new student
    public function addStudent(AddStudentRequest $request)
    {
        try {
            $student = $this->studentService->addStudent($request);
            return $this->sendResponse("Student added successfully", Response::HTTP_OK, $student);
        } catch (Exception $exception) {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    // Get all students
    public function getStudents()
    {
        try {
            $students = $this->studentService->getStudents();
            return $this->sendResponse("Students fetched successfully", Response::HTTP_OK, $students);
        } catch (Exception $exception) {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    // Get a list of all students
    public function allStudents()
    {
        try {
            $students = $this->studentService->allStudents();
            return $this->sendResponse("Students fetched successfully", Response::HTTP_OK, $students);
        } catch (Exception $exception) {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }
    
    // Add student availability
    public function addStudentAvailability(AddAvailabilityRequest $request)
    {
        try {
            $studentAvailabilities = $this->studentService->addStudentAvailability($request);
            return $this->sendResponse("Student availabilities added successfully", Response::HTTP_OK, $studentAvailabilities);
        } catch (Exception $exception) {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    // Get student availability
    public function getStudentAvailability(Request $request)
    {
        try {
            $studentAvailabilities = $this->studentService->getStudentAvailability($request);
            return $this->sendResponse("Student availabilities fetched successfully", Response::HTTP_OK, $studentAvailabilities);
        } catch (Exception $exception) {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    // Get students who are available at a specific time
    public function getAvailableStudents(Request $request)
    {
        try {
            $students = $this->studentService->getAvailableStudents($request);
            return $this->sendResponse("Available students fetched successfully", Response::HTTP_OK, $students);
        } catch (Exception $exception) {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }
}
