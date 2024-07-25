<?php
ob_start();
session_start();
require_once 'config/connect.php';
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verify if the user is logged in
if (!isset($_SESSION['customer']) || empty($_SESSION['customer'])) {
    header('Location: login.php');
    exit();
}

include 'inc/header.php'; 
include 'inc/nav.php';

$uid = $_SESSION['customerid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agree']) && $_POST['agree'] === 'true') {
        $country = htmlspecialchars(strip_tags($_POST['country']), ENT_QUOTES, 'UTF-8');
        $fname = htmlspecialchars(strip_tags($_POST['fname']), ENT_QUOTES, 'UTF-8');
        $lname = htmlspecialchars(strip_tags($_POST['lname']), ENT_QUOTES, 'UTF-8');
        $address1 = htmlspecialchars(strip_tags($_POST['address1']), ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars(strip_tags($_POST['phone']), ENT_QUOTES, 'UTF-8');
        $paymentMode = htmlspecialchars(strip_tags($_POST['payment']), ENT_QUOTES, 'UTF-8');

        // Debugging output
        echo "Selected City: $country <br>";
        echo "Payment mode: $paymentMode <br>";

        // Check if usermeta exists and prepare SQL statement
        $stmt = $connection->prepare("SELECT * FROM usersmeta WHERE uid=?");
        if (!$stmt) {
            die('Prepare failed: ' . htmlspecialchars($connection->error));
        }
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->num_rows;

        // Insert or update usermeta
        if ($count == 1) {
            $stmt = $connection->prepare("UPDATE usersmeta SET country=?, firstname=?, lastname=?, address1=?, mobile=? WHERE uid=?");
            if (!$stmt) {
                die('Prepare failed: ' . htmlspecialchars($connection->error));
            }
            $stmt->bind_param("sssssi", $country, $fname, $lname, $address1, $phone, $uid);
        } else {
            $stmt = $connection->prepare("INSERT INTO usersmeta (country, firstname, lastname, address1, mobile, uid) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                die('Prepare failed: ' . htmlspecialchars($connection->error));
            }
            $stmt->bind_param("sssssi", $country, $fname, $lname, $address1, $phone, $uid);
        }
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $total = 0;
            $productNames = [];

            // Calculate total price and gather product names
            foreach ($_SESSION['cart'] as $key => $value) {
                $stmt = $connection->prepare("SELECT * FROM products WHERE id=?");
                if (!$stmt) {
                    die('Prepare failed: ' . htmlspecialchars($connection->error));
                }
                $stmt->bind_param("i", $key);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();

                $productNames[] = $product['name'];
                $total += $product['price'] * $value['quantity'];
            }

            $productNamesString = implode(", ", $productNames);

            // Insert order into orders table
            $stmt = $connection->prepare("INSERT INTO orders (uid, totalprice, orderstatus, paymentmode, product_name, mobile, usertype, address1, country) VALUES (?, ?, 'Order Placed', ?, ?, ?, 'met', ?, ?)");
            if (!$stmt) {
                die('Prepare failed: ' . htmlspecialchars($connection->error));
            }
            $stmt->bind_param("idsssss", $uid, $total, $paymentMode, $productNamesString, $phone, $address1, $country);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $orderid = $stmt->insert_id;

                // Insert order items into orderitems table
                foreach ($_SESSION['cart'] as $key => $value) {
                    $stmt = $connection->prepare("SELECT * FROM products WHERE id=?");
                    if (!$stmt) {
                        die('Prepare failed: ' . htmlspecialchars($connection->error));
                    }
                    $stmt->bind_param("i", $key);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $product = $result->fetch_assoc();

                    $pid = $product['id'];
                    $productprice = $product['price'];
                    $quantity = $value['quantity'];

                    $stmt = $connection->prepare("INSERT INTO orderitems (pid, orderid, productprice, pquantity, product_name) VALUES (?, ?, ?, ?, ?)");
                    if (!$stmt) {
                        die('Prepare failed: ' . htmlspecialchars($connection->error));
                    }
                    $stmt->bind_param("iiids", $pid, $orderid, $productprice, $quantity, $product['name']);
                    $stmt->execute();
                }

                unset($_SESSION['cart']);

                // Sending SMS notification using Twilio
                $account_sid = 'ACd3b3ec682a5bcb2645575c25a4d63afa'; // Replace with your Twilio Account SID
                $auth_token = '2c5158b386533a6382f5d3eb21f70b5d'; // Replace with your Twilio Auth Token
                $twilio_number = "+14405717866"; // Your Twilio phone number

                $client = new Client($account_sid, $auth_token);

                // Admin phone number (replace with your actual admin phone number)
                $adminPhone = '+251947346216';

                $messageBody = "\nNew order received!\n" .
                "Order ID: $orderid\n" .
                "Customer: $fname $lname\n" .
                "Phone: $phone\n" .
                "Package: {$product['name']}\n" .
                "City: $country\n" .
                "Address: $address1\n" .
                "Payment Mode: $paymentMode\n";

                // Create and send the message
                $client->messages->create(
                    $adminPhone,
                    [
                        'from' => $twilio_number,
                        'body' => $messageBody
                    ]
                );

                // Redirect after sending SMS
                header("Location: my-account.php");
                exit();
            } else {
                echo "Error: Could not insert order.";
            }
        } else {
            echo "Error: Could not update usermeta.";
        }
    }
}

