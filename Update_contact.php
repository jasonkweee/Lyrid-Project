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

// Get the data from the AJAX request
$First_name = $_POST['First_name'];
$Last_name = $_POST['Last_name'];
$Address = $_POST['Address'];
$PhoneNumber = $_POST['PhoneNumber'];
$email = $_POST['email'];
$Company = $_POST['Company'];
$Address = $_POST['Address'];
$id = $_POST['id'];

$sql = "UPDATE CONTACTS SET First_name=?, Last_name=?, `address`=?, PhoneNumber=?, email=?, Company=?, `Address`=? WHERE id=?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("sssssssi", $First_name, $Last_name, $Address, $PhoneNumber, $email, $Company,$Address, $id);
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

