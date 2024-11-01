<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuring Verlofaanvragen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
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
        }

        .success-message {
            color: #4caf50;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        thead th {
            background-color: #ff8c00;
            color: #fff;
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #fff;
        }

        td,
        th {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        select {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            padding: 6px 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container-admin">
        @include('includes.admin-menu')

        <div class="main-content">

            @if(session('success'))
                <p class="success-message">{{ session('success') }}</p>
            @endif

            <table>
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
                            <td>{{ optional($aanvraag->user)->voornaam }}</td>
                            <td>{{ $aanvraag->verlof_reden }}</td>
                            <td>{{ $aanvraag->start_datum }}</td>
                            <td>{{ $aanvraag->eind_datum }}</td>
                            <td>{{ optional($aanvraag->type)->type }}</td>
                            <td>
                                @if(is_null($aanvraag->status))
                                    <form action="{{ route('keuring.updateStatus', $aanvraag->id) }}" method="POST">
                                        @csrf
                                        <select name="status">
                                            <option value="">Selecteer status</option>
                                            <option value="1">Goedkeuren</option>
                                            <option value="0">Weigeren</option>
                                        </select>
                                        <button type="submit">Verstuur</button>
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