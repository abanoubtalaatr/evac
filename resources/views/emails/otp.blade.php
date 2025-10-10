<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #1a73e8;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset OTP</h2>
        <p>Please use the following One-Time Password (OTP) to reset your password:</p>
        <div class="otp-code">{{ $code }}</div>
        <p>This code is valid for a limited time. Do not share it with anyone.</p>
        <p>If you did not request this, please ignore this email.</p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Application Name. All rights reserved.</p>
        </div>
    </div>
</body>
</html>