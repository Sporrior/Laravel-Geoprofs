<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gebruikersprofiel</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .container-admin {
            display: flex;
            width: 100%;
            overflow-y: auto;
            flex-wrap: wrap;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 40px;
            overflow-y: auto;
        }

        .container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 1200px;
            width: 100%;
            margin: auto;
        }

        .profiel-kaart {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .profiel-foto {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ff8c00;
        }

        .profiel-info {
            flex-grow: 1;
            margin-left: 20px;
        }

        .profiel-info h2 {
            font-size: 28px;
            color: #333;
            margin: 0;
            font-weight: 600;
        }

        .profiel-info p {
            margin: 5px 0;
            color: #555;
            font-size: 16px;
            line-height: 1.6;
        }

        .personeel {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .personeel h3 {
            color: #333;
            font-size: 22px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .personnel-table {
            width: 100%;
            border-collapse: collapse;
        }

        .personnel-table th,
        .personnel-table td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .personnel-table th {
            background-color: #ff8c00;
            color: #fff;
            font-weight: bold;
        }

        .personnel-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .profiel-bewerken,
        .wachtwoord-wijzigen {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .profiel-bewerken h3,
        .wachtwoord-wijzigen h3 {
            color: #333;
            font-size: 22px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .form-group label {
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="file"] {
            padding: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            background-color: #f9f9f9;
        }

        .form-group input:focus {
            border-color: #ff8c00;
            background-color: #fff;
        }

        .btn-primary {
            background-color: #ff8c00;
            color: #fff;
            padding: 14px 28px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
            text-align: center;
        }

        .btn-primary:hover {
            background-color: #e67e00;
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .alert {
            padding: 18px;
            border-radius: 6px;
            margin-top: 20px;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .alert-success {
            background-color: #4caf50;
            color: #fff;
        }

        .alert-danger {
            background-color: #f44336;
            color: #fff;
        }

        @media (max-width: 768px) {
            .profiel-kaart {
                flex-direction: column;
                align-items: flex-start;
            }

            .profiel-info {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <div class="container-admin">
        @include('includes.admin-menu')

        <div class="main-content">
            <div class="container">
                <div class="profiel-kaart">
                    <div class="profiel-header">
                        <img id="profielFotoDisplay" src="{{ asset('storage/' . $user_info->profielFoto) }}"
                            alt="Gebruikersprofiel" class="profiel-foto">
                    </div>
                    <div class="profiel-info">
                        <h2>{{ $user_info->voornaam }} {{ $user_info->achternaam }}</h2>
                        <p class="functie">{{ $user_info->role->role_name }}</p>
                        <p class="team">Team: {{ $user_info->team->group_name }}</p>
                        <p class="verlof-dagen">Verlof dagen : {{ $user_info->verlof_dagen }}</p>
                    </div>
                </div>

                <div class="profiel-bewerken">
                    <h3>Profiel bewerken</h3>
                    <form id="profileForm" action="{{ route('profiel.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="voornaam">Voornaam</label>
                            <input type="text" id="voornaam" name="voornaam"
                                value="{{ old('voornaam', $user_info->voornaam) }}">
                        </div>
                        <div class="form-group">
                            <label for="tussennaam">Tussennaam</label>
                            <input type="text" id="tussennaam" name="tussennaam"
                                value="{{ old('tussennaam', $user_info->tussennaam) }}">
                        </div>
                        <div class="form-group">
                            <label for="achternaam">Achternaam</label>
                            <input type="text" id="achternaam" name="achternaam"
                                value="{{ old('achternaam', $user_info->achternaam) }}">
                        </div>
                        <div class="form-group">
                            <label for="profielFoto">Profiel Foto</label>
                            <input type="file" id="profielFoto" name="profielFoto" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="telefoon">Telefoon</label>
                            <input type="text" id="telefoon" name="telefoon"
                                value="{{ old('telefoon', $user_info->telefoon) }}">
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user_info->email) }}">
                        </div>
                        <button type="submit" class="btn-primary">Opslaan</button>
                    </form>
                </div>

                <div class="wachtwoord-wijzigen">
                    <h3>Wachtwoord wijzigen</h3>
                    <form id="passwordForm" action="{{ route('profiel.changePassword') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="huidigWachtwoord">Huidig Wachtwoord</label>
                            <input type="password" id="huidigWachtwoord" name="huidigWachtwoord" required>
                        </div>
                        <div class="form-group">
                            <label for="nieuwWachtwoord">Nieuw Wachtwoord</label>
                            <input type="password" id="nieuwWachtwoord" name="nieuwWachtwoord" required>
                        </div>
                        <div class="form-group">
                            <label for="nieuwWachtwoord_confirmation">Bevestig Nieuw Wachtwoord</label>
                            <input type="password" id="nieuwWachtwoord_confirmation" name="nieuwWachtwoord_confirmation"
                                required>
                        </div>
                        <button type="submit" class="btn-primary">Wijzig Wachtwoord</button>
                    </form>
                </div>

                @if (session('success'))
                    <div class="alert alert-success" id="successMessage">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
