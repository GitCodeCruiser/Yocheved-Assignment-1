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

        $currentTime = Carbon::now();
        $notificationTime = $currentTime->copy()->addMinutes(5);

        $sessions = Session::where(function($query) use ($notificationTime) {
            $query->where(function($query) use ($notificationTime) {
                $query->where('is_daily', false)
                ->whereBetween('start_date_time', [
                    $notificationTime->format('Y-m-d H:i:00'),
                    $notificationTime->format('Y-m-d H:i:59')
                ]);
            })
            ->orWhere(function($query) use ($notificationTime) {
                $query->where('is_daily', true)
                      ->whereTime('start_date_time', $notificationTime->format('H:i:00'));
            });
        })->with(['students'])->get();
        foreach ($sessions as $session) {
            foreach($session->students as $student){
                $users[] = $student?->email;
            }

            foreach ($users as $key => $user) {
                Mail::to($user)->queue(new SessionReminder($session));
            }
        }

        $this->info('Users notified successfully.');
    }
}
