<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\ToDoTask;
use App\TaskReminders;

class ReminderCronInsertion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:insert-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduler to insert Task reminders.';

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
            $reminder_tasks = ToDoTask::getReminderTasks();

            if(!is_null($reminder_tasks))
            {
                foreach($reminder_tasks as $r_values)
                {
                    $reminder_date = date('Y-m-d', strtotime($r_values->due_date . "- ".$r_values->reminders));

                    TaskReminders::create(['task_id' => $r_values->id,
                                            'reminder_datetime' => $reminder_date." 00:00:00",
                                            'type' => "email",
                                            'status' => 0]);
                }
            }

        }catch (\Exception $e) {
            Storage::append('reminders.log', 'data --- error_msg -- '.json_encode($e->getMessage()). ' line number: '. json_encode($e->getLine()) .' File: '.json_encode($e->getFile()).' - '. date('Y-m-d H:i:s') . PHP_EOL);
        }
    }
}
