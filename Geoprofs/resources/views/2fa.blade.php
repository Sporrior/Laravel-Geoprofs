<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFF;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .input-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .input-container input {
            width: 45px;
            height: 50px;
            font-size: 1.5em;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border 0.2s ease;
        }

        .input-container input:focus {
            border-color: #4caf50;
        }

        .submit-button {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            background-color: #ff8c00;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: #ff8c00;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Authenticate Your Account</h2>
        <p>Enter the 5-digit code displayed on your device</p>
        <div class="input-container">
            <input type="password" maxlength="1" id="digit-1" oninput="moveToNextInput(this, 'digit-2')">
            <input type="password" maxlength="1" id="digit-2" oninput="moveToNextInput(this, 'digit-3')">
            <input type="password" maxlength="1" id="digit-3" oninput="moveToNextInput(this, 'digit-4')">
            <input type="password" maxlength="1" id="digit-4" oninput="moveToNextInput(this, 'digit-5')">
            <input type="password" maxlength="1" id="digit-5" oninput="moveToNextInput(this)">
        </div>
        <button class="submit-button" onclick="verifyCode()">Submit</button>
    </div>

    <script>
        function moveToNextInput(currentInput, nextInputId) {
            if (currentInput.value.length === 1) {
                if (nextInputId) {
                    document.getElementById(nextInputId).focus();
                }
            }
        }

        function verifyCode() {
            const enteredCode = Array.from({ length: 5 }, (_, i) => document.getElementById(`digit-${i + 1}`).value).join('');
            fetch("{{ route('2fa.verify') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ '2fa_code': enteredCode })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "{{ route('dashboard') }}";
                    } else {
                        alert(data.message || "Incorrect code. Please try again.");
                    }
                })
                .catch(error => console.error("Error:", error));
        }
    </script>
</body>
</html>
