<?php
// Database connection
require_once 'config.php'; // Your DB connection

// Get the order ID from the URL
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;

if ($order_id == 0) {
    echo "<p>Invalid or missing order ID.</p>";
    exit();
}

// Fetch order details from the database
$order_query = "SELECT * FROM orders WHERE id = $order_id";
$order_result = $conn->query($order_query);

if ($order_result->num_rows > 0) {
    $order = $order_result->fetch_assoc();
} else {
    echo "<p>Order not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <h1>Order Confirmation</h1>
    <p>Order ID: #<?php echo $order['id']; ?></p>
    <p>Status: <?php echo $order['status']; ?></p>
    <p>Total Amount: RM <?php echo number_format($order['total_amount'], 2); ?></p>

    <h3>Billing Address:</h3>
    <p><?php echo htmlspecialchars($order['billing_address']); ?></p>
    <p>Email: <?php echo htmlspecialchars($order['email']); ?></p>

    <h3>Order Items:</h3>
    <ul>
        <?php
        $order_items_query = "SELECT * FROM order_items WHERE order_id = " . $order['id'];
        $order_items_result = $conn->query($order_items_query);

        while ($item = $order_items_result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($item['product_name']) . " - RM " . number_format($item['price'], 2) . "</li>";
        }
        ?>
    </ul>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
