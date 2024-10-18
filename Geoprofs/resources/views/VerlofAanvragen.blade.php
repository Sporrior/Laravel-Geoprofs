<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verlofaanvraag</title>
    <link rel="stylesheet" href="{{ asset('css/verlofaanvragen.css') }}">
    @include('includes.header')

</head>

<body>

    <div class="form-container">
        <h1>Verlofaanvraag Formulier</h1>

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
                <textarea id="verlof_reden" name="verlof_reden" rows="4" required>{{ old('verlof_reden') }}</textarea>
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

</body>

</html>