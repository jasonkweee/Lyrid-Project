<?php
$servername = "localhost";
$username = "debian-sys-maint";
$password = "0DXddFBx19pUUQ6F";
$dbname = "Product_inventory";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $response = [
        'error' => 'Connection failed: ' . $conn->connect_error
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Check if 'invoice_id' is provided
if (!isset($_POST['invoice_id'])) {
    $response = [
        'error' => 'No invoice_id provided.'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

$invoice_id = $_POST['invoice_id'];

// Prepare SQL statement
$sql = "
SELECT c.id, c.Company
FROM CONTACTS c
JOIN PurchaseOrders po ON c.id = po.supplierid
WHERE po.ID = ?
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
$execute_result = $stmt->execute();

// Check if execution was successful
if (!$execute_result) {
    $response = [
        'error' => 'SQL execute error: ' . $stmt->error
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    $stmt->close();
    $conn->close();
    exit();
}

$result = $stmt->get_result();

$suppliers = [];

// Fetch results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = [
            'id' => $row['id'],
            'name' => htmlspecialchars($row['Company']) // Use htmlspecialchars to avoid XSS attacks
        ];
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Output suppliers as JSON
header('Content-Type: application/json');
echo json_encode($suppliers);
?>
