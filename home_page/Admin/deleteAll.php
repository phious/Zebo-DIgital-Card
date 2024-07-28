deleteAll.php
<?php
require '../connection.php';
if(isset($_POST['delete_btn'])){

    $query = "DELETE * FROM `plans`";
    $query_run = mysqli_query($database, $query);

    if($query_run){
        $_SESSION['success'] = "Your Data is Deleted";
        header('Location: userController.php');
    }
    else{
        $_SESSION['success'] = "Your Data is Deleted";
        header('Location: userController.php');
    }
}
