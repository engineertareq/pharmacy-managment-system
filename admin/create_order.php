<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 

$auto_invoice = 'INV-' . date('Ymd') . '-' . rand(100, 999);
$clients_res = $conn->query("SELECT user_id, full_name FROM users WHERE role = 'client'");
$staff_res = $conn->query("SELECT user_id, full_name FROM users WHERE role IN ('staff', 'admin')");

$medicines_array = [];
$medicines_res = $conn->query("SELECT medicine_id, name, sell_price, stock_quantity FROM medicines");
if ($medicines_res) {
    while($row = $medicines_res->fetch_assoc()) {
        $medicines_array[] = $row;
    }
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $invoice_number = $_POST['invoice_number'];
    $client_id = $_POST['client_id'];
    $staff_id = $_POST['staff_id'];
    
    $discount = floatval($_POST['discount']); 

    $payment_status = $_POST['payment_status'];
    $payment_method = $_POST['payment_method'];
    $order_date = $_POST['order_date'];
    
    $med_ids = $_POST['medicine_id'];
    $quantities = $_POST['quantity'];
    $prices = $_POST['price_per_unit'];

    $conn->begin_transaction();

    try {
        $calculated_sub_total = 0;
        for ($i = 0; $i < count($med_ids); $i++) {
            $qty = floatval($quantities[$i]);
            $price = floatval($prices[$i]);
            $calculated_sub_total += ($qty * $price);
        }
        
        $calculated_grand_total = $calculated_sub_total - $discount;

        $sql_order = "INSERT INTO orders (invoice_number, client_id, staff_id, sub_total, discount, grand_total, payment_status, payment_method, order_date) 
                      VALUES ('$invoice_number', '$client_id', '$staff_id', '$calculated_sub_total', '$discount', '$calculated_grand_total', '$payment_status', '$payment_method', '$order_date')";
        
        if (!$conn->query($sql_order)) {
            throw new Exception("Order Insert Failed: " . $conn->error);
        }

        $new_order_id = $conn->insert_id;

        $sql_item = $conn->prepare("INSERT INTO order_items (order_id, medicine_id, quantity, price_per_unit, total_price) VALUES (?, ?, ?, ?, ?)");
        $sql_check_stock = $conn->prepare("SELECT stock_quantity, name FROM medicines WHERE medicine_id = ?");
        $sql_update_stock = $conn->prepare("UPDATE medicines SET stock_quantity = stock_quantity - ? WHERE medicine_id = ?");

        for ($i = 0; $i < count($med_ids); $i++) {
            $m_id = $med_ids[$i];
            $qty = floatval($quantities[$i]);
            $price = floatval($prices[$i]);
            $total = $qty * $price;

            if(!empty($m_id) && $qty > 0) {
                
                $sql_check_stock->bind_param("i", $m_id);
                $sql_check_stock->execute();
                $result_stock = $sql_check_stock->get_result();
                $stock_data = $result_stock->fetch_assoc();

                if ($stock_data['stock_quantity'] < $qty) {
                    throw new Exception("Insufficient Stock for medicine: " . $stock_data['name'] . " (Available: " . $stock_data['stock_quantity'] . ")");
                }

                $sql_item->bind_param("iiidd", $new_order_id, $m_id, $qty, $price, $total);
                if (!$sql_item->execute()) {
                    throw new Exception("Item Insert Failed: " . $sql_item->error);
                }

                $sql_update_stock->bind_param("ii", $qty, $m_id);
                if (!$sql_update_stock->execute()) {
                    throw new Exception("Stock Update Failed: " . $sql_update_stock->error);
                }
            }
        }

        $conn->commit();
        
        // Success Message with PDF Link
        $message = "<div class='alert alert-success d-flex justify-content-between align-items-center'>
                        <span>Order created successfully! Invoice: <strong>$invoice_number</strong></span>
                        <a href='generate_invoice_pdf.php?id=$new_order_id' target='_blank' class='btn btn-sm btn-dark'>
                            <iconify-icon icon='solar:file-download-bold'></iconify-icon> Download PDF
                        </a>
                    </div>";
        
        $auto_invoice = 'INV-' . date('Ymd') . '-' . rand(100, 999); 

    } catch (Exception $e) {
        $conn->rollback();
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Create New Invoice</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium"><a href="index.php">Dashboard</a></li>
            <li>-</li>
            <li class="fw-medium">New Order</li>
        </ul>
    </div>

    <form method="POST" action="">
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">1. Order Details</h5></div>
            <div class="card-body">
                <?php echo $message; ?>
                <div class="row gy-3">
                    <div class="col-md-3">
                        <label class="form-label">Invoice (Auto)</label>
                        <input type="text" name="invoice_number" class="form-control bg-light" value="<?php echo $auto_invoice; ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="order_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Client</label>
                        <select name="client_id" class="form-select" required>
                            <option value="">Select Client</option>
                            <?php if ($clients_res) { while($row = $clients_res->fetch_assoc()) { echo "<option value='".$row['user_id']."'>".$row['full_name']."</option>"; } } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Staff</label>
                        <select name="staff_id" class="form-select" required>
                            <option value="">Select Staff</option>
                            <?php if ($staff_res) { while($row = $staff_res->fetch_assoc()) { echo "<option value='".$row['user_id']."'>".$row['full_name']."</option>"; } } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">2. Order Items</h5>
                <button type="button" class="btn btn-sm btn-primary-600" onclick="addItemRow()">+ Add Item</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="itemsTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="40%">Medicine</th>
                                <th width="15%">Price</th>
                                <th width="15%">Qty</th>
                                <th width="20%">Total</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">3. Payment & Totals</h5></div>
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-6">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Payment Status</label>
                                <select name="payment_status" class="form-select">
                                    <option value="Paid">Paid</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Due">Due</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select">
                                    <option value="Cash">Cash</option>
                                    <option value="Card">Card</option>
                                    <option value="Mobile Banking">Mobile Banking</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Sub Total:</span>
                                <input type="number" step="0.01" name="sub_total" id="sub_total" class="form-control w-50 text-end" readonly value="0.00">
                            </div>
                            <div class="d-flex justify-content-between mb-2 align-items-center">
                                <span>Discount:</span>
                                <input type="number" step="0.01" name="discount" id="discount" class="form-control w-50 text-end" placeholder="0.00" oninput="calculateGrandTotal()">
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2">
                                <span class="fw-bold">Grand Total:</span>
                                <input type="number" step="0.01" name="grand_total" id="grand_total" class="form-control w-50 text-end fw-bold" readonly value="0.00">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-lg btn-success-600">Complete Order</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const medicines = <?php echo json_encode($medicines_array); ?>;
</script>

<script>
    function addItemRow() {
        const table = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
        const row = table.insertRow();
        let options = '<option value="">Select Medicine</option>';
        medicines.forEach(med => {
            options += `<option value="${med.medicine_id}" data-price="${med.sell_price}" data-stock="${med.stock_quantity}">${med.name} (Stock: ${med.stock_quantity})</option>`;
        });

        row.innerHTML = `
            <td>
                <select name="medicine_id[]" class="form-select med-select" onchange="updateRowPrice(this)" required>
                    ${options}
                </select>
                <small class="text-muted stock-info"></small>
            </td>
            <td><input type="number" step="0.01" name="price_per_unit[]" class="form-control price-input" readonly></td>
            <td><input type="number" name="quantity[]" class="form-control qty-input" placeholder="1" oninput="updateRowTotal(this)" required></td>
            <td><input type="number" step="0.01" class="form-control total-input" readonly value="0.00"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger-600" onclick="removeRow(this)">X</button></td>
        `;
    }

    function updateRowPrice(selectElement) {
        const price = selectElement.options[selectElement.selectedIndex].getAttribute('data-price');
        const stock = selectElement.options[selectElement.selectedIndex].getAttribute('data-stock');
        const row = selectElement.closest('tr');
        row.querySelector('.price-input').value = price || 0;
        if(stock) {
            row.querySelector('.qty-input').setAttribute('max', stock);
            row.querySelector('.stock-info').innerText = `Max: ${stock}`;
        }
        updateRowTotal(selectElement);
    }

    function updateRowTotal(element) {
        const row = element.closest('tr');
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        
        const select = row.querySelector('.med-select');
        const stock = parseFloat(select.options[select.selectedIndex].getAttribute('data-stock')) || 0;
        
        if (qty > stock) {
            alert("Quantity exceeds available stock!");
            element.value = stock; 
            return updateRowTotal(element); 
        }

        const total = price * qty;
        row.querySelector('.total-input').value = total.toFixed(2);
        calculateGrandTotal();
    }

    function removeRow(button) {
        button.closest('tr').remove();
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let subTotal = 0;
        document.querySelectorAll('.total-input').forEach(input => {
            subTotal += parseFloat(input.value) || 0;
        });
        document.getElementById('sub_total').value = subTotal.toFixed(2);
        
       
        const discountInput = document.getElementById('discount').value;
        const discount = discountInput === "" ? 0 : parseFloat(discountInput);
        
        const grandTotal = subTotal - discount;
        document.getElementById('grand_total').value = grandTotal.toFixed(2);
    }

    window.onload = function() {
        addItemRow();
    };
</script>

<?php include './partials/layouts/layoutBottom.php' ?>