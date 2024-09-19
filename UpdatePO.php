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
$product_id = $_POST['product_id'];
$editquantity = $_POST['editquantity'];
$editprice = $_POST['editprice'];
$editnotes = $_POST['editnotes'];
$id = $_POST['editid'];

$sql = "UPDATE ProductInvoice SET product=?, quantity=?, `price`=?, notes=? WHERE id=?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("iidsi", $product_id, $editquantity, $editprice, $editnotes, $id);
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

