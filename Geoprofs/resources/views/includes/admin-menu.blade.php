<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Administratie</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<div class="zijbalk">
    <style>

        .zijbalk {
            width: 210px;
            background-color: #fff;
            height: 100vh;
            padding: 0px 20px 0 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .profiel-selectie {
            flex-grow: 1;
        }

        .profiel-sectie {
            text-align: center;
            margin-bottom: 30px;
        }

        .profiel-sectie img.nav-profiel-foto {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        .profiel-sectie h4 {
            margin: 10px 0 5px;
            font-size: 18px;
        }

        .profiel-sectie p {
            margin: 0;
            color: gray;
            font-size: 14px;
        }

        .navigatie {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .navigatie-link {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .navigatie-link.actief,
        .navigatie-link:hover {
            background-color: #ff8c00;
            color: #fff;
        }

        .logo {
            text-align: center;
        }

        .logo img.logo-icon {
            width: 150px;
        }

    </style>

    <div class="profiel-selectie">
        <div class="profiel-sectie">
            <img id="profielFotoDisplay" src="{{ asset('storage/' . $user->profielFoto) }}" alt="Profielfoto"
                class="nav-profiel-foto">
            <h4>Hallo, {{ $user->voornaam }}</h4>
            <p>{{ $user->role->roleName }}</p>
        </div>
        <ul class="navigatie">
            <li><a href="/dashboard" class="navigatie-link actief">HR Administratie</a></li>
            <li><a href="/keuring" class="navigatie-link">Verlof Goedkeuren</a></li>
            <li><a href="/verlofaanvragen" class="navigatie-link">Verlof</a></li>
            <li><a href="/ziekmelden" class="navigatie-link">Ziekmelden</a></li>
            <li><a href="/settings" class="navigatie-link">Settings</a></li>
        </ul>
    </div>
    <div class="logo">
        <a href="/dashboard"><img src="{{ asset('assets/geoprofs-oranje.png') }}" alt="Logo" class="logo-icon"></a>
    </div>
</div>
