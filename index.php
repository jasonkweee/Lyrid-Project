<!DOCTYPE html>
<html>
<?php

include 'AddOns/header.php';
$cssFile = "Plugin\indexPage.css";    
$timestamp = filemtime($cssFile);
echo "<link rel='stylesheet' href='$cssFile?$timestamp'>";

session_start(); 
if (isset($_SESSION['userDet'])) {
    $userDet = $_SESSION['userDet'];
    }

// Assuming $conn is your database connection
$sql = "SELECT qsold, price, product_name FROM Inventory";
$result = $conn->query($sql);

$dataPoints = array();

//Calculate the total revenue per inventory and stor efore graph
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $revenue = $row['qsold'] * $row['price'];
        $dataPoints[] = array("y" => $revenue, "label" => $row['product_name']);
    }
} else {
    echo "No data found.";
}

?>
<script>
 
    window.onload = function() {
 
 var chart = new CanvasJS.Chart("chartContainer", {
     title:{
         text: "Revenue of each product"
     },
     axisY: {
         title: "Revenue (in USD)",
         includeZero: true,
         prefix: "$",
     },
     data: [{
         type: "bar",
         yValueFormatString: "$#,##0",
         indexLabel: "{y}",
         indexLabelPlacement: "inside",
         indexLabelFontWeight: "bolder",
         indexLabelFontColor: "white",
         dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
     }]
 });
 chart.render();
  
 }

</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<head>
    <?php
        
        $cssFile = "Plugin\AllPage.css";
        $timestamp = filemtime($cssFile);
        echo "<link rel='stylesheet' href='$cssFile?$timestamp'>";
        $cssFile = "Plugin\indexPage.css";    
        $timestamp = filemtime($cssFile);
        echo "<link rel='stylesheet' href='$cssFile?$timestamp'>";
    ?>
</head>

<body class="back-body">
    <div>
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark" >
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <a class="navbar-brand" href="index.php">
                <img src="Photos/LogoV" alt="" width="50" height="44">
                </a>    
                <div class="dropdown">
                    <button class= "btn btn-secondary dropdown-toggle bg-dark" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Welcome <?php echo ucfirst($auth->sendUser()); ?>!
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="Logout.php">Logout</a></li>
                    </ul>
                </div>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Adminstration.php">Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Inventory.php">Inventory</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="PurchaseOrder.php">Purchase Orders</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="Data.php">Data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ContactsList.php">Contacts</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    </div> 
    <div class="container"> 
    <div class="row justify-content-between text-center mt-3">
        <div class="col-md-3">
            <div class ='homepage-data-text' >
                <?php 
                    $sql = "SELECT COUNT(`product_name`) as total FROM Orders WHERE OrderStatus= 'Not Proccessed' ";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "<h2> New Orders<br>" . $row['total'] . "</h2>";
                    } else {
                        echo "<h2 >0</h2>";
                    }
                    ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="homepage-data-text">
                <?php 
                    $sql = "SELECT COUNT(`product_name`) as total FROM Orders WHERE OrderStatus= 'Proccessing' ";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "<h2>Orders Processed<br>" . $row['total'] . "</h2>";
                    } else {
                        echo "<h2>0</h2>";
                    }
                ?>
            </div>
        </div>
        <!-- Orders Sent -->
        <div class="col-md-3">
            <div class="homepage-data-text">
                <?php 
                    $sql = "SELECT COUNT(`product_name`) as total FROM Orders WHERE OrderStatus= 'Sent' ";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "<h2>Orders Sent<br>" . $row['total'] . "</h2>";
                    } else {
                        echo "<h2>0</h2>";
                    }
                ?>
            </div>

        </div>
        <!-- Orders Received -->
        <div class="col-md-3">
            <div class="homepage-data-text">
                <?php 
                    $sql = "SELECT COUNT(`product_name`) as total FROM Orders WHERE OrderStatus= 'Received' ";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "<h2>Orders Received<br>" . $row['total'] . "</h2>";
                    } else {
                        echo "<h2>0</h2>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="row">
        <!-- Chart Container -->
        <div class="col-lg-6">
            <div id="chartContainer" style="height: 400px; width: 100%;" class="TotalSales-homepage"></div>
        </div>

        <!-- Stock Warning Section -->
        <div class="col-lg-6">
            <div class="p-3 mb-4" style="background-color: black; border: 2px solid purple; border-radius: 10px;">
                <h2 class="text-white fw-bold text-center">Stock Warning <br></h2>
                <div>
                    <table class="table table-dark table-striped warningTable">
                        <thead>
                            <tr class= 'text-center'>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
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
                            // show inventory that is under 200 items
                            $sql = "SELECT `product_name`, `quantity` FROM Inventory WHERE `quantity`<=200 ORDER BY `quantity`";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $maxRows = 5;
                                $count = 0;
                                
                                while ($row = $result->fetch_assoc()) {
                                    if ($count >= $maxRows) {
                                        break;
                                    }
                                    echo "<tr>
                                            <td>". $row["product_name"]." </td>
                                            <td>" . $row["quantity"] . "</td>
                                          </tr>";
                                    $count++;
                                }
                            } else {
                                echo "<tr><td colspan='2'><br>No Stocks In Danger</td></tr>";
                            }

                            $conn->close();
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script src="Js/Canvasjs.js?v=1.1"></script>
</html>