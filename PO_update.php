<?php 
include 'AddOns/header.php';
// Load and initialize database class 
require_once 'PO_updateclass.php'; 

$db = new DB(); 

if(isset($_POST['action'])) {
    if($_POST['action'] == 'edit'){ 
                // Check if all necessary fields are set
        if(isset($_POST['quantity'], $_POST['price'], $_POST['notes'], $_POST['status'])){
            // Update data 
            $userData = array( 
                'quantity' => $_POST['quantity'], 
                'price' => $_POST['price'], 
                'notes' => $_POST['notes'], 
                'status' => $_POST['status'] 
            ); 
            $condition = array( 
                'id' => $_POST['id'] 
            ); 
            $update = $db->update($userData, $condition); 
         
            if($update){ 
                $response = array( 
                    'status' => 1, 
                    'msg' => 'Member data has been updated successfully.', 
                    'data' => $userData 
                ); 
            }else{ 
                $response['msg'] = 'Failed to update member data. Please check the input and try again.';
            }
        } else {
            $response['msg'] = 'Missing required fields for update.';
        }
    } elseif($_POST['action'] == 'delete'){ 
        // Delete data 
        $condition = array('id' => $_POST['id']); 
        $delete = $db->delete($condition); 
     
        if($delete){ 
            $response = array( 
                'status' => 1, 
                'msg' => 'Member data has been deleted successfully.' 
            ); 
        }else{ 
            $response['msg'] = 'Failed to delete member data. The ID may not exist.';
        } 
    }
} else {
    $response['msg'] = 'GTW';
}
 
echo json_encode($response); 
exit(); 
 
?>
