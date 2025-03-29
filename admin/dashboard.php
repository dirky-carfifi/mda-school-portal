<?php
ob_start(); // Start output buffering
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../admin-login.php");
    exit();
}

include_once "../config/dbconnect.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/style-1.css">
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <?php date_default_timezone_set('Asia/Manila'); ?>
        <span><?= date('l, F j, Y') ?></span>
        <div class="ml-auto">
            <ul class="navbar-nav">
                <li class="nav-item dropdown dd-icon">
                    <span class="dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= htmlspecialchars($_SESSION['first_name']) ?> <?= htmlspecialchars($_SESSION['last_name']) ?>
                    </span>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../logout.php" onclick="return confirm('Are you sure you want to logout?');">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="sidebar-header">Admin Dashboard</h2>
        <a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
        <a href="#events" onclick="showEvents()"><i class="fa fa-th"></i> Events</a>
        <a href="#participants" onclick="showEventParticipants()"><i class="fa fa-list"></i> Event Participants</a> 
        <a href="#recentActivities" onclick="showRecentActivities()"><i class="fa fa-bell"></i> Recent Activities</a>
    </div>

    <div class="dashboard">
        <div class="row">
            <!-- Card 2 -->
            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-icon"><i class="fa fa-calendar"></i></div>
                    <div class="dashboard-card-content">
                        <h5>Total Events</h5>
                        <div class="dashboard-card-row">
                            <p>Count of all events created in the system.</p>
                            <span class="dashboard-card-value">
                                <?php
                                $sql = "SELECT * FROM event_list";
                                $result = $conn->query($sql);
                                echo $result->num_rows;
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Recent Activities Section -->
            <div id="recentActivitiesSection" style="display: none;" class="col-md-8">
                <h2>Recent Activities</h2>
                <form id="activityForm">
                    <label for="activityTitle">Activity Title:</label>
                    <input type="text" id="activityTitle" required class="form-control">
                    
                    <label for="activityDetails">Activity Details:</label>
                    <textarea id="activityDetails" rows="3" required class="form-control"></textarea>
                    <button type="button" onclick="postActivity()" class="btn btn-primary mt-2">Post Activity</button>
                </form>
                <h3 class="mt-4">Posted Activities</h3>
                <ul id="activityList" class="list-group"></ul>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-icon"><i class="fa fa-users"></i></div>
                    <div class="dashboard-card-content">
                        <h5>Total Event Participants</h5>
                        <div class="dashboard-card-row">
                            <p>Registered participants across all events.</p>
                            <span class="dashboard-card-value">
                                <?php
                                $sql = "SELECT * FROM event_registrations";
                                $result = $conn->query($sql);
                                echo $result->num_rows;
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/ajaxWork.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script>
        function showRecentActivities() {
            document.getElementById("recentActivitiesSection").style.display = "block";
        }

        function postActivity() {
            const title = document.getElementById("activityTitle").value;
            const details = document.getElementById("activityDetails").value;

            if (!title || !details) {
                alert("Please fill in both fields.");
                return;
            }

            let activities = JSON.parse(localStorage.getItem("recentActivities")) || [];
            activities.push({ title, details, date: new Date().toLocaleString() });
            localStorage.setItem("recentActivities", JSON.stringify(activities));

            loadActivities();
            document.getElementById("activityTitle").value = "";
            document.getElementById("activityDetails").value = "";
        }

        function loadActivities() {
            const activityList = document.getElementById("activityList");
            activityList.innerHTML = "";

            let activities = JSON.parse(localStorage.getItem("recentActivities")) || [];
            activities.forEach((activity) => {
                const li = document.createElement("li");
                li.className = "list-group-item";
                li.innerHTML = `<strong>${activity.title}</strong> - ${activity.details} <br> <small>${activity.date}</small>`;
                activityList.appendChild(li);
            });
        }

        window.onload = loadActivities;
    </script>
</body>
</html>
