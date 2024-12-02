<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .zijbalk {
            width: 210px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #333;
        }

        .profiel-sectie {
            text-align: center;
            margin-bottom: 40px;
        }

        .profiel-sectie img.nav-profiel-foto {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin-bottom: 10px;
            margin-top: 20px;
            transition: transform 0.3s ease;
        }

        .profiel-sectie img.nav-profiel-foto:hover {
            transform: scale(1.1);
        }

        .profiel-sectie h4 {
            margin: 10px 0 5px;
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .profiel-sectie p {
            margin: 0;
            color: gray;
            font-size: 14px;
            opacity: 0.9;
        }

        .navigatie {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .navigatie-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .navigatie-link:hover {
            background-color: #FF8C00;
            transform: translateX(5px);
        }

        .navigatie-link.actief {
            background-color: #ff8c00;
            color: #fff;
        }

        .logo {
            text-align: center;
        }

        .logo img.logo-icon {
            width: 150px;
            transition: transform 0.3s ease;
        }

        .logo img.logo-icon:hover {
            transform: rotate(5deg) scale(1.05);
        }
    </style>
</head>

<body>
    <div class="zijbalk">
        <div class="profiel-selectie">
            <div class="profiel-sectie">
                <a href="/profiel">
                    <img id="profielFotoDisplay" src="{{ asset('storage/' . $user->profielFoto) }}" alt="Profielfoto"
                        class="nav-profiel-foto">
                </a>
                <h4>Hallo, {{ $user->voornaam }}</h4>
                <p>{{ $user->role_name }}</p>
                <p>{{ $user->group_name }}</p>
            </div>
            <ul class="navigatie">
                @if($user->id >= 1) <!-- Gebruikers met rol 1 en hoger -->
                    <li><a href="/dashboard"
                            class="navigatie-link {{ request()->is('dashboard') ? 'actief' : '' }}">Dashboard</a></li>
                    <li><a href="/verlofaanvragen"
                            class="navigatie-link {{ request()->is('verlofaanvragen') ? 'actief' : '' }}">Verlof</a></li>
                    <li><a href="/ziekmelden"
                            class="navigatie-link {{ request()->is('ziekmelden') ? 'actief' : '' }}">Ziekmelden</a></li>
                    <li><a href="/settings"
                            class="navigatie-link {{ request()->is('settings') ? 'actief' : '' }}">Settings</a></li>
                    <li><a href="/code-coverage-report"
                            class="navigatie-link {{ request()->is('code-coverage-report') ? 'actief' : '' }}">Code Coverage</a></li>
                    <li>

                @endif

                @if($user->id >= 2) <!-- Managers en hoger -->
                    <li><a href="/keuring" class="navigatie-link {{ request()->is('keuring') ? 'actief' : '' }}">Verlof
                            Goedkeuren</a></li>
                @endif

                @if($user->id == 3) <!-- Alleen Office Managers -->
                    <li><a href="#" class="navigatie-link {{ request()->is('/') ? 'actief' : '' }}">HR Administratie</a>
                    <li><a href="/accouttoevoegen"
                            class="navigatie-link {{ request()->is('accouttoevoegen') ? 'actief' : '' }}">Account
                            Toevoegen</a></li>
                @endif
            </ul>
        </div>
        <div class="logo">
            <a href="/dashboard"><img src="{{ asset('assets/geoprofs-oranje.png') }}" alt="Logo" class="logo-icon"></a>
        </div>
    </div>

    <script>
        const profilePic = document.getElementById('profielFotoDisplay');
        profilePic.addEventListener('mouseover', () => {
            profilePic.style.transform = 'scale(1.1)';
        });
        profilePic.addEventListener('mouseout', () => {
            profilePic.style.transform = 'scale(1)';
        });
    </script>
</body>

</html>
