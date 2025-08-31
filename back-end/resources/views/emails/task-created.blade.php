<!DOCTYPE html>
<html>
<head>
    <title>New Task Created</title>
</head>
<body>
    <h2>New Task Created</h2>
    <p>A new task has been created in your system:</p>
    <p><strong>Title:</strong> {{ $task->title }}</p>
    <p><strong>Description:</strong> {{ $task->description }}</p>
</body>
</html>
