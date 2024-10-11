<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    @include('includes.header')

</head>


<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li><a href="/dashboard">Overzicht</a></li>
                <li><a href="/verlof">Verlof</a></li>
                <li><a href="/ziekmelden">Ziekmelden</a></li>
                <li><a href="/goedkeuring">Goedkeuring</a></li>
            </ul>
        </aside>

        <div class="main-content">
            <section id="overview" class="content-section">
                <h2>Overzicht</h2>
                <div class="grid-container">
                    <div class="card">
                        <h3>iets</h3>
                        <p>iets anders</p>
                    </div>
                </div>
            </section>

            <section id="verlof" class="content-section">
                <h2>Verlof</h2>
                <p>Hier komen grafieken en data visualisaties.</p>
            </section>

            <section id="ziekmelden" class="content-section">
                <h2>Ziekmelden</h2>
                <p>Bekijk en download de laatste rapporten.</p>
            </section>
        </div>
    </div>
</body>

</html>