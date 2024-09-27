<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Geoprofs Login</title>
    <style>
        body {
            background-color: #d98a62; /* Background color similar to the image */
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            text-align: center;
            color: white;
        }

        .login-container h1 {
            font-size: 48px;
            color: #1f5c63; /* Matching the GEOPROFS text color */
            font-weight: bold;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-form input {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #1f5c63;
            width: 300px;
            font-size: 16px;
        }

        .login-form button {
            padding: 15px;
            background-color: #d98a62;
            color: white;
            border: 1px solid white;
            border-radius: 4px;
            font-size: 16px;
            width: 320px;
            cursor: pointer;
        }

        .login-form button:hover {
            background-color: #bf7b55;
        }

        .login-container a {
            text-decoration: none;
            color: white;
            margin-top: 15px;
        }

        .login-container .forgot-password {
            margin-top: 15px;
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            width: 250px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <!-- You can use an image for the logo if available -->
            <img src="../resources/assets/geoprofs.png" alt="Geoprofs Logo"> <!-- Logo placeholder -->
        </div>
        <!-- <h1>GEOPROFS</h1> -->
        <form action="/login" method="POST" class="login-form">
            @csrf
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">LOGIN</button>
        </form>
        <a href="#" class="forgot-password">Wachtwoord vergeten?</a> <!-- Forgot password link -->
    </div>
</body>
</html>