# i18n, market placeholders, order.php

## Product site — мови

Файли: `site/lang/{no,en,uk,ru,sv,lt}.php`

Завантаження: `site/includes/i18n.php`

```php
$t = require lang/{code}.php;
$t = bh_apply_ecosystem_translations($t, $lang, 'booking'); // booking виключений з eco items
$t = bks_apply_market_translations($t, $lang);
```

### Коди та cookie

- Cookie: `bks_lang`
- Default: `no` (без `?lang=` у URL для NO)
- Метадані: `$BKS_LANGS` у `i18n.php` (label, flag, locale, html)

### SV та LT

- **sv.php** — `array_replace_recursive($en, [...])` + footer overrides + повний `order`
- **lt.php** — повний файл (не merge з en); раніше бракувало `order`, `footer.*`, `nav.order`

## Market placeholders (`site/includes/market.php`)

Підставляються в рядки `$t` через `bks_apply_market_translations()`:

| Placeholder | Приклад (uk) |
|-------------|--------------|
| `{country}` | Україна |
| `{origin}` | Норвегії |
| `{in_country}` | в Україні |
| `{for_country}` | для України та Європи |
| `{currency}` | UAH |

Використовуються в `meta`, `hero`, `intro`, **`order`** (subtitle, meta_description).

## order.php — ключі перекладу

Файл сторінки: `site/order.php`  
Еталон: `site/lang/en.php` → ключ `'order'`

```php
$o = $t['order'] ?? [];
// page_title, meta_description, h1, subtitle, intro
// benefits_title, benefits[] => title, text (4 шт)
// steps_title, steps[] (4 рядки)
// cta_contact, cta_demo, cta_solutions, cta_portfolio
// crosslinks_title
// + footer.news у crosslinks
```

Також потрібні в header/footer:

- `nav.order`
- `demo.frontend`, `nav.admin`
- `footer.order`, `footer.order_page`, `footer.news`, `footer.demo_link`
- `a11y.menu`

## Ecosystem (інші продукти)

- Дані URL: `includes/ecosystem-defs.php`
- UI рядки: `includes/ecosystem-i18n.php` → merge у `$t['ecosystem']`, `$t['footer']`
- Ключі footer: `eco_toggle`, `eco_show_more`, `eco_show_less`, `related_products`
- Product site burger: `site/includes/ecosystem-mobile-block.php` — усі items крім booking
- Product footer: `booking/includes/ecosystem-footer-block.php` (3 visible + button)

## Demo booking i18n

Файли: `booking/lang/*.php`  
Коди: no, en, uk, ru, sv (перевір `includes/i18n.php` для повного списку)

Ecosystem merge: `bh_apply_ecosystem_translations($t, $lang, 'booking')`

## Додати нову мову (product site)

1. Створити `site/lang/xx.php` (копія структури `en.php`)
2. Додати код у `$BKS_LANGS` у `site/includes/i18n.php`
3. Додати профіль у `site/includes/market.php` (якщо потрібні placeholders)
4. Додати UI у `includes/ecosystem-i18n.php` → `bh_ecosystem_ui()`
5. Оновити hreflang у `site/includes/seo.php` якщо є жорсткий список
6. Bump CSS не обов’язковий; перевірити live `?lang=xx`