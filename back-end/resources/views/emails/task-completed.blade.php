<!DOCTYPE html>
<html>
<head>
    <title>Task Completed</title>
</head>
<body>
    <h2>Task Completed</h2>
    <p>The following task has been marked as completed:</p>
    <p><strong>Title:</strong> {{ $task->title }}</p>
    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Completed at:</strong> {{ $task->updated_at->format('Y-m-d H:i:s') }}</p>
</body>
</html>
