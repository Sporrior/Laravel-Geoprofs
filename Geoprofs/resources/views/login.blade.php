<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | Geoprofs</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        img {
            width: 300px;
        }

        .container {
            background: #fff;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            text-align: center;
        }

        .logo {
            margin-bottom: 20px;
        }

        .input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .input:focus {
            border-color: #FDA085;
            outline: none;
            box-shadow: 0 0 5px rgba(253, 160, 133, 0.5);
        }

        .btn {
            width: 100%;
            background: #ff8c00;
            color: #fff;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #FDA085;
        }

        .forgot-password {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #888;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #F76C6C;
        }

        .stay-signed-in {
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #555;
        }

        .stay-signed-in input {
            margin-right: 10px;
        }

        footer {
            margin-top: 20px;
            font-size: 14px;
            color: #aaa;
        }

        footer a {
            color: #F76C6C;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #FFEBEE;
            color: #C62828;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('assets/geoprofs-oranje.png') }}" alt="Geoprofs Logo">
        </div>
        <!-- @if (session('error_message'))
            <div class="error-message">
                {{ session('error_message') }}
            </div>
        @endif -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <input type="email" name="email" class="input" placeholder="Email" value="{{ old('email') }}" required>
            <input type="password" name="password" class="input" placeholder="Password" required>
            <button type="submit" class="btn">Sign In</button>
            <a href="#" class="forgot-password">Forgot your password?</a>
            <div class="stay-signed-in">
                <input type="checkbox" id="staySignedIn" name="remember">
                <label for="staySignedIn">Stay signed in for a week</label>
            </div>
        </form>
        <footer>
            <p>&copy; Geoprofs | <a href="#">Contact</a> | <a href="#">Privacy & Terms</a></p>
        </footer>
    </div>
</body>

</html>