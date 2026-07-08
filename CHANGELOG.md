# Changelog

All notable changes to [Booking CMS](https://github.com/Ruslan-Bilohash/booking) are documented here.

Format based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).  
Version dates match `includes/version.php`.

## [1.3.0] — 2026-07-08

[Release](https://github.com/Ruslan-Bilohash/booking/releases/tag/v1.3.0)

### Added
- **30-day demo install package** (`booking.zip`) for client self-hosting — one domain per BILOHASH plan
- **`install.php`** — MySQL setup wizard with demo data, admin account and optional **BHBOOK** license activation
- **`site/demo-install.php`** — product page for self-host demo (NO, EN, UA, RU, SV, LT)
- **License runtime** — trial watermark, BHBOOK key verification via bilohash.com API
- **Customer cabinet download** — gated at [bilohash.com/ecosystem/cabinet.php](https://bilohash.com/ecosystem/cabinet.php)
- `includes/demo-package.php`, `includes/license-runtime.php`, `api/demo-download.php`
- Build scripts: `build-install.ps1`, `pack-demo-install.ps1`, `create-release.ps1`
- MySQL schema (`schema.sql`), `migrate-to-mysql.php`, `includes/mysql-migrate.php`

### Changed
- Product site version badge and changelog block synced with `BK_VERSION` 1.3.0
- README (EN, NO, UA, RU) — commercial install path and ecosystem links

## [1.2.0] — 2026-07-05

### Added
- Full GitHub project upload — demo frontend, admin panel, marketing site (`/site/`)
- Guest reviews with moderation queue and public review form (reCAPTCHA)
- Property amenities tab, location block (OpenStreetMap + Google Maps directions)
- Local admin settings: appearance, footer, reCAPTCHA v2, AI chat (Grok / OpenAI)
- Admin instructions in 4 languages (NO, EN, UA, RU)
- Mobile burger menu, language dropdown fixes, `order.php` i18n
- SEO vertical landing pages (`vertical.php`, `solutions.php`)
- Product site screenshots gallery, version changelog on `/site/`
- `prompt/` handoff docs for AI continuation

### Changed
- i18n: NO, EN, UA, RU (+ LT, SV overlays on product site)
- PageSpeed and Open Graph improvements on product pages

## [1.1.0] — 2026-07-02

### Added
- CMS settings integration in admin
- Contact form page
- SEO verticals foundation

### Changed
- Mobile performance improvements

## [1.0.0] — 2026-06-20

### Added
- Initial public release
- Booking.com-style demo frontend (search, property detail, booking form)
- Admin dashboard: properties, bookings, basic settings
- Multilingual UI (NO, EN, UA, RU)
- JSON storage with 10 seeded demo properties
- Apache `.htaccess`, sitemap, Schema.org basics

[1.3.0]: https://github.com/Ruslan-Bilohash/booking/releases/tag/v1.3.0
[1.2.0]: https://github.com/Ruslan-Bilohash/booking/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/Ruslan-Bilohash/booking/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/Ruslan-Bilohash/booking/releases/tag/v1.0.0