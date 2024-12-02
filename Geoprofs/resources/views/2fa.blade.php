<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            /* background: linear-gradient(135deg, #6C63FF, #c5c5ff); */
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .input {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: 0.3s ease;
            background-color: #f9f9f9;
        }

        .input:focus {
            border-color: #6C63FF;
            box-shadow: 0 0 8px rgba(108, 99, 255, 0.3);
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            color: #fff;
            /* background: linear-gradient(135deg, #6C63FF, #5848FF); */
            background-color: #ff8c00;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #FDA085;
            transform: translateY(-2px);
        }

        .btn:active {
            transform: scale(0.98);
        }

        .error-message {
            background: rgba(255, 0, 0, 0.1);
            color: #ff4d4d;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        footer {
            margin-top: 20px;
            font-size: 14px;
            color: #999;
        }

        footer a {
            /* color: #6C63FF; */
            color: #F76C6C;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Two-Factor Authentication</h2>
        <p>Please enter the 6-digit code sent to your email or mobile device.</p>

        @if($errors->any())
            <div class="error-message">
                {{ $errors->first('2fa_code') }}
            </div>
        @endif

        <form action="{{ route('2fa.verify') }}" method="POST">
            @csrf
            <input type="text" name="2fa_code" class="input" maxlength="6" placeholder="Enter the 6-digit code" required>
            <button type="submit" class="btn">Verify</button>
        </form>

        <footer>
            <p>&copy; 2024 Geoprofs | <a href="#">Privacy</a> | <a href="#">Contact</a></p>
        </footer>
    </div>
</body>

</html>