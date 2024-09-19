<!DOCTYPE html>
<html>
<head>
    <?php
        include 'AddOns/header.php';
        
        
    ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.3/css/rowGroup.dataTables.min.css">
     </head>
<body class="back-body">

<div>
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
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
                            <a class="nav-link" href="Inventory.php">Inventory</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="PurchaseOrder.php">Purchase Orders</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link active" href="Data.php">Data</a>
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
        <div class="col-12 me-4">
            <button class="btn btn-secondary " onclick="POEntry(this)">Order</button>
        </div>  
        <div id="POModal" class="modal" aria-labelledby="POModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-dark">
                        <div class="modal-header">
                            <h5 class="modal-title" id="POModalLabel">Add Purchase Order</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="ProductsList.php" method="POST"  id ="POProductForm">
                        <div class="mb-3">
                            <?php
                                $sql = "SELECT id, Company FROM CONTACTS";
                                $result = $conn->query($sql);
                                echo '<label for="supplier_id" class="form-label">Select Supplier</label>'; // Default option
                                // Check if the query was successful and has results
                                if ($result->num_rows > 0) {
                                    echo '<select name="supplier_id" id="supplier_id" class="form-select" required>';
                                    echo ' <option value=""></option>';
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row["id"] . '">' . htmlspecialchars($row["Company"]) . '</option>';
                                    }

                                    echo '</select>';
                                } else {
                                    echo 'No suppliers found.';
                                }
                            ?>
                        </div>
                        <div id="sectionsContainer">
                        </div>
                        <div id="addSectionButton">
                            <button type="button" class="btn btn-dark">Add Product</button>
                        </div>
                        <div class="text-end ">
                            <button type="submit" class="btn btn-dark">Order</button>
                        </div>
                        </form>
                    </div>
                </div>
        </div> 
        <div id="POAddModal" class="modal" aria-labelledby="POAddModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-dark">
                        <div class="modal-header">
                            <h5 class="modal-title" id="POAddModalLabel">Add to Invoice</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="ProductsList.php" method="POST"  id ="POAddProductForm">
                            <input type="hidden" id="invoiceId" name="invoiceId" value="">
                            <input type="hidden" id="supplier_id" name="supplier_id" value="">
                            <div class="mb-3">
                            </div>                       
                            <div id="addSectionArea">
                            </div>    
                            <div id="addmoreSectionButton">
                                <button type="button" class="btn btn-dark">Add Product</button>
                            </div>
                            <div class="text-end ">
                                <button type="submit" class="btn btn-dark">Order</button>
                            </div>
                        
                        </form>
                    </div>
                </div>
        </div> 
        <div id="editModal" class="modal" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-dark">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit invoice Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="ProductsList.php" method="POST"  id ="editProductForm">
                            <input type="hidden" id="editid" name="editid" value="">
                            <div class="mb-3">
                                <label for="product_id" class="form-label">Select Supplier</label>
                                <div class="mb-3" id="editSectionArea">
                                </div>
                                <div >
                                    <label for="editquantity" class="form-label">quantity</label>
                                    <input type="number" class="form-control" name="editquantity" id="editquantity" autocomplete="off" required>
                                </div>
                                <div >
                                    <label for="editprice" class="form-label">price</label>
                                    <input type="text" class="form-control" name="editprice" id="editprice" autocomplete="off" step=".01" required>
                                </div>

                                <label for="editnotes">notes:</label>
                                <textarea class="form-control" id="editnotes" name="editnotes" rows="4" cols="50"></textarea><br>
                            </div>  
                            <div class="text-end ">
                                <button type="submit" class="btn btn-dark">Save Changes</button>
                            </div>
                        
                        </form>
                    </div>
                </div>
        </div> 
        <div id="InvoiceModal" class="modal" aria-labelledby="InvoiceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="InvoiceModalLabel">Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>     
            <div class="row">            
                <div class="col-12" id ="InvoiceTable">
                <table id="InvoiceForm" class="table table-dark text-center me-3" style="width:100%">
                    <tbody>
                    </tbody>
                </table>
                <div id="totalPrice" class="text-end bold"></div>
                </div>
            </div>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Purchasing Status</h2>
                <table id= "myTable" class="table table-dark text-center me-3" style="width:100%">
                    <thead >
                        <tr>
                            <th class="text-center">id</th>
                            <th class="text-center">Product name</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Company</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">notes</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>


