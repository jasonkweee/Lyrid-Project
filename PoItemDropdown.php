<?php
if (isset($_POST['supplier_id'])) {
    $supplier = $_POST['supplier_id'];
    $servername = "localhost";
    $username = "debian-sys-maint";
    $password = "0DXddFBx19pUUQ6F";
    $dbname = "Product_inventory";

    try {

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT I.id, I.product_name FROM ProductSupplier PS INNER JOIN Inventory I ON PS.productid= I.id WHERE PS.supplierid = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $supplier);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['id']."'id = insideDropbox>".$row['product_name']."</option>";
                    }
                } else {
                    echo "<option value=''>No Products</option>";
                }
            } else {
                throw new Exception("Execution failed: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Preparation failed: " . $conn->error);
        }

        $conn->close();
    } catch (Exception $e) {
        echo "<option value=''>An error occurred: " . htmlspecialchars($e->getMessage()) . "</option>";

    }
}
?>