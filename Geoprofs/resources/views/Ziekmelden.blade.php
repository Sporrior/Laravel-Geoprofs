<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ziekmelden</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
        }

        .container-admin {
            display: flex;
            width: 100%;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            color: #444;
            margin-bottom: 20px;
        }

        .success-message {
            color: #fff;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
            font-size: 14px;
            transition: opacity 0.5s ease;
        }

        .info-text {
            font-size: 1em;
            color: #555;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .sick-form,
        .form-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
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
            background-color: #e07c00;
        }

        .submit-button:active {
            transform: scale(0.98);
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container-admin">
        @include('includes.admin-menu')

        <div class="main-content">
            <div class="container">
                <h1>Ziekmelden</h1>

                @if(session('success') || session('error'))
                    <div 
                        class="success-message" 
                        id="successMessage" 
                        style="background-color: {{ session('error') ? '#ff4c4c' : '#4caf50' }};">
                        {{ session('success') ?? session('error') }}
                    </div>
                @endif

                <form action="{{ route('ziekmelden.store') }}" method="POST" class="sick-form">
                    @csrf

                    <input type="hidden" name="verlof_reden" value="ziek">

                    <p class="info-text">U meldt zich ziek voor 1 dag.</p>

                    <button type="submit" class="submit-button">Ziekmelden</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.style.display = 'block';
                successMessage.style.opacity = '1';

                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 500);
                }, 3000);
            }
        });
    </script>
</body>

</html>