<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
    <style>
        /* body {
            font-family: Arial, sans-serif;
        } */
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Reset Your Password</h1>
    <p>Hello,</p>
    <p>You requested to reset your password. Click the button below to proceed:</p>
    <a href="{{ $url }}" class="button">Reset Password</a>
    <p>If you did not request this, please ignore this email.</p>
    <p>Thanks,<br> HBA Approved Team</p>
</body>
</html>
