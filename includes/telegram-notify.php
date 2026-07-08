<?php
declare(strict_types=1);

function bk_telegram_defaults(): array
{
    return [
        'telegram_enabled'          => false,
        'telegram_bot_token'        => '',
        'telegram_chat_id'          => '',
        'telegram_notify_bookings'  => true,
        'telegram_parse_mode'       => 'HTML',
    ];
}

function bk_telegram_merge(array $settings): array
{
    return array_merge(bk_telegram_defaults(), $settings);
}

function bk_telegram_apply_post(array $post, array $settings): array
{
    $settings = bk_telegram_merge($settings);
    $settings['telegram_enabled'] = !empty($post['telegram_enabled']);
    $settings['telegram_notify_bookings'] = !empty($post['telegram_notify_bookings']);
    $token = trim((string) ($post['telegram_bot_token'] ?? ''));
    if ($token !== '') {
        $settings['telegram_bot_token'] = $token;
    }
    $chatId = trim((string) ($post['telegram_chat_id'] ?? ''));
    if ($chatId !== '') {
        $settings['telegram_chat_id'] = $chatId;
    }
    $mode = strtoupper(trim((string) ($post['telegram_parse_mode'] ?? 'HTML')));
    $settings['telegram_parse_mode'] = in_array($mode, ['HTML', 'Markdown', 'MarkdownV2'], true) ? $mode : 'HTML';
    return $settings;
}

/** @return array{ok:bool,error:string} */
function bk_telegram_send(string $text, ?array $settings = null): array
{
    $s = bk_telegram_merge(is_array($settings) ? $settings : []);
    if (empty($s['telegram_enabled'])) {
        return ['ok' => false, 'error' => 'Telegram disabled'];
    }
    $token = trim((string) ($s['telegram_bot_token'] ?? ''));
    $chatId = trim((string) ($s['telegram_chat_id'] ?? ''));
    if ($token === '' || $chatId === '') {
        return ['ok' => false, 'error' => 'Token or chat ID missing'];
    }
    $url = 'https://api.telegram.org/bot' . $token . '/sendMessage';
    $payload = http_build_query([
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => $s['telegram_parse_mode'] ?? 'HTML',
    ]);
    $ctx = stream_context_create(['http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $payload,
        'timeout' => 12,
    ]]);
    $raw = @file_get_contents($url, false, $ctx);
    if ($raw === false) {
        return ['ok' => false, 'error' => 'HTTP request failed'];
    }
    $data = json_decode($raw, true);
    return ['ok' => !empty($data['ok']), 'error' => (string) ($data['description'] ?? '')];
}