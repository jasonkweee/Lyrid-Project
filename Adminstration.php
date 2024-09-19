<!DOCTYPE html>
<html>
<head>
    <?php
        include 'AddOns/header.php';
        include 'Setting/checkDuplicateAdmin.php';

        // Redirect if authority level is insufficient
        if (!$auth->checkAuthority(3)) { 
            header("Location: errorpage.php");
            exit();
        }    
        
    ?>
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
                            <a class="nav-link active" href="Adminstration.php">Admin</a>
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

<div class="container-xl mt-4 bg-dark text-white p-5 rounded">
    <div class="col-12 text-end">
        <button id="addButton" class="btn btn-secondary" onclick="addEntry(this)">Add</button>
    </div>
    <div class="modal fade " id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id = "AddUserForm">
                        <div class="mb-3">
                            <label for="addUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" name="addUsername" id="addUsername" autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <label for="addPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" name="addPassword" id="addPassword"  autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <label for="addAuthority" class="form-label">Authority</label>
                            <select class="form-select" name="addAuthority" id="addAuthority">
                            <option value= 1>View Only</option>
                            <option value= 2>Employee</option>
                            <option value= 3>Admin</option>
                            </select>
                        </div>
                            <button type="submit" class="btn btn-primary" name="submit">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4">Admin Dashboard</h2>
            <table id= "myTable" class="table table-dark text-center m-3" style="width:100%">
                <thead >
                    <tr>
                        <th class="text-center">id</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Password</th>
                        <th class="text-center">Security Level</th>
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
        <h5 class="modal-title" id="editModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editUserForm">
        <div class="mb-3">
            <label for="editId" class="form-label">ID</label>
            <input type="text" class="form-control" name="id" id="editId" readonly>
          </div>
          <div class="mb-3">
            <label for="editUsername" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="editUsername" readonly>
          </div>
          <div class="mb-3">
            <label for="editPassword" class="form-label">Password</label>
            <input type="text" class="form-control" name="password" id="editPassword" autocomplete="off" required>
          </div>
          <div class="mb-3">
            <label for="editAuthority" class="form-label">Authority</label>
            <select class="form-select" name="authority" id="editAuthority">
              <option value= 1>View Only</option>
              <option value= 2>Employee</option>
              <option value= 3>Admin</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary" name="submit">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#myTable').DataTable({
            serverSide: true,
            ajax: {
                url: 'Setting/server_processing.php',
                type: 'POST',
                dataSrc: function(json) {
                json.data = json.data.map(function(row) {
                    row[2] = '****';
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
                targets: -1, // Targets the last column (Actions column)
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
        $('#editUsername').val(data[1]);
        $('#editPassword').val("");
        $('#editAuthority').val(data[3]);

        $('#editModal').modal('show');

    };

    // Handle Edit User form submission
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'Setting/Update_user.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#editModal').modal('hide');
                table.ajax.reload(); // Reload the DataTable to show the new user
            },
            error: function() {
                alert('An error occurred while updating the user.');
            }
        });
    });
    $('#AddUserForm').on('submit', function(e) {
            e.preventDefault();
            const username = $('#addUsername').val();
            const password = $('#addPassword').val();
            const authority = $('#addAuthority').val();
            $.ajax({
                url: 'Setting/Add_user.php',
                method: 'POST',
                data: $('#AddUserForm').serialize(),
                success: function(response) {
            if (response.trim() === 'exists') {
                alert('Username already exists. Please choose a different username.');
            }else {
                $('#addModal').modal('hide');
                table.ajax.reload(); // Reload the DataTable to show the new user
            }
                },
                error: function() {
                    alert('An error occurred while adding the user.');
                }
            });
        });

        
    $('#myTable').on('click', '.deleteBtn', function() {
            var row = table.row($(this).parents('tr'));
            var id = row.data()[0]; // Assumes ID is in the first column

            if (confirm('Are you sure you want to delete this entry?')) {
                $.ajax({
                    url: 'Setting/delete_user.php',
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


    window.addEntry = function(button) {
        $('#addModal').modal('show');
    };
    });

   
</script>
<?php 
    include 'Footer.php'
?>
</html>