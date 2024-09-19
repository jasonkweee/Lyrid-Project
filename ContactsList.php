
<!DOCTYPE html>
<html>
<head>
    <?php
        include 'AddOns/header.php';
        $cssFile = "Plugin\ContactsPage.css";
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
                                <a class="nav-link" href="Inventory.php">Inventory</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="PurchaseOrder.php">Purchase Orders</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="Data.php">Orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="ContactsList.php">Contacts</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div> 
    <div class="container-xl mt-4 bg-dark text-white p-5 rounded">
        <div> 
            <div class="col-12 text-end me-4">
                <button class="btn btn-secondary " onclick="addEntry(this)">Add Contact</button>
            </div>  
            <div id="addModal" class="modal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-dark">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Contact</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="ContactsList.php" method="POST"  id ="AddContactForm">
                        <div >
                            <label for="addFirst_name" class="form-label">First name</label>
                            <input type="text" class="form-control" name="First_name" id="addFirst_name" autocomplete="off" >
                        </div>
                        <div >
                            <label for="addLast_name" class="form-label">Last name</label>
                            <input type="text" class="form-control" name="Last_name" id="addLast_name" autocomplete="off" required>
                        </div>
                        <div >
                            <label for="addPhoneNumber" class="form-label">Phone number</label>
                            <input type="text" class="form-control" name="PhoneNumber" id="addPhoneNumber" autocomplete="off" required>
                        </div>
                        <div >
                            <label for="addemail" class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" id="addemail" autocomplete="off" >
                        </div>
                        <div >
                            <label for="addCompany" class="form-label">Company</label>
                            <input type="text" class="form-control" name="Company" id="addCompany" autocomplete="off" required>
                        </div>
                        <label for="addAddress">Address:</label>
                        <textarea class="form-control" id="addAddress" name="Address" rows="4" cols="50"></textarea><br>
                        <div class="text-end ">
                            <button type="submit" class="btn btn-dark">Add Contact</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>  
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Contact List</h2>
                <table id= "myTable" class="table table-dark text-center me-3" style="width:100%">
                    <thead >
                        <tr>
                            <th class="text-center">id</th>
                            <th class="text-center">First Name</th>
                            <th class="text-center">Last Name</th>
                            <th class="text-center">Phone Number</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Company</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div> 
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editContactsForm">
                <div>
                    <label for="editId" class="form-label">ID</label>
                    <input type="text" class="form-control" name="id" id="editId" readonly>
                </div>
                <div >
                    <label for="editFirst_name" class="form-label">First name</label>
                    <input type="text" class="form-control" name="First_name" id="editFirst_name" autocomplete="off" >
                </div>
                <div >
                    <label for="editLast_name" class="form-label">Last name</label>
                    <input type="text" class="form-control" name="Last_name" id="editLast_name" autocomplete="off" required>
                </div>
                <div >
                    <label for="editPhoneNumber" class="form-label">Phone number</label>
                    <input type="text" class="form-control" name="PhoneNumber" id="editPhoneNumber" autocomplete="off" required>
                </div>
                <div >
                    <label for="editemail" class="form-label">email</label>
                    <input type="text" class="form-control" name="email" id="editemail" autocomplete="off" >
                </div>
                <div >
                    <label for="editCompany" class="form-label">Company</label>
                    <input type="text" class="form-control" name="Company" id="editCompany" autocomplete="off" readonly>
                </div>
                <label for="editAddress">Address:</label>
                <textarea class="form-control" id="editAddress" name="Address" rows="4" cols="50"></textarea><br>

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
                url: 'Setting/contactlist_proccessing.php',
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
        $('#editFirst_name').val(data[1]);
        $('#editLast_name').val(data[2]);
        $('#editPhoneNumber').val(data[3]);
        $('#editemail').val(data[4]);
        $('#editCompany').val(data[5]);
        $('#editAddress').val(data[6]);

        $('#editModal').modal('show');
    };
    window.addEntry = function(button) {
        $('#addModal').modal('show');
    };

    $('#editContactsForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'Setting/Update_contact.php',
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
    $('#AddContactForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        const First_name = $('#First_name').val();
        const Last_name = $('#Last_name').val();
        const Address = $('#Address').val();
        const email = $('#email').val();
        const PhoneNumber = $('#PhoneNumber').val();
        const Company = $('#Company').val();


        $.ajax({
            url: 'Setting/Add_Contact.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.trim() === 'exists') {
                alert('Username already exists. Please choose a different username.');
                }else {
                    $('#addModal').modal('hide');
                    table.ajax.reload(); // Reload the DataTable to show the new user
                }
            },
            error: function() {
                alert('An error occurred while adding the Contact.');
            }
        });
    });
    $('#myTable').on('click', '.deleteBtn', function() {
            var row = table.row($(this).parents('tr'));
            var id = row.data()[0]; // Assumes ID is in the first column

            if (confirm('Are you sure you want to delete this entry?')) {
                $.ajax({
                    url: 'Setting/delete_contact.php',
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