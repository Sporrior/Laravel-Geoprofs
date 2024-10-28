<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ziekmelden</title>
    <link rel="stylesheet" href="{{ asset('css/ziekmelden.css') }}">

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .header-container {
            width: 100%;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            min-height: 100vh;
            padding-top: 60px;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            text-align: center;
            margin-top: 20px;
        }

        .container h1 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #444;
        }

        .info-text {
            font-size: 1em;
            color: #555;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .submit-button {
            width: 100%;
            padding: 12px;
            font-size: 1em;
            background-color: #FF8C00;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        .submit-button:hover {
            background-color: #FF8C00;
        }

        .submit-button:active {
            transform: scale(0.98);
        }

        .success-message {
            color: #4caf50;
            font-weight: bold;
            margin-bottom: 15px;
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header-container">
        @include('includes.header')
    </div>

    <div class="form-wrapper">
        <div class="container">
            <h1>Ziekmelden</h1>

            @if(session('success'))
                <p class="success-message">{{ session('success') }}</p>
            @endif

            <form action="{{ route('ziekmelden.store') }}" method="POST" class="sick-form">
                @csrf

                <input type="hidden" name="verlof_reden" value="ziek">

                <p class="info-text">U meldt zich ziek voor 1 dag.</p>

                <button type="submit" class="submit-button">Ziekmelden</button>
            </form>
        </div>
    </div>

</body>

</html>