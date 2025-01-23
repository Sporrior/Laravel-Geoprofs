<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verlofaanvraag</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container-admin {
            display: flex;
            flex: 1;
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
            padding: 40px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            text-align: center;
            margin-bottom: 24px;
        }

        .success-message,
        .error-message {
            padding: 12px;
            font-size: 14px;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 16px;
            text-align: center;
        }

        .success-message {
            background-color: #e0f9e0;
            color: #4caf50;
        }

        .error-message {
            background-color: #ffebee;
            color: #f44336;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            transition: 0.3s ease;
            box-sizing: border-box; /* Ensure even sizing */
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #FF8C00;
            background-color: #fff;
            outline: none;
        }

        textarea {
            resize: vertical;
        }

        select {
            appearance: none;
            background-color: #f9f9f9;
        }

        .submit-button {
            background-color: #FF8C00;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            padding: 14px;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .submit-button:hover {
            background-color: #e07c00;
            transform: translateY(-2px);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        .form-footer {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
            color: #555;
        }

        .form-footer a {
            color: #FF8C00;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .form-footer a:hover {
            color: #e07c00;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            h1 {
                font-size: 20px;
                margin-bottom: 16px;
            }

            .submit-button {
                font-size: 14px;
                padding: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="container-admin">
        @include('includes.admin-menu')

        <div class="main-content">
            <div class="form-container">
                <h1>Verlofaanvraag</h1>

                @if(session('success'))
                    <div class="success-message">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="error-message">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="error-message">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="leaveForm" action="{{ route('verlofaanvragen.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="startDatum">Startdatum:</label>
                        <input type="text" id="startDatum" name="startDatum" class="flatpickr-input"
                            placeholder="dd-mm-jjjj" required>
                    </div>

                    <div class="form-group">
                        <label for="eindDatum">Einddatum:</label>
                        <input type="text" id="eindDatum" name="eindDatum" class="flatpickr-input"
                            placeholder="dd-mm-jjjj" required>
                    </div>

                    <div class="form-group">
                        <label for="verlof_reden">Reden:</label>
                        <textarea id="verlof_reden" name="verlof_reden" rows="4"
                            placeholder="Bijv. vakantie of persoonlijke redenen" required>{{ old('verlof_reden') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="verlof_soort">Type Verlof:</label>
                        <select id="verlof_soort" name="verlof_soort" required>
                            <option value="">Kies een type...</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ old('verlof_soort') == $type->id ? 'selected' : '' }}>
                                    {{ $type->type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="submit-button">Verlofaanvraag Versturen</button>
                </form>
                <div class="form-footer">
                    <p>Terug naar <a href="/dashboard">Dashboard</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#startDatum", {
                dateFormat: "d-m-Y",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        endInput.set('minDate', selectedDates[0]);
                    }
                }
            });

            const endInput = flatpickr("#eindDatum", {
                dateFormat: "d-m-Y",
                minDate: "today"
            });
        });
    </script>
</body>

</html>