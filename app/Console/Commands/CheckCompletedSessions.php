<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Session;
use App\Mail\RateStudent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckCompletedSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-completed-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this will update the session status and send email to admin to rate the session';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $adminUser = User::first();
        
        $sessions = Session::where('start_date', $now->format('Y-m-d'))
        ->where('end_time', '<', $now->format('H:i:s'))
        ->where('status', 0)
        ->with(['students'])
        ->get();

        foreach ($sessions as $session) {
            $session->status = 1;
            $session->save();

            foreach ($session->students as $student) {
                Mail::to($adminUser->email)->queue(new RateStudent($student, $adminUser));
            }

        }

        $this->info('Completed sessions checked and notifications sent.');
    }
}
