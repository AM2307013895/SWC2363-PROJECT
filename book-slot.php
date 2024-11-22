<?php
include 'db.php'; // Include database connection

// Define variables to store the form data (used for displaying in the booking summary)
$name = $email = $phone = $service = $film_type = $date = $time_slot = $total_cost = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $service = $_POST['service'];
    $film_type = $_POST['film-type'];
    $date = $_POST['date'];
    $time_slot = $_POST['time-slot'];
    $total_cost = $_POST['total-cost'];

    // Prepare and execute the INSERT query to save data into the database
    $sql = "INSERT INTO bookings (name, email, phone, service, film_type, date, time_slot, total_cost)
            VALUES (:name, :email, :phone, :service, :film_type, :date, :time_slot, :total_cost)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':service', $service);
    $stmt->bindParam(':film_type', $film_type);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time_slot', $time_slot);
    $stmt->bindParam(':total_cost', $total_cost);

    // Execute and check if the data insertion is successful
    if ($stmt->execute()) {
        $message = "Booking confirmed! Thank you for your order.";
    } else {
        $message = "Error confirming booking!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Slot - Analog Avenue</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Retaining your existing CSS design */
        .booking-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }
        .booking-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .booking-header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .booking-header p {
            color: #666;
        }
        .booking-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-section {
            margin-bottom: 30px;
        }
        .form-section h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }
        .service-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .service-option {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .service-option:hover {
            border-color: #337ab7;
        }
        .service-option.selected {
            border-color: #337ab7;
            background-color: #f0f8ff; /* Light blue background */
        }
        .time-slots {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 15px;
        }
        .time-slot {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
        }
        .time-slot:hover {
            border-color: #337ab7;
        }
        .time-slot.selected {
            border-color: #337ab7;
            background-color: #f0f8ff; /* Light blue background */
        }
        .submit-btn {
            background-color: #337ab7;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        .submit-btn:hover {
            background-color: #23527c;
        }
    </style>
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
            </ul>
        </nav>
    </header>

    <div class="booking-container">
        <div class="booking-header">
            <h1>Book a Service Slot</h1>
            <p>Select your preferred service and time slot for film developing or scanning</p>
        </div>

        <form class="booking-form" action="book-slot.php" method="POST">
            <div class="form-section">
                <h2>Personal Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2>Service Selection</h2>
                <div class="service-options">
                    <div class="service-option" onclick="selectService('Film Developing', 25)">
                        <h3>Film Developing</h3>
                        <p>Professional development of your film rolls</p>
                        <p class="service-price">From RM 25.00</p>
                    </div>
                    <div class="service-option" onclick="selectService('Film Scanning', 20)">
                        <h3>Film Scanning</h3>
                        <p>High-resolution scanning of your negatives</p>
                        <p class="service-price">From RM 20.00</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="film-type">Film Type</label>
                    <select id="film-type" name="film-type" required>
                        <option value="">Select Film Type</option>
                        <option value="35mm-color">35mm Color</option>
                        <option value="35mm-bw">35mm Black & White</option>
                        <option value="120-color">120 Color</option>
                        <option value="120-bw">120 Black & White</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Preferred Date</label>
                    <input type="date" id="date" name="date" value="<?= htmlspecialchars($date) ?>" required>
                </div>

                <div class="form-group">
                    <label for="time">Preferred Time Slot</label>
                    <div class="time-slots">
                        <div class="time-slot" onclick="selectTime('10:00 AM')">10:00 AM</div>
                        <div class="time-slot" onclick="selectTime('11:00 AM')">11:00 AM</div>
                        <div class="time-slot" onclick="selectTime('12:00 PM')">12:00 PM</div>
                        <div class="time-slot" onclick="selectTime('1:00 PM')">1:00 PM</div>
                    </div>
                    <input type="hidden" name="time-slot" id="time-slot" value="<?= htmlspecialchars($time_slot) ?>" required>
                </div>

                <div class="form-group">
                    <label for="total-cost">Total Cost (RM)</label>
                    <input type="text" id="total-cost" name="total-cost" value="<?= htmlspecialchars($total_cost) ?>" readonly>
                </div>
            </div>

            <div class="form-section">
                <button type="submit" class="submit-btn">Confirm Booking</button>
            </div>
        </form>

        <?php if (isset($message)) { ?>
            <div class="alert">
                <p><?= $message ?></p>
            </div>
        <?php } ?>
    </div>

    <script>
        // Function to select a service
        function selectService(service, price) {
            // Reset all service options (remove the highlight)
            const serviceOptions = document.querySelectorAll('.service-option');
            serviceOptions.forEach(option => {
                option.classList.remove('selected');
            });

            // Set the selected service and price
            document.getElementById('service').value = service;
            document.getElementById('total-cost').value = price.toFixed(2);

            // Add the 'selected' class to the clicked service option
            event.currentTarget.classList.add('selected');
        }

        // Function to select a time slot
        function selectTime(time) {
            // Reset all time slots (remove the highlight)
            const timeSlots = document.querySelectorAll('.time-slot');
            timeSlots.forEach(slot => {
                slot.classList.remove('selected');
            });

            // Set the selected time slot
            document.getElementById('time-slot').value = time;

            // Add the 'selected' class to the clicked time slot
            event.currentTarget.classList.add('selected');
        }
    </script>
</body>
</html>
