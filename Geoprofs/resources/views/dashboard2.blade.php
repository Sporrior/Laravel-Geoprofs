<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerkerbeheer</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard2.css') }}">
</head>

<body>
    <div class="container-vloeistof">
        <div class="rij">
            <div class="kolom-zijbalk">
                <div class="profiel-sectie">
                    <h4>Hallo, {{ $user->voornaam }}</h4>
                    <p>{{ $user->role->roleName }}</p>
                </div>
                <nav>
                    <a class="navigatie-link actief" href="#">HR Administratie</a>
                    <a class="navigatie-link" href="#">Verlof</a>
                    <a class="navigatie-link" href="#">Verlofkalender</a>
                    <a class="navigatie-link" href="#">Settings</a>
                </nav>
            </div>

            <div class="kolom">
                <div class="rij">
                    <div class="kolom">
                        <div class="kaart">
                            <div class="kaart-kop">Snelle Toegang</div>
                            <div class="quick-access-grid">
                                <div class="quick-access-item">
                                    <a href="#">
                                        <i class="ph-bold ph-calendar"></i>
                                        <p>Agenda</p>
                                    </a>
                                </div>
                                <div class="quick-access-item">
                                    <a href="#">
                                        <i class="ph-bold ph-user-plus"></i>
                                        <p>Verlof Toewijzen</p>
                                    </a>
                                </div>
                                <div class="quick-access-item">
                                    <a href="#">
                                        <i class="ph-bold ph-calendar-blank"></i>
                                        <p>Verlofkalendar</p>
                                    </a>
                                </div>
                                <div class="quick-access-item">
                                    <a href="#">
                                        <i class="ph ph-user-focus"></i>
                                        <p>Mijn Verlof</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="rij">
                            <div class="kolom">
                                <div class="kaart">
                                    <div class="kaart-kop">Laatste Documenten</div>
                                    <div class="kaart-body">
                                        <ul class="lijst-ongedecoreerd">
                                            <li><a href="#">Gezondheids- en Veiligheidsrichtlijnen</a></li>
                                            <li><a href="#">Ziektekostenverzekering in de VS</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="kolom">
                                <div class="kaart">
                                    <div class="kaart-kop">Laatste Nieuws</div>
                                    <div class="kaart-body">
                                        <ul class="lijst-ongedecoreerd">
                                            <li><a href="#">ISO 20071 Certificering</a></li>
                                            <li><a href="#">opvulling</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="kolom">
                        <div class="kaart">
                            <div class="kaart-kop">Mijn Acties</div>
                            <div class="kaart-body">
                                <p><strong>(3)</strong> Verlofaanvragen Goedkeuren</p>
                                <p><strong>(7)</strong> Presentiebladen Goedkeuren</p>
                            </div>
                        </div>

                        <div class="kaart">
                            <div class="kaart-kop">Buzz Laatste Berichten</div>
                            <div class="kaart-body">
                                <div class="bericht">
                                    <p><strong>Brody Alan</strong></p>
                                    <p>Ondersteuningsteam Herstructurering</p>
                                    <img src="buzz_afbeelding_url" alt="Buzz Afbeelding">
                                </div>
                                <div class="bericht">
                                    <p><strong>Andrew Keller</strong></p>
                                    <p>Uitbreiding Ondersteuningsteam</p>
                                </div>
                            </div>
                        </div>

                        <div class="kaart">
                            <div class="kaart-kop">Werknemers Vandaag met Verlof</div>
                            <div class="kaart-body">
                                <ul class="lijst-ongedecoreerd">
                                    <li>Aaliyah Haq (FMLA - VS)</li>
                                    <li>Cec Bonaparte (Ouderschapsverlof - VK)</li>
                                    <li>Fiona Grace (FMLA - VS)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://unpkg.com/phosphor-icons"></script>

</html>