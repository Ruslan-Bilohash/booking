<?php
/**
 * Central SMTP mailer for bilohash.com (Hostinger + PHPMailer).
 * Replaces PHP mail() which often silently fails on shared hosting.
 */

function bh_mail_load_phpmailer(): bool
{
    static $loaded = false;
    if ($loaded) {
        return true;
    }

    $bases = [
        dirname(__DIR__) . '/PHPMailer/src',
        ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/PHPMailer/src',
    ];

    foreach ($bases as $base) {
        if (!is_file($base . '/PHPMailer.php')) {
            continue;
        }
        require_once $base . '/Exception.php';
        require_once $base . '/PHPMailer.php';
        require_once $base . '/SMTP.php';
        $loaded = true;
        return true;
    }

    return false;
}

function bh_mail_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $path = __DIR__ . '/mail-config.php';
    $config = is_file($path) ? (require $path) : [];
    if (!is_array($config)) {
        $config = [];
    }
    return $config;
}

function bh_mail_last_error(): string
{
    return $GLOBALS['bh_mail_last_error'] ?? '';
}

/**
 * Send HTML email via SMTP.
 *
 * @param string|array<int, string> $to
 */
/**
 * @param array<int, string> $attachmentPaths Absolute paths to files
 */
function bh_send_mail(
    string|array $to,
    string $subject,
    string $htmlBody,
    ?string $replyToEmail = null,
    ?string $replyToName = null,
    ?string $plainBody = null,
    array $attachmentPaths = []
): bool {
    $GLOBALS['bh_mail_last_error'] = '';

    $cfg = bh_mail_config();
    if (empty($cfg['host']) || empty($cfg['username']) || empty($cfg['password'])) {
        $GLOBALS['bh_mail_last_error'] = 'SMTP not configured (mail-config.php)';
        return false;
    }

    if (!bh_mail_load_phpmailer()) {
        $GLOBALS['bh_mail_last_error'] = 'PHPMailer not found';
        return false;
    }

    $recipients = is_array($to) ? $to : [$to];
    $recipients = array_values(array_filter(array_map('trim', $recipients)));
    if ($recipients === []) {
        $GLOBALS['bh_mail_last_error'] = 'No recipient';
        return false;
    }

    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host       = $cfg['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $cfg['username'];
        $mail->Password   = $cfg['password'];
        $mail->SMTPSecure = $cfg['secure'] ?? PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = (int) ($cfg['port'] ?? 465);
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];

        $fromEmail = $cfg['from_email'] ?? $cfg['username'];
        $fromName  = $cfg['from_name'] ?? 'BILOHASH';
        $mail->setFrom($fromEmail, $fromName);

        foreach ($recipients as $addr) {
            if (filter_var($addr, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress($addr);
            }
        }

        if ($replyToEmail && filter_var($replyToEmail, FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($replyToEmail, $replyToName ?? '');
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = $plainBody ?? trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody)));

        foreach ($attachmentPaths as $filePath) {
            if (is_string($filePath) && is_file($filePath)) {
                $mail->addAttachment($filePath);
            }
        }

        $mail->send();
        return true;
    } catch (Throwable $e) {
        $GLOBALS['bh_mail_last_error'] = $e->getMessage();
        error_log('bh_send_mail failed: ' . $e->getMessage());
        return false;
    }
}

/** Owner inbox for all BILOHASH registrations and subscriptions. */
function bh_owner_email(): string
{
    return 'rbilohash@gmail.com';
}

/**
 * HTML notification layout (dark BILOHASH style).
 *
 * @param array<string, scalar|null> $fields
 */
function bh_mail_notification_html(string $headline, array $fields, ?string $adminUrl = null): string
{
    $adminUrl = $adminUrl ?? 'https://bilohash.com/ecosystem/admin.php#admin-messages';
    $rows = '';
    foreach ($fields as $label => $value) {
        if ($value === null || $value === '') {
            continue;
        }
        $rows .= '<tr><td style="padding:8px 16px 8px 0;color:#94a3b8;vertical-align:top;white-space:nowrap">'
            . htmlspecialchars((string) $label)
            . '</td><td style="padding:8px 0;color:#f1f5f9">'
            . nl2br(htmlspecialchars((string) $value))
            . '</td></tr>';
    }

    return '<!DOCTYPE html><html><head><meta charset="utf-8"></head>'
        . '<body style="margin:0;font-family:system-ui,sans-serif;background:#0f172a;color:#e2e8f0;padding:24px">'
        . '<div style="max-width:560px;margin:0 auto;background:#1e293b;border-radius:12px;padding:24px;border:1px solid rgba(34,211,238,.35)">'
        . '<h2 style="color:#22d3ee;margin:0 0 16px;font-size:1.15rem">' . htmlspecialchars($headline) . '</h2>'
        . '<table style="width:100%;border-collapse:collapse;font-size:14px">' . $rows . '</table>'
        . '<p style="margin:20px 0 0;font-size:13px"><a href="' . htmlspecialchars($adminUrl) . '" style="color:#22d3ee">'
        . 'Open ecosystem CRM admin →</a></p>'
        . '</div></body></html>';
}

/**
 * Notify owner — plain key:value body or pre-built HTML.
 */
function bh_mail_send(string $to, string $subject, string $body, ?string $replyToEmail = null): bool
{
    $html = str_contains($body, '<html') || str_contains($body, '<body')
        ? $body
        : bh_mail_plain_body_to_html($subject, $body);

    return bh_send_mail($to, $subject, $html, $replyToEmail);
}

function bh_mail_plain_body_to_html(string $headline, string $plainBody): string
{
    $fields = [];
    foreach (preg_split('/\r\n|\r|\n/', $plainBody) as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        if (preg_match('/^([^:]+):\s*(.*)$/', $line, $m)) {
            $fields[trim($m[1])] = trim($m[2]);
        }
    }
    if ($fields === []) {
        $fields['Details'] = $plainBody;
    }

    return bh_mail_notification_html($headline, $fields);
}

/**
 * @param array<string, scalar|null> $fields
 */
function bh_owner_notify(string $subject, string $headline, array $fields, ?string $replyToEmail = null): bool
{
    return bh_send_mail(
        bh_owner_email(),
        $subject,
        bh_mail_notification_html($headline, $fields),
        $replyToEmail
    );
}