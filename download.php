<?php
require_once 'config.php';

if (!isset($_GET['type']) || !isset($_GET['id'])) {
    die("Invalid request.");
}

$type = sanitize($_GET['type']);
$id = intval($_GET['id']);

if ($type === 'book') {
    $sql = "SELECT * FROM books WHERE id = $id AND is_free = 1 AND file_url IS NOT NULL";
    $result = mysqli_query($conn, $sql);
    
    if ($book = mysqli_fetch_assoc($result)) {
        // Update download count
        mysqli_query($conn, "UPDATE books SET download_count = download_count + 1 WHERE id = $id");
        
        // Set headers for file download
        $file_path = $book['file_url'];
        $file_name = basename($file_path);
        
        if (file_exists($file_path)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            die("File not found.");
        }
    } else {
        die("Book not found or not available for free download.");
    }
} else {
    die("Invalid download type.");
}
?>