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

$product_id = intval($_POST['id']);

$conn->begin_transaction();

try {
    // Disable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");

    // Step 1: Delete from Inventory
    $sql = "DELETE FROM Inventory WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->close();
    } else {
        throw new Exception("Failed to prepare the Inventory deletion SQL statement.");
    }

    // Step 2: Delete from ProductSupplier
    $sql = "DELETE FROM ProductSupplier WHERE productid = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->close();
    } else {
        throw new Exception("Failed to prepare the ProductSupplier deletion SQL statement.");
    }

    // Enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");

    // Commit the transaction
    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Product and its supplier relationship deleted successfully."]);
} catch (Exception $e) {
    // Rollback the transaction if something failed
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$conn->close();
?>
