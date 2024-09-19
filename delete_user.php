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

    $id = intval($_POST['id']);

    $sql = "DELETE FROM users where id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to prepare the SQL statement"]);
    }

?>