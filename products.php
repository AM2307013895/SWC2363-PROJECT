<?php
// Start the session
session_start();

// database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "analog_avenue";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product data from the database, including the id
$sql = "SELECT id, name, description, price, image_url FROM products";
$result = $conn->query($sql);

// Handle adding product to cart
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Check if the product_id is valid and add it to the session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If the product is already in the cart, increase the quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    header("Location: cart.php"); // Redirect to cart page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Analog Avenue - Products</title>
  <link rel="stylesheet" href="styles.css">
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
        <li><a href="products.php" class="active">Products</a></li>
        <li><a href="services.html">Services</a></li>
        <li><a href="gallery.html">Gallery</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="login.html">Profile</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section class="products">
      <h1>Our Products</h1>
      <div class="product-grid">
        <?php
        // Display products from the database
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product">';
                echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<h2>' . htmlspecialchars($row['name']) . '</h2>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<p>Price: RM ' . number_format($row['price'], 2) . '</p>';
                // Change button text and redirect URL to cart.php with product id
                echo '<a href="products.php?product_id=' . $row['id'] . '"><button>Add to Cart</button></a>';
                echo '</div>';
            }
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
      </div>
    </section>
  </main>

  <section class="call-to-action">
    <h2>Get in Touch</h2>
    <p>For any inquiries regarding our services, you are welcome to contact us.</p>
    <a href="contact.html"><button>Contact Us</button></a>
  </section>
  
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
            <li><a href="login.html">Profile</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h4>Follow Us</h4>
        <ul>
          <li><a href="#">Facebook</a></li>
          <li><a href="#">Twitter</a></li>
          <li><a href="#">Instagram</a></li>
        </ul>
      </div>
    </div>
  </footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
