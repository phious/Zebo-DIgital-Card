const express = require('express');
const bodyParser = require('body-parser');
const nodemailer = require('nodemailer');

const app = express();
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Configure the email transport
const transporter = nodemailer.createTransport({
    service: 'Gmail',
    auth: {
        user: 'your-email@gmail.com',
        pass: 'your-email-password'
    }
});

function sendConfirmationEmail(to, subject, text) {
    const mailOptions = {
        from: 'your-email@gmail.com',
        to: to,
        subject: subject,
        text: text
    };

    transporter.sendMail(mailOptions, function(error, info) {
        if (error) {
            console.log('Error sending email: ', error);
        } else {
            console.log('Email sent: ' + info.response);
        }
    });
}

// Route to handle form submission
app.post('/submit', (req, res) => {
    // Process the form submission and save data

    // Send confirmation email
    const { contactEmail } = req.body;
    const subject = 'Order Confirmation from ZEbo Digital Card';
    const text = `Thank you for your order. We have received your request and will process it shortly.`;
    
    sendConfirmationEmail(contactEmail, subject, text);

    res.send('Order submitted successfully.');
});

// Start the server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});
// Route to handle order tracking
app.get('/track/:orderId', (req, res) => {
    const { orderId } = req.params;
    // Fetch order status from the database (mocked here)
    const status = 'Your order is being processed'; // Replace with actual data retrieval
    res.json({ status });
});
// Route to handle feedback submission
app.post('/feedback', (req, res) => {
    const { feedback } = req.body;
    // Save feedback to the database (mocked here)
    console.log('Feedback received:', feedback);
    res.json({ message: 'Feedback received' });
});
