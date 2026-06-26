# Booking CMS

Universal **PHP booking platform** — hotels, apartments and cabins. Booking.com-inspired UI, multilingual frontend, property search, demo booking flow and admin panel. Portfolio project by [Ruslan Bilohash](https://bilohash.com/).

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![License](https://img.shields.io/badge/license-Demo%20%2F%20Portfolio-blue)
![i18n](https://img.shields.io/badge/languages-NO%20%7C%20EN%20%7C%20UA-green)

## Live demo

| Resource | URL |
|----------|-----|
| **Frontend demo** | https://bilohash.com/booking/ |
| **Admin panel** | https://bilohash.com/booking/admin/ |
| **Product page** | https://bilohash.com/booking/site/ |
| **Launch news** | https://bilohash.com/news/booking-cms.html |

**Admin login (demo):** `demo` / `bilobook2026`

## Features

### Public frontend
- Homepage with search hero, trending destinations and deals
- Search results with filters (type, price, sort)
- Property detail pages with rating, amenities and booking summary
- Demo booking form (guest details → saved to JSON)
- Responsive layout (mobile, tablet, desktop)
- Languages: **Norwegian** (default), **English**, **Ukrainian** (`?lang=` + cookie)

### Admin panel
- Dashboard with stats (properties, bookings, revenue)
- Property list and edit (price, deal %, rating, names, active/hidden)
- Booking management (pending / confirmed / cancelled)
- Sidebar navigation with mobile drawer

### Marketing site (`/site/`)
- Product landing for Booking CMS
- 4 languages: NO, EN, UA, LT
- Screenshots gallery and tech stack

## Tech stack

- PHP 8+ (no framework)
- JSON storage (`data/properties.json`, `data/bookings.json`)
- Modular i18n (`lang/*.php`)
- Apache `.htaccess`, SEO (canonical, hreflang, sitemap)
- Font Awesome 6, vanilla CSS & JS

## Requirements

- PHP 8.0 or newer
- Apache with `mod_rewrite` (or nginx equivalent)
- Writable `data/` directory

## Installation

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
├── property.php           # Property detail
├── book.php               # Booking form
├── config.php             # Main config
├── init.php               # Bootstrap
├── lang/                  # NO, EN, UK translations
├── includes/              # Header, footer, i18n, storage
├── assets/css|js/         # Frontend styles & scripts
├── data/
│   ├── properties.php     # Seed data (10 demo properties)
│   ├── properties.json    # Runtime storage (auto-created)
│   └── bookings.json      # Bookings (auto-created)
├── admin/                 # Admin panel
│   ├── login.php
│   ├── index.php          # Dashboard
│   ├── properties.php
│   ├── property.php
│   └── bookings.php
├── site/                  # Marketing landing (4 langs)
├── screen/                # UI screenshots (SVG)
├── sitemap.xml
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

## License

Demo and portfolio use. Contact the author for commercial licensing or custom deployment.
