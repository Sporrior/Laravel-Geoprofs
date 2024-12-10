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
                        <a href="#">
                            <i class="ph-bold ph-calendar"></i>
                            <p>Agenda</p>
                        </a>
                    </div>
                    <div class="quick-access-item">
                        <a href="#">
                            <i class="ph-bold ph-user-plus"></i>
                            <p>Verlof Toewijzen</p>
                        </a>
                    </div>
                    <div class="quick-access-item">
                        <a href="#">
                            <i class="ph-bold ph-calendar-blank"></i>
                            <p>Verlofkalendar</p>
                        </a>
                    </div>
                    <div class="quick-access-item">
                        <a href="#">
                            <i class="ph ph-user-focus"></i>
                            <p>Mijn Verlof</p>
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
                <div class="kaart-kop">Verlofkalender</div>
                <div class="calendar-container" id="calendar"></div>
            </div>
        </div>
    </div>

    <script>
        const leaveData = @json($verlofaanvragen);

        const today = new Date();
        const dayOfWeek = today.getDay();
        const startOfWeek = new Date(today);
        startOfWeek.setDate(today.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1));

        const daysOfWeek = ["Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag", "Zondag"];
        const calendarContainer = document.getElementById("calendar");

        daysOfWeek.forEach((day, index) => {
            const dayDiv = document.createElement("div");
            dayDiv.classList.add("calendar-day");

            const currentDate = new Date(startOfWeek);
            currentDate.setDate(startOfWeek.getDate() + index);

            const formattedDate = currentDate.toISOString().split('T')[0];

            if (currentDate.toDateString() === today.toDateString()) {
                dayDiv.classList.add("current-day");
            }

            const leaveStatus = leaveData.find(item => {
                const startDate = new Date(item.start_datum);
                const endDate = new Date(item.eind_datum);

                return item.status === 1 && formattedDate >= item.start_datum && formattedDate <= item.eind_datum;
            });

            const status = document.createElement("div");
            status.classList.add("status");

            status.innerText = leaveStatus ? leaveStatus.verlof_reden : "";

            dayDiv.innerHTML = `<div>${day}</div><div>${currentDate.getDate()} ${currentDate.toLocaleString('default', { month: 'short' })}</div>`;
            dayDiv.appendChild(status);

            calendarContainer.appendChild(dayDiv);
        });
    </script>
</body>

</html>