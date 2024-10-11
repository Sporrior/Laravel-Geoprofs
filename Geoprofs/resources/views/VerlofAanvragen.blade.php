<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verlofaanvraag</title>
</head>
<body>

    <h1>Verlofaanvraag Formulier</h1>

    <!-- Display success message -->
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <!-- Display validation errors -->
    @if ($errors->any())
        <div>
            <ul style="color: red;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('verlofaanvragen.store') }}" method="POST">
        @csrf

        <div>
            <label for="startDatum">Startdatum:</label>
            <input type="date" id="startDatum" name="startDatum" value="{{ old('startDatum') }}" required>
        </div>

        <div>
            <label for="eindDatum">Einddatum:</label>
            <input type="date" id="eindDatum" name="eindDatum" value="{{ old('eindDatum') }}" required>
        </div>

        <div>
            <label for="verlof_reden">reden:</label>
            <textarea id="verlof_reden" name="verlof_reden" rows="4" cols="50" required>{{ old('verlof_reden') }}</textarea>
        </div>

        <div>
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

        <div>
            <button type="submit">Verlofaanvraag Versturen</button>
        </div>
    </form>

</body>
</html>
