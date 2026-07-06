# Стан проєкту Booking CMS

## Версії та кеш-бастинг

| Компонент | Функція / файл | Поточна v |
|-----------|----------------|-----------|
| Demo CSS | `includes/site-settings.php` → `bk_public_style_version()` | **43** |
| Demo critical | `assets/css/critical.css` (inline у head) | без query |
| Product CSS | `site/includes/seo.php` → `bks_site_style_version()` | **22** |
| Product JS | `site/includes/seo.php` → `bks_site_script_version()` | **8** (defer у head) |
| Product critical | `site/assets/css/site-critical.css` | inline |
| Admin CSS | `assets/css/admin.css` | окремий bump у layout |
| Скрипт продукту | `BK_VERSION` у `includes/version.php` | **1.2.0** |

## URL-и (production)

| Сторінка | URL |
|----------|-----|
| Demo home | https://bilohash.com/booking/index.php |
| Product home | https://bilohash.com/booking/site/index.php |
| Order (product) | https://bilohash.com/booking/site/order.php |
| Order (demo) | https://bilohash.com/booking/order.php |
| Contact (product) | https://bilohash.com/booking/site/contact.php |
| Solutions | https://bilohash.com/booking/solutions.php |
| Admin | https://bilohash.com/booking/admin/login.php |

## Що зроблено (остання сесія)

### Product site `/site/`

1. **order.php переклади 100%** — усі 6 мов (en, no, uk, ru, sv, lt). LT раніше не мав блоку `order`.
2. **Бургер-меню** перероблено:
   - Пункти: Демо фронтенд, Адмін, Замовити розробку (з іконками)
   - Спойлер «Інші продукти Bilohash CMS» — `ecosystem-mobile-block.php`
   - Панель перенесена **всередину** `<header>` (фікс «не працює»)
   - Overlay залишився поза header
3. **Мова mobile** — прапор 44×44 по центру, шеврон прихований на `<1199px`
4. **footer.php** — виправлено шлях ecosystem: `dirname(__DIR__, 2) . '/includes/ecosystem-footer-block.php'`
5. **site.js** — defer у head; закриття `<details>` при close nav

### Demo `/booking/`

1. **Мобільний прапор** — вирівняно з бургером 44×44, emoji font-family, оновлена розмітка `lang-dropdown.php`
2. **Dropdown позиція** — `top` з урахуванням demo-strip (`--bk-demo-strip-h` + `--bk-header-bar-h`)

## Відомі моменти / не закрито

- README каже 4 мови на demo, фактично **6** (додано sv, lt на product; demo має sv у lang)
- Деплой на сервер — лише mirror `C:\BILOHASH.COM\`; FTP/хостинг окремо
- WordPress plugin copy: `wordpress/wp-content/plugins/bilohash-booking/` — може відставати від `bilohash/booking/`
- PC-шапка product site **без** nav-якорів (Funkcijos, Screens…) — лише в бургері на mobile; desktop — кнопки demo/admin/order/contact

## Демо-дані

- `data/properties.json`, `data/bookings.json`
- 10 demo properties, admin: `demo` / `bilobook2026`

## Контакт / CMS

- `includes/cms-contact.php` — форми, `nav_discuss` по мовах
- Product contact: `site/contact.php` + cms-contact styles