<?php
session_start();
require 'connection.php';
if(isset($_POST['formbtn'])){
    $customerName = $_POST['name'];
    $tele = $_POST['tel'];
    $countryCode = $_POST['countryCode'];
    $email = $_POST['email']; 
    $usertype = 'met';

    $stmt = $database->prepare("INSERT INTO contacts (name, tel, email, country_code, usertype) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $customerName, $tele, $email, $countryCode, $usertype);

    if ($stmt->execute()) {
        $phoneNumber = "+251985586071";
        $_SESSION['status'] = "Admin Profile Added";
        header('Location: tel:' . $phoneNumber);
    } else {
        $_SESSION['status'] = "Admin Profile Not Added";
        header('Location: tel:' . $phoneNumber);
    }

    $stmt = $database->prepare("SELECT * FROM contacts WHERE usertype = ?");
    $stmt->bind_param("s", $usertype);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $database->close();
}