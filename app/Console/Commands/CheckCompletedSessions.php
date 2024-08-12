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
    protected $description = 'This will update the session status and send an email to the admin to rate the session';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $adminUser = User::first();
        
        // Retrieve sessions that need to be updated and notified
        $sessions = Session::where('start_date', $now->format('Y-m-d'))
            ->where('end_time', '<', $now->format('H:i:s'))
            ->where('status', 0)
            ->with(['students'])
            ->get();

        // Update session status and send email notifications
        foreach ($sessions as $session) {
            $session->status = 1;
            $session->save();

            foreach ($session->students as $student) {
                Mail::to($adminUser->email)->queue(new RateStudent($student, $adminUser));
            }
        }

        // Output a success message to the console
        $this->info('Completed sessions checked and notifications sent.');
    }
}
