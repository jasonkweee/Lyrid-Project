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
    3 => 'price',
    4 => 'purchaseprice',
    5 => 'qsold',
    6 => 'Company',
    7 => 'notes' 
);

// Retrieve data from DataTables AJAX request
$limit = intval($_POST['length']);
$offset = intval($_POST['start']);
$orderColumn = $columns[$_POST['order'][0]['column']];
$orderDirection = $_POST['order'][0]['dir'] == 'asc' ? 'ASC' : 'DESC'; 
$searchValue = $conn->real_escape_string($_POST['search']['value']);  

// Build the SQL query
$sql = "SELECT i.id, i.product_name, i.quantity, i.price, i.purchaseprice, i.qsold, i.notes, c.Company AS company
        FROM Inventory i
        LEFT JOIN ProductSupplier ps ON i.id = ps.productid
        LEFT JOIN CONTACTS c ON ps.supplierid = c.id
        WHERE 1";

if (!empty($searchValue)) {
    $sql .= " AND (i.product_name LIKE '%" . $searchValue . "%' 
             OR i.quantity LIKE '%" . $searchValue . "%' 
             OR i.price LIKE '%" . $searchValue . "%' 
             OR i.purchaseprice LIKE '%" . $searchValue . "%' 
             OR i.qsold LIKE '%" . $searchValue . "%' 
             OR i.notes LIKE '%" . $searchValue . "%'
             OR c.Company LIKE '%" . $searchValue . "%')";  
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
        $row['product_name'],
        $row['quantity'],
        $row['price'],
        $row['purchaseprice'],
        $row['qsold'],
        $row['company'] ,
        $row['notes']
    );
}

// Get total number of records
$totalRecordsQuery = "SELECT COUNT(*) AS total FROM Inventory";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];

// Get filtered number of records
$filteredRecordsQuery = "SELECT COUNT(*) AS total FROM Inventory i
                          LEFT JOIN ProductSupplier ps ON i.id = ps.productid
                          LEFT JOIN CONTACTS c ON ps.supplierid = c.id
                          WHERE 1";
if (!empty($searchValue)) {
    $filteredRecordsQuery .= " AND (i.product_name LIKE '%" . $searchValue . "%' 
                                 OR i.quantity LIKE '%" . $searchValue . "%' 
                                 OR i.price LIKE '%" . $searchValue . "%' 
                                 OR i.purchaseprice LIKE '%" . $searchValue . "%' 
                                 OR i.qsold LIKE '%" . $searchValue . "%' 
                                 OR i.notes LIKE '%" . $searchValue . "%'
                                 OR c.Company LIKE '%" . $searchValue . "%')";  
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
