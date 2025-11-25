<?php
// php/functions.php
require_once __DIR__ . '/db.php';

/**
 * Set user session structure used across the app
 */
function set_user_session(array $user)
{
    $_SESSION['user'] = [
        'id' => $user['id'],
        'fullname' => $user['fullname'],
        'email' => $user['email'],
        'role' => $user['role'] ?? 'user'
    ];
}

/**
 * REMEMBER ME:
 * create selector:validator pair -> store selector + hash(validator) in DB,
 * set cookie "remember_me" to selector:validator (validator is secret).
 */
function remember_me_create(PDO $pdo, int $user_id)
{
    $selector = bin2hex(random_bytes(12));
    $validator = bin2hex(random_bytes(32));
    $token_hash = password_hash($validator, PASSWORD_DEFAULT);
    $expires = (new DateTime('+30 days'))->format('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO remember_tokens (selector, token_hash, user_id, expires_at) VALUES (?,?,?,?)");
    $stmt->execute([$selector, $token_hash, $user_id, $expires]);

    // set cookie (httpOnly)
    $cookieValue = $selector . ':' . $validator;
    setcookie('remember_me', $cookieValue, time() + 60 * 60 * 24 * 30, '/', '', false, true);
}

/**
 * Attempt login using remember_me cookie.
 * Returns true if successful (and session is set).
 */
function remember_me_login(PDO $pdo)
{
    if (empty($_COOKIE['remember_me'])) return false;
    $parts = explode(':', $_COOKIE['remember_me']);
    if (count($parts) !== 2) return false;
    list($selector, $validator) = $parts;

    $stmt = $pdo->prepare("SELECT * FROM remember_tokens WHERE selector = ? LIMIT 1");
    $stmt->execute([$selector]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) return false;

    // expired?
    if (new DateTime() > new DateTime($row['expires_at'])) {
        $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE id = ?");
        $stmt->execute([$row['id']]);
        setcookie('remember_me', '', time() - 3600, '/', '', false, true);
        return false;
    }

    // verify validator
    if (!password_verify($validator, $row['token_hash'])) {
        // possible theft: remove all tokens for this user
        $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = ?");
        $stmt->execute([$row['user_id']]);
        setcookie('remember_me', '', time() - 3600, '/', '', false, true);
        return false;
    }

    // fetch user and set session
    $stmt = $pdo->prepare("SELECT id, fullname, email, role FROM users WHERE id = ?");
    $stmt->execute([$row['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) return false;

    // rotate token: delete old and create a new one
    $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE id = ?");
    $stmt->execute([$row['id']]);
    remember_me_create($pdo, $user['id']);

    set_user_session($user);
    return true;
}

/**
 * Clear remember-me token for current cookie
 */
function remember_me_clear(PDO $pdo)
{
    if (!empty($_COOKIE['remember_me'])) {
        $parts = explode(':', $_COOKIE['remember_me']);
        if (count($parts) === 2) {
            $selector = $parts[0];
            $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE selector = ?");
            $stmt->execute([$selector]);
        }
        setcookie('remember_me', '', time() - 3600, '/', '', false, true);
    }
}

/**
 * Send email. Uses PHPMailer if installed and configured,
 * otherwise falls back to PHP mail().
 */
function send_mail(string $toEmail, string $toName, string $subject, string $htmlBody): bool
{
    $cfgFile = __DIR__ . '/mail_config.php';
    if (!file_exists($cfgFile)) {
        // fallback
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: no-reply@localhost\r\n";
        return mail($toEmail, $subject, $htmlBody, $headers);
    }

    $mailCfg = require $cfgFile;
    if (empty($mailCfg)) return false;

    // prefer PHPMailer if present
    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (file_exists($autoload)) {
        require_once $autoload;
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $mailCfg['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $mailCfg['username'];
            $mail->Password = $mailCfg['password'];
            if (!empty($mailCfg['secure'])) $mail->SMTPSecure = $mailCfg['secure'];
            $mail->Port = $mailCfg['port'];
            $mail->setFrom($mailCfg['from_email'], $mailCfg['from_name']);
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;
            $mail->send();
            return true;
        } catch (Exception $ex) {
            error_log('Mail error: ' . $ex->getMessage());
            return false;
        }
    }

    // fallback to mail()
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: " . ($mailCfg['from_email'] ?? 'no-reply@localhost') . "\r\n";
    return mail($toEmail, $subject, $htmlBody, $headers);
}
