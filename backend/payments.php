<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    $data = $_POST;
}

// Connect to database
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// GET all payments
if ($method == 'GET') {
    // Validate and sanitize input
    $page = isset($data['page']) ? (int) $data['page'] : 1;
    $limit = isset($data['limit']) ? (int) $data['limit'] : 10;

    // SQL query structure
    $stmt = $pdo->prepare('SELECT * FROM payments LIMIT :offset, :limit');
    $stmt->bindParam(':offset', ($page - 1) * $limit, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    // Output processing
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($payments);
}

// POST new payment
elseif ($method == 'POST') {
    // Validate and sanitize input
    $amount = isset($data['amount']) ? (float) $data['amount'] : null;
    $description = isset($data['description']) ? trim($data['description']) : null;

    if (empty($amount) || empty($description)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid request data']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('INSERT INTO payments (amount, description) VALUES (:amount, :description)');
    $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->execute();

    // Output processing
    $paymentId = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Payment created successfully', 'payment_id' => $paymentId]);
}

// PUT update payment
elseif ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden']);
        exit;
    }

    // Validate and sanitize input
    $paymentId = isset($data['payment_id']) ? (int) $data['payment_id'] : null;
    $amount = isset($data['amount']) ? (float) $data['amount'] : null;
    $description = isset($data['description']) ? trim($data['description']) : null;

    if (empty($paymentId) || (empty($amount) && empty($description))) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid request data']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('UPDATE payments SET amount = :amount, description = :description WHERE payment_id = :payment_id');
    $stmt->bindParam(':payment_id', $paymentId, PDO::PARAM_INT);
    if (!empty($amount)) {
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
    } else {
        $stmt->bindParam(':amount', $amount, PDO::PARAM_NULL);
    }
    if (!empty($description)) {
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    } else {
        $stmt->bindParam(':description', $description, PDO::PARAM_NULL);
    }
    $stmt->execute();

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Payment updated successfully']);
}

// DELETE payment
elseif ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden']);
        exit;
    }

    // Validate and sanitize input
    $paymentId = isset($data['payment_id']) ? (int) $data['payment_id'] : null;

    if (empty($paymentId)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid request data']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('DELETE FROM payments WHERE payment_id = :payment_id');
    $stmt->bindParam(':payment_id', $paymentId, PDO::PARAM_INT);
    $stmt->execute();

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Payment deleted successfully']);
}

// Invalid request method
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Method not allowed']);
}

$pdo = null;