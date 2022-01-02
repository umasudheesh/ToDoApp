<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ToDoTask;
use App\User;

class ToDoTaskController extends Controller
{
    private $sucess_status = 200;
    private $user = '';

    public function __construct(Request $request)
    {
        $token = $request->header('Authorization');
        $this->user = User::where('api_token',$token)->first();
    }

    // --------------- [ Create Task ] ------------------
    public function createTask(Request $request)
    {
        $user = $this->user;

        if(!empty($user))
        {
            $data = $request->all();
            $rules = [
                "title" => "required|max:500|regex:/^[a-zA-Z0-9\s]+$/",
                "body" => "required|max:2000|regex:/^[a-zA-Z0-9\s]+$/",
                "complete_status" => "required|boolean"
            ];  

            $due_date = "";
            $reminders = "";

            $messages = [];
            if(array_key_exists("due_date",$data))
            {
                $due_date = $data['due_date'];
                $rules['due_date'] = 'required|date_format:Y-m-d';
            }

            if(array_key_exists("reminders",$data))
            {
                $rules['due_date'] = 'required|date_format:Y-m-d';
                $rules['reminders'] = 'required';
                $reminders = $data['reminders'];
            }

            $validator = Validator::make($data, $rules);

            if($validator->fails()) {
                return response()->json(["validation_errors" => $validator->errors()]);
            }

            $date = date('Y-m-d H:i:s');
            $reminder_year = !empty($data['reminders']) ? date("Y", strtotime($date . "+ ".$data['reminders'])) : '';

            if($reminder_year == 1970)
            {
                return response()->json(["status" => "failed", "success" => false, 
                    "message" => "Please provide proper reminder format. Allowed formats as follows - day(s), week(s), month(s), year(s). Eg: 2 days, 1 week, 1 month, 3 years etc.",
                    ]);
            }

            $task_array = array(
                "user_id" => $user->id,
                "title" => $request->title,
                "body" => $request->body,
                "status" => $request->complete_status,
                "due_date" => !empty($due_date) ? $due_date : null,
                "reminders" => $reminders
            );

            $task = ToDoTask::create($task_array);

            if(!is_null($task)) {
                return response()->json(["status" => $this->sucess_status, "success" => true, "data" => $task]);
            }
    
            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! task not created."]);
            }
        }
        else
        {
            return response()->json([
                'message' => 'User not found',
                ]);
        }
    }

    // --------------- [ Update Task ] ------------------
    public function updateTask(Request $request, $task_id)
    {
        $user = $this->user;

        if(!empty($user))
        {
            if($task_id == 'undefined' || $task_id == "") {
                return response()->json(["status" => "failed", "success" => false, "message" => "Alert! enter the task id"]);
            }

            $task = ToDoTask::where('user_id', $user->id)->where('id', $task_id)->first();

            if(!is_null($task))
            {
                $data = $request->all();
                $rules = [
                    "title" => "required|max:500|regex:/^[a-zA-Z0-9\s]+$/",
                    "body" => "required|max:2000|regex:/^[a-zA-Z0-9\s]+$/",
                    "complete_status" => "required|boolean"
                ];  
    
                $messages = [];
                if(array_key_exists("due_date",$data))
                {
                    $rules['due_date'] = 'required|date_format:Y-m-d';
                }
    
                if(array_key_exists("reminders",$data))
                {
                    $rules['due_date'] = 'required|date_format:Y-m-d';
                    $rules['reminders'] = 'required';
                }
    
                $validator = Validator::make($data, $rules);
    
                if($validator->fails()) {
                    return response()->json(["validation_errors" => $validator->errors()]);
                }
    
                $date = date('Y-m-d H:i:s');
                $reminder_year = !empty($data['reminders']) ? date("Y", strtotime($date . "+ ".$data['reminders'])) : '';
    
                if($reminder_year == 1970)
                {
                    return response()->json(["status" => "failed", "success" => false, 
                    "message" => "Please provide proper reminder format. Allowed formats as follows - day(s), week(s), month(s), year(s). Eg: 2 days, 1 week, 1 month, 3 years etc.",
                    ]);
                }
    
                $task_array = array(
                    "user_id" => $user->id,
                    "title" => !empty($request->title) ? $request->title : $task->title,
                    "body" => !empty($request->body) ? $request->body : $task->body,
                    "status" => !empty($request->complete_status) ? $request->complete_status : $task->status,
                    "due_date" => !empty($data['due_date']) ? $data['due_date'] : $task->due_date,
                    "reminders" => !empty($data['reminders']) ? $data['reminders'] : $task->reminders
                );
    
                $task_status = ToDoTask::where("id", $task_id)->update($task_array);
    
                if($task_status == 1) {
                    return response()->json(["status" => $this->sucess_status, "success" => true, "message" => "Todo updated successfully", "data" => $task_array]);
                }
    
                else {
                    return response()->json(["status" => $this->sucess_status, "success" => true, "message" => "Todo not updated"]);
                }
            }
            else
            {
                return response()->json(["status" => "failed", "success" => false, "message" => "Alert! todo not found"]);
            }
        }
        else
        {
            return response()->json(["status" => "failed", "success" => false,
                'message' => 'User not found',
                ]);
        }
    }

    // ---------------- [ Task Listing ] -----------------
    public function listTasks(Request $request)
    {
        $user = $this->user;
        if(!empty($user))
        {
            $tasks = ToDoTask::getTaskListing($user, $request->all());
            if(count($tasks) > 0) {
                return response()->json(["status" => $this->sucess_status, "success" => true, "count" => count($tasks), "data" => $tasks]);
            }
    
            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no todo found"]);
            }
        }
        else
        {
            return response()->json(["status" => "failed", "success" => false,
                'message' => 'User not found',
                ]);
        }
    }

    // ----------------- [ Delete Task ] -------------------
    public function deleteTask($task_id)
    {
        $user = $this->user;

        if(!empty($user))
        {
            if($task_id == 'undefined' || $task_id == "") {
                return response()->json(["status" => "failed", "success" => false, "message" => "Alert! enter the task id"]);
            }
    
            $task = ToDoTask::where('user_id', $user->id)->where('id', $task_id)->first();
    
            if(!is_null($task)) {
    
                $delete_status = ToDoTask::where("id", $task_id)->delete();
    
                if($delete_status == 1) {
    
                    return response()->json(["status" => $this->sucess_status, "success" => true, "message" => "Success! todo deleted"]);
                }
    
                else {
                    return response()->json(["status" => "failed", "success" => false, "message" => "Alert! todo not deleted"]);
                }
            }
    
            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Alert! todo not found"]);
            }
        }
        else
        {
            return response()->json(["status" => "failed", "success" => false,
                'message' => 'User not found',
                ]);
        }
    }
}
