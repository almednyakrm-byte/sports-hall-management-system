<?php
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'You must be logged in to access this resource']);
    exit;
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize the database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize the input
    $membership_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($membership_id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid membership ID']);
        exit;
    }

    // Prepare the SQL query
    $stmt = $pdo->prepare('SELECT * FROM memberships WHERE id = :id');
    $stmt->bindParam(':id', $membership_id);

    // Execute the query
    $stmt->execute();

    // Process the output
    $membership = $stmt->fetch();
    if ($membership === false) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Membership not found']);
        exit;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($membership);
    exit;
}

// Handle POST requests
if ($method == 'POST') {
    // Validate and sanitize the input
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request body']);
        exit;
    }

    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Only admins can create new memberships']);
        exit;
    }

    // Prepare the SQL query
    $stmt = $pdo->prepare('INSERT INTO memberships (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);

    // Execute the query
    try {
        $stmt->execute();
        $membership_id = $pdo->lastInsertId();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['id' => $membership_id]);
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create membership']);
        exit;
    }
}

// Handle PUT requests
if ($method == 'PUT') {
    // Validate and sanitize the input
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request body']);
        exit;
    }

    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Only admins can update memberships']);
        exit;
    }

    // Prepare the SQL query
    $stmt = $pdo->prepare('UPDATE memberships SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);

    // Execute the query
    try {
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Membership updated successfully']);
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update membership']);
        exit;
    }
}

// Handle DELETE requests
if ($method == 'DELETE') {
    // Validate and sanitize the input
    $membership_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($membership_id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid membership ID']);
        exit;
    }

    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Only admins can delete memberships']);
        exit;
    }

    // Prepare the SQL query
    $stmt = $pdo->prepare('DELETE FROM memberships WHERE id = :id');
    $stmt->bindParam(':id', $membership_id);

    // Execute the query
    try {
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Membership deleted successfully']);
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete membership']);
        exit;
    }
}

// Handle invalid request methods
http_response_code(405);
header('Content-Type: application/json');
echo json_encode(['error' => 'Method not allowed']);
exit;