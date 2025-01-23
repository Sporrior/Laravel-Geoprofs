<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/phosphor-icons">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
        }

        .container-admin {
            display: flex;
            width: 100%;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 95vh;
        }

        .kaart {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .kaart-kop {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 10px;
            font-size: 18px;
        }

        .quick-access-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .quick-access-item {
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            padding: 20px;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .quick-access-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .calendar-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .calendar-day {
            flex: 1;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 5px;
            background-color: #fafafa;
        }

        .calendar-day.current-day {
            background-color: #FF8C00;
            color: white;
            font-weight: bold;
        }

        .calendar-day .status {
            font-size: 12px;
            margin-top: 5px;
            color: #666;
        }

        @media (max-width: 768px) {
            .container-admin {
                flex-direction: column;
            }

            .quick-access-grid {
                grid-template-columns: 1fr;
            }

            .calendar-container {
                flex-direction: column;
            }

            .calendar-day {
                margin-bottom: 10px;
                margin-right: 0;
            }
        }

        .aanvragen-lijst {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 10px;
        }

        .aanvraag-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .aanvraag-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .aanvraag-header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .aanvraag-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .aanvraag-details p span {
            font-weight: bold;
            color: #333;
        }

        .status-label {
            font-weight: bold;
            color: #FF8C00;
        }

        .status-label.goedgekeurd {
            color: #4CAF50;
        }

        .status-label.geweigerd {
            color: #F44336;
        }

        :root {
            --numDays: 5;
            --numHours: 10;
            --timeHeight: 60px;
            --calBgColor: #fff1f8;
            --eventBorderColor: #f2d3d8;
            --eventColor1: #ffd6d1;
            --eventColor2: #fafaa3;
            --eventColor3: #e2f8ff;
            --eventColor4: #d1ffe6;
        }

        .calendar {
            display: grid;
            gap: 10px;
            grid-template-columns: auto 1fr;
            margin: 2rem;
        }

        .timeline {
            display: grid;
            grid-template-rows: repeat(var(--numHours), var(--timeHeight));
        }

        .days {
            display: grid;
            grid-column: 2;
            gap: 5px;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }

        .day {
            grid-template-rows: repeat(var(--numHours), var(--timeHeight));
            border-radius: 5px;
            background: var(--calBgColor);
        }

        .start-10 {
            grid-row-start: 2;
        }

        .start-12 {
            grid-row-start: 4;
        }

        .start-1 {
            grid-row-start: 5;
        }

        .start-2 {
            grid-row-start: 6;
        }

        .end-12 {
            grid-row-end: 4;
        }

        .end-1 {
            grid-row-end: 5;
        }

        .end-3 {
            grid-row-end: 7;
        }

        .end-4 {
            grid-row-end: 8;
        }

        .end-5 {
            grid-row-end: 9;
        }

        .title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .verlofaanvraag {
            border: 1px solid var(--eventBorderColor);
            border-radius: 5px;
            padding: 0.5rem;
            margin: 0 0.5rem;
            background: white;
        }

        .space,
        .date {
            height: 60px;
            background-color: white;
        }

        .corp-fi {
            background: var(--eventColor1);
        }

        .ent-law {
            background: var(--eventColor2);
        }

        .writing {
            background: var(--eventColor3);
        }

        .securities {
            background: var(--eventColor4);
        }

        .date {
            gap: 1em;
        }

        .date-num {
            font-size: 3rem;
            font-weight: 600;
            display: inline;
        }

        .date-day {
            display: inline;
            font-size: 3rem;
            font-weight: 100;
        }
    </style>
</head>

<body>
    <div class="container-admin">
        @include('includes.admin-menu')

        <div class="main-content">
            <div class="kaart">
                <div class="kaart-kop">Snelle Toegang</div>
                <div class="quick-access-grid">
                    <div class="quick-access-item">
                    <a href="{{ $user_info->role_id == 3 ? '/account-toevoegen' : '/ziekmelden' }}">
                        <i class="ph-bold ph-calendar"></i>
                        <p>{{ $user_info->role_id == 3 ? 'Account Toevoegen' : 'Ziekmelden' }}</p>
                    </a>
                    </div>
                    <div class="quick-access-item">
                        <a href="{{ $user_info->role_id == 3 || 2 ? '/verlofaanvragen' : '/ziekmelden' }}">
                            <i class="ph-bold ph-user-plus"></i>
                            <p>{{$user_info->role_id == 3 || 2 ? 'Verlof' : 'Ziekmelden'}}</p>
                        </a>
                    </div>
                    <div class="quick-access-item">
                        <a href="#">
                            <i class="ph-bold ph-calendar-blank"></i>
                            <p>Verlofkalendar</p>
                        </a>
                    </div>
                    <div class="quick-access-item">
                    <a href="{{ $user_info->role_id == 3 ? '/keuring' : '/profiel' }}">
                        <i class="ph ph-user-focus"></i>
                        <p>{{ $user_info->role_id == 3 ? 'Verlof Goedkeuren' : 'Profiel' }}</p>
                        </a>
                    </div>
                </div>
            </div>

            <div class="kaart">
                <div class="kaart-kop">Mijn Acties</div>
                <div class="kaart-body">
                    @if ($lopendeAanvragen->isNotEmpty())
                        <p><strong>({{ $lopendeAanvragen->count() }})</strong> Lopende Aanvragen:</p>
                        <div class="aanvragen-lijst">
                            @foreach ($lopendeAanvragen as $aanvraag)
                                <div class="aanvraag-item">
                                    <div class="aanvraag-header">
                                        <strong>{{ $aanvraag->verlof_reden }}</strong>
                                    </div>
                                    <div class="aanvraag-details">
                                        <p><span>Van:</span> {{ $aanvraag->start_datum->format('d-m-Y') }}</p>
                                        <p><span>Tot:</span> {{ $aanvraag->eind_datum->format('d-m-Y') }}</p>
                                        <p><span>Status:</span> <span class="status-label">{{ $aanvraag->status_label }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>Geen lopende aanvragen op dit moment.</p>
                    @endif
                </div>
            </div>

            <div class="kaart">
                <div class="calendar">
                    <div class="days">
                        @foreach ($dagen as $dag)
                            <div class="day">
                                <div class="date">
                                    <p class="date-num">{{ $dag['datumNummer'] }}</p>
                                    <p class="date-day">{{ $dag['datumDag'] }}</p>
                                </div>
                                <div class="verlofaanvragen">
                                    @foreach ($dag['verlofaanvragen'] as $verlofaanvraag)
                                        <div class="verlofaanvraag" style="
                                                    grid-row-start: {{ $verlofaanvraag['start'] }};
                                                    grid-row-end: {{ $verlofaanvraag['end'] }};">
                                            <p class="voornaam">{{ $verlofaanvraag['voornaam'] }}</p>
                                            <p class="reason">{{ $verlofaanvraag['reden'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>