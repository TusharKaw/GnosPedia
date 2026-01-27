<?php
/**
 * GnosPedia Conversations API
 * Simple REST API for real-time threaded discussions
 * 
 * Endpoints:
 *   GET  /api/conversations.php?workspace=X&page=Y  - Get messages
 *   POST /api/conversations.php                      - Add message
 *   DELETE /api/conversations.php?id=Z&workspace=X&page=Y - Delete message
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Data storage directory
$dataDir = __DIR__ . '/../data/conversations';
if (!file_exists($dataDir)) {
    mkdir($dataDir, 0755, true);
}

function getConversationFile($workspace, $page)
{
    global $dataDir;
    $key = preg_replace('/[^a-zA-Z0-9_-]/', '_', $workspace . '_' . $page);
    return $dataDir . '/' . $key . '.json';
}

function readConversation($workspace, $page)
{
    $file = getConversationFile($workspace, $page);
    if (!file_exists($file)) {
        return ['messages' => []];
    }
    return json_decode(file_get_contents($file), true);
}

function writeConversation($workspace, $page, $data)
{
    $file = getConversationFile($workspace, $page);
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

function generateId()
{
    return base_convert(time(), 10, 36) . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);
}

// GET - Fetch messages
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $workspace = $_GET['workspace'] ?? '';
    $page = $_GET['page'] ?? '';

    if (!$workspace || !$page) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing workspace or page parameter']);
        exit;
    }

    $data = readConversation($workspace, $page);
    echo json_encode($data);
    exit;
}

// POST - Add message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $workspace = $input['workspace'] ?? '';
    $page = $input['page'] ?? '';
    $content = $input['content'] ?? '';
    $user = $input['user'] ?? 'Anonymous';
    $replyTo = $input['replyTo'] ?? null;

    if (!$workspace || !$page || !$content) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $data = readConversation($workspace, $page);

    $newMessage = [
        'id' => generateId(),
        'user' => $user,
        'content' => $content,
        'timestamp' => date('c'),
        'replyTo' => $replyTo
    ];

    $data['messages'][] = $newMessage;
    writeConversation($workspace, $page, $data);

    echo json_encode(['success' => true, 'message' => $newMessage]);
    exit;
}

// DELETE - Remove message
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $workspace = $_GET['workspace'] ?? '';
    $page = $_GET['page'] ?? '';
    $messageId = $_GET['id'] ?? '';

    if (!$workspace || !$page || !$messageId) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required parameters']);
        exit;
    }

    $data = readConversation($workspace, $page);
    $data['messages'] = array_values(array_filter($data['messages'], function ($m) use ($messageId) {
        return $m['id'] !== $messageId;
    }));
    writeConversation($workspace, $page, $data);

    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
