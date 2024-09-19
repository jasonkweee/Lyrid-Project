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
    0 => 'ID',
    1 => 'Company',
    2 => 'totalprice',
    3 => 'status',
);

// Retrieve data from DataTables AJAX request
$limit = intval($_POST['length']);
$offset = intval($_POST['start']);
$orderColumn = $columns[$_POST['order'][0]['column']];
$orderDirection = $_POST['order'][0]['dir'] == 'asc' ? 'ASC' : 'DESC';
$searchValue = $conn->real_escape_string($_POST['search']['value']);

// Build the SQL query
$sql = "SELECT i.ID, c.Company, i.totalprice, i.status
        FROM PurchaseOrders i 
        LEFT JOIN CONTACTS c ON c.id = i.supplierid
        WHERE 1=1";

// Apply search filter
if (!empty($searchValue)) {
    $sql .= " AND (i.ID LIKE '%$searchValue%' 
                OR c.Company LIKE '%$searchValue%' 
                OR i.totalprice LIKE '%$searchValue%'
                OR i.status LIKE '%$searchValue%')";
}

// Add ordering
$sql .= " ORDER BY $orderColumn $orderDirection";

// Limit the number of records fetched (for pagination)
$sql .= " LIMIT $limit OFFSET $offset";

// Execute the query
$result = $conn->query($sql);

// Count the total number of records without any filters
$totalRecordsQuery = "SELECT COUNT(*) AS total FROM PurchaseOrders";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];

// Count the total number of records with the current filter applied
$filteredRecordsQuery = "SELECT COUNT(*) AS total 
                         FROM PurchaseOrders i
                         LEFT JOIN CONTACTS c ON c.id = i.supplierid
                         WHERE 1=1";
if (!empty($searchValue)) {
    $filteredRecordsQuery .= " AND (i.ID LIKE '%$searchValue%' 
                                    OR c.Company LIKE '%$searchValue%' 
                                    OR i.totalprice LIKE '%$searchValue%'
                                    OR i.status LIKE '%$searchValue%')";
}

$filteredRecordsResult = $conn->query($filteredRecordsQuery);
$filteredRecords = $filteredRecordsResult->fetch_assoc()['total'];

// Prepare the data for the JSON response
$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = array(
        $row['ID'],
        $row['Company'], // Now using the Company name instead of supplierid
        $row['totalprice'],
        $row['status']
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

