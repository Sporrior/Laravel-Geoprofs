<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gebruikersprofiel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
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
        }

        .main-content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
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
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .profiel-foto {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ff8c00;
        }

        .profiel-info {
            flex-grow: 1;
        }

        .profiel-info h2 {
            font-size: 24px;
            color: #333;
            margin: 0;
        }

        .profiel-info p {
            margin: 5px 0;
            color: gray;
            font-size: 16px;
        }

        .personeel {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .personeel h3 {
            color: #333;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .personnel-table {
            width: 100%;
            border-collapse: collapse;
        }

        .personnel-table th,
        .personnel-table td {
            padding: 12px;
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
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .profiel-bewerken h3,
        .wachtwoord-wijzigen h3 {
            color: #333;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .form-group label {
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #ff8c00;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

    </style>
</head>

<body>

    <div class="container-admin">
        @include('includes.admin-menu')

        <div class="main-content">
            <div class="container">
                <div class="profiel-kaart">
                    <div class="profiel-header">
                        <img id="profielFotoDisplay" src="{{ asset('storage/' . $user->profielFoto) }}"
                            alt="Gebruikersprofiel" class="profiel-foto">
                    </div>
                    <div class="profiel-info">
                        <h2>{{ $user->voornaam }} {{ $user->achternaam }}</h2>
                        <p class="functie">{{ $user->role->role_name }}</p>
                        <p class="team">Team: {{ $user->team->group_name }}</p>
                        <p class="verlof-dagen">verlof dagen : {{ $user->verlof_dagen }}</p>
                    </div>
                </div>

                <div class="personeel">
                    <h3>Personeel</h3>
                    <table class="personnel-table">
                        <thead>
                            <tr>
                                <th>Voornaam</th>
                                <th>Achternaam</th>
                                <th>Telefoonnummer</th>
                                <th>Email</th>
                                <th>Functie</th>
                                <th>verlof dagen</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $persoon)
                                <tr>
                                    <td>{{ $persoon->voornaam }}</td>
                                    <td>{{ $persoon->achternaam }}</td>
                                    <td>{{ $persoon->telefoon }}</td>
                                    <td>{{ $persoon->email }}</td>
                                    <td>{{ $persoon->role->role_name }}</td>
                                    <td>{{ $persoon->verlof_dagen }}</td>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
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
                                value="{{ old('voornaam', $user->voornaam) }}">
                        </div>
                        <div class="form-group">
                            <label for="tussennaam">Tussennaam</label>
                            <input type="text" id="tussennaam" name="tussennaam"
                                value="{{ old('tussennaam', $user->tussennaam) }}">
                        </div>
                        <div class="form-group">
                            <label for="achternaam">Achternaam</label>
                            <input type="text" id="achternaam" name="achternaam"
                                value="{{ old('achternaam', $user->achternaam) }}">
                        </div>
                        <div class="form-group">
                            <label for="profielFoto">Profiel Foto</label>
                            <input type="file" id="profielFoto" name="profielFoto" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="telefoon">Telefoon</label>
                            <input type="text" id="telefoon" name="telefoon"
                                value="{{ old('telefoon', $user->telefoon) }}">
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}">
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
