# Booking CMS

Універсальний **PHP-скрипт бронювання** для будь-якого бізнесу з резерваціями — готелі, оренда житла, лікарі та стоматологи, салони краси, SPA, фітнес і інше. Постачається як демо у стилі Booking.com; налаштуйте підписи, типи оголошень і робочі процеси під свою нішу. Багатомовний фронтенд, пошук, демо-бронювання, відгуки гостей і адмін-панель. Портфоліо-проєкт [Руслана Білогаша](https://bilohash.com/).

**Версія:** 1.2.0 · **Мови:** [English](README.md) · [Norsk](README-no.md) · [Українська](README-uk.md) · [Русский](README-ru.md)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/badge/version-1.2.0-blue)
![i18n](https://img.shields.io/badge/languages-NO%20%7C%20EN%20%7C%20UA%20%7C%20RU-green)

## Live demo

| Ресурс | URL |
|--------|-----|
| **Демо фронтенд** | https://bilohash.com/booking/ |
| **Адмін-панель** | https://bilohash.com/booking/admin/ |
| **Сторінка продукту** | https://bilohash.com/booking/site/ |
| **Замовити сайт** | https://bilohash.com/booking/order.php |
| **Рішення** | https://bilohash.com/booking/solutions.php |
| **Sitemap** | https://bilohash.com/booking/sitemap.php |
| **Новина про реліз** | https://bilohash.com/news/booking-cms.html |

**Вхід в адмінку (демо):** `demo` / `bilobook2026`

## Можливості

### Публічний фронтенд
- Головна з пошуком, популярними напрямками та акціями
- Результати пошуку з фільтрами (тип, ціна, сортування)
- Сторінка об'єкта: огляд, зручності, відгуки гостей
- **Розташування** — GPS-координати, карта OpenStreetMap, кнопка «Почати поїздку» (маршрут Google Maps)
- Демо-форма бронювання (дані гостя → зберігаються в JSON)
- Публічна форма відгуку з reCAPTCHA та чергою модерації
- Контакти та сторінка замовлення індивідуальної розробки
- SEO-вертикалі (готелі, оренда, клініки, салони, SPA, фітнес, обладнання)
- Адаптивна верстка з мобільним burger-меню
- Мови: **норвезька** (за замовчуванням), **англійська**, **українська**, **російська** (`?lang=` + cookie)

### Адмін-панель
- Дашборд зі статистикою (об'єкти, бронювання, виручка)
- Список і редагування об'єктів (ціна, знижка %, рейтинг, назви, координати, активний/прихований)
- Керування бронюваннями (очікує / підтверджено / скасовано)
- Модерація відгуків гостей (схвалити, приховати, видалити, додати)
- **Налаштування:** зовнішній вигляд (кольори, футер), reCAPTCHA v2, AI-чат (Grok / OpenAI)
- Багатомовна адмінка (NO, EN, UA, RU)
- Бічне меню з мобільним drawer

### Маркетинговий сайт (`/site/`)
- Лендінг продукту Booking CMS
- Мови: NO, EN, UA, RU, LT
- Галерея скріншотів, версія та технічний стек

## Технології

- PHP 8+ (без фреймворку)
- JSON-сховище (`data/*.json`) — MySQL за запитом
- Модульна i18n (`lang/*.php`)
- Apache `.htaccess`, SEO (canonical, hreflang, Schema.org, sitemap)
- OpenStreetMap embed + маршрути Google Maps (без API-ключа)
- Font Awesome 6, чистий CSS і JS

## Вимоги

- PHP 8.0 або новіший
- Apache з `mod_rewrite` (або аналог для nginx)
- Запис у каталог `data/`

## Встановлення

1. Клонуйте або скопіюйте папку `booking/` у корінь сайту:
   ```bash
   git clone https://github.com/Ruslan-Bilohash/booking.git booking
   ```
2. Переконайтеся, що веб-сервер віддає `/booking/`.
3. Надайте права на запис для `data/`:
   ```bash
   chmod 755 data
   ```
4. Відкрийте `https://ваш-домен.com/booking/` — демо-об'єкти створюються автоматично при першому завантаженні.

### Локальний PHP-сервер (розробка)

```bash
cd booking
php -S localhost:8080
```

Відкрийте http://localhost:8080/

### Конфігурація

Відредагуйте `config.php`:

```php
define('BK_BASE_PATH', '/booking');  // URL-шлях
define('BK_SITE_NAME', 'Booking CMS');
define('BK_CURRENCY', 'NOK');
define('BK_DEMO_MODE', true);
```

Змініть облікові дані адміна в `includes/admin-auth.php` перед продакшеном.

## Структура проєкту

```
booking/
├── index.php              # Головна
├── search.php             # Результати пошуку
├── property.php           # Сторінка об'єкта + карта
├── book.php               # Форма бронювання
├── contact.php            # Контакти
├── order.php              # Замовити сайт
├── solutions.php          # Хаб рішень
├── vertical.php           # SEO-вертикалі
├── config.php             # Основний конфіг
├── init.php               # Bootstrap
├── lang/                  # Переклади NO, EN, UK, RU
├── includes/              # Header, footer, i18n, storage, SEO
├── assets/css|js/         # Стилі та скрипти фронтенду
├── data/
│   ├── properties.php     # Seed-дані (10 демо-об'єктів)
│   ├── properties.json    # Runtime (авто)
│   ├── bookings.json      # Бронювання (авто)
│   ├── reviews.json       # Відгуки (авто)
│   └── settings.json      # Налаштування адмінки (авто)
├── admin/                 # Адмін-панель
├── site/                  # Маркетинговий лендінг
├── screen/                # Скріншоти UI (SVG)
├── sitemap.php / sitemap.xml
└── robots.txt
```

## Скріншоти

| Екран | Файл |
|-------|------|
| Головна | `screen/home.svg` |
| Пошук | `screen/search.svg` |
| Об'єкт | `screen/property.svg` |
| Бронювання | `screen/book.svg` |
| Адмін-дашборд | `screen/admin-dashboard.svg` |
| Бронювання в адмінці | `screen/admin-bookings.svg` |
| Відгуки в адмінці | `screen/admin-reviews.svg` |
| Рішення | `screen/solutions.svg` |

## Демо-режим

Це **демо / портфоліо**-проєкт:
- Жовта смуга: *«Демо-сайт у розробці»*
- Без реальних платежів
- Бронювання зберігаються в JSON-файлах
- Зображення з Unsplash (з локальним placeholder)

## Автор

**Руслан Білогаш**
- Сайт: https://bilohash.com/
- GitHub: https://github.com/Ruslan-Bilohash/
- Email: rbilohash@gmail.com

## Ліцензія

Демо та портфоліо. Звертайтеся до автора для комерційної ліцензії або індивідуального розгортання.