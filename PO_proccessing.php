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
    0 => 'invoiceid',
    1 => 'product_name',
    2 => 'quantity',
    3 => 'Company',
    4 => 'price',
    5 => 'status',
    6 => 'notes',
    7 => 'id',
);

// Retrieve data from DataTables AJAX request
$limit = intval($_POST['length']);
$offset = intval($_POST['start']);
$orderColumn = $columns[$_POST['order'][0]['column']];
$orderDirection = $_POST['order'][0]['dir'] == 'asc' ? 'ASC' : 'DESC';
$searchValue = $conn->real_escape_string($_POST['search']['value']);
$filterId = isset($_POST['id']) ? intval($_POST['id']) : null;

// Build the SQL query
$sql = "SELECT pi.invoiceid, i.product_name, pi.quantity, pi.price, c.Company, po.status, pi.notes, pi.id
        FROM PurchaseOrders po 
        RIGHT JOIN ProductInvoice pi ON pi.invoiceid = po.ID
        INNER JOIN Inventory i ON i.id = pi.product
        INNER JOIN CONTACTS c ON po.supplierid = c.id
        WHERE 1=1";

// Apply ID filter if provided
if ($filterId) {
    $sql .= " AND pi.id = $filterId";
}

// Apply search filter
if (!empty($searchValue)) {
    $sql .= " AND (pi.invoiceid LIKE '%$searchValue%' 
                OR i.product_name LIKE '%$searchValue%' 
                OR pi.quantity LIKE '%$searchValue%'
                OR c.Company LIKE '%$searchValue%'
                OR pi.price LIKE '%$searchValue%'
                OR po.status LIKE '%$searchValue%'
                OR pi.notes LIKE '%$searchValue%'
                OR pi.id LIKE '%$searchValue%')";
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
                      WHERE 1=1";

// Apply ID filter if provided
if ($filterId) {
    $totalRecordsQuery .= " AND pi.id = $filterId";
}

// Count the total number of records with the current filter applied
$filteredRecordsQuery = "SELECT COUNT(*) AS total 
                            FROM PurchaseOrders po 
                            RIGHT JOIN ProductInvoice pi ON pi.invoiceid = po.ID
                            INNER JOIN Inventory i ON i.id = pi.product
                            INNER JOIN CONTACTS c ON po.supplierid = c.id
                            WHERE 1=1";

// Apply ID filter if provided
if ($filterId) {
    $filteredRecordsQuery .= " AND pi.id = $filterId";
}

$filteredRecordsResult = $conn->query($filteredRecordsQuery);
$filteredRecords = $filteredRecordsResult->fetch_assoc()['total'];

// Prepare the data for the JSON response
$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = array(
        $row['invoiceid'],
        $row['product_name'],
        $row['quantity'],
        $row['Company'],
        $row['price'],
        $row['status'],
        $row['notes'],
        $row['id']
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
