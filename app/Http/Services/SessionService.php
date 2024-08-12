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
    // Add a new session
    public function addSession($request)
    {
        $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
        $startTime = Carbon::parse($request->start_time)->format('H:i:s');
        $endTime = Carbon::parse($request->end_time)->format('H:i:s');

        // Ensure start time is before end time
        if ($startTime >= $endTime) {
            throw new Exception('Start time should be less than end time', Response::HTTP_OK);
        }
        
        // Check for overlapping sessions
        $overlappingSessions = Session::where(function ($query) use ($startDate, $startTime, $endTime, $request) {
            $query->where(function ($subQuery) use ($startDate, $startTime, $endTime, $request) {
                if ($request->is_daily) {
                    $subQuery->where(function ($timeQuery) use ($startTime, $endTime, $startDate) {
                        $timeQuery->whereRaw('TIME(start_time) BETWEEN ? AND ?', [$startTime, $endTime])
                            ->orWhereRaw('TIME(end_time) BETWEEN ? AND ?', [$startTime, $endTime])
                            ->orWhereRaw('? BETWEEN TIME(start_time) AND TIME(end_time)', [$startTime])
                            ->orWhereRaw('? BETWEEN TIME(start_time) AND TIME(end_time)', [$endTime]);
                    })->where('start_date', '>=', $startDate); // Only consider future or current dates
                } else {
                    $subQuery->where('start_date', $startDate)
                        ->where(function ($subSubQuery) use ($startTime, $endTime) {
                            $subSubQuery->whereBetween('start_time', [$startTime, $endTime])
                                ->orWhereBetween('end_time', [$startTime, $endTime])
                                ->orWhere(function ($innerSubQuery) use ($startTime, $endTime) {
                                    $innerSubQuery->where('start_time', '<=', $startTime)
                                        ->where('end_time', '>=', $endTime);
                                });
                        });
                }
            });
        })->exists();

        // Throw exception if overlapping sessions are found
        if ($overlappingSessions) {
            throw new Exception("The new session overlaps with an existing session.", Response::HTTP_OK);
        }
        
        // Create a new session
        $session = Session::create([
            'start_date' => $startDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'target' => $request->target,
            'is_daily' => $request->is_daily
        ]);
        
        return $session;
    }

    // Get paginated list of sessions
    public function getSessions()
    {
        $sessions = Session::paginate(10);
        return $sessions;
    }

    // Get a specific session by ID
    public function getSession($request)
    {
        $session = Session::where('id', $request->session_id)->first();

        if ($session) {
            if ($session->is_daily) {
                $sessionWithRating = Session::with(['rating' => function ($query) {
                    $query->whereDate('created_at', now()->format('Y-m-d'));
                }])->find($session->id);
            } else {
                $sessionWithRating = Session::with('rating')->find($session->id);
            }

            return $sessionWithRating;
        } else {
            throw new Exception("No session found", Response::HTTP_OK);
        }
    }

    // Schedule a student for a session
    public function scheduleStudent($request)
    {
        $session = Session::where('id', $request->session_id)->first();
        
        if (!$session) {
            throw new Exception("Please enter a valid session id", Response::HTTP_OK);
        }

        $student = Student::where('id', $request->student_id)->first();
        
        if (!$student) {
            throw new Exception("Please enter a valid student id", Response::HTTP_OK);
        }

        // Check if the student is already scheduled for this session
        $existingSchedule = StudentSchedule::where('student_id', $student->id)
                                           ->where('session_id', $request->session_id)
                                           ->first();

        if ($existingSchedule) {
            throw new Exception("This student is already scheduled for this session", Response::HTTP_OK);
        }

        // Schedule the student for the session
        $studentSchedule = StudentSchedule::create([
            'student_id' => $request->student_id,
            'session_id' => $request->session_id
        ]);

        return $studentSchedule;
    }

    // Rate a session
    public function rateSession($request)
    {
        $session = Session::where('id', $request->session_id)->with(['students'])->first();
        
        if (!$session) {
            throw new Exception("Please enter a valid session id", Response::HTTP_OK);
        }

        // Rate the session for each student
        if (!$session->is_daily) {
            foreach ($session->students as $student) {
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
                    ]
                );
            }
        } else {
            foreach ($session->students as $student) {
                $rating = SessionRating::where('session_id', $session->id)
                        ->where('student_id', $student->id)
                        ->whereDate('created_at', now()->format('Y-m-d'))
                        ->first();

                if ($rating) {
                    $rating->update([
                        'obtained_rating' => $request->rating
                    ]);
                } else {
                    $rating = SessionRating::create([
                        'session_id' => $session->id,
                        'student_id' => $student->id,
                        'total_rating' => $session->target,
                        'obtained_rating' => $request->rating,
                        'created_at' => now()
                    ]);
                }
            }
        }

        return SessionRating::where('session_id', $session->id)->get();
    }

    // Add multiple sessions
    public function addMultipleSessions($request)
    {
        $sessions = $request->all();
        $addedSessions = [];

        foreach ($sessions as $session) {
            if (isset($session['fromDate']) && isset($session['toDate'])) {
                $addedSessions[] = Session::create([
                    'start_date' => Carbon::parse($session['fromDate']),
                    'start_time' => now()->format('H:i:s'),
                    'end_time' => now()->addMinute(15)->format('H:i:s'),
                    'target' => $session['target'],
                    'is_daily' => false,
                ]);
            }
        }

        return $addedSessions;
    }
}
