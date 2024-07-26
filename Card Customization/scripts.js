document.addEventListener('DOMContentLoaded', function() {
    // File upload drag-and-drop functionality
    const fileUpload = document.querySelector('.file-upload');
    const fileInput = fileUpload.querySelector('input[type="file"]');
    const fileDragDrop = fileUpload.querySelector('.file-drag-drop');

    fileDragDrop.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file) {
            fileDragDrop.querySelector('p').textContent = file.name;
        }
    });

    fileDragDrop.addEventListener('dragover', (e) => {
        e.preventDefault();
        fileDragDrop.classList.add('drag-over');
    });

    fileDragDrop.addEventListener('dragleave', () => {
        fileDragDrop.classList.remove('drag-over');
    });

    fileDragDrop.addEventListener('drop', (e) => {
        e.preventDefault();
        fileDragDrop.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file) {
            fileInput.files = e.dataTransfer.files;
            fileDragDrop.querySelector('p').textContent = file.name;
        }
    });

    // Preview modal functionality
    const previewModal = document.getElementById('preview-modal');
    const closeModal = document.querySelector('.close');
    const editButton = document.getElementById('edit-button');
    const confirmButton = document.getElementById('confirm-button');

    document.getElementById('card-details-form').addEventListener('submit', function(e) {
        e.preventDefault();
        generatePreview();
        previewModal.style.display = 'block';
    });

    closeModal.addEventListener('click', () => {
        previewModal.style.display = 'none';
    });

    editButton.addEventListener('click', () => {
        previewModal.style.display = 'none';
    });

    confirmButton.addEventListener('click', () => {
        document.getElementById('card-details-form').submit();
    });

    function generatePreview() {
        const canvas = document.getElementById('card-canvas');
        const ctx = canvas.getContext('2d');

        // Example of preview content; customize this as needed
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#4a90e2';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = '#fff';
        ctx.font = 'bold 20px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(document.getElementById('company-name').value, canvas.width / 2, canvas.height / 2);
    }
});
const nodemailer = require('nodemailer');

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

// Example usage
app.post('/submit', (req, res) => {
    // Process the form submission and save data

    // Send confirmation email
    const { contactEmail } = req.body;
    const subject = 'Order Confirmation from ZEbo Digital Card';
    const text = `Thank you for your order. We have received your request and will process it shortly.`;
    
    sendConfirmationEmail(contactEmail, subject, text);

    res.send('Order submitted successfully.');
});


document.getElementById('card-details-form').addEventListener('submit', function(e) {
    e.preventDefault();
    generatePreview();
    previewModal.style.display = 'block';

    // Show a success message
    alert('Your order has been submitted successfully. A confirmation email will be sent shortly.');
});

// Initialize canvas
const canvas = document.getElementById('card-canvas');
const ctx = canvas.getContext('2d');

// Function to update preview
function updatePreview() {
    const text = document.getElementById('card-text').value;
    const logo = document.getElementById('card-logo').files[0];
    const theme = document.getElementById('card-theme').value;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Set theme color (for simplicity)
    ctx.fillStyle = theme;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Draw text
    ctx.fillStyle = '#000';
    ctx.font = '20px Arial';
    ctx.fillText(text, 10, 50);

    // Draw logo if provided
    if (logo) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                ctx.drawImage(img, 10, 60, 100, 100); // Adjust position and size as needed
            }
            img.src = event.target.result;
        }
        reader.readAsDataURL(logo);
    }
}

// Event listener for update button
document.getElementById('update-preview').addEventListener('click', updatePreview);
document.getElementById('feedback-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const feedback = document.getElementById('feedback').value;
    
    fetch('/submit-feedback', { // Update with your backend endpoint
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ feedback }),
    })
    .then(response => response.json())
    .then(data => {
        alert('Feedback submitted successfully!');
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
document.getElementById('apply-template').addEventListener('click', function() {
    const selectedTemplate = document.getElementById('template-select').value;
    // Logic to apply the selected template
    console.log('Template selected:', selectedTemplate);
});
document.getElementById('feedback-form').addEventListener('submit', function(event) {
    const feedback = document.getElementById('feedback').value;
    if (!feedback.trim()) {
        alert('Please provide your feedback.');
        event.preventDefault();
    }
});
