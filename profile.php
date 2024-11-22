<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "analog_avenue";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, phone, address FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    // User data found
    $user = $result->fetch_assoc();
} else {
    // Handle case when user data is not found
    $user = null;
    $error_message = "User data not found.";
}

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user !== null) {
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $update_sql = "UPDATE users SET phone = ?, address = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $phone, $address, $user_id);

    if ($update_stmt->execute()) {
        $success_message = "Profile updated successfully!";
        // Refresh user data
        $stmt->execute();
        $user = $result->fetch_assoc();
    } else {
        $error_message = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Dashboard - Analog Avenue</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="stylesFile.css">
</head>
<body>
    <header>
        <div class="banner">
            <a href="index.html">
                <img src="Logo/White Logo/LogoWhiteTP.png" alt="AALogo">
            </a>
        </div>
        <nav>
            <ul class="menu">
                <li><a href="products.html">Products</a></li>
                <li><a href="services.html">Services</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="user-info">
                <img src="Images/userAvatar.png" alt="User Avatar" class="user-avatar">
                <div class="user-details">
                    <h2><?php echo $user ? htmlspecialchars($user['name']) : 'User'; ?></h2>
                    <p><?php echo $user ? htmlspecialchars($user['email']) : ''; ?></p>
                    <p><?php echo $user ? htmlspecialchars($user['phone']) : ''; ?></p>
                    <p><?php echo $user ? htmlspecialchars($user['address']) : ''; ?></p>
                </div>
            </div>
            <button class="edit-profile-btn" onclick="openModal()">Edit Profile</button>
        </div>

        <div class="dashboard-content">
        <div class="dashboard-section">
            <h3>Order Overview</h3>
            <div class="order-status">
                <div class="status-item pending">
                    <div class="status-icon">&#x23F3;</div> <!-- Clock icon for Pending -->
                    <div class="status-info">
                        <h4>Pending</h4>
                        <p>2 Orders</p>
                    </div>
                </div>
                <div class="status-item paid">
                    <div class="status-icon">&#x2714;</div> <!-- Checkmark icon for Paid -->
                    <div class="status-info">
                        <h4>Paid</h4>
                        <p>3 Orders</p>
                    </div>
                </div>
                <div class="status-item delivered">
                    <div class="status-icon">&#x1F4E6;</div> <!-- Package icon for Delivered -->
                    <div class="status-info">
                        <h4>Delivered</h4>
                        <p>5 Orders</p>
                    </div>
                </div>
            </div>

            <div class="recent-orders">
                <h4>Recent Orders</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#12345</td>
                            <td>2023-05-15</td>
                            <td>Delivered</td>
                            <td>RM 150.00</td>
                        </tr>
                        <tr>
                            <td>#12346</td>
                            <td>2023-05-18</td>
                            <td>Paid</td>
                            <td>RM 89.99</td>
                        </tr>
                        <tr>
                            <td>#12347</td>
                            <td>2023-05-20</td>
                            <td>Pending</td>
                            <td>RM 210.50</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


            <div class="dashboard-section">
                <h3>Account Information</h3>
                <p><strong>Name:</strong> <?php echo $user ? htmlspecialchars($user['name']) : ''; ?></p>
                <p><strong>Email:</strong> <?php echo $user ? htmlspecialchars($user['email']) : ''; ?></p>
                <p><strong>Phone:</strong> <?php echo $user ? htmlspecialchars($user['phone']) : ''; ?></p>
                <p><strong>Address:</strong> <?php echo $user ? htmlspecialchars($user['address']) : ''; ?></p>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="edit-profile-modal" id="editProfileModal">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h3>Edit Profile</h3>
        <form method="POST">
            <div class="edit-form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?php echo $user ? htmlspecialchars($user['phone']) : ''; ?>" required>
            </div>
            <div class="edit-form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?php echo $user ? htmlspecialchars($user['address']) : ''; ?>" required>
            </div>
            <button type="submit" class="save-btn">Save Changes</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2023 Analog Avenue. All rights reserved.</p>
    </footer>

    <script>
        const modal = document.getElementById('editProfileModal');
        const overlay = document.getElementById('modalOverlay');

        function openModal() {
            modal.style.display = 'block';
            overlay.style.display = 'block';
        }

        function closeModal() {
            modal.style.display = 'none';
            overlay.style.display = 'none';
        }
    </script>
</body>
</html>
