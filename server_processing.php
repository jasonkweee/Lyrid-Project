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
    0 => 'username',
    1 => 'password',
    2 => 'authority'
);

// Retrieve data from DataTables AJAX request
$limit = $_POST['length'];
$offset = $_POST['start'];
$orderColumn = $columns[$_POST['order'][0]['column']];
$orderDirection = $_POST['order'][0]['dir'];
$searchValue = $_POST['search']['value'];

// Build the SQL query
$sql = "SELECT id, username, `password`, authority FROM users WHERE 1";

if (!empty($searchValue)) {
    $sql .= " AND (username LIKE '%" . $searchValue . "%' 
             OR password LIKE '%" . $searchValue . "%' 
             OR authority LIKE '%" . $searchValue . "%')";
}

$sql .= " ORDER BY " . $orderColumn . " " . $orderDirection;
$sql .= " LIMIT " . $offset . ", " . $limit;

// Execute the query
$result = $conn->query($sql);

// Fetch data
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = array(
        $row['id'],
        $row['username'],
        $row['password'],
        $row['authority']
    );
}

// Get total number of records
$totalRecordsQuery = "SELECT COUNT(*) AS total FROM users";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];

// Get filtered number of records
$filteredRecordsQuery = "SELECT COUNT(*) AS total FROM users WHERE 1";
if (!empty($searchValue)) {
    $filteredRecordsQuery .= " AND (username LIKE '%" . $searchValue . "%' 
                                 OR password LIKE '%" . $searchValue . "%' 
                                 OR authority LIKE '%" . $searchValue . "%')";
}
$filteredRecordsResult = $conn->query($filteredRecordsQuery);
$filteredRecords = $filteredRecordsResult->fetch_assoc()['total'];

// Return data in JSON format
echo json_encode(array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($filteredRecords),
    "data" => $data
));

// Close connection
$conn->close();
?>