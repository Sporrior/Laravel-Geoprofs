<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Geoprofs Register</title>
    <style>
        body {
            background-color: #d98a62;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            text-align: center;
            color: white;
        }

        .register-container h1 {
            font-size: 48px;
            color: #1f5c63;
            font-weight: bold;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .register-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .register-form input {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #1f5c63;
            width: 300px;
            font-size: 16px;
        }

        .register-form button {
            padding: 15px;
            background-color: white;
            color: #d98a62;
            border: 1px solid white;
            border-radius: 4px;
            font-size: 16px;
            width: 320px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .register-form button:hover {
            background-color: #bf7b55;
        }

        .register-container a {
            text-decoration: none;
            color: white;
            margin-top: 15px;
        }

        /* Error message styling */
        .error-message {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h1>Register</h1>

        <form action="{{ route('register.submit') }}" method="POST" class="register-form">
            @csrf
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            <button type="submit">REGISTER</button>
        </form>

        <a href="{{ route('login') }}">Already have an account? Login here.</a>
    </div>
</body>

</html>