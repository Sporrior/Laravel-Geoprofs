@if(!$user->role->id == 2 || $user->role->id == 3)

@elseif($user->role->id == 1)
    <script>window.location = "/dashboard";</script>
@endif
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuring Verlofaanvragen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            overflow-y: auto;
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
            gap: 15px;
            overflow-y: auto;
        }

        .success-message {
            background-color: #4caf50;
            color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
            font-size: 14px;
            transition: opacity 0.5s ease, transform 0.3s ease;
            transform: translateY(-20px);
        }

        .leave-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .leave-card:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .leave-card h3 {
            font-size: 18px;
            color: #333;
            margin: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .leave-details {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 14px;
            color: #555;
        }

        .leave-detail {
            flex: 1 1 50%;
            border-left: 4px solid #ff8c00;
            padding-left: 8px;
            margin-bottom: 8px;
        }

        .leave-detail strong {
            color: #333;
            font-weight: 600;
        }

        .status {
            font-weight: bold;
            color: #4caf50;
        }

        .status.pending {
            color: #ffa000;
        }

        .status.approved {
            color: #4caf50;
        }

        .status.rejected {
            color: #f44336;
        }

        .status-form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .status-form select {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f8f8f8;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .status-form select:focus {
            border-color: #007bff;
            outline: none;
        }

        .status-form button {
            padding: 8px 14px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .status-form button:hover {
            background-color: #0056b3;
        }

        .status-form button:active {
            transform: scale(0.98);
        }
    </style>
</head>

<body>
    <div class="container-admin">
        @include('includes.admin-menu')

        <div class="main-content">
            @if(session('success'))
                <div class="success-message" id="successMessage">{{ session('success') }}</div>
            @endif

            @foreach($verlofaanvragens->sortByDesc('updated_at') as $aanvraag)
                <div class="leave-card">
                    <h3>{{ optional($aanvraag->user)->voornaam }}'s Verlofaanvraag</h3>
                    <div class="leave-details">
                        <div class="leave-detail"><strong>Reden:</strong> {{ $aanvraag->verlof_reden }}</div>
                        <div class="leave-detail"><strong>Start Datum:</strong> {{ $aanvraag->start_datum }}</div>
                        <div class="leave-detail"><strong>Eind Datum:</strong> {{ $aanvraag->eind_datum }}</div>
                        <div class="leave-detail"><strong>Type Verlof:</strong> {{ optional($aanvraag->type)->type }}</div>
                        <div class="leave-detail">
                            <strong>Status:</strong>
                            <span class="status
                            {{ is_null($aanvraag->status) ? 'pending' : ($aanvraag->status == 1 ? 'approved' : 'rejected') }}">
                                {{ is_null($aanvraag->status) ? 'Pending' : ($aanvraag->status == 1 ? 'Goedgekeurd' : 'Weigeren') }}
                            </span>
                        </div>
                    </div>

                    <!-- Status update form, available for all statuses -->
                    <form action="{{ route('keuring.updateStatus', $aanvraag->id) }}" method="POST" class="status-form">
                        @csrf
                        <select name="status">
                            <option value="">Selecteer status</option>
                            <option value="1" {{ $aanvraag->status === 1 ? 'selected' : '' }}>Goedkeuren</option>
                            <option value="0" {{ $aanvraag->status === 0 ? 'selected' : '' }}>Weigeren</option>
                        </select>
                        <button type="submit">{{ is_null($aanvraag->status) ? 'Verstuur' : 'Wijzigen' }}</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.style.display = 'block';
                successMessage.style.opacity = '1';

                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 500);
                }, 3000);
            }
        });
    </script>
</body>

</html>
