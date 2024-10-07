<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gebruikersprofiel</title>
    <link rel="stylesheet" href="{{ asset('css/profiel.css') }}">
</head>

<body>
    <div class="container">
        <div class="profiel-links">
            <div class="profiel-kaart">
                <div class="profiel-header">
                    <img src="{{ $user->profielFoto ? asset('storage/' . $user->profielFoto) : asset('storage/default_profile_photo.png') }}"
                        alt="Gebruikersprofiel" class="profiel-foto">
                </div>
                <div class="profiel-info">
                    <h4>Gebruiker: {{ $user->voornaam }}</h4>
                    <h4>Functie: {{ $user->role->roleName }}</h4>
                    <h4>Team: {{ $user->team->group_name }}</h4>
                </div>
            </div>

            <div class="actieve-personnel">
                <h3>Actieve personeelsleden</h3>
                <ul>
                    @foreach($users as $person)
                        @if($person->role->roleName == 'werknemer')
                            <li>{{ $person->voornaam }} {{ $person->achternaam }} - {{ $person->role->roleName }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="profiel-rechts">
            <div class="profiel-bewerken">
                <h3>Profiel bewerken</h3>
                <form action="{{ route('profiel.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="voornaam">Voornaam:</label>
                        <input type="text" id="voornaam" name="voornaam" value="{{ old('voornaam', $user->voornaam) }}">
                    </div>
                    <div class="form-group">
                        <label for="tussennaam">Tussennaam:</label>
                        <input type="text" id="tussennaam" name="tussennaam"
                            value="{{ old('tussennaam', $user->tussennaam) }}">
                    </div>
                    <div class="form-group">
                        <label for="achternaam">Achternaam:</label>
                        <input type="text" id="achternaam" name="achternaam"
                            value="{{ old('achternaam', $user->achternaam) }}">
                    </div>
                    <div class="form-group">
                        <label for="profielFoto">Profiel Foto URL:</label>
                        <input type="text" id="profielFoto" name="profielFoto"
                            value="{{ old('profielFoto', $user->profielFoto) }}">
                    </div>
                    <div class="form-group">
                        <label for="telefoon">Telefoon:</label>
                        <input type="text" id="telefoon" name="telefoon" value="{{ old('telefoon', $user->telefoon) }}">
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}">
                    </div>
                    <button type="submit" class="btn-primary">Opslaan</button>
                </form>
            </div>

            <div class="wachtwoord-wijzigen">
                <h3>Wachtwoord wijzigen</h3>
                <form action="{{ route('profiel.changePassword') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="huidigWachtwoord">Huidig Wachtwoord:</label>
                        <input type="password" id="huidigWachtwoord" name="huidigWachtwoord" required>
                    </div>
                    <div class="form-group">
                        <label for="nieuwWachtwoord">Nieuw Wachtwoord:</label>
                        <input type="password" id="nieuwWachtwoord" name="nieuwWachtwoord" required>
                    </div>
                    <div class="form-group">
                        <label for="bevestigWachtwoord">Bevestig Wachtwoord:</label>
                        <input type="password" id="bevestigWachtwoord" name="bevestigWachtwoord" required>
                    </div>
                    <button type="submit" class="btn-primary">Wijzig Wachtwoord</button>
                </form>
            </div>
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