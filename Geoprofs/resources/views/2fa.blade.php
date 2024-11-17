<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .input {
            padding: 10px;
            font-size: 18px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Two-Factor Authentication</h2>
        <p>Please enter the 6-digit code provided to you.</p>

        @if($errors->any())
            <p style="color: red;">{{ $errors->first('2fa_code') }}</p>
        @endif

        <form action="{{ route('2fa.verify') }}" method="POST">
            @csrf
            <input type="text" name="2fa_code" class="input" maxlength="6" placeholder="Enter 6-digit code" required>
            <br>
            <button type="submit" class="btn">Verify</button>
        </form>
    </div>
</body>
</html>
