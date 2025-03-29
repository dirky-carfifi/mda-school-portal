<?php
include_once "../config/dbconnect.php";

// Fetch events occurring today or in the future
$sql = "SELECT title, location, DATE_FORMAT(event_date, '%b %d, %Y') as date
        FROM event_list 
        WHERE event_date >= CURDATE() 
        ORDER BY event_date ASC 
        LIMIT 10";
$result = $conn->query($sql);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'title' => $row['title'],
            'date' => $row['date'],
            'location' => $row['location']
        ];
    }
}
echo json_encode($events);
?>