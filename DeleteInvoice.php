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

// Get the ID from the POST request
$id = $_POST['id'];

// Start a transaction
$conn->begin_transaction();

try {

    // Prepare and execute the delete query
    $sql = "DELETE FROM PurchaseOrders WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->commit();
        echo json_encode(["status" => "success"]);}

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

// Close the connection
$conn->close();
?>
