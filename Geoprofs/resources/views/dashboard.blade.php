<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

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
        grid-template-columns: 1fr 1fr;
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
</style>

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
                    <p><strong>(3)</strong> Verlofaanvragen Goedkeuren</p>
                    <p><strong>(7)</strong> Presentiebladen Goedkeuren</p>
                </div>
            </div>

            <div class="kaart">
                <div class="kaart-kop">Verlofkalender</div>
                <div class="calendar-container" id="calendar">
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://unpkg.com/phosphor-icons"></script>
<script>
    const leaveData = @json($verlofaanvragen);

    const today = new Date();
    const dayOfWeek = today.getDay();
    const startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1));

    const daysOfWeek = ["Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vridag", "Zaterdag", "Zondag"];
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

            return formattedDate >= item.start_datum && formattedDate <= item.eind_datum;
        });

        const status = document.createElement("div");
        status.classList.add("status");

        status.innerText = leaveStatus ? leaveStatus.verlof_reden : "";

        dayDiv.innerHTML = `<div>${day}</div><div>${currentDate.getDate()} ${currentDate.toLocaleString('default', { month: 'short' })}</div>`;
        dayDiv.appendChild(status);

        calendarContainer.appendChild(dayDiv);
    });
</script>
</html>