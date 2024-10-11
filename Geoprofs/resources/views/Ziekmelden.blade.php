<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ziekmelden</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="{{ asset('css/ziekmelden.css') }}">

</head>
<body>

<div class="container">
    <h1>Ziekmelden</h1>

    @if(session('success'))
        <p class="success-message">{{ session('success') }}</p>
    @endif

    <form action="{{ route('ziekmelden.store') }}" method="POST" class="sick-form">
        @csrf

        <input type="hidden" name="verlof_reden" value="ziek">

        <p class="info-text">U meldt zich ziek voor 1 dag.</p>

        <button type="submit" class="submit-button">Ziekmelden</button>
    </form>
</div>

</body>
</html>
