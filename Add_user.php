<?php
    include 'checkDuplicateAdmin.php'; 

    if (isset($_POST['addUsername'])) {
        $username = $_POST['addUsername'];
    
        // Check for duplicate
        if (checkDupe($username)) {
            echo 'exists';
            $username = 0;
            exit();
        }
    
        $password = $_POST['addPassword'];
        $authority = $_POST['addAuthority'];

    
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


    $usernames = $_POST['addUsername'];
    $password = $_POST['addPassword'];
    $authority = $_POST['addAuthority'];

    $sql = "INSERT INTO users (username, `password`, authority) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssi", $usernames, $password, $authority);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "User updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update user"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to prepare the SQL statement"]);
    }

?>