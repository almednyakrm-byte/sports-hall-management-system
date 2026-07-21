<?php
// Import database connection
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get the user role
$userRole = $_SESSION['user_role'];

// Handle different HTTP methods
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Get all bookings or a specific booking by id
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $pdo->prepare('SELECT * FROM bookings WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $booking = $stmt->fetch();
            if ($booking) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($booking);
            } else {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Booking not found']);
            }
        } else {
            $stmt = $pdo->prepare('SELECT * FROM bookings');
            $stmt->execute();
            $bookings = $stmt->fetchAll();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($bookings);
        }
        break;

    case 'POST':
        // Create a new booking
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['user_id']) || !isset($data['room_id']) || !isset($data['start_date']) || !isset($data['end_date'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }
        $stmt = $pdo->prepare('INSERT INTO bookings (user_id, room_id, start_date, end_date) VALUES (:user_id, :room_id, :start_date, :end_date)');
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':room_id', $data['room_id']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->execute();
        $id = $pdo->lastInsertId();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['id' => $id]);
        break;

    case 'PUT':
        // Update a booking
        if ($userRole !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        $id = $_GET['id'];
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['user_id']) || !isset($data['room_id']) || !isset($data['start_date']) || !isset($data['end_date'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }
        $stmt = $pdo->prepare('UPDATE bookings SET user_id = :user_id, room_id = :room_id, start_date = :start_date, end_date = :end_date WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':room_id', $data['room_id']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Booking updated successfully']);
        break;

    case 'DELETE':
        // Delete a booking
        if ($userRole !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        $id = $_GET['id'];
        $stmt = $pdo->prepare('DELETE FROM bookings WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Booking deleted successfully']);
        break;

    default:
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Method not allowed']);
        break;
}