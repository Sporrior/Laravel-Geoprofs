<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
            background-color: #45a049;
        }

        .popup-2fa {
            position: fixed;
            top: 20px;
            right: -350px;
            width: 300px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            z-index: 1000;
            transition: right 0.5s ease-in-out, opacity 0.5s ease-in-out;
            opacity: 0;
            border-radius: 10px;
            text-align: center;
        }

        .popup-2fa.show {
            right: 10px;
            opacity: 1;
        }

        .timer-container {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 10px auto;
        }

        .timer-circle {
            transform: rotate(-90deg);
        }

        .timer-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Authenticate Your Account</h2>
        <p>Enter the 5-digit code sent to your device</p>
        <div class="input-container">
            <input type="password" maxlength="1" id="digit-1" oninput="moveToNextInput(this, 'digit-2')">
            <input type="password" maxlength="1" id="digit-2" oninput="moveToNextInput(this, 'digit-3')">
            <input type="password" maxlength="1" id="digit-3" oninput="moveToNextInput(this, 'digit-4')">
            <input type="password" maxlength="1" id="digit-4" oninput="moveToNextInput(this, 'digit-5')">
            <input type="password" maxlength="1" id="digit-5" oninput="moveToNextInput(this)">
        </div>
        <button class="submit-button" onclick="verifyCode()">Submit</button>
    </div>

    <div id="popup-2fa" class="popup-2fa">
        <div>2FA Code: <span id="code">{{ session('2fa_code') }}</span></div>
        <div class="timer-container">
            <svg class="timer-circle" width="80" height="80">
                <circle cx="40" cy="40" r="35" stroke="#ddd" stroke-width="8" fill="none" />
                <circle id="progress-circle" cx="40" cy="40" r="35" stroke="#4caf50" stroke-width="8" fill="none"
                    stroke-dasharray="219.91" stroke-dashoffset="0"
                    style="transition: stroke-dashoffset 1s linear, stroke 1s ease;" />
            </svg>
            <div class="timer-text" id="timer">10</div>
        </div>
    </div>

    <script>
        let countdown = 10;
        let timer;
        const totalTime = 10;
        const circle = document.getElementById("progress-circle");
        const timerText = document.getElementById("timer");

        function startCountdown() {
            const popup = document.getElementById("popup-2fa");
            popup.classList.add("show");

            timer = setInterval(() => {
                countdown--;
                timerText.innerText = countdown;

                const offset = (219.91 * countdown) / totalTime;
                circle.style.strokeDashoffset = 219.91 - offset;

                if (countdown > 5) {
                    circle.style.stroke = "#4caf50";
                } else if (countdown > 2) {
                    circle.style.stroke = "#ff9800";
                } else {
                    circle.style.stroke = "#f44336";
                }

                if (countdown === 0) {
                    regenerateCode();
                    countdown = totalTime;
                }
            }, 1000);
        }

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

        function regenerateCode() {
            fetch("{{ route('2fa.regenerate') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("code").innerText = data.new_code;
                });
        }

        startCountdown();
    </script>
</body>

</html>