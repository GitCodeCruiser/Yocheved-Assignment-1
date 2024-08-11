<!DOCTYPE html>
<html>
<head>
    <title>Reminder for Session</title>
</head>
<body>
    <p>Hi {{ $admin->name }},</p>
    <p>You have a session aligned at {{ $session->start_time }}</p>
    <p>Thank you!</p>
</body>
</html>
