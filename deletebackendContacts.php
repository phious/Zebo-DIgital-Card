<?php
require 'connection.php';

if(isset($_POST['delete_btn'])){
    $id = $_POST['delete_id'];

    $query = "DELETE FROM `contacts` WHERE id='$id' ";
    $query_run = mysqli_query($database, $query);

    if($query_run){
        $_SESSION['success'] = "Your Data is Deleted";
        header('Location: contacts.php');
    }
}