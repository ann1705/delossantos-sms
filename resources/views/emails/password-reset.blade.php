<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: white;
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            color: #1A2236;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        .content {
            color: #333;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .code-box {
            background-color: #f9f9f9;
            border-left: 4px solid #FFD600;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .code-box p {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 14px;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #1A2236;
            text-align: center;
            letter-spacing: 5px;
            font-family: monospace;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #856404;
        }
        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .button {
            display: inline-block;
            background-color: #FFD600;
            color: #1A2236;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Request</h1>
            <p>UniFAST-TDP SMS Portal</p>
        </div>

        <div class="content">
            <p>Hello,</p>

            <p>We received a request to reset the password for your account (<strong>{{ $email }}</strong>). If you did not make this request, you can safely ignore this email.</p>

            <p>Your password reset code is:</p>

            <div class="code-box">
                <p>Enter this code on the password reset page:</p>
                <div class="code">{{ $resetCode }}</div>
            </div>

            <div class="warning">
                <strong>⚠️ Important:</strong> This code will expire in 60 minutes. Do not share this code with anyone.
            </div>

            <p><strong>Steps to reset your password:</strong></p>
            <ol>
                <li>Go to the password reset page on the UniFAST-TDP SMS portal</li>
                <li>Enter your email address</li>
                <li>Enter the code shown above</li>
                <li>Create a new password</li>
                <li>Confirm your new password</li>
                <li>Log in with your new password</li>
            </ol>

            <p>If you have any issues, please contact support.</p>

            <p>
                Best regards,<br>
                <strong>UniFAST-TDP SMS Team</strong>
            </p>
        </div>

        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; 2026 UniFAST-TDP SMS. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
