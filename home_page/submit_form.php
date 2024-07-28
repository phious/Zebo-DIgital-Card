<?php
session_start();
require 'connection.php';

if (isset($_POST['formbtn'])) {
    // Retrieve and sanitize form data
    $fullname = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['mobile']);
    $email = htmlspecialchars($_POST['email']);
    $plan = htmlspecialchars($_POST['plan']);
    $price = htmlspecialchars($_POST['price']);

    // Prepare SQL statement
    $stmt = $database->prepare("INSERT INTO plans (name, mobile, email, plan, price) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        $_SESSION['status'] = "Failed to prepare statement: " . $database->error;
        header('Location: orderCard.php');
        exit();
    }

    // Bind parameters
    $stmt->bind_param("sssss", $fullname, $phone, $email, $plan, $price);

    // Execute statement
    if ($stmt->execute()) {
        $_SESSION['status'] = "Admin Profile Added";
        header('Location: successorder.php');
    } else {
        $_SESSION['status'] = "Admin Profile Not Added: " . $stmt->error;
        header('Location: orderCard.php');
    }

    // Close statement and database connection
    $stmt->close();
    $database->close();
}
?>
