<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <div class="logo">
                <img src="{{ asset('assets/geoprofs-oranje.png') }}" alt="Logo" class="logo-icon">
            </div>
            <div class="search-container">
                <input type="text" placeholder="Search for anything..." class="search-bar">
            </div>
        </div>

        <div class="navbar-center">
            <a href="#" class="nav-link active">Verlof</a>
            <a href="#" class="nav-link">Ziekmelden</a>
            <a href="#" class="nav-link">Goedkeuring</a>
        </div>

        <div class="navbar-right">
            <a href="{{ route('profiel.show') }}">
                <img src="{{ asset('images/profile-placeholder.jpg') }}" alt="User Profile" class="profile-pic">
            </a>
        </div>
    </nav>

    <div class="widgets-container">
        <div class="widget-left">
            <h2>Vakantiedagen Over</h2>
            <p>Je hebt nog <strong>10</strong> vakantiedagen over.</p>
        </div>

        <div class="widget-right">
            <h2>Verlofaanvragen</h2>
            <ul class="responsive-table">
                <li class="header">
                    <div>Datum van aanvraag</div>
                    <div>Datum</div>
                    <div>Werknemer</div>
                    <div>Status</div>
                </li>
                @foreach($verlofaanvragen as $aanvraag)
                <li>
                    <div data-label="Datum van aanvraag">{{ $aanvraag->aanvraag_datum }}</div>
                    <div data-label="Datum">{{ $aanvraag->start_datum }} - {{ $aanvraag->eind_datum }}</div>
                    <div data-label="Werknemer">{{ $aanvraag->user->voornaam }}</div>
                    <div data-label="Status" class="status">{{ $aanvraag->status_label }}</div>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="widget-bottom">
            <h2>Lege Widget</h2>
            <p>Deze widget is momenteel leeg.</p>
        </div>
    </div>

    <script>
        document.querySelectorAll('.status').forEach(function (statusEl) {
            const statusText = statusEl.textContent.trim().toLowerCase();

            if (statusText === 'in behandeling') {
                statusEl.style.color = 'grey';
            } else if (statusText === 'geweigerd') {
                statusEl.style.color = 'red';
            } else if (statusText === 'goedgekeurd') {
                statusEl.style.color = 'green';
            }
        });
    </script>

</body>

</html>