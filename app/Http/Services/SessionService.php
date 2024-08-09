<?php
namespace App\Http\Services;

use Carbon\Carbon;
use App\Models\Session;

class SessionService{
    public function addSession($request){
        $session = Session::create([
            'start_date_time' => Carbon::parse($request->start_date_time)->format('Y-m-d H:i:s'),
            'end_date_time' => Carbon::parse($request->start_date_time)->format('Y-m-d H:i:s'),
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