<?php
include_once "config/dbconnect.php";

$query = "SELECT * FROM event_list ORDER BY id DESC";
$result = $conn->query($query);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Prepend "." to the image path
        $imagePath = '.' . $row['image'];

        $events[] = [
            'title' => $row['title'],
            'event_date' => $row['event_date'],
            'description' => $row['description'],
            'location' => $row['location'],
            'image' => $imagePath, // Use updated path
            'important' => (int)$row['important'],
            'upcoming' => (int)$row['upcoming']
        ];
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($events);
?>
