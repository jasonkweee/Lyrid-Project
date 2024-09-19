<?php
include 'checkDuplicateInventory.php'; 

if (isset($_POST['product_name'])) {
    $product_name = $_POST['product_name'];

    // Check for duplicate
    if (checkDupe($product_name)) {
        echo 'exists';
        exit();
    }
}

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

$product_name = $_POST['product_name'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$pprice = $_POST['Pprice'];
$notes = $_POST['notes'];
$supplier_id = $_POST['supplier_id'];

$sql = "INSERT INTO Inventory (product_name, quantity, price, purchaseprice, notes, supplierID) VALUES (?, ?,  ?, ?, ?, ?)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("siddsi", $product_name, $quantity, $price, $pprice,$notes, $supplier_id);
    if ($stmt->execute()) {
        $product_id = $stmt->insert_id;

        // Insert into ProductSupplier table
        $sql2 = "INSERT INTO ProductSupplier (supplierid, productid) VALUES (?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("ii", $supplier_id, $product_id);

        if ($stmt2->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update user"]);
        }
        $stmt2->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update user"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Failed to prepare the SQL statement"]);
}

$conn->close();
?>
