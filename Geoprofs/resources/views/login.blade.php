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
            <h1><img src="{{ asset('assets/geoprofs-blauw.png') }}" alt="Geoprofs logo"></h1>
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

                        <div class="field padding-bottom--24">
                            <label for="email">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="field padding-bottom--24">
                            <div class="grid--50-50">
                                <label for="password">Password</label>
                                <div class="reset-pass">
                                    <a href="#">Forgot your password?</a>
                                </div>
                            </div>
                            <input type="password" name="password" required>
                        </div>

                        <div class="field field-checkbox padding-bottom--24 flex-flex align-center">
                            <label for="checkbox">
                                <input type="checkbox" name="remember"> Stay signed in for a week
                            </label>
                        </div>

                        <div class="field padding-bottom--24">
                            <input type="submit" name="submit" value="Continue">
                        </div>
                    </form>

                    @if ($errors->any())
                        <div class="error-message">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
            <div class="footer-link padding-top--24">
                <div class="listing padding-top--24 padding-bottom--24 flex-flex center-center">
                    <span><a href="#">© Geoprofs</a></span>
                    <span><a href="#">Contact</a></span>
                    <span><a href="#">Privacy & terms</a></span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>