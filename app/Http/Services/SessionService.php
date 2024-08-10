<?php

namespace App\Http\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Session;
use App\Models\Student;
use App\Models\SessionRating;
use App\Models\StudentSchedule;
use Symfony\Component\HttpFoundation\Response;

class SessionService
{
    public function addSession($request)
    {

        $startDateTime = Carbon::parse($request->start_date_time)->format('Y-m-d H:i:s');
        $endDateTime = Carbon::parse($request->end_date_time)->format('Y-m-d H:i:s');
        $startTime = Carbon::parse($startDateTime)->format('H:i:s');
        $endTime = Carbon::parse($endDateTime)->format('H:i:s');

        if ($startDateTime >= $endDateTime) {
            throw new Exception('Start date time should be less than end date time', Response::HTTP_OK);
        }

        $overlappingSessions = Session::where(function ($query) use ($startDateTime, $endDateTime, $startTime, $endTime, $request) {
            $query->where(function ($subQuery) use ($startDateTime, $endDateTime, $startTime, $endTime, $request) {
                if ($request->is_daily) {
                    $subQuery->whereRaw('TIME(start_date_time) BETWEEN ? AND ?', [$startTime, $endTime])
                        ->orWhereRaw('TIME(end_date_time) BETWEEN ? AND ?', [$startTime, $endTime])
                        ->orWhereRaw('? BETWEEN TIME(start_date_time) AND TIME(end_date_time)', [$startTime])
                        ->orWhereRaw('? BETWEEN TIME(start_date_time) AND TIME(end_date_time)', [$endTime]);
                } else {
                    $subQuery->whereBetween('start_date_time', [$startDateTime, $endDateTime])
                        ->orWhereBetween('end_date_time', [$startDateTime, $endDateTime])
                        ->orWhere(function ($subSubQuery) use ($startDateTime, $endDateTime) {
                            $subSubQuery->where('start_date_time', '<=', $startDateTime)
                                ->where('end_date_time', '>=', $endDateTime);
                        });
                }
            });
        })->exists();


        if ($overlappingSessions) {
            throw new Exception("The new session overlaps with an existing session.", Response::HTTP_OK);
        }

        $session = Session::create([
            'start_date_time' => Carbon::parse($request->start_date_time)->format('Y-m-d H:i:s'),
            'end_date_time' => Carbon::parse($request->end_date_time)->format('Y-m-d H:i:s'),
            'target' => $request->target,
            'is_daily' => $request->is_daily
        ]);

        return $session;
    }

    public function getSessions()
    {
        $sessions = Session::paginate(10);
        return $sessions;
    }

    public function getSession($request)
    {
        $session = Session::with('rating')->where('id', $request->session_id)->first();
        return $session;
    }

    public function scheduleStudent($request){
        $session = Session::where('id', $request->session_id)->first();
        
        if(!$session){
            throw new Exception("Please enter a valid session id", Response::HTTP_OK);
        }

        $student = Student::where('id', $request->student_id)->first();
        
        if(!$student){
            throw new Exception("Please enter a valid student id", Response::HTTP_OK);
        }

        $existingSchedule = StudentSchedule::where('student_id', $request->student_id)
                                   ->where('session_id', $request->session_id)
                                   ->first();

        if ($existingSchedule) {
            throw new Exception("This student is already scheduled for this session", Response::HTTP_OK);
        }

        $studentSchedule = StudentSchedule::create([
            'student_id' => $request->student_id,
            'session_id' => $request->session_id
        ]);

        return $studentSchedule;
    }

    public function rateSession($request){
        $session = Session::where('id', $request->session_id)->with(['students'])->first();
        
        if(!$session){
            throw new Exception("Please enter a valid session id", Response::HTTP_OK);
        }

        foreach($session->students as $student){
            SessionRating::updateOrCreate(
                [
                    'session_id' => $session->id,
                    'student_id' => $student->id,
                ],
                [
                'session_id' => $session->id,
                'student_id' => $student->id,
                'total_rating' => $session->target,
                'obtained_rating' => $request->rating
            ]);
        }

        return SessionRating::where('session_id', $session->id)->get();
    }

    public function addMultipleSessions($request){
        $sessions = $request->all();
        $addedSessions = [];

        foreach($sessions as $session){
            $addedSessions[] = Session::create([
                'start_date_time' => Carbon::parse($session['fromDate'])->format('Y-m-d H:i:s'),
                'end_date_time' => Carbon::parse($session['toDate'])->format('Y-m-d H:i:s'),
                'target' => $session['target'],
                'is_daily' => false,
            ]);
        }

        return $addedSessions;
    }
}
