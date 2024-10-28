<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verlofaanvraag</title>
    <link rel="stylesheet" href="{{ asset('css/verlofaanvragen.css') }}">

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .header-container {
            width: 100%;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            min-height: 100vh;
            padding-top: 60px;
            box-sizing: border-box;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            margin-top: 20px;
            text-align: center;
        }

        .form-container h1 {
            font-size: 1.8em;
            margin-bottom: 25px;
            color: #444;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            color: #666;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            color: #555;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #FF8C00;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        .submit-button {
            width: 100%;
            padding: 12px;
            font-size: 1em;
            background-color: #FF8C00;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        .submit-button:hover {
            background-color: #FF8C00;
        }

        .submit-button:active {
            transform: scale(0.98);
        }

        .success-message {
            color: #4caf50;
            font-weight: bold;
            margin-bottom: 15px;
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
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

        .error-message li {
            font-size: 0.9em;
            line-height: 1.5;
        }
    </style>
</head>

<body>

    <div class="header-container">
        @include('includes.header')
    </div>

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

            <form action="{{ route('verlofaanvragen.store') }}" method="POST" class="leave-form">
                @csrf

                <div class="form-group">
                    <label for="startDatum">Startdatum:</label>
                    <input type="date" id="startDatum" name="startDatum" value="{{ old('startDatum') }}" required>
                </div>

                <div class="form-group">
                    <label for="eindDatum">Einddatum:</label>
                    <input type="date" id="eindDatum" name="eindDatum" value="{{ old('eindDatum') }}" required>
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

</body>

</html>