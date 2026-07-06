# Booking CMS — папка prompt (handoff)

Документація для продовження розробки з поточного стану. Читай у такому порядку:

| Файл | Призначення |
|------|-------------|
| [AGENT-PROMPT.md](./AGENT-PROMPT.md) | **Головний промпт** — встав у новий чат з AI |
| [PROJECT-STATE.md](./PROJECT-STATE.md) | Версії, URL, що зроблено, що лишилось |
| [FILE-STRUCTURE.md](./FILE-STRUCTURE.md) | Дерево файлів і ролі модулів |
| [I18N-AND-MARKET.md](./I18N-AND-MARKET.md) | Мови, плейсхолдери, order.php, ecosystem |
| [UI-AND-CSS.md](./UI-AND-CSS.md) | Шапка, бургер, lang dropdown, CSS-версії |
| [DEPLOY.md](./DEPLOY.md) | Деплой на bilohash.com, mirror, bump версій |

## Швидкі шляхи

| Що | Де |
|----|-----|
| Робоча копія (dev) | `C:\bilohash\booking\` |
| Live mirror (копія на диск) | `C:\BILOHASH.COM\booking\` |
| Product site | `booking/site/` → https://bilohash.com/booking/site/ |
| Demo frontend | `booking/` → https://bilohash.com/booking/ |
| Спільна екосистема | `C:\bilohash\includes\` (ecosystem-i18n, cms-contact) |

## Версія продукту

**Booking CMS v1.2.0** (2026-07-05) — `includes/version.php`

## Останні зміни (сесія 2026-07-06)

- Product site (`/site/`): переклади `order.php` на 100% (6 мов, LT був порожній)
- Product site: бургер-меню — 3 пункти + спойлер ecosystem; фікс відкриття (panel всередині header)
- Product site: вирівнювання мобільного прапора мови (CSS v22, JS v8 defer у head)
- Demo booking (`/booking/`): вирівнювання мобільного прапора (CSS v43)

Оновлено: 2026-07-06