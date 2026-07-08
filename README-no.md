# Booking CMS

Universelt **PHP-bestillingsskript** for alle typer reservasjoner — hoteller, leieboliger, legetimer og tannlege, skjønnhetssalonger, spa, trening og mer. Leveres som en Booking.com-lignende hotell-demo; tilpass etiketter, listetyper og arbeidsflyt til din bransje. Flerspråklig frontend, søk, demo-bestillingsflyt, gjesteanmeldelser og adminpanel. Porteføljeprosjekt av [Ruslan Bilohash](https://bilohash.com/).

**Versjon:** 1.3.0 · **Språk:** [English](README.md) · [Norsk](README-no.md) · [Українська](README-uk.md) · [Русский](README-ru.md)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/badge/version-1.3.0-blue)
![Release](https://img.shields.io/github/v/release/Ruslan-Bilohash/booking?label=release)
![i18n](https://img.shields.io/badge/languages-NO%20%7C%20EN%20%7C%20UA%20%7C%20RU-green)

## Live demo

| Ressurs | URL |
|---------|-----|
| **Frontend-demo** | https://bilohash.com/booking/ |
| **Adminpanel** | https://bilohash.com/booking/admin/ |
| **Produktside** | https://bilohash.com/booking/site/ |
| **Bestill / kontakt** | https://bilohash.com/booking/order.php |
| **Løsninger** | https://bilohash.com/booking/solutions.php |
| **Sitemap** | https://bilohash.com/booking/sitemap.php |
| **Lansering** | https://bilohash.com/news/booking-cms.html |
| **30-dagers demo-install** | https://bilohash.com/booking/site/demo-install.php |
| **Nedlasting (kundepanel)** | https://bilohash.com/ecosystem/cabinet.php |
| **Bli med i økosystemet** | https://bilohash.com/ecosystem/join.php |
| **GitHub-release** | https://github.com/Ruslan-Bilohash/booking/releases/tag/v1.3.0 |

**Admin-innlogging (demo):** `demo` / `bilobook2026`

## Funksjoner

### Offentlig frontend
- Hjemmeside med søkehero, populære destinasjoner og tilbud
- Søkeresultater med filtre (type, pris, sortering)
- Eiendomsdetaljer: oversikt, fasiliteter, gjesteanmeldelser
- **Beliggenhet** — GPS-koordinater, OpenStreetMap-kart, «Start reise» (Google Maps-rute)
- Demo-bestillingsskjema (gjesteinfo → lagres i JSON)
- Offentlig anmeldelsesskjema med reCAPTCHA og modereringskø
- Kontaktside og bestillingsside for skreddersydd utvikling
- SEO-vertikalsider (hotell, leie, klinikk, salong, spa, trening, utstyr)
- Responsivt design med mobil burgermeny
- Språk: **norsk** (standard), **engelsk**, **ukrainsk**, **russisk** (`?lang=` + informasjonskapsel)

### Adminpanel
- Dashboard med statistikk (eiendommer, bestillinger, omsetning)
- Eiendomsliste og redigering (pris, rabatt %, vurdering, navn, koordinater, aktiv/skjult)
- Bestillingshåndtering (venter / bekreftet / kansellert)
- Moderering av gjesteanmeldelser (godkjenn, skjul, slett, legg til)
- **Innstillinger:** utseende (farger, bunntekst), reCAPTCHA v2, AI-chat (Grok / OpenAI)
- Flerspråklig admin (NO, EN, UA, RU)
- Sidemeny med mobil skuff

### Produktside (`/site/`)
- Landingsside for Booking CMS
- **`demo-install.php`** — 30-dagers egenhosting-demo (NO, EN, UA, RU, SV, LT)
- Språk: NO, EN, UA, RU, LT, SV
- Skjermbilder, versjonsinfo og teknisk stack

### Kommersielt installasjonspakke (v1.3.0)
- **`booking.zip`** — 30-dagers prøvearkiv for kundens hosting (1 domene per BILOHASH-plan)
- **`install.php`** — MySQL-veiviser med demodata, admin og valgfri **BHBOOK**-lisensnøkkel
- Nedlasting via [kundepanel](https://bilohash.com/ecosystem/cabinet.php) etter innlogging og godkjente vilkår
- Lisens: prøveperiode, **BHBOOK**-verifisering via bilohash.com API

## Teknisk stack

- PHP 8+ (uten rammeverk)
- JSON-lagring (`data/*.json`) på bilohash.com-demo · **MySQL** for kommersiell installasjon (`schema.sql`, `install.php`)
- Modulær i18n (`lang/*.php`)
- Apache `.htaccess`, SEO (canonical, hreflang, Schema.org, sitemap)
- OpenStreetMap-innbygging + Google Maps-ruter (ingen API-nøkkel)
- Font Awesome 6, vanlig CSS og JS

## Krav

- PHP 8.0 eller nyere
- Apache med `mod_rewrite` (eller nginx-tilsvarende)
- Skrivbar `data/`-mappe

## Installasjon

### Alternativ A — Git-klon (utvikling / JSON-demo)

1. Klon eller kopier `booking/`-mappen til web-roten:
   ```bash
   git clone https://github.com/Ruslan-Bilohash/booking.git booking
   ```
2. Sørg for at webserveren kan serve `/booking/`.
3. Sett skriverettigheter på `data/`:
   ```bash
   chmod 755 data
   ```
4. Åpne `https://ditt-domene.no/booking/` — demo-eiendommer opprettes automatisk ved første lasting.

### Alternativ B — 30-dagers kommersiell demo (MySQL)

1. Abonner på [bilohash.com/ecosystem/join.php](https://bilohash.com/ecosystem/join.php) og last ned **`booking.zip`** fra [kundepanelet](https://bilohash.com/ecosystem/cabinet.php).
2. Last opp og pakk ut til `/public_html/booking/` på din hosting.
3. Åpne `https://ditt-domene.no/booking/install.php` og fullfør MySQL-veiviseren (demodata + admin).
4. Valgfritt: legg inn **BHBOOK**-lisensnøkkel under installasjon eller senere i admin.

Ferdigbygget arkiv: [GitHub release v1.3.0](https://github.com/Ruslan-Bilohash/booking/releases/tag/v1.3.0) (`booking-install-v1.3.0.zip`).

### Lokal PHP innebygd server (utvikling)

```bash
cd booking
php -S localhost:8080
```

Åpne http://localhost:8080/

### Konfigurasjon

Rediger `config.php`:

```php
define('BK_BASE_PATH', '/booking');  // URL-sti
define('BK_SITE_NAME', 'Booking CMS');
define('BK_CURRENCY', 'NOK');
define('BK_DEMO_MODE', true);
```

Endre admin-pålogging i `includes/admin-auth.php` før produksjonsbruk.

## Prosjektstruktur

```
booking/
├── index.php              # Hjemmeside
├── search.php             # Søkeresultater
├── property.php           # Eiendomsdetaljer + kart
├── book.php               # Bestillingsskjema
├── contact.php            # Kontaktskjema
├── order.php              # Bestill skreddersydd nettside
├── solutions.php          # Løsningshub
├── vertical.php           # SEO-vertikalsider
├── config.php             # Hovedkonfig
├── init.php               # Bootstrap
├── lang/                  # NO, EN, UK, RU oversettelser
├── includes/              # Header, footer, i18n, lagring, SEO
├── assets/css|js/         # Frontend-stiler og skript
├── data/
│   ├── properties.php     # Seed-data (10 demo-eiendommer)
│   ├── properties.json    # Runtime-lagring (auto)
│   ├── bookings.json      # Bestillinger (auto)
│   ├── reviews.json       # Gjesteanmeldelser (auto)
│   └── settings.json      # Admin-innstillinger (auto)
├── admin/                 # Adminpanel
├── site/                  # Markedsføringslanding
├── screen/                # UI-skjermbilder (SVG)
├── sitemap.php / sitemap.xml
└── robots.txt
```

## Skjermbilder

| Skjerm | Fil |
|--------|-----|
| Hjemmeside | `screen/home.svg` |
| Søk | `screen/search.svg` |
| Eiendom | `screen/property.svg` |
| Bestilling | `screen/book.svg` |
| Admin dashboard | `screen/admin-dashboard.svg` |
| Admin bestillinger | `screen/admin-bookings.svg` |
| Admin anmeldelser | `screen/admin-reviews.svg` |
| Løsninger | `screen/solutions.svg` |

## Demo-modus

Dette er et **demo / portefølje**-prosjekt:
- Gul stripe: *«Demoside under utvikling»*
- Ingen ekte betalinger
- Bestillinger lagres i JSON-filer
- Eksempelbilder fra Unsplash (med lokal reserve)

## Forfatter

**Ruslan Bilohash**
- Nettsted: https://bilohash.com/
- GitHub: https://github.com/Ruslan-Bilohash/
- E-post: rbilohash@gmail.com

## Lisens

Demo og porteføljebruk. Kontakt forfatteren for kommersiell lisens eller skreddersydd deploy.