// Fetch user details for pre-filling the form
$stmt = $connection->prepare("SELECT * FROM usersmeta WHERE uid=?");
if (!$stmt) {
    die('Prepare failed: ' . htmlspecialchars($connection->error));
}
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
$r = $result->fetch_assoc();

// List of available cities
$cities = ["Shashamane", "Hawassa", "Addis Ababa", "Adama", "Dodola", "Wolayta", "Arsi Negele", "Meki", "Wondo", "Dilla"];
?>

<!-- HTML form and content for order processing -->
<section id="content">
    <div class="content-blog">         
        <form method="post">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="billing-details">
                            <h3 class="uppercase">Order Details</h3>
                            <div class="space30"></div>
                            <label class="">City </label>
                            <select name="country" class="form-control">
                                <option value="">Select City</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?php echo $city; ?>" <?php echo (isset($r['country']) && $r['country'] == $city) ? 'selected' : ''; ?>><?php echo $city; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="clearfix space20"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>First Name </label>
                                    <input name="fname" class="form-control" placeholder="" value="<?php echo isset($r['firstname']) ? $r['firstname'] : ''; ?>" type="text">
                                </div>
                                <div class="col-md-6">
                                    <label>Last Name </label>
                                    <input name="lname" class="form-control" placeholder="" value="<?php echo isset($r['lastname']) ? $r['lastname'] : ''; ?>" type="text">
                                </div>
                            </div>
                            <div class="clearfix space20"></div>
                            <label>Address </label>
                            <input name="address1" class="form-control" placeholder="Street address" value="<?php echo isset($r['address1']) ? $r['address1'] : ''; ?>" type="text">
                            <div class="clearfix space20"></div>
                            <label>Phone </label>
                            <input name="phone" class="form-control" id="billing_phone" placeholder="" value="<?php echo isset($r['mobile']) ? $r['mobile'] : ''; ?>" type="text">
                        </div>
                    </div>
                </div>
                
                <div class="space30"></div>
                <h4 class="heading">Your order</h4>
                
                <table class="table table-bordered extra-padding">
                    <tbody>
                        <tr>
                            <th>Cart Subtotal</th>
                            <td><span class="amount">0.00</span></td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="clearfix space30"></div>
              
                <div class="payment-method">
                    <h4 class="heading">Payment Method</h4>
                    <div class="payment-options">
                        <label style="color: red;"><input type="radio" name="payment" value="Half now"> ግማሽ ክፍያ አሁን ልካለዉ</label><br>
                        <label style="color: red;"><input type="radio" name="payment" value="Full later"> ሙሉ ክፍያ በኃላ</label><br>
                    </div>
                    <div class="space30"></div>
                    <input name="agree" id="checkboxG2" class="css-checkbox" type="checkbox" value="true">
                    <span style="color: red;">እችላለሁ፣ ግዢ እና የግዢ ሪፖርትስን ለመቀበል ዝግጁ ነኝ። (ግዴታ ነው)</span>
                    <div class="space30"></div>
                    <input type="submit" class="button btn-lg" value="Order Now">
                </div>
            </div>        
        </form>        
    </div>
</section>

<?php include 'inc/footer.php'; ?>