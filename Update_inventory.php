<?php
include 'checkDuplicateInventory.php'; 

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

// Get the data from the AJAX request
$product_name = $_POST['product_name'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$Pprice = $_POST['Pprice'];
$qsold = $_POST['qsold'];
$notes = $_POST['notes'];
$id = $_POST['id'];

$sql = "UPDATE Inventory SET product_name=?, quantity=?, `price`=?, `purchaseprice`=?, qsold=?, notes=? WHERE id=?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("siddisi", $product_name, $quantity, $price, $Pprice, $qsold, $notes, $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update user"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Failed to prepare the SQL statement"]);
}

$conn->close();
?>

