<?php
namespace App\Http\Services;

use Exception;
use App\Models\Session;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\StudentAvailability;
use Illuminate\Console\Scheduling\Schedule;
use Symfony\Component\HttpFoundation\Response;

class StudentService{
    public function getStudents(){
        $students = Student::paginate(10);
        return $students;
    }

    public function allStudents(){
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
        $student = Student::where('slug', $request->student_id)->first();
        
        if(!$student){
            throw new Exception("Please enter a valid student id", Response::HTTP_OK);
        }
        StudentAvailability::where('student_id', $student->id)->delete();

        foreach($request->days as $day){
            StudentAvailability::create([
                'student_id' => $student->id,
                'day' => $day
            ]);
        }

        $studentAvailabilities = Student::where('id', $student->id)->with("studentAvailability")->first();
        return $studentAvailabilities;
    }

    public function getStudentAvailability($request){
        $student = Student::where('slug', $request->student_id)->first();
        if(!$student){
            throw new Exception("Please enter a valid student id", Response::HTTP_OK);
        }

        $studentAvailabilities = Student::where('id', $student->id)->with("studentAvailability")->first();
        return $studentAvailabilities;
    }

    public function getAvailableStudents($request){
        $session = Session::where('id', $request->session_id)->first();

        if (!$session) {
            throw new Exception("Please enter a valid session id", Response::HTTP_OK);
        }

        $date = Carbon::parse('2024-08-09');
        $day = $date->dayOfWeek;

        $students = Student::whereHas('studentAvailability', function($query) use ($session) {
            if ($session->is_daily) {
                // Check if the student is available all 7 days
                $query->select('student_id')
                      ->whereIn('day', [0, 1, 2, 3, 4, 5, 6])
                      ->groupBy('student_id')
                      ->havingRaw('COUNT(DISTINCT day) = 7');
            } else {
                // Check if the student is available on the specific day
                $day = Carbon::now()->dayOfWeek; // Adjust according to your needs
                $query->where('day', $day);
            }
        })->get();
        
        return $students;
    }
}