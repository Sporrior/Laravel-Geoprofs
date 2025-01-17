    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verlof Data Export</title>
    </head>

    <body>
        <a href="{{ route('verlofdata.export', request()->query()) }}" class="export-button">Exporteren naar Excel</a>
    </body>
