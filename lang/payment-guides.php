<?php
/**
 * Payment setup guides — booking checkout (no, en, uk, ru, sv)
 */
return [
    'en' => [
        'paypal' => [
            'title' => 'PayPal setup',
            'intro' => 'Connect PayPal REST API for card and PayPal wallet checkout on book.php. Use Sandbox first, then Live.',
            'steps' => [
                'Create a Business account at PayPal and open the Developer Dashboard.',
                'Create a REST app (Sandbox) and copy Client ID and Secret.',
                'Set return URL to your booking success page, e.g. /booking/book.php (success state).',
                'Paste Client ID and Secret here, enable PayPal and save.',
                'Run a Sandbox test booking, then create a Live app and switch mode to Live.',
            ],
            'links' => [
                ['label' => 'PayPal Developer Dashboard', 'url' => 'https://developer.paypal.com/dashboard/'],
                ['label' => 'REST API docs', 'url' => 'https://developer.paypal.com/api/rest/'],
            ],
            'note' => 'Store secrets only on the server. Never expose Client Secret in frontend JavaScript.',
        ],
        'stripe' => [
            'title' => 'Stripe setup',
            'intro' => 'Stripe handles cards, Klarna and wallets for hotel and rental bookings.',
            'steps' => [
                'Sign up at Stripe and complete business verification.',
                'In Developers → API keys copy Publishable key and Secret key (Test mode first).',
                'Create a webhook endpoint for booking payment events when you go live.',
                'Copy the webhook signing secret and save all keys here.',
                'Enable the provider — guests can select Stripe on book.php (demo simulates payment).',
            ],
            'links' => [
                ['label' => 'Stripe Dashboard', 'url' => 'https://dashboard.stripe.com/'],
                ['label' => 'API keys', 'url' => 'https://dashboard.stripe.com/apikeys'],
            ],
            'note' => 'Use Test keys until checkout flow is verified. NOK is supported for Norwegian properties.',
        ],
        'vipps' => [
            'title' => 'Vipps MobilePay setup',
            'intro' => 'Vipps ePayment API is the standard mobile payment method in Norway for bookings.',
            'steps' => [
                'Register at Vipps MobilePay Portal and order ePayment API access.',
                'Create API keys: Client ID, Client Secret, Subscription Key and MSN.',
                'Set callback URL to https://yourdomain.com/booking/api/vipps-callback.php',
                'Use test environment (apitest.vipps.no) for development.',
                'Paste all keys here, enable Vipps and test with the Vipps test app.',
            ],
            'links' => [
                ['label' => 'Vipps MobilePay Portal', 'url' => 'https://portal.vippsmobilepay.com/'],
                ['label' => 'ePayment API docs', 'url' => 'https://developer.vippsmobilepay.com/docs/APIs/epayment-api/'],
            ],
            'note' => 'Callback auth token should be a long random string shared between your server and Vipps.',
        ],
    ],
    'no' => [
        'paypal' => [
            'title' => 'PayPal-oppsett',
            'intro' => 'Koble PayPal REST API til book.php. Bruk Sandbox først, deretter Live.',
            'steps' => [
                'Opprett Business-konto hos PayPal og åpne Developer Dashboard.',
                'Opprett REST-app (Sandbox) og kopier Client ID og Secret.',
                'Sett return URL til bestillingssiden, f.eks. /booking/book.php',
                'Lim inn nøkler her, aktiver PayPal og lagre.',
                'Test i Sandbox, opprett Live-app og bytt til Live.',
            ],
            'links' => [
                ['label' => 'PayPal Developer Dashboard', 'url' => 'https://developer.paypal.com/dashboard/'],
                ['label' => 'REST API-dokumentasjon', 'url' => 'https://developer.paypal.com/api/rest/'],
            ],
            'note' => 'Lagre hemmeligheter kun på serveren.',
        ],
        'stripe' => [
            'title' => 'Stripe-oppsett',
            'intro' => 'Stripe for kort, Klarna og lommebøker ved hotell- og leiebooking.',
            'steps' => [
                'Registrer deg hos Stripe og fullfør verifisering.',
                'Kopier Publishable key og Secret key (Test først).',
                'Opprett webhook for betalingshendelser ved produksjon.',
                'Lagre nøkler her og aktiver Stripe.',
                'Gjester kan velge Stripe på book.php (demo simulerer betaling).',
            ],
            'links' => [
                ['label' => 'Stripe Dashboard', 'url' => 'https://dashboard.stripe.com/'],
                ['label' => 'API-nøkler', 'url' => 'https://dashboard.stripe.com/apikeys'],
            ],
            'note' => 'Bruk Test-nøkler til flyten er verifisert. NOK støttes.',
        ],
        'vipps' => [
            'title' => 'Vipps MobilePay-oppsett',
            'intro' => 'Vipps ePayment for mobilbetaling ved booking i Norge.',
            'steps' => [
                'Registrer deg i Vipps MobilePay Portal og bestill ePayment API.',
                'Opprett Client ID, Secret, Subscription Key og MSN.',
                'Sett callback URL til https://dittdomene.no/booking/api/vipps-callback.php',
                'Bruk testmiljø under utvikling.',
                'Lim inn nøkler, aktiver Vipps og test med test-app.',
            ],
            'links' => [
                ['label' => 'Vipps MobilePay Portal', 'url' => 'https://portal.vippsmobilepay.com/'],
                ['label' => 'ePayment API', 'url' => 'https://developer.vippsmobilepay.com/docs/APIs/epayment-api/'],
            ],
            'note' => 'Callback token bør være en lang tilfeldig streng.',
        ],
    ],
    'uk' => [
        'paypal' => [
            'title' => 'Налаштування PayPal',
            'intro' => 'Підключіть PayPal REST API для оплати на book.php. Спочатку Sandbox, потім Live.',
            'steps' => [
                'Створіть Business-акаунт PayPal і відкрийте Developer Dashboard.',
                'Створіть REST-додаток (Sandbox) і скопіюйте Client ID та Secret.',
                'Вкажіть return URL на сторінку успіху бронювання, напр. /booking/book.php',
                'Вставте ключі, увімкніть PayPal і збережіть.',
                'Протестуйте в Sandbox, потім перейдіть на Live.',
            ],
            'links' => [
                ['label' => 'PayPal Developer Dashboard', 'url' => 'https://developer.paypal.com/dashboard/'],
                ['label' => 'REST API', 'url' => 'https://developer.paypal.com/api/rest/'],
            ],
            'note' => 'Секрети зберігайте лише на сервері.',
        ],
        'stripe' => [
            'title' => 'Налаштування Stripe',
            'intro' => 'Stripe для карток, Klarna та гаманців при бронюванні.',
            'steps' => [
                'Зареєструйтесь у Stripe і пройдіть верифікацію.',
                'Скопіюйте Publishable key та Secret key (спочатку Test).',
                'Налаштуйте webhook для продакшн-платежів.',
                'Збережіть ключі та увімкніть Stripe.',
                'Гості обирають Stripe на book.php (демо симулює оплату).',
            ],
            'links' => [
                ['label' => 'Stripe Dashboard', 'url' => 'https://dashboard.stripe.com/'],
                ['label' => 'API keys', 'url' => 'https://dashboard.stripe.com/apikeys'],
            ],
            'note' => 'Використовуйте Test-ключі до перевірки checkout.',
        ],
        'vipps' => [
            'title' => 'Налаштування Vipps',
            'intro' => 'Vipps ePayment — стандарт мобільної оплати в Норвегії.',
            'steps' => [
                'Зареєструйтесь у Vipps MobilePay Portal.',
                'Отримайте Client ID, Secret, Subscription Key та MSN.',
                'Callback URL: https://yourdomain.com/booking/api/vipps-callback.php',
                'Тестове середовище: apitest.vipps.no',
                'Увімкніть Vipps і протестуйте з тестовим додатком.',
            ],
            'links' => [
                ['label' => 'Vipps MobilePay Portal', 'url' => 'https://portal.vippsmobilepay.com/'],
                ['label' => 'ePayment API', 'url' => 'https://developer.vippsmobilepay.com/docs/APIs/epayment-api/'],
            ],
            'note' => 'Callback token — довгий випадковий рядок між сервером і Vipps.',
        ],
    ],
    'ru' => [
        'paypal' => [
            'title' => 'Настройка PayPal',
            'intro' => 'Подключите PayPal REST API для оплаты на book.php. Сначала Sandbox, затем Live.',
            'steps' => [
                'Создайте Business-аккаунт PayPal и откройте Developer Dashboard.',
                'Создайте REST-приложение (Sandbox) и скопируйте Client ID и Secret.',
                'Укажите return URL на страницу успеха, напр. /booking/book.php',
                'Вставьте ключи, включите PayPal и сохраните.',
                'Протестируйте в Sandbox, затем перейдите на Live.',
            ],
            'links' => [
                ['label' => 'PayPal Developer Dashboard', 'url' => 'https://developer.paypal.com/dashboard/'],
                ['label' => 'REST API', 'url' => 'https://developer.paypal.com/api/rest/'],
            ],
            'note' => 'Секреты храните только на сервере.',
        ],
        'stripe' => [
            'title' => 'Настройка Stripe',
            'intro' => 'Stripe для карт, Klarna и кошельков при бронировании.',
            'steps' => [
                'Зарегистрируйтесь в Stripe и пройдите верификацию.',
                'Скопируйте Publishable key и Secret key (сначала Test).',
                'Настройте webhook для продакшн-платежей.',
                'Сохраните ключи и включите Stripe.',
                'Гости выбирают Stripe на book.php (демо симулирует оплату).',
            ],
            'links' => [
                ['label' => 'Stripe Dashboard', 'url' => 'https://dashboard.stripe.com/'],
                ['label' => 'API keys', 'url' => 'https://dashboard.stripe.com/apikeys'],
            ],
            'note' => 'Используйте Test-ключи до проверки checkout.',
        ],
        'vipps' => [
            'title' => 'Настройка Vipps',
            'intro' => 'Vipps ePayment — стандарт мобильной оплаты в Норвегии.',
            'steps' => [
                'Зарегистрируйтесь в Vipps MobilePay Portal.',
                'Получите Client ID, Secret, Subscription Key и MSN.',
                'Callback URL: https://yourdomain.com/booking/api/vipps-callback.php',
                'Тестовая среда: apitest.vipps.no',
                'Включите Vipps и протестируйте с тестовым приложением.',
            ],
            'links' => [
                ['label' => 'Vipps MobilePay Portal', 'url' => 'https://portal.vippsmobilepay.com/'],
                ['label' => 'ePayment API', 'url' => 'https://developer.vippsmobilepay.com/docs/APIs/epayment-api/'],
            ],
            'note' => 'Callback token — длинная случайная строка между сервером и Vipps.',
        ],
    ],
    'sv' => [
        'paypal' => [
            'title' => 'PayPal-konfiguration',
            'intro' => 'Anslut PayPal REST API för betalning på book.php. Använd Sandbox först, sedan Live.',
            'steps' => [
                'Skapa ett Business-konto hos PayPal och öppna Developer Dashboard.',
                'Skapa en REST-app (Sandbox) och kopiera Client ID och Secret.',
                'Ange return URL till bokningssidan, t.ex. /booking/book.php',
                'Klistra in nycklar här, aktivera PayPal och spara.',
                'Testa i Sandbox, skapa Live-app och byt till Live.',
            ],
            'links' => [
                ['label' => 'PayPal Developer Dashboard', 'url' => 'https://developer.paypal.com/dashboard/'],
                ['label' => 'REST API-dokumentation', 'url' => 'https://developer.paypal.com/api/rest/'],
            ],
            'note' => 'Lagra hemligheter endast på servern.',
        ],
        'stripe' => [
            'title' => 'Stripe-konfiguration',
            'intro' => 'Stripe för kort, Klarna och plånböcker vid hotell- och hyresbokning.',
            'steps' => [
                'Registrera dig hos Stripe och slutför verifiering.',
                'Kopiera Publishable key och Secret key (Test först).',
                'Skapa webhook för betalningshändelser i produktion.',
                'Spara nycklar här och aktivera Stripe.',
                'Gäster kan välja Stripe på book.php (demo simulerar betalning).',
            ],
            'links' => [
                ['label' => 'Stripe Dashboard', 'url' => 'https://dashboard.stripe.com/'],
                ['label' => 'API-nycklar', 'url' => 'https://dashboard.stripe.com/apikeys'],
            ],
            'note' => 'Använd Test-nycklar tills checkout-flödet är verifierat. NOK stöds.',
        ],
        'vipps' => [
            'title' => 'Vipps MobilePay-konfiguration',
            'intro' => 'Vipps ePayment för mobilbetalning vid bokning i Sverige och Norden.',
            'steps' => [
                'Registrera dig i Vipps MobilePay Portal och beställ ePayment API.',
                'Skapa Client ID, Secret, Subscription Key och MSN.',
                'Ange callback URL till https://dindomän.se/booking/api/vipps-callback.php',
                'Använd testmiljö under utveckling.',
                'Klistra in nycklar, aktivera Vipps och testa med testappen.',
            ],
            'links' => [
                ['label' => 'Vipps MobilePay Portal', 'url' => 'https://portal.vippsmobilepay.com/'],
                ['label' => 'ePayment API', 'url' => 'https://developer.vippsmobilepay.com/docs/APIs/epayment-api/'],
            ],
            'note' => 'Callback-token bör vara en lång slumpmässig sträng.',
        ],
    ],
];