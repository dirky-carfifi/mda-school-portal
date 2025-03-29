<?php
include_once "../config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $id = isset($_POST['event_id']) ? $_POST['event_id'] : null;
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $event_date = isset($_POST['event_date']) ? $_POST['event_date'] : null;
    $location = isset($_POST['location']) ? $_POST['location'] : '';
    $important = isset($_POST['important']) ? (int) $_POST['important'] : 0; // Dropdown value
    $upcoming = isset($_POST['upcoming']) ? (int) $_POST['upcoming'] : 0; // Dropdown value

// Check if `existingImage` is set, or default it
$final_image = isset($_POST['existingImage']) ? $_POST['existingImage'] : '';

// Handle file upload if a new image is provided
if (!empty($_FILES['newImage']['name'])) {
    $image = $_FILES['newImage'];
    $dir = '../uploads/';
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp');
    if (in_array($ext, $valid_extensions)) {
        $newImageName = uniqid() . '.' . $ext;
        if (move_uploaded_file($image['tmp_name'], $dir . $newImageName)) {
            $image_path = '/uploads/' . $newImageName; // Use /uploads/ as the image path
        } else {
            echo "Error uploading image.";
            exit;
        }
    } else {
        echo "Invalid image format.";
        exit;
    }
}

// Use the final image path for further processing
if (!empty($image_path)) {
    $final_image = $image_path; // Update final_image if new image is uploaded
}

    // Ensure `id` is valid
    if (empty($id)) {
        echo "Error: Event ID is required.";
        exit;
    }

    // Prepare the update query
    $query = "
        UPDATE event_list 
        SET 
            title = ?, 
            description = ?, 
            event_date = ?, 
            location = ?, 
            important = ?, 
            upcoming = ?, 
            image = ? 
        WHERE id = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    // Bind parameters and execute
    $stmt->bind_param(
        "ssssissi",
        $title,
        $description,
        $event_date,
        $location,
        $important,
        $upcoming,
        $final_image,
        $id
    );

    if ($stmt->execute()) {
        echo "true";
    } else {
        echo "Error executing query: " . $stmt->error;
    }
} else {
    echo "Invalid request method.";
}
?>
