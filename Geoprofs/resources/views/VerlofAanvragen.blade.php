<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verlofaanvraag</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
            display: flex;
            flex-direction: column;
        }

        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .success-message {
            color: #4caf50;
            background-color: #e0f9e0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .error-message {
            color: #f44336;
            font-weight: bold;
            margin-bottom: 15px;
            background-color: #ffebee;
            padding: 10px;
            border-radius: 5px;
        }

        .error-message ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        input,
        select,
        textarea {
            padding: 10px;
            font-size: 16px;
            color: #555;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
        }

        textarea {
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #FF8C00;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        .submit-button {
            background-color: #FF8C00;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        .submit-button:hover {
            background-color: #e07c00;
        }

        .submit-button:active {
            transform: scale(0.98);
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 20px;
            }

            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container-admin">
        @include('includes.admin-menu')

        <div class="main-content">
            <div class="form-wrapper">
                <div class="form-container">
                    <h1>Verlofaanvraag</h1>

                    @if(session('success'))
                        <p class="success-message">{{ session('success') }}</p>
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
                                placeholder="dd/mm/yyyy" required>
                        </div>

                        <div class="form-group">
                            <label for="eindDatum">Einddatum:</label>
                            <input type="text" id="eindDatum" name="eindDatum" class="flatpickr-input"
                                placeholder="dd/mm/yyyy" required>
                        </div>

                        <div class="form-group">
                            <label for="verlof_reden">Reden:</label>
                            <textarea id="verlof_reden" name="verlof_reden" rows="4"
                                required>{{ old('verlof_reden') }}</textarea>
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

                        <div class="form-group">
                            <button type="submit" class="submit-button">Verlofaanvraag Versturen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#startDatum", {
            dateFormat: "d-m-Y", // Display date as d-m-Y
            onChange: function(selectedDates, dateStr, instance) {
                // Set min date on end date when a start date is selected
                if (selectedDates.length > 0) {
                    endInput.set('minDate', selectedDates[0]);
                }
            }
        });
        
        const endInput = flatpickr("#eindDatum", {
            dateFormat: "d-m-Y", // Display date as d-m-Y
            minDate: "today"
        });
    });
</script>
</body>

</html>