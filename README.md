# Aan de slag

## Installatie van Laravel

### Composer installeren
Indien Composer nog niet is geïnstalleerd, kun je deze downloaden via:

[https://getcomposer.org/](https://getcomposer.org/)

### Node.js installeren
Indien Node.js nog niet is geïnstalleerd, kun je deze downloaden via:

[https://nodejs.org/en](https://nodejs.org/en)

---

## Het project installeren

### Clone de repository
Clone de repository naar jouw lokale omgeving:

```bash
git clone https://github.com/Sporrior/Laravel-Geoprofs
```

### Ga naar de projectmap
Navigeer naar de map:

```bash
cd Laravel-Geoprofs
```

### Installeer Composer en npm

```bash
composer install
npm install
```

### Kopieer en configureer de `.env`-bestand
Kopieer het voorbeeldbestand en pas de configuratie aan waar nodig:

```bash
copy .env.example .env
```

### Genereer een applicatiesleutel

```bash
php artisan key:generate
```

### Voer de database-migraties uit

```bash
php artisan migrate
```

### Vul de database met standaardgegevens (seeds)

```bash
php artisan db:seed
```

### Bouw de afhankelijkheden en start de lokale ontwikkelserver

```bash
npm run build
npm run dev
```

---

## Alle commando's in één overzicht

```bash
git clone https://github.com/Sporrior/Laravel-Geoprofs
cd Laravel-Geoprofs
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan db:seed
npm run build
npm run dev
```

---

# Veelvoorkomende installatieproblemen

## Problemen met `php.ini`
Als je problemen ondervindt met `php.ini` en je gebruikt XAMPP:

1. Open XAMPP.
2. Klik naast **APACHE** op "Config" en selecteer "PHP (php.ini)".
3. Zoek de regel:

   ```ini
   ;extension=intl
   ```

4. Verwijder het puntkomma (`;`) zodat de regel wordt:

   ```ini
   extension=intl
   ```

5. Start Apache opnieuw.

## Problemen met de database

### Database vernieuwen

Als je een fout krijgt met betrekking tot de database, voer dan de volgende commando's uit:

```bash
php artisan migrate:refresh
php artisan db:seed
```

---

# Server starten

## Met XAMPP
1. Start XAMPP.
2. Start **Apache** en **MySQL**.

## Via de command-line

Start de PHP-server:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Start npm:

```bash
npm run dev
```

### Toegang tot de applicatie

Open in je browser:

[http://localhost:8000/](http://localhost:8000/)

---

# Inloggen

### Standaard inloggegevens

**E-mailadres:**

```text
ahmad@gmail.com
```

**Wachtwoord:**

```text
Ahmad
```

---

## Problemen met inloggen
Als je problemen ervaart met inloggen, voer dan de volgende commando's uit om de database te vernieuwen:

```bash
php artisan migrate --seed
```

---
