<?php

namespace App\Validators;

use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskValidator
{
    public static function validate(array $data)
    {
        return Validator::make($data, [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in([Task::STATUS_PENDING, Task::STATUS_IN_PROGRESS, Task::STATUS_COMPLETED])],
            'priority' => ['required', Rule::in([Task::PRIORITY_LOW, Task::PRIORITY_MEDIUM, Task::PRIORITY_HIGH])],
            'deadline' => ['nullable', 'date', 'after_or_equal:today'],
        ])->validate();
    }
}
