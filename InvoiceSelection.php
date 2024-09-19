<?php
$servername = "localhost";
$username = "debian-sys-maint";
$password = "0DXddFBx19pUUQ6F";
$dbname = "Product_inventory";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


$invoice_id = $_POST['invoice_id'];

// Prepare SQL statement
$sql = "
SELECT c.id, c.Company, i.product_name, pi.quantity, pi.price FROM CONTACTS c RIGHT JOIN PurchaseOrders po ON c.id = po.supplierid RIGHT JOIN ProductInvoice pi on pi.invoiceid = po.ID LEFT JOIN Inventory i ON i.id = pi.product WHERE po.ID = ?
";

$stmt = $conn->prepare($sql);

// Check if the statement was prepared correctly
if (!$stmt) {
    $response = [
        'error' => 'SQL prepare error: ' . $conn->error
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    $conn->close();
    exit();
}

// Bind parameters and execute statement
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
$totalprice = $invoice_id;

// Fetch results
if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $row_total = $row['quantity'] * $row['price'];
        $totalprice += $row_total;
        
        $data[] = [
            'product_name' => $row['product_name'],
            'quantity' => $row['quantity'],
            'price' => $row['price'],
            'total' => $row_total,
            'total_price' => $totalprice
        ];
    }
}

// Prepare the final response
$response = [
    'data' => $data,
    'total_price' => $totalprice
];

header('Content-Type: application/json');
echo json_encode($response);

// Close the statement and connection
$stmt->close();
$conn->close();

?>
