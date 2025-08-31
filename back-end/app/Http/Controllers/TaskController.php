<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Mail\NewTaskNotification;
use App\Mail\TaskCompletedNotification;
use App\Validators\TaskValidator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('tenant_id', tenant('id'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($tasks);
    }

    public function store()
    {
        $data = TaskValidator::validate(request()->all());
        $data['tenant_id'] = tenant('id');

        $task = Task::create($data);

        // Send email notification
        Mail::to(auth()->user()->email)->queue(new NewTaskNotification($task));

        return response()->json($task, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $task = Task::where('tenant_id', tenant('id'))->findOrFail($id);
        return response()->json($task);
    }

    public function update($id)
    {
        $task = Task::where('tenant_id', tenant('id'))->findOrFail($id);
        $data = TaskValidator::validate(request()->all());

        $task->update($data);

        return response()->json($task);
    }

    public function complete($id)
    {
        $task = Task::where('tenant_id', tenant('id'))->findOrFail($id);
        $task->update(['status' => 'completed']);

        // Send email notification
        Mail::to(auth()->user()->email)->queue(new TaskCompletedNotification($task));

        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::where('tenant_id', tenant('id'))->findOrFail($id);
        $task->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
