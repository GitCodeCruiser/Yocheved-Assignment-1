<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Services\StudentService;
use App\Http\Requests\AddStudentRequest;
use App\Http\Requests\AddAvailabilityRequest;
use Symfony\Component\HttpFoundation\Response;


class StudentController extends Controller
{
    private $studentService;
    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function addStudent(AddStudentRequest $request){
        try 
        {
            $student = $this->studentService->addStudent($request);
            return $this->sendResponse("Students fetched successfully", Response::HTTP_OK, $student);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    public function getStudents(){
        try 
        {
            $students = $this->studentService->getStudents();
            return $this->sendResponse("Students fetched successfully", Response::HTTP_OK, $students);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    public function addStudentAvailability(AddAvailabilityRequest $request){
        try 
        {
            $studentAvailabilities = $this->studentService->addStudentAvailability($request);
            return $this->sendResponse("Students availabilities added successfully", Response::HTTP_OK, $studentAvailabilities);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    public function getStudentAvailability(Request $request){
        try 
        {
            $studentAvailabilities = $this->studentService->getStudentAvailability($request);
            return $this->sendResponse("Students availabilities fetched successfully", Response::HTTP_OK, $studentAvailabilities);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }
}
