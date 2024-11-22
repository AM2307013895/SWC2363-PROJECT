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

// Fetch product data from the database for cart display
$total = 0; // Initialize total
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];
    $product_ids = implode(",", array_keys($cart_items)); // Get all product IDs in the cart
    $sql = "SELECT id, name, price, image_url FROM products WHERE id IN ($product_ids)";
    $result = $conn->query($sql);
}

// Handle the remove action (if "Remove" button is clicked)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_product_id'])) {
    $remove_product_id = $_POST['remove_product_id'];

    // Remove the item from the cart
    if (isset($_SESSION['cart'][$remove_product_id])) {
        unset($_SESSION['cart'][$remove_product_id]);
    }

    // Refresh the page after removing the item
    header("Location: cart.php");
    exit();
}

// Update cart quantity if Add or Minus button is clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if ($action == 'add') {
        // Increase quantity by 1
        $_SESSION['cart'][$product_id] += 1;
    } elseif ($action == 'minus' && $_SESSION['cart'][$product_id] > 1) {
        // Decrease quantity by 1, but don't go below 1
        $_SESSION['cart'][$product_id] -= 1;
    }

    // Refresh the page after updating the cart
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Analog Avenue</title>
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

    <div class="cart-container">
        <div class="cart-header">
            <h1>Your Shopping Cart</h1>
            <span><?php echo count($_SESSION['cart']); ?> items</span>
        </div>

        <div class="cart-items">
            <?php
            if (isset($result) && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $product_id = $row['id'];
                    $quantity = $cart_items[$product_id];
                    $item_total = $row['price'] * $quantity; // Calculate the total price for this item
                    $total += $item_total; // Add the item total to the overall total

                    echo '<div class="cart-item">';
                    echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<div class="item-details">';
                    echo '<h2>' . htmlspecialchars($row['name']) . '</h2>';
                    echo '<p>Price: RM ' . number_format($row['price'], 2) . '</p>';
                    echo '<div class="item-quantity">';
                    echo '<form action="cart.php" method="POST" style="display:inline;">';
                    echo '<button type="submit" name="action" value="minus">-</button>';
                    echo '<input type="number" class="quantity-input" value="' . $quantity . '" min="1" readonly>';
                    echo '<button type="submit" name="action" value="add">+</button>';
                    echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                    echo '</form>';
                    echo '</div>';
                    echo '<p>Total: RM ' . number_format($item_total, 2) . '</p>';
                    echo '<form action="cart.php" method="POST" style="display:inline;">';
                    echo '<button type="submit" class="remove-item">Remove</button>';
                    echo '<input type="hidden" name="remove_product_id" value="' . $product_id . '">';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>Your cart is empty.</p>";
            }
            ?>
        </div>

        <div class="cart-summary">
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
            <button class="checkout-btn">Proceed to Checkout</button>
        </div>

        <div class="continue-shopping">
            <a href="products.php">Continue Shopping</a>
        </div>
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
