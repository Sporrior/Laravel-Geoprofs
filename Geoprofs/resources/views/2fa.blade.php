<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>2FA Verification</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        .popup-2fa {
            display: block;
            position: fixed;
            top: 10px;
            right: 10px;
            width: 300px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            z-index: 1000;
        }

        .popup-2fa .timer {
            font-size: 1.5em;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
            <h1><img src="{{ asset('assets/geoprofs.png') }}" alt="Geoprofs logo"></h1>
        </div>
        <div class="formbg-outer">
            <div class="formbg">
                <div class="formbg-inner padding-horizontal--48">
                    <span class="padding-bottom--15">Enter the 2FA code sent to your device</span>
                    <form action="{{ route('2fa.verify') }}" method="POST" class="2fa-form">
                        @csrf

                        @if ($errors->any())
                            <div class="error-message">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="field padding-bottom--24">
                            <label for="2fa_code">2FA Code</label>
                            <input type="text" name="2fa_code" required>
                        </div>

                        <div class="field padding-bottom--24">
                            <input type="submit" name="submit" value="Verify Code">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="popup-2fa" class="popup-2fa">
        <div>2FA Code: <span id="code">{{ session('2fa_code') }}</span></div>
        <div>Enter the code within <span class="timer" id="timer">10</span> seconds</div>
    </div>

    <script>
        let countdown = 10;
        let timer;

        function startCountdown() {
            timer = setInterval(() => {
                countdown--;
                document.getElementById('timer').innerText = countdown;
                if (countdown === 0) {
                    regenerateCode(); 
                    countdown = 10; 
                }
            }, 1000);
        }

        function regenerateCode() {
            fetch("{{ route('2fa.regenerate') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('code').innerText = data.new_code;
            })
            .catch(error => console.error('Error:', error));
        }

        startCountdown();
    </script>
</body>

</html>
