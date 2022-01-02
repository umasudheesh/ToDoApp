<html>
    <body>
        Hello {{$username}},

        <p>This is the reminder about the up coming to-do task for completion</p>
        <p>Task Details are as follows : </p>
        <p>{{$task_title}}</p>
        <p>{{$task_description}}</p>
        <p>Due Date : <strong>{{$due_date}}</strong></p>
        <p>Status : <strong>@if($complete_status == 0) in-complete @else complete @endif</strong></p>
    </body>
</html>