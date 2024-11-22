<?php
// Start the session
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

// Initialize total amount
$total = 0;

// Check if cart is set and not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];
    $product_ids = implode(",", array_keys($cart_items)); // Get all product IDs from the cart
    $sql = "SELECT id, name, price, image_url FROM products WHERE id IN ($product_ids)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display cart items
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['id'];
            $quantity = $cart_items[$product_id];
            $item_total = $row['price'] * $quantity;
            $total += $item_total;
        }
    } else {
        echo "<p>No products found in your cart.</p>";
    }
} else {
    echo "<p>Your cart is empty.</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Analog Avenue</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="stylesCart.css">
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
                <li><a href="products.php">Products</a></li>
                <li><a href="services.html">Services</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="login.html">Profile</a></li>
            </ul>
        </nav>
    </header>

    <div class="checkout-container">
        <h1>Checkout</h1>

        <div class="cart-summary">
            <h2>Your Cart Items</h2>

            <!-- Cart Items -->
            <div class="cart-items">
                <?php
                if (isset($result) && $result->num_rows > 0) {
                    $cart_items = $_SESSION['cart'];
                    $result->data_seek(0); // Reset the result pointer
                    while ($row = $result->fetch_assoc()) {
                        $product_id = $row['id'];
                        $quantity = $cart_items[$product_id];
                        $item_total = $row['price'] * $quantity;

                        echo '<div class="cart-item">';
                        echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '<div class="item-details">';
                        echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                        echo '<p>Price: RM ' . number_format($row['price'], 2) . '</p>';
                        echo '<p>Quantity: ' . $quantity . '</p>';
                        echo '<p>Total: RM ' . number_format($item_total, 2) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-container">
            <div class="summary-item">
                <span>Subtotal</span>
                <span>RM <?php echo number_format($total, 2); ?></span>
            </div>
            <div class="summary-item">
                <span>Shipping</span>
                <span>RM 10.00</span>
            </div>
            <div class="summary-item summary-total">
                <span>Total</span>
                <span>RM <?php echo number_format($total + 10, 2); ?></span> <!-- Total including shipping -->
            </div>
        </div>

        <!-- Billing Information Section -->
        <div class="billing-info">
            <h2>Billing Information</h2>
            <!-- Billing form can be added here -->
        </div>

        <button class="checkout-btn">Proceed to Payment</button>
    </div>

    <footer>
        <div class="footer-grid">
            <div class="footer-column">
                <h4>About Us</h4>
                <p>We are a Kuala Lumpur-based film lab specializing in film development, scanning, and offering a range of films for purchase. Whether you're capturing memories on disposable cameras or using professional film stocks, we're here to bring your moments to life.</p>
            </div>
            <div class="footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="services.html">Services</a></li>
                    <li><a href="gallery.html">Gallery</a></li>
                    <li><a href="about.html">About</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Contact Us</h4>
                <p>Email: support@analogavenue.com</p>
                <p>Phone: +60 12-345 6789</p>
            </div>
        </div>
        <p>&copy; 2023 Analog Avenue. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
