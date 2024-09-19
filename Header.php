<!DOCTYPE html>
<html>
<head>
<?php         
    $cssFile = "Plugin\AllPage.css";
    $timestamp = filemtime($cssFile);
    echo "<link rel='stylesheet' href='$cssFile?$timestamp'>";

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
    
    require_once 'Setting/Authentication.php';

    $auth = new Authentication('localhost', 'debian-sys-maint', '0DXddFBx19pUUQ6F', 'Product_inventory');

    // Redirect if not logged in
    if (!$auth->isLoggedIn()) {
        header("Location: Login.php");
        exit();
    }
         ?>
         
    <link href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
