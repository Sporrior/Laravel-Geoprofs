<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuring Verlofaanvragen</title>
</head>
<body>

    <h1>Keuring van Verlofaanvragen</h1>

    <!-- Success Message -->
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <!-- Table for displaying leave requests -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Werknemer</th>
                <th>Reden</th>
                <th>Start Datum</th>
                <th>Eind Datum</th>
                <th>Type Verlof</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($verlofaanvragens as $aanvraag)
                <tr>
                    <td>{{ optional($aanvraag->user)->voornaam }}</td> <!-- Use optional to prevent errors -->
                    <td>{{ $aanvraag->verlof_reden }}</td>
                    <td>{{ $aanvraag->start_datum }}</td>
                    <td>{{ $aanvraag->eind_datum }}</td>
                    <td>{{ optional($aanvraag->type)->type }}</td> <!-- Optional for type as well -->
                    <td>
                        @if(is_null($aanvraag->status))
                            <form action="{{ route('keuring.updateStatus', $aanvraag->id) }}" method="POST">
                                @csrf
                                <select name="status">
                                    <option value="">Selecteer status</option>
                                    <option value="1">Goedgekeurd</option>
                                    <option value="0">Weigeren</option>
                                </select>
                                <button type="submit">Bijwerken</button>
                            </form>
                        @else
                            {{ $aanvraag->status == 1 ? 'Goedgekeurd' : 'Weigeren' }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
