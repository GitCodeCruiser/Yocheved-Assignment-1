<?php
namespace App\Http\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionService{
    public function addSession($request){

        $startDateTime = Carbon::parse($request->start_date_time)->format('Y-m-d H:i:s');
        $endDateTime = Carbon::parse($request->end_date_time)->format('Y-m-d H:i:s');

        if ($startDateTime >= $endDateTime) {
            throw new Exception('Start date time should be less than end date time', Response::HTTP_OK);
        }

        $overlappingSessions = Session::where(function($query) use ($startDateTime, $endDateTime) {
            $query->whereBetween('start_date_time', [$startDateTime, $endDateTime])
                  ->orWhereBetween('end_date_time', [$startDateTime, $endDateTime])
                  ->orWhere(function($query) use ($startDateTime, $endDateTime) {
                      $query->where('start_date_time', '<=', $startDateTime)
                            ->where('end_date_time', '>=', $endDateTime);
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

    public function getSession(){
        $sessions = Session::all();
        return $sessions;
    }
}