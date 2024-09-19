<?php

$servername = "localhost";
$username = "debian-sys-maint";
$password = "0DXddFBx19pUUQ6F";
$dbname = "Product_inventory";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the columns for sorting and filtering
$columns = array(
    0 => 'id',
    1 => 'product_name',
    2 => 'quantity',
    3 => 'Company',
    4 => 'price',
    5 => 'status',
    6 => 'notes',
    7 => 'invoiceid',
);

// Retrieve data from DataTables AJAX request
$limit = intval($_POST['length']);
$offset = intval($_POST['start']);
$orderColumn = $columns[$_POST['order'][0]['column']];
$orderDirection = $_POST['order'][0]['dir'] == 'asc' ? 'ASC' : 'DESC';
$searchValue = $conn->real_escape_string($_POST['search']['value']);

// Retrieve invoice IDs from POST data
$invoiceIds = isset($_POST['invoiceIds']) ? $_POST['invoiceIds'] : array();

// Convert invoice IDs to a format suitable for SQL IN clause
if (!empty($invoiceIds)) {
    $invoiceIds = array_map('intval', $invoiceIds);
    $invoiceIdsList = implode(',', $invoiceIds);
} else {
    $invoiceIdsList = '0'; // This will result in no records being returned
}

// Build the SQL query
$sql = "SELECT pi.id, pi.invoiceid, i.product_name, pi.quantity, pi.price, c.Company, po.status, pi.notes 
        FROM PurchaseOrders po 
        RIGHT JOIN ProductInvoice pi ON pi.invoiceid = po.ID
        INNER JOIN Inventory i ON i.id = pi.product
        INNER JOIN CONTACTS c ON po.supplierid = c.id
        WHERE pi.invoiceid IN ($invoiceIdsList)";

// Apply search filter
if (!empty($searchValue)) {
    $sql .= " AND (pi.id LIKE '%$searchValue%' 
                OR i.product_name LIKE '%$searchValue%' 
                OR pi.quantity LIKE '%$searchValue%'
                OR c.Company LIKE '%$searchValue%'
                OR pi.price LIKE '%$searchValue%'
                OR po.status LIKE '%$searchValue%'
                OR pi.notes LIKE '%$searchValue%'
                OR pi.invoiceid LIKE '%$searchValue%')";
}

// Add ordering
$sql .= " ORDER BY $orderColumn $orderDirection";

// Limit the number of records fetched (for pagination)
$sql .= " LIMIT $limit OFFSET $offset";

// Execute the query
$result = $conn->query($sql);

// Count the total number of records without any filters
$totalRecordsQuery = "SELECT COUNT(*) AS total 
                      FROM PurchaseOrders po 
                      RIGHT JOIN ProductInvoice pi ON pi.invoiceid = po.ID
                      INNER JOIN Inventory i ON i.id = pi.product
                      INNER JOIN CONTACTS c ON po.supplierid = c.id
                      WHERE pi.invoiceid IN ($invoiceIdsList)";

// Count the total number of records with the current filter applied
$filteredRecordsQuery = $totalRecordsQuery;

// Execute the total records queries
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];

$filteredRecordsResult = $conn->query($filteredRecordsQuery);
$filteredRecords = $filteredRecordsResult->fetch_assoc()['total'];

// Prepare the data for the JSON response
$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = array(
        $row['id'],             // id first in the output
        $row['product_name'],
        $row['quantity'],
        $row['Company'],
        $row['price'],
        $row['status'],
        $row['notes'],
        $row['invoiceid']       // invoiceid last in the output
    );
}

// Prepare the JSON response
$response = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $filteredRecords,
    "data" => $data
);

// Send the JSON response
echo json_encode($response);

// Close the database connection
$conn->close();

?>
