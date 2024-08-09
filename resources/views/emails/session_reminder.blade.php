<!DOCTYPE html>
<html>
<head>
    <title>Session Reminder</title>
</head>
<body>
    <h1>Hello, {{ $session->user->name }}</h1>
    <p>This is a reminder that your session "{{ $session->name }}" will start in 5 minutes.</p>
    <p>Start Time: {{ Carbon\Carbon::parse($session->start_date_time)->format('Y-m-d H:i:s') }}</p>
</body>
</html>