<script>
    jQuery(document).ready(function($) {
        var table = $('#myTable').DataTable({
            serverSide: true,
            ajax: {
                url: 'Setting/PO_proccessing.php',
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
                defaultContent: '<button class="btn btn-secondary btn-sm editBtn me-3" onclick="editEntry(this)">Edit</button><button class="btn btn-danger btn-sm deleteBtn ms-3">Delete</button>',
            }
            ],
            rowGroup: {
                dataSrc: 0,
                startRender: function(rows, group) {
                    return 'Invoice ' + group; // Return the custom text for the header
                },
                endRender: function(rows, group) {
                    return '<td colspan="8" class="text-center">' +
                    '    <button class="btn btn-primary btn-sm addBtn" onclick="POAddEntry(\'' + group + '\')">Add to Invoice</button>' +
                    '    <button class="btn btn-primary btn-sm addBtn" onclick="OpenInvoice(\'' + group + '\')">Show Invoice</button>' +
                    '</td>';
                },  
                className: 'bg-dark'     
        },
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

        window.POEntry = function(button) {
            $('#POModal').modal('show'); 
    };

        window.POAddEntry = function(invoiceId) {
            $('#POAddModal').modal('show'); 
            $('#POAddModal').find('#invoiceId').val(invoiceId);
            fetchSupplierDetails(invoiceId); 
        };

        $(document).on('change', '#supplier_id', function() {
            var supplier_id = $(this).val();

            if (!supplier_id) return; // Prevent AJAX if no supplier is selected

            $.ajax({
                url: 'Setting/PoItemDropdown.php',
                type: 'POST',
                data: { supplier_id: supplier_id },
                success: function(data) {
                    $('#sectionsContainer insideDropbox').remove();
                    $('#sectionsContainer select[id^="item"]').each(function() {
                            $(this).html(data);
                    });
                },
                error: function() {
                    alert('An error occurred while fetching items. Please try again.');
                }
            });
        });
        function POItemChange(supplier_id) {
            var supplier_id = $(`#supplier_id`).val();

            if (!supplier_id) return; // Prevent AJAX if no supplier is selected

            $.ajax({
                url: 'Setting/PoItemDropdown.php',
                type: 'POST',
                data: { supplier_id: supplier_id },
                success: function(data) {
                    $('#sectionsContainer insideDropbox').remove();
                    $('#sectionsContainer select[id^="item"]').each(function() {
                        if ($(this).find('option').length === 1){
                            $(this).html(data);
                        }
                    });
                },
                error: function() {
                    alert('An error occurred while fetching items. Please try again.');
                }
            });
        };

        window.OpenInvoice = function(invoice_id) {
            $.ajax({
            url: 'Setting/InvoiceSelection.php',
            type: 'POST',
            data: { invoice_id: invoice_id },
            dataType: 'json', // Assuming the response is in JSON format
            success: function(response) {  
                $('#InvoiceForm tbody').empty();

                // Append table headers only once
                if ($('#InvoiceForm thead').length === 0) {
                    var tableHeader = `
                        <thead>
                            <tr>
                                <th class="text-center">Product name</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                    `;
                    $('#InvoiceForm').prepend(tableHeader);
                }

                // Append rows to the table body
                $.each(response.data, function(index, row) {
                    var newRow = `
                        <tr class="bg-dark">
                            <td>${row.product_name}</td>
                            <td>${row.quantity}</td>
                            <td>$${row.price}</td>
                            <td>$${row.total}</td>
                        </tr>
                    `;
                    $('#InvoiceForm tbody').append(newRow);
                });

                // Update total price
                $('#totalPrice').text('Total: $' + response.total_price);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching invoice data:', error);
            }
            });
            $('#InvoiceModal').modal('show');
        };

    function fetchSupplierDetails(invoiceId) {
        $.ajax({
            url: 'Setting/GetSupplierDetails.php',
            type: 'POST',
            data: { invoice_id: invoiceId },
            success: function(data) {
                if (Array.isArray(data)) {
                    addProductOptions(data);
                } else {
                        console.error('Unexpected data format:', data);
                        alert('An unexpected error occurred while fetching supplier details.');
                    }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                alert('An error occurred while fetching supplier details. Please try again.');
            }
        });
    }
        function addProductOptions(suppliers) {
            $.each(suppliers, function(index, supplier) {
                 var supplier_id = supplier.id;
                 $('#POAddModal').find('#supplier_id').val(supplier_id);

                if (!supplier_id) return; 

                $.ajax({
                    url: 'Setting/PoItemDropdown.php',
                    type: 'POST',
                    data: { supplier_id: supplier_id },
                    success: function(data) {
                        $('#addSectionArea select[id^="additem"]').each(function() {
                        if ($(this).find('option').length === 1) { // Only populate if it has only default option
                            $(this).html(data);
                        }
                    });
                    },
                    error: function() {
                        alert('An error occurred while fetching items. Please try again.');
                    }
                });
            });
        };
        

        function addSection() {
                var sectionIndex = $('#sectionsContainer .expandable-section').length + 1;
                var newSection = `
                    <div>
                        <div class="expandable-section mb-3">
                            <label for="item ${sectionIndex}">Item ${sectionIndex}:</label>
                                <select name="item${sectionIndex}" id="item${sectionIndex}"  class="form-select"  >
                                    <option value=""></option>
                                </select>
                        </div>
                        <div >
                            <label for="quantity${sectionIndex}" class="form-label">quantity</label>
                            <input type="number" class="form-control" name="quantity${sectionIndex}" id="quantity${sectionIndex}" autocomplete="off" required>
                        </div>
                        <div >
                            <label for="price${sectionIndex}" class="form-label">price</label>
                            <input type="text" class="form-control" name="price${sectionIndex}" id="price${sectionIndex}" autocomplete="off" step=".01" required>
                        </div>

                        <label for="notes${sectionIndex}">notes:</label>
                        <textarea class="form-control" id="notes${sectionIndex}" name="notes${sectionIndex}" rows="4" cols="50"></textarea><br>
                    </div> `;
                $('#sectionsContainer').append(newSection);
                POItemChange(supplier_id);
        }

        window.editEntry = function(button) {
            var table = $('#myTable').DataTable();
            var data = table.row($(button).parents('tr')).data();
            $('#editid').val(data[7]); 

            var supplier_id = data[3];
            $('#edititem').val(data[1]);
            $('#editquantity').val(data[2]);
            $('#editprice').val(data[4]);
            $('#editstatus').val(data[5]);
            $('#editnotes').val(data[6]);

            $.ajax({
                    url: 'Setting/EditProductDropdown.php',
                    type: 'POST',
                    data: { supplier_id: supplier_id },
                    success: function(data) {
                        $('#editSectionArea').html(data);
                    },
                    error: function() {
                        alert('An error occurred while fetching items. Please try again.');
                    }
                });

            $('#editModal').modal('show');
        };

        $('#editProductForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'Setting/UpdatePO.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                },
                error: function() {
                    alert('An error occurred while updating the contact.');
                }
            });
        });        
        $('#myTable').on('click', '.deleteBtn', function() {
            var row = table.row($(this).parents('tr'));
            var id = row.data()[7];


            if (confirm('Are you sure you want to delete this entry?')) {
                $.ajax({
                    url: 'Setting/Delete_PO.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        $('#myTable').DataTable().ajax.reload();
                    },
                    error: function() {
                        alert('An error occurred while sending the delete request.');
                    }
                });
            }
        });

        function addmoreSection() {
                var addSectionIndex = $('#addSectionArea .expandable-section').length + 1;
                var addnewSection = `
                    <div>
                        <div class="expandable-section mb-3">
                            <label for="additem ${addSectionIndex}">Item ${addSectionIndex}:</label>
                                <select name="additem${addSectionIndex}" id="additem${addSectionIndex}"  class="form-select"  >
                                    <option value=""></option>
                                </select>
                        </div>
                        <div >
                            <label for="addquantity${addSectionIndex}" class="form-label">quantity</label>
                            <input type="addnumber" class="form-control" name="addquantity${addSectionIndex}" id="addquantity${addSectionIndex}" autocomplete="off" required>
                        </div>
                        <div >
                            <label for="addprice${addSectionIndex}" class="form-label">price</label>
                            <input type="text" class="form-control" name="addprice${addSectionIndex}" id="addprice${addSectionIndex}" autocomplete="off" step=".01" required>
                        </div>

                        <label for="addnotes${addSectionIndex}">notes:</label>
                        <textarea class="form-control" id="addnotes${addSectionIndex}" name="addnotes${addSectionIndex}" rows="4" cols="50"></textarea><br>
                    </div> `;
                $('#addSectionArea').append(addnewSection);
                fetchSupplierDetails($('#invoiceId').val());
        }

            addSection();
            addmoreSection();

            // Add new sections when the button is clicked
            $('#addSectionButton').click(function() {
                addSection();
            });
            $('#addmoreSectionButton').click(function() {
                addmoreSection();
            });

            $(document).on('click', '.toggle-button', function() {
                var target = $(this).data('target');
                $(target).toggleClass('show');
                var isExpanded = $(target).hasClass('show');
                $(this).text(isExpanded ? 'Collapse Section' : $(this).data('original-text'));
            });

            $(document).on('mouseover', '.toggle-button', function() {
                if (!$(this).data('original-text')) {
                    $(this).data('original-text', $(this).text());
                }
            });

            $('#POProductForm').on('submit', function(e) {
                e.preventDefault(); 
                let sectionIndex = $('#sectionsContainer .expandable-section').length;

                let supplier_id = $(`#supplier_id`).val();
                let items = [];
                let quantities = [];
                let prices = [];
                let notes = [];

                // Collect data and store in arrays
                while (sectionIndex > 0) {
                    items.push($(`#item${sectionIndex}`).val());
                    quantities.push($(`#quantity${sectionIndex}`).val());
                    prices.push($(`#price${sectionIndex}`).val());
                    notes.push($(`#notes${sectionIndex}`).val());

                    sectionIndex--;
                }

                // Prepare the data to be sent to the server
                let postData = {
                    supplier_id: supplier_id, 
                    items: items,
                    quantities: quantities,
                    prices: prices,
                    notes: notes
                };

                // Perform AJAX request
                $.ajax({
                    url: 'Setting/Add_PO.php',
                    method: 'POST',
                    data: JSON.stringify(postData),
                    contentType: 'application/json',
                    success: function(response) {
                        $('#POProductForm')[0].reset();
                        $('#supplier_id').val('');
                        $('#POModal').modal('hide');
                        table.ajax.reload(); // Reload the DataTable to show the new user
                    },
                    error: function() {
                        alert('An error occurred while sending the data.');
                    }
                });
                $(POModal).find('form')[0].reset();
                $(POModal).find('#sectionsContainer').empty();
                $(POModal).find('#supplier_id').val('');
                $(POModal).find('.alert').remove();
                addSection();

            });

            $('#POAddProductForm').on('submit', function(e) {
                e.preventDefault(); 
                let addSectionIndex = $('#addSectionArea .expandable-section').length;

                let supplier_id = $('#POAddProductForm').find('#supplier_id').val();
                let invoice_id = $('#POAddProductForm').find('#invoiceId').val();
                let items = [];
                let quantities = [];
                let prices = [];
                let notes = [];

                
                while (addSectionIndex > 0) {
                    items.push($(`#additem${addSectionIndex}`).val());
                    quantities.push($(`#addquantity${addSectionIndex}`).val());
                    prices.push($(`#addprice${addSectionIndex}`).val());
                    notes.push($(`#addnotes${addSectionIndex}`).val());

                    addSectionIndex--;
                }


                let postData = {
                    supplier_id: supplier_id, 
                    invoice_id: invoice_id,
                    items: items,
                    quantities: quantities,
                    prices: prices,
                    notes: notes
                };

                // Perform AJAX request
                $.ajax({
                    url: 'Setting/Add_PO.php',
                    method: 'POST',
                    data: JSON.stringify(postData),
                    contentType: 'application/json',
                    success: function(response) {
                        $('#POAddProductForm')[0].reset();
                        $('#supplier_id').val('');
                        $('#POAddModal').modal('hide');
                        table.ajax.reload(); // Reload the DataTable to show the new user
                    },
                    error: function() {
                        alert('An error occurred while sending the data.');
                    }
                });
                $(POAddModal).find('form')[0].reset();
                $(POAddModal).find('#sectionsContainer').empty();
                $(POAddModal).find('#supplier_id').val('');
                $(POAddModal).find('.alert').remove();
                addmoreSection();

            });
            $('#POModal').on('hidden.bs.modal', function () {
                $(POModal).find('form')[0].reset();
                $(POModal).find('#sectionsContainer').empty();
                $(POModal).find('#supplier_id').val('');
                $(POModal).find('.alert').remove();
                addSection();
            });

            $('#POAddModal').on('hidden.bs.modal', function () {
                $(POAddModal).find('form')[0].reset();
                $(POAddModal).find('#addSectionArea').empty();
                $(POAddModal).find('.alert').remove();
                addmoreSection();;
            });
            
    });
</script>
</body>
</html>