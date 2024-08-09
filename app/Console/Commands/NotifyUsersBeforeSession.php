<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Session;
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

        $startDateTime = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');

        $sessions = Session::where('start_date_time', '>=', $startDateTime)->where('end_date_time', '<=', now())->get();

        foreach ($sessions as $session) {
            $users[] = $session?->student?->email;
            
            foreach ($users as $key => $user) {
                Mail::send('emails.session_reminder', ['session' => $session], function ($message) use ($user) {
                    $message->to($user->email)->subject('Reminder: Your session starts in 5 minutes');
                });
            }
        }

        $this->info('Users notified successfully.');
    }
}
