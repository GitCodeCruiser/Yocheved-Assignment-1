<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Session;
use App\Mail\SessionReminder;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyUsersBeforeSession extends Command
{
    protected $signature = 'notify:users-before-session';
    protected $description = 'Notify users 5 minutes before their session start time';

    public function __construct()
    {
        parent::__construct();
    }

    // Handle the command execution
    public function handle()
    {
        // Get the admin user and their email
        $adminUser = User::first();
        $users[] = $adminUser->email;

        // Calculate the event start time 5 minutes from now
        $eventStartTime = Carbon::now()->addMinute(5)->format("H:i:s");

        // Fetch sessions that need to be notified
        $sessions = Session::where(function($query) use ($eventStartTime) {
            $query->where(function($query) use ($eventStartTime) {
                $query->where('is_daily', false)->where('start_time', '<=', $eventStartTime);
            })
            ->orWhere(function($query) use ($eventStartTime) {
                $query->where('is_daily', true)->where('start_time', '<=', $eventStartTime);
            });
        })->whereNull('notified_at')->with(['students'])->get();

        // Loop through each session to send notifications
        foreach ($sessions as $session) {
            
            // Update the session to mark it as notified
            $session->update([
                'notified_at' => now(),
            ]);

            // Collect emails of all students in the session
            foreach ($session->students as $student) {
                $users[] = $student?->email;
            }

            // Send email notification to each user
            foreach ($users as $user) {
                Mail::to($user)->queue(new SessionReminder($adminUser, $session));
            }
        }

        // Output a success message to the console
        $this->info('Users notified successfully.');
    }
}
