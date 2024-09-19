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

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Retrieve the data from the POST request
$supplier_id = $data['supplier_id'];
$invoice_id = $data['invoice_id'];

// Start a transaction
$conn->begin_transaction();

try {
    if (isset($invoice_id) && !empty($invoice_id)){
        $purchase_order_id = $invoice_id;
        
        
    }else{
        $sql = "INSERT INTO PurchaseOrders (supplierid) VALUES (?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $supplier_id);
            if ($stmt->execute()) {
                $purchase_order_id = $stmt->insert_id;
                $sql = "UPDATE PurchaseOrders SET ID = ? where orderid =?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $purchase_order_id, $purchase_order_id);
                $stmt->execute();
            }
            else{
                throw new Exception("Failed to insert into PurchaseOrders");
            }
        }else{
            throw new Exception("Failed to insert into PurchaseOrders");
        }
        
    }
    $total = 0.0;
    $items = $data['items'];
    $quantities = $data['quantities'];
    $prices = $data['prices'];
    $notes = $data['notes'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO ProductInvoice (invoiceid, product, quantity, price, notes) VALUES (?, ?, ?, ?, ?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        throw new Exception("Error preparing the ProductInvoice statement: " . $conn->error);   
    }
    // Bind parameters
    $stmt->bind_param("iisds", $purchase_order_id, $item, $quantity, $price, $note);


    // Execute for each item
    for ($i = 0; $i < count($items); $i++) {
        $item = $items[$i];
        $quantity = $quantities[$i];
        $price = $prices[$i];
        $total += $quantity * $price;
        $note = $notes[$i];
        if(!$stmt->execute()){
            throw new Exception("Failed to insert into PurchaseOrders");
        }
    }
     
    $sql = "UPDATE PurchaseOrders SET totalprice = ? where ID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("di", $total, $purchase_order_id);
        if ($stmt->execute()) {
            $conn->commit();
            echo json_encode(["status" => "success", "message" => "Transaction completed successfully."]);
        }
        else{
            throw new Exception("Failed to insert into PurchaseOrders");
        }
    }else{
        throw new Exception("Failed to insert into PurchaseOrders");
    }

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

// Close the connection
$stmt->close();
$conn->close();
?>
