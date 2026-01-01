<?php
require_once 'config.php';

if (!isset($_GET['type']) || !isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$type = sanitize($_GET['type']);
$id = intval($_GET['id']);
$price = isset($_GET['price']) ? floatval($_GET['price']) : 0;
$name = isset($_GET['name']) ? urldecode($_GET['name']) : '';

if ($type === 'subject') {
    $sql = "SELECT * FROM subjects WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $item = mysqli_fetch_assoc($result);
    $item_type = 'Subject';
} elseif ($type === 'book') {
    $sql = "SELECT * FROM books WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $item = mysqli_fetch_assoc($result);
    $item_type = 'Book';
} else {
    die("Invalid item type.");
}

if (!$item) {
    die("Item not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - <?php echo htmlspecialchars($name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-shopping-cart me-2"></i> Checkout</h3>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-4">Order Summary</h4>
                        
                        <div class="mb-4 p-3 border rounded">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Item:</span>
                                <strong><?php echo htmlspecialchars($name); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Type:</span>
                                <span class="badge bg-info"><?php echo $item_type; ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Price:</span>
                                <strong class="text-primary">₹<?php echo number_format($price, 2); ?></strong>
                            </div>
                        </div>
                        
                        <h5 class="mb-3">Payment Methods</h5>
                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="razorpay" checked>
                                <label class="form-check-label" for="razorpay">
                                    <i class="fas fa-credit-card me-2"></i> Credit/Debit Card (Razorpay)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="upi">
                                <label class="form-check-label" for="upi">
                                    <i class="fas fa-mobile-alt me-2"></i> UPI
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="cod">
                                <label class="form-check-label" for="cod">
                                    <i class="fas fa-money-bill-wave me-2"></i> Cash on Delivery (For Physical Books)
                                </label>
                            </div>
                        </div>
                        
                        <h5 class="mb-3">Contact Information</h5>
                        <form id="checkoutForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" required>
                            </div>
                            <div class="mb-4">
                                <label for="address" class="form-label">Address (For Physical Delivery)</label>
                                <textarea class="form-control" id="address" rows="3"></textarea>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-lock me-2"></i> Proceed to Payment
                                </button>
                                <a href="subject.php?slug=<?php echo $subject_slug; ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Course
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would integrate with your payment gateway
            // For now, just show an alert
            alert('Payment integration would go here. Redirecting to payment gateway...');
            
            // For Razorpay integration example:
            /*
            var options = {
                "key": "YOUR_RAZORPAY_KEY",
                "amount": "<?php echo $price * 100; ?>", // in paise
                "currency": "INR",
                "name": "Prep with Daljeet",
                "description": "<?php echo $name; ?>",
                "handler": function(response) {
                    alert("Payment successful! Payment ID: " + response.razorpay_payment_id);
                    // Save order to database
                    window.location.href = "payment_success.php";
                }
            };
            var rzp = new Razorpay(options);
            rzp.open();
            */
        });
    </script>
</body>
</html>