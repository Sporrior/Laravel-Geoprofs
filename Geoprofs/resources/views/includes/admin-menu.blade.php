<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            object-fit: cover;
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
                    <img id="profielFotoDisplay"
                        src="{{ $user_info->profielFoto && file_exists(public_path('storage/' . $user_info->profielFoto)) 
                            ? asset('storage/' . $user_info->profielFoto) 
                            : asset('images/default-profile.jpg') }}"
                        alt="Profielfoto"
                        class="nav-profiel-foto">
                </a>
                <h4>Hallo, {{ $user_info->voornaam ?? 'Gebruiker' }}</h4>
                <p>{{ $user_info->role->role_name ?? 'Geen rol' }}</p>
                <p>{{ $user_info->team->group_name ?? 'Geen team' }}</p>
            </div>
            <ul class="navigatie">
                @if($user_info->role_id >= 1)
                    <li><a href="/dashboard" class="navigatie-link {{ request()->is('dashboard') ? 'actief' : '' }}">Dashboard</a></li>
                    <li><a href="/verlofaanvragen" class="navigatie-link {{ request()->is('verlofaanvragen') ? 'actief' : '' }}">Verlof</a></li>
                    <li><a href="/ziekmelden" class="navigatie-link {{ request()->is('ziekmelden') ? 'actief' : '' }}">Ziekmelden</a></li>
                    <li><a href="/code-coverage-report" class="navigatie-link {{ request()->is('code-coverage-report') ? 'actief' : '' }}">Code Coverage</a></li>
                @endif

                @if($user_info->role_id >= 2)
                    <li><a href="/keuring" class="navigatie-link {{ request()->is('keuring') ? 'actief' : '' }}">Verlof Goedkeuren</a></li>
                @endif

                @if($user_info->role_id == 3)
                    <li><a href="/account-toevoegen" class="navigatie-link {{ request()->is('account-toevoegen') ? 'actief' : '' }}">Account Toevoegen</a></li>
                @endif
                <a href="#" class="navigatie-link" onclick="logout()">Log uit</a>
            </ul>
        </div>
        <div class="logo">
            <a href="/dashboard"><img src="{{ asset('assets/geoprofs-oranje.png') }}" alt="Logo" class="logo-icon"></a>
        </div>
    </div>
    
    <script>
    function logout() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("{{ route('logout') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
        }).then(response => {
            if (response.ok) {
                window.location.href = "/";
            } else {
                console.error("Logout mislukt.");
            }
        }).catch(error => console.error("Er is een fout opgetreden:", error));
    }
</script>
</body>

</html>