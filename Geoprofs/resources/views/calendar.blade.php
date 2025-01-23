<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <style>
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
            display: grid;
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

        body {
            font-family: system-ui, sans-serif;
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
    <div class="calendar">
        <div class="timeline">
            <div class="space"></div>
            @for ($hour = 9; $hour <= 18; $hour++)
                <div class="time-marker">{{ $hour <= 12 ? $hour . ' AM' : ($hour - 12) . ' PM' }}</div>
            @endfor
        </div>
        <div class="days">
            @foreach ($dagen as $dagen)
                <div class="day">
                    <div class="date">
                        <p class="date-num">{{ $dagen['datumNummer'] }}</p>
                        <p class="date-day">{{ $dagen['datumDag'] }}</p>
                    </div>
                    <div class="verlofaanvragen">
                        @foreach ($dagen['verlofaanvragen'] as $verlofaanvraag)
                            <div class="verlofaanvraag"
                                style="grid-row-start: {{ $verlofaanvraag['start'] }}; grid-row-end: {{ $verlofaanvraag['end'] }};">
                                <p class="voornaam">{{ $verlofaanvraag['voornaam'] }}</p>
                                <p class="reason">{{ $verlofaanvraag['reden'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>