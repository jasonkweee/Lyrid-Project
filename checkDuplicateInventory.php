<?php
function checkDupe($product_name) {
    $servername = "localhost";
    $dbUsername = "debian-sys-maint";
    $dbPassword = "0DXddFBx19pUUQ6F"; 
    $dbname = "Product_inventory";
                    
    // Create connection
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
                    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = $conn->prepare("SELECT * FROM Inventory WHERE product_name = ?");
    if ($query === false) {
        die("Failed to prepare the SQL statement: " . $conn->error);
    }

    $query->bind_param('s', $product_name);
    $query->execute();
    
    $result = $query->get_result();
    $isDuplicate = $result->num_rows > 0;
    
    // Close the statement and connection
    $query->close();
    $conn->close();

    return $isDuplicate;
}
?>
