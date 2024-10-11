<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ziekmelden</title>
</head>
<body>

<h1>Ziekmelden</h1>

<!-- Success Message -->
@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<!-- Ziekmelding Form -->
<form action="{{ route('ziekmelden.store') }}" method="POST">
    @csrf

    <!-- Fixed reason input -->
    <input type="hidden" name="verlof_reden" value="ziek">

    <p>U meldt zich ziek voor 1 dag.</p>

    <button type="submit">Ziekmelden</button>
</form>

</body>
</html>
