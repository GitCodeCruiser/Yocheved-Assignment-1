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

    public function handle()
    {
        $adminUser = User::first();
        $users[] = $adminUser->email;

        $eventStartTime = Carbon::now()->addMinute(5)->format("H:i:s");

        $sessions = Session::where(function($query) use ($eventStartTime) {
            $query->where(function($query) use ($eventStartTime) {
                $query->where('is_daily', false)->where('start_time', '<=', $eventStartTime);
            })
            ->orWhere(function($query) use ($eventStartTime) {
                $query->where('is_daily', true)->where('start_time', '<=', $eventStartTime);
            });
        })->whereNull('notified_at')->with(['students'])->get();

        foreach ($sessions as $session) {
            
            $session->update([
                'notified_at' => now(),
            ]);

            foreach ($session->students as $student) {
                $users[] = $student?->email;
            }

            foreach ($users as $user) {
                Mail::to($user)->queue(new SessionReminder($adminUser, $session));
            }
        }

        $this->info('Users notified successfully.');
    }
}
