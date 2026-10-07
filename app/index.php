<?php
declare(strict_types=1);
$host = 'db';
$user = 'student';
$pass = 'student_pass';
$db = 'lab';
function h(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $row = $pdo->query('SELECT NOW() AS now')->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(503);
    exit('Ошибка подключения к MySQL. Проверьте настройки и состояние db.');
}
?>
<!doctype html>
<html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Практика PHP MySQL Docker</title></head>
<body>
<h1>Привет из PHP-контейнера!</h1>
<p>MySQL отвечает: <?= h($row['now']) ?></p>
<p>Хост PHP: <?= h(gethostname()) ?></p>
<p>Версия PHP: <?= h(phpversion()) ?></p>
<p>PDO драйвер: <?= h($pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) ?></p>
</body></html>
