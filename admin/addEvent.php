<?php
include_once "../config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = isset($_POST['event_name']) ? $_POST['event_name'] : '';
    $event_date = isset($_POST['event_date']) ? $_POST['event_date'] : null;
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $location = isset($_POST['location']) ? $_POST['location'] : '';
    $important = isset($_POST['important']) ? (int)$_POST['important'] : 0;
    $upcoming = isset($_POST['upcoming']) ? (int)$_POST['upcoming'] : 0;

    $image_path = '';

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $dir = '../uploads/'; // Directory to store the uploaded image
        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp');
        
        if (in_array($ext, $valid_extensions)) {
            $image_name = uniqid() . '.' . $ext;
            if (move_uploaded_file($image['tmp_name'], $dir . $image_name)) {
                $image_path = '/uploads/' . $image_name; // Save root-relative path to the database
            } else {
                echo "Error uploading image.";
                exit;
            }
        } else {
            echo "Invalid image format.";
            exit;
        }
    }

    // Insert event into database
    $query = "INSERT INTO event_list (title, event_date, description, location, important, upcoming, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    $stmt->bind_param(
        "ssssiss",
        $event_name,
        $event_date,
        $description,
        $location,
        $important,
        $upcoming,
        $image_path
    );

    if ($stmt->execute()) {
        echo "true";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid request method.";
}
?>
