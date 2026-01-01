<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['id']) && isset($data['type'])) {
        $id = intval($data['id']);
        $type = sanitize($data['type']);
        
        if ($type === 'book') {
            mysqli_query($conn, "UPDATE books SET download_count = download_count + 1 WHERE id = $id");
        } elseif ($type === 'speaking_example') {
            mysqli_query($conn, "UPDATE speaking_examples SET download_count = download_count + 1 WHERE id = $id");
        }
        
        echo json_encode(['success' => true, 'message' => 'Download tracked']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
    }
}
?>