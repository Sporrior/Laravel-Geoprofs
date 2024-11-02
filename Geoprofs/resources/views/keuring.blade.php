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
            height: 100vh;
            overflow: hidden;
            display: flex;
        }

        .container-admin {
            display: flex;
            width: 100%;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            margin-top: -20px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            max-height: 100vh;
            box-sizing: border-box;
        }


        .success-message {
            background-color: #4caf50;
            color: #fff;
            padding: 15px 20px;
            border-radius: 5px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        thead th {
            background-color: #ff8c00;
            color: #fff;
            padding: 14px;
            text-align: left;
            font-size: 16px;
            font-weight: bold;
        }

        tbody tr {
            transition: background-color 0.3s ease;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        td,
        th {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        form {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }

        select {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        select:focus {
            border-color: #007bff;
        }

        button {
            padding: 8px 14px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:active {
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
                                            <option value="">status</option>
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