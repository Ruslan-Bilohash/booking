# Booking CMS

Универсальный **PHP-скрипт бронирования** для любого бизнеса с резервациями — отели, аренда жилья, врачи и стоматологи, салоны красоты, SPA, фитнес и другое. Поставляется как демо в стиле Booking.com; настройте подписи, типы объявлений и рабочие процессы под свою нишу. Многоязычный фронтенд, поиск, демо-бронирование, отзывы гостей и админ-панель. Портфолио-проект [Руслана Билогаша](https://bilohash.com/).

**Версия:** 1.2.0 · **Языки:** [English](README.md) · [Norsk](README-no.md) · [Українська](README-uk.md) · [Русский](README-ru.md)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/badge/version-1.2.0-blue)
![i18n](https://img.shields.io/badge/languages-NO%20%7C%20EN%20%7C%20UA%20%7C%20RU-green)

## Live demo

| Ресурс | URL |
|--------|-----|
| **Демо фронтенд** | https://bilohash.com/booking/ |
| **Админ-панель** | https://bilohash.com/booking/admin/ |
| **Страница продукта** | https://bilohash.com/booking/site/ |
| **Заказать сайт** | https://bilohash.com/booking/order.php |
| **Решения** | https://bilohash.com/booking/solutions.php |
| **Sitemap** | https://bilohash.com/booking/sitemap.php |
| **Новость о релизе** | https://bilohash.com/news/booking-cms.html |

**Вход в админку (демо):** `demo` / `bilobook2026`

## Возможности

### Публичный фронтенд
- Главная с поиском, популярными направлениями и акциями
- Результаты поиска с фильтрами (тип, цена, сортировка)
- Страница объекта: обзор, удобства, отзывы гостей
- **Расположение** — GPS-координаты, карта OpenStreetMap, кнопка «Начать поездку» (маршрут Google Maps)
- Демо-форма бронирования (данные гостя → сохраняются в JSON)
- Публичная форма отзыва с reCAPTCHA и очередью модерации
- Контакты и страница заказа индивидуальной разработки
- SEO-вертикали (отели, аренда, клиники, салоны, SPA, фитнес, оборудование)
- Адаптивная вёрстка с мобильным burger-меню
- Языки: **норвежский** (по умолчанию), **английский**, **украинский**, **русский** (`?lang=` + cookie)

### Админ-панель
- Дашборд со статистикой (объекты, бронирования, выручка)
- Список и редактирование объектов (цена, скидка %, рейтинг, названия, координаты, активный/скрытый)
- Управление бронированиями (ожидает / подтверждено / отменено)
- Модерация отзывов гостей (одобрить, скрыть, удалить, добавить)
- **Настройки:** внешний вид (цвета, футер), reCAPTCHA v2, AI-чат (Grok / OpenAI)
- Многоязычная админка (NO, EN, UA, RU)
- Боковое меню с мобильным drawer

### Маркетинговый сайт (`/site/`)
- Лендинг продукта Booking CMS
- Языки: NO, EN, UA, RU, LT
- Галерея скриншотов, версия и технический стек

## Технологии

- PHP 8+ (без фреймворка)
- JSON-хранилище (`data/*.json`) — MySQL по запросу
- Модульная i18n (`lang/*.php`)
- Apache `.htaccess`, SEO (canonical, hreflang, Schema.org, sitemap)
- OpenStreetMap embed + маршруты Google Maps (без API-ключа)
- Font Awesome 6, чистый CSS и JS

## Требования

- PHP 8.0 или новее
- Apache с `mod_rewrite` (или аналог для nginx)
- Запись в каталог `data/`

## Установка

1. Клонируйте или скопируйте папку `booking/` в корень сайта:
   ```bash
   git clone https://github.com/Ruslan-Bilohash/booking.git booking
   ```
2. Убедитесь, что веб-сервер отдаёт `/booking/`.
3. Выдайте права на запись для `data/`:
   ```bash
   chmod 755 data
   ```
4. Откройте `https://ваш-домен.com/booking/` — демо-объекты создаются автоматически при первой загрузке.

### Локальный PHP-сервер (разработка)

```bash
cd booking
php -S localhost:8080
```

Откройте http://localhost:8080/

### Конфигурация

Отредактируйте `config.php`:

```php
define('BK_BASE_PATH', '/booking');  // URL-путь
define('BK_SITE_NAME', 'Booking CMS');
define('BK_CURRENCY', 'NOK');
define('BK_DEMO_MODE', true);
```

Измените учётные данные админа в `includes/admin-auth.php` перед продакшеном.

## Структура проекта

```
booking/
├── index.php              # Главная
├── search.php             # Результаты поиска
├── property.php           # Страница объекта + карта
├── book.php               # Форма бронирования
├── contact.php            # Контакты
├── order.php              # Заказать сайт
├── solutions.php          # Хаб решений
├── vertical.php           # SEO-вертикали
├── config.php             # Основной конфиг
├── init.php               # Bootstrap
├── lang/                  # Переводы NO, EN, UK, RU
├── includes/              # Header, footer, i18n, storage, SEO
├── assets/css|js/         # Стили и скрипты фронтенда
├── data/
│   ├── properties.php     # Seed-данные (10 демо-объектов)
│   ├── properties.json    # Runtime (авто)
│   ├── bookings.json      # Бронирования (авто)
│   ├── reviews.json       # Отзывы (авто)
│   └── settings.json      # Настройки админки (авто)
├── admin/                 # Админ-панель
├── site/                  # Маркетинговый лендинг
├── screen/                # Скриншоты UI (SVG)
├── sitemap.php / sitemap.xml
└── robots.txt
```

## Скриншоты

| Экран | Файл |
|-------|------|
| Главная | `screen/home.svg` |
| Поиск | `screen/search.svg` |
| Объект | `screen/property.svg` |
| Бронирование | `screen/book.svg` |
| Админ-дашборд | `screen/admin-dashboard.svg` |
| Бронирования в админке | `screen/admin-bookings.svg` |
| Отзывы в админке | `screen/admin-reviews.svg` |
| Решения | `screen/solutions.svg` |

## Демо-режим

Это **демо / портфолио**-проект:
- Жёлтая полоса: *«Демо-сайт в разработке»*
- Без реальных платежей
- Бронирования сохраняются в JSON-файлах
- Изображения с Unsplash (с локальным placeholder)

## Автор

**Руслан Билогаш**
- Сайт: https://bilohash.com/
- GitHub: https://github.com/Ruslan-Bilohash/
- Email: rbilohash@gmail.com

## Лицензия

Демо и портфолио. Обращайтесь к автору для коммерческой лицензии или индивидуального развёртывания.