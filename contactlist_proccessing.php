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
    1 => 'First_name',
    2 => 'Last_name',
    3 => 'PhoneNumber',
    4 => 'email',
    5 => 'Company',
    6 => 'Address'  
);

// Retrieve data from DataTables AJAX request
$limit = intval($_POST['length']);
$offset = intval($_POST['start']);
$orderColumn = $columns[$_POST['order'][0]['column']];
$orderDirection = $_POST['order'][0]['dir'] == 'asc' ? 'ASC' : 'DESC'; 
$searchValue = $conn->real_escape_string($_POST['search']['value']);  

// Build the SQL query
$sql = "SELECT id, First_name, Last_name, PhoneNumber, email, Company, Address FROM CONTACTS WHERE 1";

if (!empty($searchValue)) {
    $sql .= " AND (First_name LIKE '%" . $searchValue . "%' 
             OR Last_name LIKE '%" . $searchValue . "%' 
             OR PhoneNumber LIKE '%" . $searchValue . "%' 
             OR email LIKE '%" . $searchValue . "%' 
             OR Company LIKE '%" . $searchValue . "%' 
             OR Address LIKE '%" . $searchValue . "%')";  
}

$sql .= " ORDER BY " . $orderColumn . " " . $orderDirection;
$sql .= " LIMIT " . $offset . ", " . $limit;

// Execute the query
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Fetch data
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = array(
        $row['id'],
        $row['First_name'],
        $row['Last_name'],
        $row['PhoneNumber'],
        $row['email'],
        $row['Company'],
        $row['Address']  
    );
}

// Get total number of records
$totalRecordsQuery = "SELECT COUNT(*) AS total FROM CONTACTS";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];

// Get filtered number of records
$filteredRecordsQuery = "SELECT COUNT(*) AS total FROM CONTACTS WHERE 1";
if (!empty($searchValue)) {
    $filteredRecordsQuery .= " AND (First_name LIKE '%" . $searchValue . "%' 
                                 OR Last_name LIKE '%" . $searchValue . "%' 
                                 OR PhoneNumber LIKE '%" . $searchValue . "%' 
                                 OR email LIKE '%" . $searchValue . "%' 
                                 OR Company LIKE '%" . $searchValue . "%' 
                                 OR Address LIKE '%" . $searchValue . "%')";  
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
