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
$usernames = $_POST['username'];
$password = $_POST['password'];
$authority = $_POST['authority'];
$id = $_POST['id'];

$sql = "UPDATE users SET username=?, `password`=?, authority=? WHERE id=?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ssii", $usernames, $password, $authority, $id);
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
