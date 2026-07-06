# UI: шапка, бургер, мова, адаптив

## Breakpoint

**1200px** — межа mobile/desktop для обох сайтів.

- `< 1200px`: burger + mobile lang, desktop nav/actions приховані
- `≥ 1200px`: повна шапка, burger hidden

## Product site (`bks-*`)

### Desktop header (`site/includes/header.php`)

```
[Logo]                    [Demo] [Admin] [Order] [Contact]
         [Lang dropdown — flag + code + chevron]
```

Nav-якорі (Features, Screens…) **прибрані з PC-шапки** — лише в footer / burger mobile.

### Mobile header

```
[Logo]                    [Lang 44×44] [Burger 44×44]
```

При відкритті burger — панель **розгортається вниз всередині header**:

1. Демо фронтенд (play icon)
2. Адмін (lock icon)
3. Замовити розробку (laptop icon)
4. `<details>` — Інші продукти Bilohash CMS (ecosystem list)

+ overlay `#bksOverlay` на весь екран (z-index 290), header nav-open z-index 400.

### Lang dropdown (product)

- Розмітка: `<details class="bks-lang-details">` + `<summary class="bks-lang-dropdown-btn">`
- Mobile: тільки прапор, `.bks-lang-code` і `.bks-lang-chevron` hidden
- Класи: `.bks-lang-flag`, `.bks-lang-chevron`, `.bks-lang-current`
- JS: `site.js` — toggle details, close при burger open

### CSS файли

| Файл | Роль |
|------|------|
| `site-critical.css` | FOUC: header, burger, lang 44×44 |
| `site.css` | Повні стилі, order hero, footer eco |

### Типові фікси mobile

```css
.bks-menu-toggle .fas,
.bks-lang-chevron i { pointer-events: none; }

@media (max-width: 1199px) {
  .bks-lang-code, .bks-lang-chevron { display: none; }
  .bks-lang-dropdown-btn { width: 44px; height: 44px; … }
}
```

## Demo booking (`bk-*`)

### Top bar

```
.bk-top-bar (sticky)
  ├── .bk-demo-strip (жовта смуга)
  └── .bk-header (синій)
        ├── .bk-header-inner (logo + mobile tools)
        └── .bk-header-panel (drawer на mobile)
```

### Mobile tools

- `#bkLangDropdownMobile` — прапор 44×44, `.bk-lang-flag-only`
- `#bkMenuBtn` — burger 44×44

### Lang dropdown (demo)

- Button-based (не details), JS у `main.js` → `.bk-lang-dropdown.is-open`
- Variants: `--header` (desktop), `--mobile`, `--strip`
- Розмітка: `.bk-lang-flag` + `.bk-lang-code` + `.bk-lang-chevron`

### CSS змінні (demo mobile menu position)

```css
:root {
  --bk-demo-strip-h: 36px;
  --bk-header-bar-h: 56px;
}
```

Lang menu fixed top: `strip + header + safe-area`.

## order.php hero (product)

Секції: `.bks-order-hero`, `.bks-order-body`  
Кнопки hero: `.bks-page-cta` — стилі outline/ghost для білого тексту на синьому (не глобальні `.bks-btn-*` на білому фоні).

## Admin

- Окремі стилі: `admin.css`, `admin-settings.css`
- Mobile settings nav: `adm-settings-mobile-nav` з `<details>` groups
- PC sidebar + mobile drawer (не плутати з product site burger)

## Після змін UI

1. Bump `bks_site_style_version()` або `bk_public_style_version()`
2. Оновити `site-critical.css` / `critical.css` якщо чіпали above-the-fold
3. Ctrl+F5 на live
4. Copy to `C:\BILOHASH.COM\booking\`