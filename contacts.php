<?php
session_start();
if(isset($_SESSION["user"])){
    if(($_SESSION["user"])=="" or $_SESSION['usertype']!='met'){
        
    }else{
        $useremail=$_SESSION["user"];
    }

}else{
    //header("location: DigitalCard/index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contacts</title>
    <link rel="stylesheet" type="text/css" href="css/style2.css">
    <link rel="stylesheet" type="text/css" href="css/takeorderbtn.css">

    
</head>


<body>
    <main class="table" id="customers_table">
    <button class="btn" onclick="window.location.href = 'indexAdmin.php'; "> Home </button>
        <section class="table__header">
            <h1>Contacts</h1>
            <div class="input-group">
                <input type="search" placeholder="Search Data...">
                <img src="images/search.png" alt="">
            </div>
           
        </section>
        <section class="table__body">
        <?php
        require 'connection.php';
        $query = "SELECT * From `contacts` WHERE usertype= 'met' ";
        $query_run = mysqli_query($database, $query)
        ?>
            <table>
                <thead>
                    <tr>
                        <th> Name <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Phone number <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Email <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Call <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Delete <span class="icon-arrow">&UpArrow;</span></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                                    if(mysqli_num_rows($query_run) > 0){
                            
                                        while($row = mysqli_fetch_assoc($query_run)){
                                            
                                            ?>
                            
                                        <tr>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['country_code'] . '' . $row['tel']; ?></td>
                                            <td><?php echo $row['email']; ?></td>

                                            <?php
                                           $id = $row['id']; // Replace 1 with the desired ID

                                           $sql = "SELECT country_code, tel FROM contacts WHERE id = ?";
                                           $stmt = $database->prepare($sql);
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
                                                <form action="deletebackendContacts.php" method="post">
                                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>" >
                                                     <button type="submit"  name="delete_btn" class="button" > <img src="images/bin.png" alt=""></button>
                                                </form>
                                            </td>
                                           
                                            


                                           
                                        </tr>
                                        
                                        <?php
                                        }
                                        
                                    }else {
                                        "No Record Found";
                                    } 
                                    ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="js/script2.js"></script>

</body>

</html>
