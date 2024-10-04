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
    <div class="profiel-kaart">
        <div class="profiel-header">
            <img src="{{ asset('images/profile-placeholder.jpg') }}" alt="User Profile" class="profile-pic">
        </div>
        <div class="profiel-info">
            <h4>Gebruiker: {{ Auth::user()->voornaam }}</h4>
            <h4>Functie: {{ Auth::user()->role->roleName }}</h4>
            <h4>Team: {{ Auth::user()->team->group_name }}</h4>
        </div>
    </div>
    <div class="profiel-bewerken">
        <h3>Profiel bewerken</h3>
        <form action="{{ route('profiel.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="voornaam">Voornaam:</label>
                <input type="text" id="voornaam" name="voornaam" value="{{ Auth::user()->voornaam }}">
            </div>
            <div class="form-group">
                <label for="tussennaam">Tussennaam:</label>
                <input type="text" id="tussennaam" name="tussennaam" value="{{ Auth::user()->tussennaam }}">
            </div>
            <div class="form-group">
                <label for="achternaam">Achternaam:</label>
                <input type="text" id="achternaam" name="achternaam" value="{{ Auth::user()->achternaam }}">
            </div>
            <div class="form-group">
                <label for="profielFoto">Profiel Foto URL:</label>
                <input type="text" id="profielFoto" name="profielFoto" value="{{ Auth::user()->profielFoto }}">
            </div>
            <div class="form-group">
                <label for="telefoon">Telefoon:</label>
                <input type="text" id="telefoon" name="telefoon" value="{{ Auth::user()->telefoon }}">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ Auth::user()->email }}">
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
                <input type="password" id="huidigWachtwoord" name="huidigWachtwoord">
            </div>
            <div class="form-group">
                <label for="nieuwWachtwoord">Nieuw Wachtwoord:</label>
                <input type="password" id="nieuwWachtwoord" name="nieuwWachtwoord">
            </div>
            <div class="form-group">
                <label for="bevestigWachtwoord">Bevestig Wachtwoord:</label>
                <input type="password" id="bevestigWachtwoord" name="bevestigWachtwoord">
            </div>
            <button type="submit" class="btn-primary">Wijzig Wachtwoord</button>
        </form>
    </div>
   </div>
</body>

</html>