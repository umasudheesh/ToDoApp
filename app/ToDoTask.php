<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToDoTask extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'to_do_task';

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
        'user_id', 'title', 'body', 'due_date', 'reminders', 'status'
    ];

    protected function getTaskListing($user, $filter_params)
    {
        $query = ToDoTask::where("user_id", $user->id);
        if(isset($filter_params['complete_status']))
        {
                $query->where('status', $filter_params['complete_status']);
        }
        $result = $query->orderBy('due_date', 'desc')->get();

        return $result;
    }

    protected function getReminderTasks()
    {
        $result = ToDoTask::where('status', 0)
                            ->whereNotNull('due_date')
                            ->where('due_date', '>=', date('Y-m-d'))
                            ->get();
                            
        return $result;
    }
}
