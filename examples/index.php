<?php

declare(strict_types=1);

const DB_DSN = 'mysql:host=cm-database;dbname=cm_api_server;charset=utf8mb4';
const DB_USER = 'cm_db_user';
const DB_PASSWORD = 'secret';

$errors = [];
$mailStatus = null;

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $pdo = new PDO(DB_DSN, DB_USER, DB_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS demo_notes (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            body VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    return $pdo;
}

function redisClient(): Redis
{
    $redis = new Redis();
    $redis->connect('cm-redis', 6379, 2.0);

    return $redis;
}

function sendMail(string $subject, string $text): void
{
    $socket = stream_socket_client('tcp://cm-mailpit:1025', $errno, $error, 3);
    if ($socket === false) {
        throw new RuntimeException($error !== '' ? $error : 'SMTP недоступен');
    }

    $read = static function () use ($socket): string {
        $line = '';
        do {
            $line = fgets($socket);
            if ($line === false) {
                throw new RuntimeException('Пустой ответ SMTP');
            }
        } while (isset($line[3]) && $line[3] === '-');

        return $line;
    };
    $write = static function (string $command) use ($socket, $read): void {
        fwrite($socket, $command . "\r\n");
        $reply = $read();
        if ($reply === '' || ($reply[0] !== '2' && $reply[0] !== '3')) {
            throw new RuntimeException(trim($reply));
        }
    };

    $read();
    $write('EHLO examples');
    $write('MAIL FROM:<demo@hello-docker.loc>');
    $write('RCPT TO:<demo@hello-docker.loc>');
    $write('DATA');
    fwrite(
        $socket,
        "Subject: {$subject}\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n{$text}\r\n.\r\n"
    );
    $reply = $read();
    if ($reply === '' || $reply[0] !== '2') {
        throw new RuntimeException(trim($reply));
    }
    fwrite($socket, "QUIT\r\n");
    fclose($socket);
}

try {
    $pdo = db();
} catch (Throwable $exception) {
    $pdo = null;
    $errors[] = 'MySQL: ' . $exception->getMessage();
}

try {
    $redis = redisClient();
    $visits = $redis->incr('examples:visits');
} catch (Throwable $exception) {
    $redis = null;
    $visits = null;
    $errors[] = 'Redis: ' . $exception->getMessage();
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && $pdo instanceof PDO) {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'add') {
        $body = trim((string) ($_POST['body'] ?? ''));
        if ($body === '') {
            $errors[] = 'Текст заметки пустой.';
        } else {
            $statement = $pdo->prepare('INSERT INTO demo_notes (body) VALUES (:body)');
            $statement->execute(['body' => mb_substr($body, 0, 255)]);
            if ($redis instanceof Redis) {
                $redis->set('examples:last_note', $body);
            }
        }
    }

    if ($action === 'delete') {
        $statement = $pdo->prepare('DELETE FROM demo_notes WHERE id = :id');
        $statement->execute(['id' => (int) ($_POST['id'] ?? 0)]);
    }
}

$mailSubject = 'Проверка Mailpit ' . date('Y.m.d H:m:d');
$mailBody = 'Письмо отправлено со страницы /examples/.';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && (string) ($_POST['action'] ?? '') === 'mail') {
    $mailSubject = trim((string) ($_POST['mail_subject'] ?? ''));
    $mailBody = trim((string) ($_POST['mail_body'] ?? ''));
    $mailSubject = str_replace(["\r", "\n"], '', $mailSubject);

    if ($mailSubject === '' || $mailBody === '') {
        $errors[] = 'Тема и текст письма не должны быть пустыми.';
    } else {
        try {
            sendMail($mailSubject, $mailBody);
            $mailStatus = 'Письмо ушло в Mailpit.';
        } catch (Throwable $exception) {
            $errors[] = 'Почта: ' . $exception->getMessage();
        }
    }
}

$notes = [];
if ($pdo instanceof PDO) {
    $notes = $pdo->query('SELECT id, body, created_at FROM demo_notes ORDER BY id DESC')->fetchAll();
}

$lastNote = $redis instanceof Redis ? $redis->get('examples:last_note') : false;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Примеры стека</title>
    <style>
        body { font-family: sans-serif; margin: 2rem auto; max-width: 42rem; line-height: 1.4; }
        section { margin-bottom: 2rem; }
        code { background: #f2f2f2; padding: 0 .25rem; }
        form { margin: .5rem 0; }
        input[type="text"], textarea { width: 100%; box-sizing: border-box; }
        textarea { min-height: 6rem; }
        .error { color: #8a1f11; }
        table { border-collapse: collapse; width: 100%; }
        td, th { border-bottom: 1px solid #ddd; text-align: left; padding: .35rem; }
    </style>
</head>
<body>
    <h1>Примеры стека</h1>
    <p><a href="/examples/readme.php">Readme</a></p>
    <p>PHP <?= h(PHP_VERSION) ?>. Каталог на хосте: <code>examples</code>, в контейнерах: <code>/var/www/examples</code>.</p>
    <p>
        <a href="http://hello-docker.loc:8081/" target="_blank" rel="noopener">phpMyAdmin</a>
        ·
        <a href="http://hello-docker.loc:8082/" target="_blank" rel="noopener">Redis Commander</a>
        ·
        <a href="http://hello-docker.loc:8025/" target="_blank" rel="noopener">Mailpit</a>
        ·
        <a href="http://hello-docker.loc/app/" target="_blank" rel="noopener">Клиент</a>
    </p>

    <?php foreach ($errors as $error): ?>
        <p class="error"><?= h($error) ?></p>
    <?php endforeach; ?>

    <section>
        <h2>Redis</h2>
        <?php if ($visits !== null): ?>
            <p>Счётчик открытий страницы <code>examples:visits</code>: <strong><?= h((string) $visits) ?></strong>.</p>
            <p>Последняя заметка в ключе <code>examples:last_note</code>: <?= $lastNote !== false && $lastNote !== '' ? h((string) $lastNote) : 'ещё нет' ?>.</p>
        <?php endif; ?>
    </section>

    <section>
        <h2>MySQL</h2>
        <p>База <code>cm_api_server</code> на <code>cm-database</code>. Таблица <code>demo_notes</code> создаётся при открытии страницы.</p>
        <form method="post">
            <input type="hidden" name="action" value="add">
            <input type="text" name="body" maxlength="255" placeholder="Новая заметка">
            <button type="submit">Сохранить</button>
        </form>
        <table>
            <tr><th>ID</th><th>Текст</th><th>Когда</th><th></th></tr>
            <?php foreach ($notes as $note): ?>
                <tr>
                    <td><?= h((string) $note['id']) ?></td>
                    <td><?= h((string) $note['body']) ?></td>
                    <td><?= h((string) $note['created_at']) ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= h((string) $note['id']) ?>">
                            <button type="submit">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <section>
        <h2>Почта</h2>
        <form method="post">
            <input type="hidden" name="action" value="mail">
            <p><input type="text" name="mail_subject" value="<?= h($mailSubject) ?>"></p>
            <p><textarea name="mail_body"><?= h($mailBody) ?></textarea></p>
            <button type="submit">Отправить письмо в Mailpit</button>
        </form>
        <?php if ($mailStatus !== null): ?>
            <p><?= h($mailStatus) ?> Смотреть в <a href="http://hello-docker.loc:8025/" target="_blank" rel="noopener">Mailpit</a>.</p>
        <?php endif; ?>
    </section>
</body>
</html>
