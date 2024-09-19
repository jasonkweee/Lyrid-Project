
<!DOCTYPE html>
<html>
<head>
    <?php
        include 'AddOns/header.php';
        $cssFile = "Plugin\InventoryPage.css";
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
                                <a class="nav-link" aria-current="page" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="Adminstration.php">Admin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="Inventory.php">Inventory</a>
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
    <div class="container-xl mt-4 bg-dark text-white p-5 rounded">
            <div class="col-12 text-end me-4">
                <button class="btn btn-secondary " onclick="addEntry(this)">Add New Product</button>
            </div>  
            <div id="addModal" class="modal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-dark">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add New Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="ProductsList.php" method="POST"  id ="AddProductForm">
                        <div class="mb-3">
                            <?php
                            $sql = "SELECT id, Company FROM CONTACTS";
                            $result = $conn->query($sql);
                            echo '<label for="supplier_id" class="form-label">Select Supplier</label>'; // Default option
                            // Check if the query was successful and has results
                            if ($result->num_rows > 0) {
                                echo '<select name="supplier_id" id="supplier_id" class="form-select" required>';
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row["id"] . '">' . htmlspecialchars($row["Company"]) . '</option>';
                                }

                                echo '</select>';
                            } else {
                                echo 'No suppliers found.';
                            }
                            ?>
                        </div>
                        <div >
                            <label for="addproduct_name" class="form-label">Product name</label>
                            <input type="text" class="form-control" name="product_name" id="addproduct_name" autocomplete="off" >
                        </div>
                        <div >
                            <label for="addquantity" class="form-label">quantity</label>
                            <input type="number" class="form-control" name="quantity" id="addquantity" autocomplete="off" required>
                        </div>
                        <div >
                            <label for="addprice" class="form-label">price</label>
                            <input type="number" class="form-control" name="price" id="addprice" autocomplete="off" step=".01" required>
                        </div>
                        <div >
                            <label for="addPprice" class="form-label">Purchase Price</label>
                            <input type="text" class="form-control" name="Pprice" id="addPprice" autocomplete="off" step=".01" required>
                        </div>
                        <label for="addnotes">notes:</label>
                        <textarea class="form-control" id="addnotes" name="notes" rows="4" cols="50"></textarea><br>
                        <div class="text-end ">
                            <button type="submit" class="btn btn-dark">Add Product</button>
                        </div>
                        </form>
                </div>
            </div>
        </div>  
    
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Inventory List</h2>
                <table id= "myTable" class="table table-dark text-center me-3" style="width:100%">
                    <thead >
                        <tr>
                            <th class="text-center">id</th>
                            <th class="text-center">Product name</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Selling Price</th>
                            <th class="text-center">Purchase Price</th>
                            <th class="text-center">Quantity Sold</th>
                            <th class="text-center">Company</th>
                            <th class="text-center">Notes</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductsForm">
                <div>
                    <label for="editId" class="form-label">ID</label>
                    <input type="text" class="form-control" name="id" id="editId" readonly>
                </div>
                <div >
                    <label for="editproduct_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" name="product_name" id="editproduct_name" readonly>
                </div>
                <div >
                    <label for="editquantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="quantity" id="editquantity" autocomplete="off" required>
                </div>
                <div >
                    <label for="editprice" class="form-label">Sale Price</label>
                    <input type="text" class="form-control" name="price" id="editprice" autocomplete="off" step=".01" required>
                </div>
                <div >
                    <label for="editPprice" class="form-label">Purchase Price</label>
                    <input type="text" class="form-control" name="Pprice" id="editPprice" autocomplete="off" step=".01" required>
                </div>
                <div >
                    <label for="editqsold" class="form-label">Sold Items</label>
                    <input type="text" class="form-control" name="qsold" id="editqsold" autocomplete="off" >
                </div>
                <label for="editnotes">notes:</label>
                <textarea class="form-control" id="editnotes" name="notes" rows="4" cols="50"></textarea><br>

                <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>         


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
    jQuery(document).ready(function($) {
        var table = $('#myTable').DataTable({
            serverSide: true,
            ajax: {
                url: 'Setting/Inventory_proccessing.php',
                type: 'POST',
                dataSrc: function(json) {
                json.data = json.data.map(function(row) {
                    return row;
                });
                return json.data;
                }
            },
            searching: true,   
            ordering: true,    
            info: true,     
            lengthChange: true, 
            pageLength: 5,     
            lengthMenu: [5, 10, 25, 50], 
            order: [[0, 'asc']],
            columnDefs: [
            {
                targets: -1,
                data: null,
                defaultContent: '<button class="btn btn-secondary btn-sm editBtn me-3 " onclick="editEntry(this)">Edit</button><button class="btn btn-danger btn-sm deleteBtn ms-3">Delete</button>',
            }
            ],
            language: {
                search: "Search table:",
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    window.editEntry = function(button) {
        var table = $('#myTable').DataTable();
        var data = table.row($(button).parents('tr')).data();
        $('#editId').val(data[0]);
        $('#editproduct_name').val(data[1]);
        $('#editquantity').val(data[2]);
        $('#editprice').val(data[3]);
        $('#editPprice').val(data[4]);
        $('#editqsold').val(data[5]);
        $('#editnotes').val(data[7]);

        $('#editModal').modal('show');
    };
    window.addEntry = function(button) {
        $('#addModal').modal('show');
    };

    $('#editProductsForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'Setting/Update_inventory.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#editModal').modal('hide');
                table.ajax.reload(); // Reload the DataTable to show the new user
            },
            error: function() {
                alert('An error occurred while updating the contact.');
            }
        });
    });     
    $('#AddProductForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        const product_name = $('#product_name').val();
        const quantity = $('#quantity').val();
        const notes = $('#notes').val();
        const email = $('#email').val();
        const price = $('#price').val();
        const Pprice = $('#Pprice').val();
        const qsold = $('#qsold').val();
        const supplier_id = $('#supplier_id').val();
        const id = $('#id').val();

        $.ajax({
            url: 'Setting/Add_inventory.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.trim() === 'exists') {
                alert('Product already exists.');
            } else {
                $('#addModal').modal('hide');
                table.ajax.reload(); // Reload the DataTable to show the new user
            } },
            error: function() {
                alert('An error occurred while adding the Product.');
            }
        });
    });
    $('#myTable').on('click', '.deleteBtn', function() {
            var row = table.row($(this).parents('tr'));
            var id = row.data()[0]; // Assumes ID is in the first column

            if (confirm('Are you sure you want to delete this entry?')) {
                $.ajax({
                    url: 'Setting/delete_inventory.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        row.remove().draw(); // Remove the row and redraw the table
                    },
                    error: function() {
                        alert('An error occurred while deleting the entry.');
                    }
                });
            }
        });
    });


   
</script>
</body>
</html>