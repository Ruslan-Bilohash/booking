# Промпт для AI — продовжити Booking CMS

Скопіюй блок нижче в новий чат (Cursor / Grok / інший агент).

---

Ти працюєш над **Booking CMS** — PHP-скрипт онлайн-бронювання від Bilohash (портфоліо Ruslan Bilohash).

## Контекст проєкту

- **Робоча директорія:** `C:\bilohash\booking\`
- **Деплой mirror:** `C:\BILOHASH.COM\booking\` (копіювати змінені файли після правок)
- **Live:**
  - Demo: https://bilohash.com/booking/
  - Product site: https://bilohash.com/booking/site/
  - Admin: https://bilohash.com/booking/admin/ (demo / bilobook2026)
- **Версія:** v1.2.0 (`includes/version.php`)
- **Документація handoff:** `booking/prompt/*.md` — читай перед змінами

## Два «сайти» в одній папці

1. **`/booking/`** — live demo (готелі, пошук, бронювання). Стилі `assets/css/style.css?v=43`, JS `assets/js/main.js`. Шапка: жовта demo-strip + синій header, бургер `#bkMenuBtn`, мова mobile `#bkLangDropdownMobile`.

2. **`/booking/site/`** — маркетинговий product site (лендінг, order, contact). Префікс класів `bks-`. CSS `site/assets/css/site.css?v=22`, JS `site/assets/js/site.js?v=8` (defer у `<head>` через `site/includes/seo.php`). Бургер `#bksMenuBtn`, панель `#bksMobilePanel` **всередині** `<header>`.

## Мови

| Зона | Коди | Файли |
|------|------|-------|
| Demo + admin | no (default), en, uk, ru, sv | `booking/lang/*.php` |
| Product site | no, en, uk, ru, sv, lt | `booking/site/lang/*.php` |
| Ecosystem (спільно) | merge через `includes/ecosystem-i18n.php` | NO/EN/UK/RU/LT |

Плейсхолдери product site: `{origin}`, `{in_country}`, `{for_country}`, `{currency}`, `{country}` — підставляються в `site/includes/market.php` + `bks_apply_market_translations()`.

## Правила розробки

- Виконуй зміни сам (не лише інструкції користувачу).
- Після правок CSS/JS — **bump версії** (`bk_public_style_version`, `bks_site_style_version`, `bks_site_script_version`).
- Деплой на mirror: копіювати змінені файли в `C:\BILOHASH.COM\booking\`.
- Не рефакторити поза scope запиту.
- Product site mobile: бургер = Демо фронтенд, Адмін, Замовити розробку + `<details>` ecosystem (`site/includes/ecosystem-mobile-block.php`).
- Мобільний прапор мови: кнопка 44×44, тільки emoji по центру, без шеврона; `pointer-events: none` на іконках всередині button.

## Ключові файли

```
booking/includes/header.php          — demo шапка
booking/includes/lang-dropdown.php   — variant: header | mobile | strip
booking/assets/css/style.css         — demo стилі (v43)
booking/site/includes/header.php     — product шапка + mobile panel
booking/site/order.php               — сторінка замовлення
booking/site/lang/                   — переклади product site
includes/ecosystem-i18n.php          — інші продукти Bilohash
includes/cms-contact.php             — контакт / nav_discuss
```

## Типові задачі

- Новий переклад → `site/lang/{code}.php`, ключі як у `en.php`
- Нова секція product site → `site/index.php` + `site/lang/*` + `site.css`
- Фікс мобільної шапки → `site-critical.css` + `site.css` + `site.js`
- Фікс demo header → `critical.css` + `style.css`

Перед відповіддю прочитай релевантні файли з `booking/prompt/`.

---