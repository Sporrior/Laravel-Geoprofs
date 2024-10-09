<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gebruikersprofiel</title>
    <link rel="stylesheet" href="{{ asset('css/profiel.css') }}">
</head>

@include('includes.header')

<body>
    <div class="container">
        <div class="profiel">
            <div class="profiel-kaart">
                <div class="profiel-header">
                    <img id="profielFotoDisplay" src="{{ asset('storage/' . $user->profielFoto) }}"
                        alt="Gebruikersprofiel" class="profiel-foto">
                </div>
                <div class="profiel-info">
                    <h2>{{ $user->voornaam }} {{ $user->achternaam }}</h2>
                    <p class="functie">{{ $user->role->roleName }}</p>
                    <p class="team">Team: {{ $user->team->group_name }}</p>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $person)
                            @if($person->role->roleName == 'werknemer')
                                <tr>
                                    <td>{{ $person->voornaam }}</td>
                                    <td>{{ $person->achternaam }}</td>
                                    <td>{{ $person->telefoon }}</td>
                                    <td>{{ $person->email }}</td>
                                    <td>{{ $person->role->roleName }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="profiel-bewerken">
            <h3>Profiel bewerken</h3>
            <form id="profileForm" action="{{ route('profiel.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="voornaam">Voornaam</label>
                    <input type="text" id="voornaam" name="voornaam" value="{{ old('voornaam', $user->voornaam) }}">
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
                    <input type="text" id="telefoon" name="telefoon" value="{{ old('telefoon', $user->telefoon) }}">
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
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
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
</body>

</html>