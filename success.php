<?php
session_start();
include 'db.php';       // Your DB connection
require_once 'config.php'; // Aamarpay configuration

// Check if data is posted from Aamarpay
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mer_txnid'])) {

    $mer_txnid = $_POST['mer_txnid']; // This is your Invoice Number

    // 1. Verify the transaction using API
    $verify_url = VERIFY_URL . "?request_id=$mer_txnid&store_id=" . STORE_ID . "&signature_key=" . SIGNATURE_KEY . "&type=json";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $verify_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL check for sandbox
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    // 2. Check Payment Status
    if (isset($result['pay_status']) && $result['pay_status'] == 'Successful') {
        
        $tran_id = $conn->real_escape_string($result['mer_txnid']);
        $amount_paid = $result['amount'];

        // 3. Update Database: Mark order as 'paid'
        // We update 'payment_status' in the 'orders' table where 'invoice_number' matches
        $sql = "UPDATE orders SET payment_status = 'paid' WHERE invoice_number = '$tran_id'";
        
        if (mysqli_query($conn, $sql)) {
            // 4. Clear the Cart upon success
            if (isset($_SESSION['cart'])) {
                unset($_SESSION['cart']);
            }
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Payment Success - PharmaCare</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
                <style>
                    body { background-color: #f8f9fa; }
                    .card { border: none; border-radius: 15px; }
                </style>
            </head>
            <body class="d-flex justify-content-center align-items-center vh-100">
                
                <div class="card p-5 shadow-lg text-center" style="max-width: 500px; width: 100%;">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="text-success fw-bold mb-3">Payment Successful!</h2>
                    <p class="text-muted">Thank you for your purchase. Your order has been confirmed.</p>
                    
                    <div class="bg-light p-3 rounded mb-4 text-start">
                        <p class="mb-2 border-bottom pb-2"><strong>Invoice No:</strong> <?php echo $tran_id; ?></p>
                        <p class="mb-2 border-bottom pb-2"><strong>Amount Paid:</strong> <?php echo $amount_paid; ?> BDT</p>
                        <p class="mb-0"><strong>Status:</strong> <span class="badge bg-success">Paid</span></p>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="index.php" class="btn btn-primary btn-lg">Back to Home</a>
                        <a href="shop.php" class="btn btn-outline-secondary">Continue Shopping</a>
                    </div>
                </div>

            </body>
            </html>
            <?php
        } else {
            echo "<div class='alert alert-danger text-center mt-5'>Database Error: " . mysqli_error($conn) . "</div>";
        }

    } else {
        // Verification Failed
        echo "<div class='alert alert-danger text-center mt-5'><h1>Payment Verification Failed!</h1><p>Please contact support.</p><a href='index.php'>Go Back</a></div>";
    }

} else {
    // Direct Access Prevention
    header("Location: index.php");
    exit();
}
?>