<?php
namespace App\Http\Services;

use Exception;
use App\Models\Session;
use App\Models\Student;
use Illuminate\Support\Carbon;
use App\Models\StudentAvailability;
use Symfony\Component\HttpFoundation\Response;

class StudentService{
    public function getStudents(){
        $students = Student::get();
        return $students;
    }

    public function addStudent($request){
        $student = Student::create([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth
        ]);

        return $student;
    }

    public function addStudentAvailability($request){
        $student = Student::where('id', $request->student_id)->exists();

        if(!$student){
            throw new Exception("Please enter a valid student id", Response::HTTP_OK);
        }
        StudentAvailability::where('student_id', $request->student_id)->delete();

        foreach($request->days as $day){
            StudentAvailability::create([
                'student_id' => $request->student_id,
                'day' => $day
            ]);
        }

        $studentAvailabilities = Student::where('id', $request->student_id)->with("studentAvailability")->first();
        return $studentAvailabilities;
    }

    public function getStudentAvailability($request){
        $student = Student::where('id', $request->student_id)->exists();
        if(!$student){
            throw new Exception("Please enter a valid student id", Response::HTTP_OK);
        }

        $studentAvailabilities = Student::where('id', $request->student_id)->with("studentAvailability")->first();
        return $studentAvailabilities;
    }

    public function getAvailableStudents($request){
        $session = Session::where('id', $request->session_id)->first();
        
        if(!$session){
            throw new Exception("Please enter a valid session id", Response::HTTP_OK);
        }

        $date = Carbon::parse('2024-08-09');
        $day = $date->dayOfWeek;

        $students = Student::withWhereHas('studentAvailability', function($query) use ($day){
            $query->where('day', $day);
        })->get();

        return $students;
    }
}