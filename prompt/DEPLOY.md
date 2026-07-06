# Деплой і версії

## Шляхи

| Роль | Шлях Windows |
|------|----------------|
| Dev / source of truth | `C:\bilohash\booking\` |
| Local live mirror | `C:\BILOHASH.COM\booking\` |
| Спільні includes | `C:\bilohash\includes\` → `C:\BILOHASH.COM\includes\` |

Production URL: https://bilohash.com/booking/

## PowerShell — копіювання файлів

```powershell
$files = @(
  'booking\site\includes\header.php',
  'booking\site\assets\css\site.css',
  'booking\assets\css\style.css'
  # … додати змінені шляхи
)
$dest = 'C:\BILOHASH.COM'
foreach ($f in $files) {
  $src = Join-Path 'C:\bilohash' $f
  $tgt = Join-Path $dest $f
  $d = Split-Path $tgt -Parent
  if (-not (Test-Path $d)) { New-Item -ItemType Directory -Path $d -Force | Out-Null }
  Copy-Item $src $tgt -Force
}
```

## Коли bump версії

| Змінили | Де bump |
|---------|---------|
| `booking/assets/css/style.css` | `bk_public_style_version()` у `includes/site-settings.php` |
| `booking/assets/css/critical.css` | без query string — браузер кешує inline; все одно оновлюй при великих змінах |
| `booking/site/assets/css/site.css` | `bks_site_style_version()` у `site/includes/seo.php` |
| `booking/site/assets/js/site.js` | `bks_site_script_version()` у `site/includes/seo.php` |
| `booking/assets/js/main.js` | перевір де підключається (footer demo) — додати `?v=` якщо немає |
| Admin CSS | версія в `admin/includes/layout.php` або аналог |

## Чеклист перед закриттям задачі

- [ ] Зміни в `C:\bilohash\booking\`
- [ ] Bump CSS/JS version якщо торкались assets
- [ ] Copy на `C:\BILOHASH.COM\`
- [ ] Перевірка mobile + desktop на live URL
- [ ] Перевірка `?lang=uk`, `?lang=lt` на product site

## Типові набори файлів для деплою

### Тільки product site lang

```
booking/site/lang/lt.php
booking/site/lang/no.php
…
```

### Product UI (burger + lang)

```
booking/site/includes/header.php
booking/site/includes/ecosystem-mobile-block.php
booking/site/assets/css/site.css
booking/site/assets/css/site-critical.css
booking/site/assets/js/site.js
booking/site/includes/seo.php
booking/site/includes/footer.php
```

### Demo lang flag

```
booking/includes/lang-dropdown.php
booking/assets/css/style.css
booking/assets/css/critical.css
booking/includes/site-settings.php
```

## WordPress plugin (опційно)

Копія публічної частини може жити в:

`C:\bilohash\wordpress\wp-content\plugins\bilohash-booking\`

Синхронізувати вручну при релізі — не автоматично.

## Git

Репозиторій за README: `github.com/Ruslan-Bilohash/booking`  
Локально workspace може бути без git у `C:\Users\rbilo` — основний код у `C:\bilohash\`.