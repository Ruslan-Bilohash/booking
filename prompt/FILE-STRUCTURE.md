# Структура файлів Booking CMS

Корінь: `C:\bilohash\booking\`  
Спільні модулі Bilohash: `C:\bilohash\includes\`

```
bilohash/
├── includes/                          # Спільне для всіх CMS Bilohash
│   ├── ecosystem-i18n.php             # UI + labels інших продуктів (shop, auction…)
│   ├── ecosystem-defs.php             # URL каталогу продуктів
│   ├── cms-contact.php                # Контактні форми, nav_discuss
│   ├── cms-contact.css
│   └── bh-cms-site-settings.php       # Shared settings helpers
│
└── booking/
    ├── prompt/                        # ← ЦЯ ПАПКА (handoff для AI)
    │
    ├── config.php                     # BASE_PATH, site_url, bk_url()
    ├── init.php                       # i18n, helpers bootstrap
    ├── index.php                      # Demo homepage
    ├── search.php, property.php, book.php, contact.php, order.php
    ├── solutions.php, vertical.php    # SEO verticals
    ├── sitemap.php, robots.txt, llms.txt
    │
    ├── lang/                          # Demo i18n: en, no, uk, ru, sv (+ payment-guides)
    ├── data/
    │   ├── properties.json / .php
    │   ├── bookings.json
    │   └── vertical-defs.php
    │
    ├── includes/                      # Demo shared PHP
    │   ├── header.php, footer.php
    │   ├── lang-dropdown.php          # variants: header | mobile | strip
    │   ├── i18n.php, seo.php, helpers.php, storage.php
    │   ├── site-settings.php          # Settings + bk_public_style_version()
    │   ├── version.php                # BK_VERSION 1.2.0
    │   ├── ecosystem-footer-block.php # Footer: 3 visible + show more
    │   ├── ecosystem-strip.php
    │   ├── vertical-template.php, vertical-lib.php
    │   ├── search-form.php, property-card.php
    │   └── chat-widget.php
    │
    ├── assets/
    │   ├── css/
    │   │   ├── style.css              # Demo main (v43)
    │   │   ├── critical.css           # Inline above-the-fold
    │   │   ├── admin.css
    │   │   └── admin-settings.css
    │   └── js/
    │       └── main.js                # Burger, lang, guests popup, footer eco
    │
    ├── admin/                         # Admin panel
    │   ├── login.php, logout.php
    │   ├── includes/layout.php, layout-end.php
    │   ├── includes/lang-dropdown.php
    │   ├── includes/settings/         # Tabs, forms (appearance, recaptcha, chat…)
    │   └── settings-*.php
    │
    ├── screen/                        # SVG screenshots для product site
    │
    └── site/                          # PRODUCT MARKETING SITE
        ├── config.php                 # BKS_BASE_PATH=/booking/site
        ├── init.php                   # i18n + seo
        ├── index.php                  # Landing
        ├── order.php                  # Замовити розробку
        ├── contact.php
        ├── .htaccess
        │
        ├── lang/                      # en, no, uk, ru, sv, lt
        │   ├── en.php                 # Еталон перекладів
        │   ├── lt.php                 # Повний order + footer (додано 2026-07)
        │   └── sv.php                 # array_replace_recursive($en, …)
        │
        ├── includes/
        │   ├── header.php             # Logo, lang, burger, desktop actions
        │   ├── footer.php             # Grid + ecosystem block
        │   ├── lang-dropdown.php      # <details> + summary (не button)
        │   ├── ecosystem-mobile-block.php  # Burger spoiler «інші продукти»
        │   ├── ecosystem-bar.php
        │   ├── seo.php                # Styles, schemas, CSS/JS versions
        │   ├── i18n.php               # + bh_apply_ecosystem_translations
        │   ├── market.php             # {origin}, {currency}, …
        │   └── helpers.php
        │
        └── assets/
            ├── css/
            │   ├── site.css           # v22
            │   └── site-critical.css
            └── js/
                └── site.js            # v8 — burger, lang details, footer eco
```

## Потоки завантаження

### Demo page (`/booking/index.php`)

```
init.php → header.php → [content] → footer.php
         ↳ bk_render_public_stylesheets()  → critical.css + style.css?v=
         ↳ main.js (у footer demo)
```

### Product page (`/booking/site/index.php`)

```
init.php → header.php → [content] → footer.php
         ↳ bks_render_stylesheets() → site-critical + site.css?v=
         ↳ site.js defer у head (seo.php)
```

## ID елементів (для JS/CSS)

| Елемент | Demo | Product site |
|---------|------|--------------|
| Header | `#bkHeader` | `#bksHeader` |
| Burger | `#bkMenuBtn` | `#bksMenuBtn` |
| Mobile panel | `#bkHeaderPanel` (всередині header) | `#bksMobilePanel` (всередині header) |
| Overlay | `#bkOverlay` | `#bksOverlay` |
| Lang (mobile) | `#bkLangDropdownMobile` | `#bksLangDetails` (details) |
| Lang menu | `#bkLangMenuMobile` | `#bksLangMenu` |

## Mirror deploy

```
C:\bilohash\booking\  →  C:\BILOHASH.COM\booking\
```

Ті самі відносні шляхи; на bilohash.com `config.php` фіксує `BKS_BASE_PATH`.