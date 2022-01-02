<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskReminders extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_reminders';

    /**
      * Primary Key.
      *
      * @var string
      */
    public $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'task_id', 'reminder_datetime', 'type', 'status', 'manual_status'
    ];

    protected function getTaskReminderData()
    {
        $result = TaskReminders::select('users.name as username', 'users.email as useremail', 'to_do_task.title as task_title', 'to_do_task.body as task_description', 'to_do_task.due_date', 'to_do_task.status as complete_status', 'task_reminders.id as reminder_id','task_reminders.reminder_datetime', 'task_reminders.type as medium_type')
                    ->join('to_do_task','to_do_task.id', '=', 'task_reminders.task_id')
                    ->join('users', 'users.id', '=', 'to_do_task.user_id')
                    ->where('task_reminders.status', 0)
                    ->where('reminder_datetime', '=', date('Y-m-d'))
                    ->get();

        return $result;
    }
}
