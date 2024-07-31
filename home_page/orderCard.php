<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/png">
    <title>Contact Form</title>
    <link rel="stylesheet" href="assets/stylesContact.css">
</head>
<body>
    <div class="contact-form-container">
        <form class="contact-form" action="submit_form.php" method="POST">
            <h2>Thank you for choosing us!</h2>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone number</label>
                <input type="number" id="phone" name="mobile" required>
            </div>
            <div class="form-group">
                <label for="email">Email (optional)</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <input type="hidden" id="plan" name="plan" value="<?php echo isset($_POST['selectedPlan']) ? $_POST['selectedPlan'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
              
                <input type="hidden" id="price" name="price" value="<?php echo isset($_POST['selectedPrice']) ? $_POST['selectedPrice'] : ''; ?>" readonly>
            </div>
            <button name="formbtn" type="submit">Order now</button>
        </form>
    </div>
</body>
</html>