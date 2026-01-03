<?php 
session_start();
include 'db.php';
require_once 'config.php'; 


if (empty($_SESSION['cart'])) {
    echo "<script>location.href='shop.php';</script>";
    exit();
}

$msg = "";

if (isset($_POST['place_order'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $addr = $conn->real_escape_string($_POST['address']);
    $pay_method = $_POST['payment_method'];

    $grand_total = 0;
    $items_data = [];
    
    $ids = implode(',', array_keys($_SESSION['cart']));
    if(!empty($ids)){
        $sql = "SELECT * FROM medicines WHERE medicine_id IN ($ids)";
        $result = $conn->query($sql);
        
        while($row = $result->fetch_assoc()) {
            $qty = $_SESSION['cart'][$row['medicine_id']];
            $grand_total += ($row['sell_price'] * $qty);
            $row['qty'] = $qty; 
            $items_data[] = $row;
        }
    }

    $client_id = "NULL"; 
    $user_email = "guest@example.com"; 

    if (isset($_SESSION['user_id'])) {
        $uid = intval($_SESSION['user_id']);
        $check_user = $conn->query("SELECT user_id, email FROM users WHERE user_id = $uid");
        if ($check_user && $check_user->num_rows > 0) {
            $u_data = $check_user->fetch_assoc();
            $client_id = $uid; 
            $user_email = $u_data['email'];
        }
    }

    $invoice = "INV-" . time();
    
 
    $sql_order = "INSERT INTO orders (invoice_number, client_id, sub_total, grand_total, payment_method, payment_status) 
                  VALUES ('$invoice', $client_id, '$grand_total', '$grand_total', '$pay_method', 'pending')";

    if ($conn->query($sql_order) === TRUE) {
        $order_id = $conn->insert_id;

        foreach($items_data as $item) {
            $mid = $item['medicine_id'];
            $iqty = $item['qty'];
            $iprice = $item['sell_price'];
            $itotal = $iqty * $iprice;
            
            $conn->query("INSERT INTO order_items (order_id, medicine_id, quantity, price_per_unit, total_price) 
                          VALUES ('$order_id', '$mid', '$iqty', '$iprice', '$itotal')");
        }



        if ($pay_method == 'cash') {

            unset($_SESSION['cart']);
            $msg = "<div class='alert alert-success p-5 text-center'>
                        <h1><i class='fas fa-check-circle text-success'></i> Order Placed!</h1>
                        <p class='lead'>Your order has been confirmed. Invoice: <strong>$invoice</strong></p>
                        <a href='index.php' class='btn btn-primary mt-3'>Continue Shopping</a>
                    </div>";
        } else {
        
            $post_data = [
                'store_id' => STORE_ID,
                'signature_key' => SIGNATURE_KEY,
                'cus_name' => $name,
                'cus_email' => $user_email,
                'cus_phone' => $phone,
                'cus_add1' => $addr,
                'cus_add2' => $addr,
                'cus_city' => 'Dhaka',
                'cus_country' => 'Bangladesh',
                'amount' => $grand_total,
                'currency' => 'BDT',
                'tran_id' => $invoice, // Use Invoice as Transaction ID
                'success_url' => SUCCESS_URL,
                'fail_url' => FAIL_URL,
                'cancel_url' => CANCEL_URL,
                'desc' => 'Medicine Order',
                'type' => 'json'
            ];

    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, API_URL);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            if (isset($result['payment_url']) && !empty($result['payment_url'])) {
                echo "<script>window.location.href='" . $result['payment_url'] . "';</script>";
                exit();
            } else {
                $msg = "<div class='alert alert-danger'>Payment Gateway Error. Please try Cash on Delivery.</div>";
            }
        }

    } else {
        $msg = "<div class='alert alert-danger'>Database Error: " . $conn->error . "</div>";
    }
}

include 'inc/header.php'; 
?>

<?php if ($msg != ""): ?>
    <div class="container mt-5">
        <?php echo $msg; ?>
    </div>
<?php else: ?>

<div class="container py-5">
    <h2 class="mb-4">Checkout</h2>
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Billing Details</div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" required 
                                   value="<?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Address</label>
                            <textarea name="address" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="cash">Cash on Delivery</option>
                                <option value="card">Aamarpay</option>
                                <option value="mobile_banking">Mobile Banking (Bkash/Nagad)</option>
                            </select>
                        </div>
                        <button type="submit" name="place_order" class="btn btn-success w-100 btn-lg">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <h5 class="card-title">Your Order</h5>
                    <hr>
                    <ul class="list-group list-group-flush mb-3">
                        <?php 
                        $total = 0;
                        if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])):
                            $ids = implode(',', array_keys($_SESSION['cart']));
                            if(!empty($ids)):
                                $result = $conn->query("SELECT * FROM medicines WHERE medicine_id IN ($ids)");
                                while($row = $result->fetch_assoc()):
                                    $qty = $_SESSION['cart'][$row['medicine_id']];
                                    $total += $row['sell_price'] * $qty;
                        ?>
                        <li class="list-group-item d-flex justify-content-between bg-transparent px-0">
                            <span><?php echo $row['name']; ?> <small class="text-muted">x <?php echo $qty; ?></small></span>
                            <span>৳<?php echo number_format($row['sell_price'] * $qty, 2); ?></span>
                        </li>
                        <?php 
                                endwhile; 
                            endif;
                        endif;
                        ?>
                    </ul>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span>৳<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
<?php include 'inc/footer.php'; ?>