<?php
    $supplier = $_POST['supplier_id'];
    $servername = "localhost";
    $username = "debian-sys-maint";
    $password = "0DXddFBx19pUUQ6F";
    $dbname = "Product_inventory";    

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
   $sql = "SELECT DISTINCT i.id, i.product_name FROM Inventory i LEFT JOIN ProductSupplier pi on i.supplierID = pi.supplierid LEFT JOIN CONTACTS c on pi.supplierid = c.id  WHERE c.Company = ?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("s", $supplier );
   $stmt->execute();
   $result = $stmt->get_result();

    if ($result->num_rows > 0) {
    echo '<select name="product_id" id="product_id" class="form-select" required>';
    echo ' <option value="">Select the change of product</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row["id"] . '">' . htmlspecialchars($row["product_name"]) . '</option>';
    }

        echo '</select>';
    } else {
        echo 'No products found.';
    }
?>