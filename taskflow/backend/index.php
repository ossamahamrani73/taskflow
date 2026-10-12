<?php
// ============================================================
// TaskFlow — Tiny REST router (no framework)
// Endpoints:
//   GET    /api/tasks        → list all tasks
//   GET    /api/tasks/{id}   → single task
//   POST   /api/tasks        → create task
//   PUT    /api/tasks/{id}   → update task
//   DELETE /api/tasks/{id}   → delete task
// ============================================================

require __DIR__ . '/config.php';

const VALID_STATUSES = ['todo', 'doing', 'done'];

/**
 * Send a JSON response and stop.
 */
function respond(mixed $data, int $code = 200): void
{
    http_response_code($code);
    echo json_encode($data);
    exit;
}

/**
 * Read and decode the JSON request body.
 */
function getBody(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/**
 * Validate task input. Returns [clean array, error message|null].
 */
function validateTaskInput(array $body, bool $titleRequired = true): array
{
    $title = trim($body['title'] ?? '');
    $description = trim($body['description'] ?? '');
    $status = $body['status'] ?? 'todo';

    if ($titleRequired && $title === '') {
        return [[], 'The "title" field is required.'];
    }
    if ($title !== '' && mb_strlen($title) > 255) {
        return [[], 'The "title" must not exceed 255 characters.'];
    }
    if (!in_array($status, VALID_STATUSES, true)) {
        return [[], 'Invalid status. Allowed values: todo, doing, done.'];
    }

    return [
        ['title' => $title, 'description' => $description, 'status' => $status],
        null,
    ];
}

// --- Routing --------------------------------------------------------------
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip the script directory so the router works from any base path
// (e.g. php -S localhost:8000 or an Apache subfolder).
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($scriptDir !== '' && str_starts_with($path, $scriptDir)) {
    $path = substr($path, strlen($scriptDir));
}

if (!preg_match('#^/api/tasks(?:/(\d+))?$#', $path, $matches)) {
    respond(['error' => 'Not found.'], 404);
}
$id = isset($matches[1]) ? (int) $matches[1] : null;

switch ($method) {
    // --- LIST ALL ---------------------------------------------------------
    case 'GET':
        if ($id === null) {
            $stmt = $pdo->query('SELECT * FROM tasks ORDER BY created_at DESC');
            respond($stmt->fetchAll());
        }
        $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ?');
        $stmt->execute([$id]);
        $task = $stmt->fetch();
        $task ? respond($task) : respond(['error' => 'Task not found.'], 404);
        break;

    // --- CREATE -----------------------------------------------------------
    case 'POST':
        if ($id !== null) {
            respond(['error' => 'Not found.'], 404);
        }
        [$input, $error] = validateTaskInput(getBody(), true);
        if ($error !== null) {
            respond(['error' => $error], 422);
        }
        $stmt = $pdo->prepare(
            'INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)'
        );
        $stmt->execute([$input['title'], $input['description'], $input['status']]);
        $newId = (int) $pdo->lastInsertId();
        $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ?');
        $stmt->execute([$newId]);
        respond($stmt->fetch(), 201);
        break;

    // --- UPDATE -----------------------------------------------------------
    case 'PUT':
        if ($id === null) {
            respond(['error' => 'Task id is required.'], 400);
        }
        [$input, $error] = validateTaskInput(getBody(), false);
        if ($error !== null) {
            respond(['error' => $error], 422);
        }
        // Build a dynamic update with only the fields provided
        $body = getBody();
        $fields = [];
        $values = [];
        foreach (['title', 'description', 'status'] as $field) {
            if (array_key_exists($field, $body)) {
                $fields[] = "$field = ?";
                $values[] = $input[$field];
            }
        }
        if ($fields === []) {
            respond(['error' => 'Nothing to update. Provide title, description or status.'], 422);
        }
        $values[] = $id;
        $stmt = $pdo->prepare('UPDATE tasks SET ' . implode(', ', $fields) . ' WHERE id = ?');
        $stmt->execute($values);
        if ($stmt->rowCount() === 0) {
            respond(['error' => 'Task not found.'], 404);
        }
        $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ?');
        $stmt->execute([$id]);
        respond($stmt->fetch());
        break;

    // --- DELETE -----------------------------------------------------------
    case 'DELETE':
        if ($id === null) {
            respond(['error' => 'Task id is required.'], 400);
        }
        $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ?');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 0) {
            respond(['error' => 'Task not found.'], 404);
        }
        http_response_code(204);
        break;

    default:
        respond(['error' => 'Method not allowed.'], 405);
}
