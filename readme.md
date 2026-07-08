# Booking CMS

Universal **PHP booking script** for any reservation business — hotels, apartment rentals, doctor & dentist appointments, beauty salons, spas, fitness and more. Ships as a Booking.com-style hotel demo; customize labels, listing types and workflows for your niche. Multilingual frontend, search, demo booking flow, guest reviews and admin panel. Portfolio project by [Ruslan Bilohash](https://bilohash.com/).

**Version:** 1.3.0 · **Languages:** [English](README.md) · [Norsk](README-no.md) · [Українська](README-uk.md) · [Русский](README-ru.md)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/badge/version-1.3.0-blue)
![i18n](https://img.shields.io/badge/languages-NO%20%7C%20EN%20%7C%20UA%20%7C%20RU-green)
![Release](https://img.shields.io/github/v/release/Ruslan-Bilohash/booking?label=release)

## Live demo

| Resource | URL |
|----------|-----|
| **Frontend demo** | https://bilohash.com/booking/ |
| **Admin panel** | https://bilohash.com/booking/admin/ |
| **Product page** | https://bilohash.com/booking/site/ |
| **Order / contact** | https://bilohash.com/booking/order.php |
| **Solutions hub** | https://bilohash.com/booking/solutions.php |
| **Sitemap** | https://bilohash.com/booking/sitemap.php |
| **Launch news** | https://bilohash.com/news/booking-cms.html |
| **30-day demo install** | https://bilohash.com/booking/site/demo-install.php |
| **Download (cabinet)** | https://bilohash.com/ecosystem/cabinet.php |
| **Join ecosystem** | https://bilohash.com/ecosystem/join.php |
| **GitHub release** | https://github.com/Ruslan-Bilohash/booking/releases/tag/v1.3.0 |

**Admin login (demo):** `demo` / `bilobook2026`

## Features

### Public frontend
- Homepage with search hero, trending destinations and deals
- Search results with filters (type, price, sort)
- Property detail: overview, amenities, guest reviews tabs
- **Location block** — GPS coordinates, OpenStreetMap embed, “Start trip” (Google Maps directions)
- Demo booking form (guest details → saved to JSON)
- Public guest review form with reCAPTCHA and moderation queue
- Contact page and order page for custom development
- SEO vertical landing pages (hotels, rentals, clinics, salons, spa, fitness, equipment)
- Responsive layout with mobile burger menu
- Languages: **Norwegian** (default), **English**, **Ukrainian**, **Russian** (`?lang=` + cookie)

### Admin panel
- Dashboard with stats (properties, bookings, revenue)
- Property list and edit (price, deal %, rating, names, coordinates, active/hidden)
- Booking management (pending / confirmed / cancelled)
- Guest review moderation (approve, hide, delete, add)
- **Settings:** appearance (colours, footer), reCAPTCHA v2, AI chat widget (Grok / OpenAI)
- Multilingual admin UI (NO, EN, UA, RU)
- Sidebar navigation with mobile drawer

### Marketing site (`/site/`)
- Product landing for Booking CMS
- **`demo-install.php`** — 30-day self-host demo page (NO, EN, UA, RU, SV, LT)
- Languages: NO, EN, UA, RU, LT, SV
- Screenshots gallery, version info and tech stack

### Commercial install package (v1.3.0)
- **`booking.zip`** — 30-day trial archive for client hosting (1 domain per BILOHASH plan)
- **`install.php`** — MySQL setup wizard with demo data, admin account and optional **BHBOOK** license key
- Download via [customer cabinet](https://bilohash.com/ecosystem/cabinet.php) after sign-in and terms acceptance
- License runtime: trial watermark, **BHBOOK** verification via bilohash.com API

## Tech stack

- PHP 8+ (no framework)
- JSON storage (`data/*.json`) for bilohash.com demo · **MySQL** for commercial install (`schema.sql`, `install.php`)
- Modular i18n (`lang/*.php`)
- Apache `.htaccess`, SEO (canonical, hreflang, Schema.org, sitemap)
- OpenStreetMap embed + Google Maps directions (no API key)
- Font Awesome 6, vanilla CSS & JS

## Requirements

- PHP 8.0 or newer
- Apache with `mod_rewrite` (or nginx equivalent)
- Writable `data/` directory

## Installation

### Option A — Git clone (development / JSON demo)

1. Clone or copy the `booking/` folder to your web root:
   ```bash
   git clone https://github.com/Ruslan-Bilohash/booking.git booking
   ```
2. Ensure the web server document root can serve `/booking/`.
3. Set write permissions on `data/`:
   ```bash
   chmod 755 data
   ```
4. Open `https://your-domain.com/booking/` — demo properties are seeded automatically on first load.

### Option B — 30-day commercial demo (MySQL)

1. Subscribe at [bilohash.com/ecosystem/join.php](https://bilohash.com/ecosystem/join.php) and download **`booking.zip`** from the [customer cabinet](https://bilohash.com/ecosystem/cabinet.php).
2. Upload and extract to `/public_html/booking/` on your hosting.
3. Open `https://your-domain.com/booking/install.php` and complete the MySQL wizard (demo data + admin).
4. Optionally enter a **BHBOOK** license key during install or in admin later.

Pre-built archive: [GitHub release v1.3.0](https://github.com/Ruslan-Bilohash/booking/releases/tag/v1.3.0) (`booking-install-v1.3.0.zip`).

### Local PHP built-in server (development)

```bash
cd booking
php -S localhost:8080
```

Open http://localhost:8080/

### Configuration

Edit `config.php`:

```php
define('BK_BASE_PATH', '/booking');  // URL path
define('BK_SITE_NAME', 'Booking CMS');
define('BK_CURRENCY', 'NOK');
define('BK_DEMO_MODE', true);
```

Change admin credentials in `includes/admin-auth.php` before production use.

## Project structure

```
booking/
├── index.php              # Homepage
├── search.php             # Search results
├── property.php           # Property detail + map
├── book.php               # Booking form
├── contact.php            # Contact form
├── order.php              # Order custom website
├── solutions.php          # Solutions hub
├── vertical.php           # SEO vertical pages
├── install.php            # MySQL install wizard (commercial demo)
├── migrate-to-mysql.php   # JSON → MySQL migration helper
├── schema.sql             # MySQL schema
├── config.php             # Main config
├── init.php               # Bootstrap
├── lang/                  # NO, EN, UK, RU translations
├── includes/              # Header, footer, i18n, storage, SEO
├── assets/css|js/         # Frontend styles & scripts
├── data/
│   ├── properties.php     # Seed data (10 demo properties)
│   ├── properties.json    # Runtime storage (auto-created)
│   ├── bookings.json      # Bookings (auto-created)
│   ├── reviews.json       # Guest reviews (auto-created)
│   └── settings.json      # Admin settings (auto-created)
├── admin/                 # Admin panel
│   ├── login.php
│   ├── index.php          # Dashboard
│   ├── properties.php
│   ├── property.php
│   ├── bookings.php
│   ├── reviews.php
│   └── settings-*.php     # Appearance, reCAPTCHA, chat
├── site/                  # Marketing landing (+ demo-install.php)
├── includes/demo-package.php, license-runtime.php
├── screen/                # UI screenshots (SVG)
├── sitemap.php / sitemap.xml
└── robots.txt
```

## Screenshots

| Screen | File |
|--------|------|
| Homepage | `screen/home.svg` |
| Search | `screen/search.svg` |
| Property | `screen/property.svg` |
| Booking form | `screen/book.svg` |
| Admin dashboard | `screen/admin-dashboard.svg` |
| Admin bookings | `screen/admin-bookings.svg` |
| Admin reviews | `screen/admin-reviews.svg` |
| Solutions | `screen/solutions.svg` |

## Demo mode

This is a **demo / portfolio** project:
- Yellow strip: *“Demo site under development”*
- No real payments
- Bookings stored in JSON files
- Sample images from Unsplash (with local placeholder fallback)

## Author

**Ruslan Bilohash**
- Website: https://bilohash.com/
- GitHub: https://github.com/Ruslan-Bilohash/
- Email: rbilohash@gmail.com

## Changelog

| Version | Date | Highlights |
|---------|------|------------|
| **1.3.0** | 2026-07-08 | 30-day demo install package, `install.php` MySQL wizard, BHBOOK license, `demo-install.php` (6 langs) |
| 1.2.0 | 2026-07-05 | Full project upload, product site, mobile nav, order i18n |
| 1.1.0 | 2026-07-02 | Admin settings, SEO verticals |
| 1.0.0 | 2026-06-20 | Initial Booking CMS demo |

## License

Demo and portfolio use on bilohash.com. Commercial deployment via BILOHASH ecosystem subscription — contact the author or use [join](https://bilohash.com/ecosystem/join.php).
