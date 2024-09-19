// Get the new modal
var modal = document.getElementById("editModal");

// Get the button that opens the modal
var editBtn = document.getElementById("editSelectedBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
editBtn.onclick = function() {
    // Find the selected checkbox
    var selectedId = document.querySelector('input[name="selected_id"]:checked');
    if (selectedId) {
        var id = selectedId.value;

        // Fetch the data for the selected ID
        fetch(`fetch_item.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                // Populate the form fields
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_product_name').value = data.product_name;
                document.getElementById('edit_quantity').value = data.quantity;
                document.getElementById('edit_notes').value = data.notes;

                // Show the modal
                modal.style.display = "block";
            });
    } else {
        alert('Please select one item to edit.');
    }
};

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
