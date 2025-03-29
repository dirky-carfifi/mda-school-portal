<?php
// Start the session
session_start();

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$db = "adminlogin";

$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $username = mysqli_real_escape_string($data, $_POST["username"]);
    $password = $_POST["password"]; // Plain password entered by the user

    // Query to fetch user details based on username
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $data->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password (use password_verify if passwords are hashed)
        if ($password == $row["password"]) { // Replace this with password_verify() for hashed passwords
            // Store user information in session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['usertype'] = $row['usertype'];

            // Redirect user based on their role
            if ($row["usertype"] == "user") {
                header("Location: admin/dashboard.php"); // Update with the actual user dashboard path
            } elseif ($row["usertype"] == "admin") {
                header("Location: admin/dashboard.php"); // Update with the actual admin dashboard path
            }
            exit(); // Stop further script execution after redirection
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}

// Close the database connection
mysqli_close($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" type="image/jpg" href="images/josh.jpg">
    <title>Monli Admin</title>
    <link rel="stylesheet" href="stylesheets/adminlogin.css" />
    <style>
    /* Blue Back Button Styling */
    .back-btn {
      display: inline-flex;
      align-items: center;
      padding: 12px 20px;
      margin: 20px 0;
      font-size: 16px;
      font-weight: bold;
      color: #fff;
      text-decoration: none;
      background: linear-gradient(135deg,rgb(219, 37, 189),rgb(141, 10, 82));
      border-radius: 30px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      transition: transform 0.2s ease, box-shadow 0.3s ease;
    }

    .back-btn:hover {
      transform: translateX(-5px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .back-btn i {
      margin-right: 8px;
      font-size: 20px;
      transition: transform 0.2s ease;
    }

    .back-btn:hover i {
      transform: translateX(-3px);
    }
  </style>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <header>
        <h1>Admin Login</h1>
    </header>

    <main>
        <div class="logo">
            <img src="images/logo.png" alt="SK Barangay Sta. Ana Logo" />
        </div>

        <section id="admin-login">
            <h2>Monlimar Admin</h2>
            <form action="admin-login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required />
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required />
                </div>
                <button type="submit" class="btn">Sign In</button>
            </form>

            <?php
            // Display error message if login fails
            if (isset($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            ?>
             <!-- Blue Back Button -->
      <a href="portal/dashboard.html" class="back-btn">
        <i class="fas fa-arrow-left"></i> Go Back
      </a>
    </section>
    </main>
        </section>
    </main>

    
</body>
</html>
