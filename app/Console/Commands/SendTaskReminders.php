<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\ToDoTask;
use App\TaskReminders;
use App\User;
use Mail;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send task reminders to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $reminder_data = TaskReminders::getTaskReminderData();

            if(!is_null($reminder_data))
            {
                foreach($reminder_data as $r_values)
                {
                    $mail_subject = "To Do Task Reminder - ".date('Y-m-d');
                    $email_data = [
                                    'username' => $r_values->username,
                                    'task_title' => $r_values->task_title,
                                    'task_description' => $r_values->task_description,
                                    'due_date' => $r_values->due_date,
                                    'complete_status' => $r_values->complete_status
                                ];

                    $email_ids = ['uma.maha2@gmail.com', $r_values->useremail];
                    Mail::send('email.send_reminder', $email_data, function ($m) use($email_ids, $mail_subject) {
                        $m->from('uma.maha2@gmail.com', 'To-Do App');
                        $m->to($email_ids)->subject($mail_subject);
                    });

                    TaskReminders::where('id', $r_values->reminder_id)->update(['status' => 1]);
                }
            }

            
            
        }catch (\Exception $e) {
            Storage::append('reminders.log', 'send Reminder --- error_msg -- '.json_encode($e->getMessage()). ' line number: '. json_encode($e->getLine()) .' File: '.json_encode($e->getFile()).' - '. date('Y-m-d H:i:s') . PHP_EOL);
        }
    }
}
