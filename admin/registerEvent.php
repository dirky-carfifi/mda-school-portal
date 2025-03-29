<?php
include_once "../config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $participantType = $_POST['participantType'];
    $eventTitle = $_POST['eventTitle'] ?? null;

    if ($participantType === 'individual') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $profileImage = $_FILES['profileImage'] ?? null;

        if ($profileImage && $profileImage['tmp_name']) {
            $uploadDir = '../uploads/';
            $imageName = uniqid() . '_' . basename($profileImage['name']);
            move_uploaded_file($profileImage['tmp_name'], $uploadDir . $imageName);
            $profileImagePath = '/uploads/' . $imageName;
        }

        $sql = "INSERT INTO event_registrations (participantType, name, email, profileImage, eventTitle) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $participantType, $name, $email, $profileImagePath, $eventTitle);
        $stmt->execute();

    } elseif ($participantType === 'group') {
        $groupName = $_POST['groupName'];
        $memberNames = $_POST['memberName'] ?? [];
        $memberEmails = $_POST['memberEmail'] ?? [];
        $memberImages = $_FILES['memberProfileImage'] ?? [];

        foreach ($memberNames as $index => $memberName) {
            $memberEmail = $memberEmails[$index];
            $profileImagePath = null;

            if (isset($memberImages['tmp_name'][$index])) {
                $uploadDir = '../uploads/';
                $imageName = uniqid() . '_' . basename($memberImages['name'][$index]);
                move_uploaded_file($memberImages['tmp_name'][$index], $uploadDir . $imageName);
                $profileImagePath = '/uploads/' . $imageName;
            }

            $sql = "INSERT INTO event_registrations (participantType, groupName, name, email, profileImage, eventTitle) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $participantType, $groupName, $memberName, $memberEmail, $profileImagePath, $eventTitle);
            $stmt->execute();
        }
    }

    echo "Registration successful.";
}
?>