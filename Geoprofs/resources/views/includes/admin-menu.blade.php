<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Administratie</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <div class="container-admin">
        <div class="rij">
            <div class="kolom-zijbalk">
                <div class="profiel-sectie">
                    <h4>Hallo, {{ $user->voornaam }}</h4>
                    <p>{{ $user->role->roleName }}</p>
                </div>
                <nav>
                    <a class="navigatie-link actief" href="/dashboard">HR Administratie</a>
                    <a class="navigatie-link" href="/verlofaanvragen">Verlof</a>
                    <a class="navigatie-link" href="/ziekmelden">Ziekmelden</a>
                    <a class="navigatie-link" href="/settings">Settings</a>
                </nav>
            </div>
        </div>
    </div>
</body>

</html>