<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
</head>
<body>

    <h1>Create New User</h1>

    <!-- Success Message -->
     @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <!-- Display validation errors -->
    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('addusers.store') }}" method="POST">
        @csrf
        <label for="voornaam">Voornaam:</label>
        <input type="text" id="voornaam" name="voornaam" required>

        <label for="tussennaam">Tussennaam:</label>
        <input type="text" id="tussennaam" name="tussennaam">

        <label for="achternaam">Achternaam:</label>
        <input type="text" id="achternaam" name="achternaam" required>

        <label for="telefoon">Telefoon:</label>
        <input type="text" id="telefoon" name="telefoon" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Wachtwoord:</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirmation">Bevestig Wachtwoord:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <button type="submit">Register</button>
    </form>

</body>
</html>
