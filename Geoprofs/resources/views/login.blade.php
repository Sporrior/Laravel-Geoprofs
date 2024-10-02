<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Geoprofs Login</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-container">
        <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
            <h1><img src="{{ asset('assets/geoprofs.png') }}" alt="Geoprofs logo"></h1>
        </div>
        <div class="formbg-outer">
            <div class="formbg">
                <div class="formbg-inner padding-horizontal--48">
                    <span class="padding-bottom--15">Sign in to your account</span>

                    @if (session('error_message'))
                        <div class="error-message">
                            <p>{{ session('error_message') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('login.submit') }}" method="POST" class="login-form">
                        @csrf

        <form action="{{ route('login.submit') }}" method="POST" class="login-form">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">LOGIN</button>
        </form>
        <a href="#" class="forgot-password">Wachtwoord vergeten?</a>
    </div>
</body>

</html>