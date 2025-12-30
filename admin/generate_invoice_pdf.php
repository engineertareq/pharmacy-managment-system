<?php
// admin/generate_invoice_pdf.php
require '../libs/fpdf/fpdf.php';
include 'inc/db_connect.php';

if (!isset($_GET['id'])) {
    die("Order ID is missing.");
}

$order_id = $_GET['id'];

// Fetch Order Data
$sql_order = "SELECT o.*, c.full_name as client_name, c.email as client_email, c.phone as client_phone 
              FROM orders o 
              LEFT JOIN users c ON o.client_id = c.user_id 
              WHERE o.order_id = $order_id";
$order_result = $conn->query($sql_order);
$order = $order_result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

// Fetch Items
$sql_items = "SELECT oi.*, m.name as medicine_name 
              FROM order_items oi 
              LEFT JOIN medicines m ON oi.medicine_id = m.medicine_id 
              WHERE oi.order_id = $order_id";
$items_result = $conn->query($sql_items);

// PDF Class
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 10, 'PHARMACY INVOICE', 0, 1, 'C');
        $this->Ln(10);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();

// ... (Rest of your PDF layout logic remains exactly the same) ...
// Invoice Info
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(130, 5, 'Invoice To:', 0, 0);
$pdf->Cell(59, 5, 'Invoice Details:', 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(130, 5, 'Client: ' . $order['client_name'], 0, 0);
$pdf->Cell(59, 5, 'Invoice #: ' . $order['invoice_number'], 0, 1);
$pdf->Cell(130, 5, 'Phone: ' . ($order['client_phone'] ?? 'N/A'), 0, 0);
$pdf->Cell(59, 5, 'Date: ' . date('d M Y', strtotime($order['order_date'])), 0, 1);
$pdf->Cell(130, 5, 'Email: ' . ($order['client_email'] ?? 'N/A'), 0, 0);
$pdf->Cell(59, 5, 'Status: ' . $order['payment_status'], 0, 1);
$pdf->Ln(10);

// Table
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(10, 10, '#', 1, 0, 'C', true);
$pdf->Cell(90, 10, 'Medicine Name', 1, 0, 'L', true);
$pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Price', 1, 0, 'R', true);
$pdf->Cell(30, 10, 'Total', 1, 1, 'R', true);

$pdf->SetFont('Arial', '', 10);
$count = 1;
while ($item = $items_result->fetch_assoc()) {
    $pdf->Cell(10, 8, $count++, 1, 0, 'C');
    $pdf->Cell(90, 8, $item['medicine_name'], 1, 0, 'L');
    $pdf->Cell(30, 8, $item['quantity'], 1, 0, 'C');
    $pdf->Cell(30, 8, number_format($item['price_per_unit'], 2), 1, 0, 'R');
    $pdf->Cell(30, 8, number_format($item['total_price'], 2), 1, 1, 'R');
}

// Totals
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(130, 5, '', 0, 0);
$pdf->Cell(30, 5, 'Sub Total:', 0, 0, 'R');
$pdf->Cell(30, 5, number_format($order['sub_total'], 2), 0, 1, 'R');
$pdf->Cell(130, 5, '', 0, 0);
$pdf->Cell(30, 5, 'Discount:', 0, 0, 'R');
$pdf->Cell(30, 5, '-' . number_format($order['discount'], 2), 0, 1, 'R');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(130, 10, '', 0, 0);
$pdf->Cell(30, 10, 'Grand Total:', 0, 0, 'R');
$pdf->Cell(30, 10, number_format($order['grand_total'], 2), 0, 1, 'R');

// --- THE CRITICAL CHANGE IS HERE ---
// Change 'I' (Inline) to 'D' (Download)
$pdf->Output('D', 'Invoice-' . $order['invoice_number'] . '.pdf');
?>