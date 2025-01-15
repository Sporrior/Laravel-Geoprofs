<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .input {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .btn {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            color: #fff;
            background-color: #ff8c00;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #FDA085;
            transform: translateY(-2px);
        }

        .alert {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            background: rgba(255, 0, 0, 0.1);
            color: #ff4d4d;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert.show {
            display: block;
            opacity: 1;
        }

        footer {
            margin-top: 20px;
            font-size: 14px;
            color: #999;
        }

        footer a {
            color: #F76C6C;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Two-Factor Authentication</h2>
        <p>Enter the code you received in Discord.</p>

        <div id="alert" class="alert"></div>

        <form id="2faForm">
            <input type="text" id="2faCode" class="input" maxlength="6" placeholder="Enter the 6-digit code" required>
            <button type="button" id="verifyBtn" class="btn">Verify</button>
        </form>

        <footer>
            <p>&copy; 2024 Geoprofs | <a href="#">Privacy</a> | <a href="#">Contact</a></p>
        </footer>
    </div>

    <script>
        const alertBox = document.getElementById('alert');
        const verifyBtn = document.getElementById('verifyBtn');

        verifyBtn.addEventListener('click', () => {
            const code = document.getElementById('2faCode').value;

            if (!/^\d{6}$/.test(code)) {
                showAlert('Please enter a valid 6-digit code.');
                return;
            }

            fetch('/verify-2fa', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ '2fa_code': code })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = '/dashboard';
                    } else {
                        showAlert(data.message);
                    }
                })
                .catch(() => {
                    showAlert('An error occurred. Please try again later.');
                });
        });

        function showAlert(message) {
            alertBox.textContent = message;
            alertBox.classList.add('show');
            setTimeout(() => alertBox.classList.remove('show'), 5000);
        }
    </script>
</body>

</html>