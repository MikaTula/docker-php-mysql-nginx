<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['users']) || !is_array($_SESSION['users'])) {
    $_SESSION['users'] = [
        ['id' => 1, 'name' => 'Анна Иванова', 'email' => 'anna@example.com'],
        ['id' => 2, 'name' => 'Борис Петров', 'email' => 'boris@example.com'],
        ['id' => 3, 'name' => 'Вера Смирнова', 'email' => 'vera@example.com'],
        ['id' => 4, 'name' => 'Глеб Орлов', 'email' => 'gleb@example.com'],
        ['id' => 5, 'name' => 'Дина Козлова', 'email' => 'dina@example.com'],
    ];
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = rtrim(is_string($path) ? $path : '/', '/');
$fields = requestFields($method);

if ($path === '/api/users/all' && $method === 'GET') {
    respond(200, $_SESSION['users']);
}

if ($path === '/api/users' && $method === 'GET') {
    $error = requireInt($fields, 'id');
    if ($error !== null) {
        respond(400, $error);
    }
    $user = findUser((int) $fields['id']);
    if ($user === null) {
        respond(404, ['message' => 'No such user']);
    }
    respond(200, $user);
}

if ($path === '/api/users' && $method === 'POST') {
    $error = requireString($fields, 'name') ?? requireString($fields, 'email');
    if ($error !== null) {
        respond(400, $error);
    }
    $nextId = 1;
    foreach ($_SESSION['users'] as $user) {
        $nextId = max($nextId, (int) $user['id'] + 1);
    }
    $created = [
        'id' => $nextId,
        'name' => trim((string) $fields['name']),
        'email' => trim((string) $fields['email']),
    ];
    $_SESSION['users'][] = $created;
    respond(201, $created);
}

if ($path === '/api/users' && $method === 'PUT') {
    $error = requireInt($fields, 'id');
    if ($error !== null) {
        respond(400, $error);
    }
    $index = findUserIndex((int) $fields['id']);
    if ($index === null) {
        respond(404, ['message' => 'No such user']);
    }
    $current = $_SESSION['users'][$index];
    if (isset($fields['name']) && trim((string) $fields['name']) !== '') {
        $current['name'] = trim((string) $fields['name']);
    }
    if (isset($fields['email']) && trim((string) $fields['email']) !== '') {
        $current['email'] = trim((string) $fields['email']);
    }
    $_SESSION['users'][$index] = $current;
    respond(200, $current);
}

if ($path === '/api/users' && $method === 'DELETE') {
    $error = requireInt($fields, 'id');
    if ($error !== null) {
        respond(400, $error);
    }
    $index = findUserIndex((int) $fields['id']);
    if ($index === null) {
        respond(404, ['message' => 'No such user']);
    }
    array_splice($_SESSION['users'], $index, 1);
    respond(204);
}

respond(404, ['message' => 'Page not found']);

function requestFields(string $method): array
{
    if ($method === 'GET') {
        return $_GET;
    }

    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if ($method === 'POST' && str_contains($contentType, 'application/x-www-form-urlencoded')) {
        return $_POST;
    }

    $raw = file_get_contents('php://input');
    $fields = [];
    if (is_string($raw) && $raw !== '') {
        parse_str($raw, $fields);
    }

    return $fields;
}

function requireInt(array $fields, string $key): ?array
{
    if (!isset($fields[$key]) || $fields[$key] === '') {
        return [$key => 'Field is required'];
    }
    if (filter_var($fields[$key], FILTER_VALIDATE_INT) === false) {
        return [$key => 'Field must be int'];
    }

    return null;
}

function requireString(array $fields, string $key): ?array
{
    if (!isset($fields[$key]) || trim((string) $fields[$key]) === '') {
        return [$key => 'Field is required'];
    }

    return null;
}

function findUser(int $id): ?array
{
    $index = findUserIndex($id);

    return $index === null ? null : $_SESSION['users'][$index];
}

function findUserIndex(int $id): ?int
{
    foreach ($_SESSION['users'] as $index => $user) {
        if ((int) $user['id'] === $id) {
            return $index;
        }
    }

    return null;
}

function respond(int $status, mixed $body = null): never
{
    http_response_code($status);
    if ($status !== 204 && $body !== null) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    exit;
}
