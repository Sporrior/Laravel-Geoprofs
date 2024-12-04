<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Toevoegen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
        }

        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .success-message,
        .error-message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .success-message {
            color: #4caf50;
            background-color: #e0f9e0;
            text-align: center;
            font-weight: bold;
        }

        .error-message {
            color: #f44336;
            background-color: #ffebee;
            font-weight: bold;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        select {
            padding: 10px;
            font-size: 16px;
            color: #555;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .submit-button {
            background-color: #FF8C00;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: #e07c00;
        }
    </style>
</head>

<body>
    <div class="container-admin">
        @include('includes.admin-menu')

        <div class="main-content">
            <div class="form-wrapper">
                <div class="form-container">
                    <h1>Account Toevoegen</h1>

                    @if(session('success'))
                        <p class="success-message">{{ session('success') }}</p>
                    @endif

                    @if(session('error'))
                        <div class="error-message">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="error-message">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('account-toevoegen.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="voornaam">Voornaam:</label>
                            <input type="text" id="voornaam" name="voornaam" value="{{ old('voornaam') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="tussennaam">Tussenvoegsel:</label>
                            <input type="text" id="tussennaam" name="tussennaam" value="{{ old('tussennaam') }}">
                        </div>

                        <div class="form-group">
                            <label for="achternaam">Achternaam:</label>
                            <input type="text" id="achternaam" name="achternaam" value="{{ old('achternaam') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="telefoon">Telefoonnummer:</label>
                            <input type="text" id="telefoon" name="telefoon" value="{{ old('telefoon') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">E-mailadres:</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Wachtwoord:</label>
                            <input type="password" id="password" name="password" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Bevestig Wachtwoord:</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="form-group">
                            <label for="rol">Rol:</label>
                            <select id="rol" name="role_id" required>
                                <option value="">Kies een rol...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->role_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="submit-button">Account Aanmaken</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>