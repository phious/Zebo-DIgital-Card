<?php
session_start();

if(isset($_SESSION["user"])){
    if(($_SESSION["user"])=="" || $_SESSION['usertype']!='met'){
        header("location: web.php");
        exit();
    }else{
        $useremail = $_SESSION["user"];
    }
}else{
    header("location: web.php");
    exit();
}
?> 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orders</title>  
    <link rel="stylesheet" type="text/css" href="css/style2.css">
    <link rel="stylesheet" type="text/css" href="css/takeorderbtn.css">
</head>

<body>
    <main class="table" id="customers_table">
        <button class="btn" onclick="window.location.href = 'indexAdmin.php';"> Home </button>
        <section class="table__header">
            <h1>Orders</h1>
            <div class="input-group">
                <input type="search" placeholder="Search Data...">
                <img src="images/search.png" alt="">
            </div>
        </section>
        <section class="table__body">
        <?php
        require_once 'config/connect.php';
        $sql = "SELECT o.id, o.product_name, o.orderstatus, o.paymentmode, o.`timestamp`, o.mobile, o.address1, o.country, u.firstname, u.lastname
                FROM orders o 
                JOIN usersmeta u ON o.uid=u.uid 
                ORDER BY o.id DESC";
        $res = mysqli_query($connection, $sql); 
        ?>
            <table>
                <thead>
                    <tr>
                        <th> Name <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Package <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Time <span class="icon-arrow">&UpArrow;</span></th>
                        <th> City <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Address <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Call <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Delete <span class="icon-arrow">&UpArrow;</span></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if(mysqli_num_rows($res) > 0){
                    while($row = mysqli_fetch_assoc($res)){
                        ?>
                        <tr>
                            <td><?php echo $row['firstname']. " " . $row['lastname']; ?></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['timestamp']; ?></td>
                            <td><?php echo $row['country']; ?></td>
                            <td><?php echo $row['address1']; ?></td>
                            <?php
                                $id = $row['id']; // Replace 1 with the desired ID

                                $sql = "SELECT country_code, mobile FROM orders WHERE id = ?";
                                $stmt = $connection->prepare($sql);
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                                $stmt->bind_result($countryCode, $phoneNumber);
                                $stmt->fetch();
                                $stmt->close();
                            ?>

                            <td>       
                                    <!-- <a href="tel:<?php // echo $phoneNumber; ?>" id="callButton">Call</a> -->
                                    <div class="call-button">
                                    <a href="tel:<?php echo $countryCode . $phoneNumber; ?>"><img src="images/phone-call.png" alt=""></a>
                                    </div>     
                            </td>

                            <td>
                                <form action="deletebackend.php" method="post">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>" >
                                    <button type="submit" name="delete_btn" class="button"> <img src="images/bin.png" alt=""></button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7'>No Record Found</td></tr>";
                } 
                ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="js/script2.js"></script>
</body>
</html>