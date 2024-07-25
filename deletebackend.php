<?php
 require_once 'config/connect.php';

if(isset($_POST['delete_btn'])){
    $id = $_POST['delete_id'];

    $query = "DELETE FROM `orders` WHERE id='$id' ";
    $query_run = mysqli_query($connection, $query);

    if($query_run){
        $_SESSION['success'] = "Your Data is Deleted";
        header('Location: Takeorder.php');
    }
}