<?php
    include 'checkDuplicateContact.php'; 

     if (isset($_POST['Company'])) {
        $Company = $_POST['Company'];
        
        // Check for duplicate
        if (checkDupe($Company)) {
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


    $First_name= $_POST['First_name'];
    $Last_name= $_POST['Last_name'];
    $Email= $_POST['email'];
    $Phone_number= $_POST['PhoneNumber'];
    $Address= $_POST['Address'];
    $Company= $_POST['Company'];

    $sql = "INSERT INTO CONTACTS (First_name, Last_name, `address`, PhoneNumber, email, Company) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssss",  $First_name, $Last_name, $Address, $Phone_number, $Email, $Company);
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