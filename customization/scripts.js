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
document.addEventListener('DOMContentLoaded', function() {
    // File Upload for Header Image
    const headerImageInput = document.getElementById('header-image');
    const headerImageDragDrop = document.querySelector('.file-drag-drop');
    headerImageInput.addEventListener('change', function() {
        const fileName = this.files[0].name;
        headerImageDragDrop.innerHTML = `<p>${fileName}</p>`;
    });

    // Webpage Preview Modal
    const webpagePreviewModal = document.getElementById('webpage-preview-modal');
    const closeWebpageModal = document.querySelector('#webpage-preview-modal .close');
    const editWebpageButton = document.getElementById('edit-webpage-button');
      // Continue from the previous code
      closeWebpageModal.addEventListener('click', function() {
        webpagePreviewModal.style.display = 'none';
    });

    editWebpageButton.addEventListener('click', function() {
        webpagePreviewModal.style.display = 'none';
    });

    confirmWebpageButton.addEventListener('click', function() {
        alert('Your webpage design has been confirmed!');
        webpagePreviewModal.style.display = 'none';
    });

    function updateWebpagePreview() {
        const pageTitle = document.getElementById('page-title').value;
        const headerImage = document.getElementById('header-image').files[0];
        const backgroundColor = document.getElementById('background-color').value;
        const fontFamily = document.getElementById('font-family').value;
        const fontSize = document.getElementById('font-size').value;
        const layout = document.getElementById('layout').value;
        const content = document.getElementById('content').value;
        const socialMediaLinks = document.getElementById('social-media-links').value;

        const previewContainer = document.getElementById('webpage-preview');

        // Clear previous content
        previewContainer.innerHTML = '';

        // Create header
        const header = document.createElement('header');
        header.style.backgroundColor = '#f0f0f0';
        header.style.padding = '10px';
        header.style.textAlign = 'center';

        const title = document.createElement('h1');
        title.textContent = pageTitle;
        title.style.fontFamily = fontFamily;
        title.style.fontSize = `${fontSize}px`;
        header.appendChild(title);

        if (headerImage) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(headerImage);
            img.style.width = '100%';
            img.style.height = 'auto';
            header.appendChild(img);
        }

        previewContainer.appendChild(header);

        // Apply background color
        previewContainer.style.backgroundColor = backgroundColor;

        // Apply layout
        if (layout === 'single-column') {
            previewContainer.style.display = 'block';
        } else if (layout === 'two-column') {
            previewContainer.style.display = 'flex';
            previewContainer.style.flexDirection = 'row';
            previewContainer.style.justifyContent = 'space-between';
        } else if (layout === 'three-column') {
            previewContainer.style.display = 'flex';
            previewContainer.style.flexDirection = 'row';
            previewContainer.style.justifyContent = 'space-between';
        }

        // Add content
        const contentElement = document.createElement('p');
        contentElement.textContent = content;
        contentElement.style.fontFamily = fontFamily;
        contentElement.style.fontSize = `${fontSize}px`;
        previewContainer.appendChild(contentElement);

        // Add social media links
        if (socialMediaLinks) {
            const socialMediaElement = document.createElement('div');
            socialMediaElement.className = 'social-media-links';
            const links = socialMediaLinks.split(',');
            links.forEach(link => {
                const anchor = document.createElement('a');
                anchor.href = link.trim();
                anchor.textContent = link.trim();
                anchor.style.display = 'block';
                socialMediaElement.appendChild(anchor);
            });
            previewContainer.appendChild(socialMediaElement);
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('webpage-customization-form');
    const previewModal = document.getElementById('webpage-preview-modal');
    const previewContainer = document.getElementById('webpage-preview');
    const closePreviewButton = document.querySelector('#webpage-preview-modal .close');
    const updatePreviewButton = document.getElementById('update-preview');
    const confirmWebpageButton = document.getElementById('confirm-webpage-button');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        updateWebpagePreview();
        previewModal.style.display = 'block';
    });
    
    closePreviewButton.addEventListener('click', function() {
        previewModal.style.display = 'none';
    });
    
    updatePreviewButton.addEventListener('click', function() {
        updateWebpagePreview();
    });

    confirmWebpageButton.addEventListener('click', function() {
        alert('Your webpage design has been saved!');
        previewModal.style.display = 'none';
        // Here you can add logic to save the webpage data to a server or local storage
    });

    function updateWebpagePreview() {
        const pageTitle = document.getElementById('page-title').value;
        const headerImage = document.getElementById('header-image').files[0];
        const backgroundColor = document.getElementById('background-color').value;
        const fontFamily = document.getElementById('font-family').value;
        const fontSize = document.getElementById('font-size').value;
        const layout = document.getElementById('layout').value;
        const content = document.getElementById('content').value;
        const socialMediaLinks = document.getElementById('social-media-links').value;

        previewContainer.innerHTML = '';

        const header = document.createElement('header');
        header.style.backgroundColor = '#f0f0f0';
        header.style.padding = '10px';
        header.style.textAlign = 'center';

        const title = document.createElement('h1');
        title.textContent = pageTitle;
        title.style.fontFamily = fontFamily;
        title.style.fontSize = `${fontSize}px`;
        header.appendChild(title);

        if (headerImage) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(headerImage);
            img.style.width = '100%';
            img.style.height = 'auto';
            header.appendChild(img);
        }

        previewContainer.appendChild(header);

        previewContainer.style.backgroundColor = backgroundColor;

        if (layout === 'single-column') {
            previewContainer.style.display = 'block';
        } else if (layout === 'two-column') {
            previewContainer.style.display = 'flex';
            previewContainer.style.flexDirection = 'row';
            previewContainer.style.justifyContent = 'space-between';
        } else if (layout === 'three-column') {
            previewContainer.style.display = 'flex';
            previewContainer.style.flexDirection = 'row';
            previewContainer.style.justifyContent = 'space-between';
        }

        const contentElement = document.createElement('p');
        contentElement.textContent = content;
        contentElement.style.fontFamily = fontFamily;
        contentElement.style.fontSize = `${fontSize}px`;
        previewContainer.appendChild(contentElement);

        if (socialMediaLinks) {
            const socialMediaElement = document.createElement('div');
            socialMediaElement.className = 'social-media-links';
            const links = socialMediaLinks.split(',');
            links.forEach(link => {
                const anchor = document.createElement('a');
                anchor.href = link.trim();
                anchor.textContent = link.trim();
                anchor.style.display = 'block';
                socialMediaElement.appendChild(anchor);
            });
            previewContainer.appendChild(socialMediaElement);
        }
    }
});

});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('webpage-customization-form');
    const previewModal = document.getElementById('webpage-preview-modal');
    const previewContainer = document.getElementById('webpage-preview');
    const closePreviewButton = document.querySelector('#webpage-preview-modal .close');
    const updatePreviewButton = document.getElementById('update-preview');
    const confirmWebpageButton = document.getElementById('confirm-webpage-button');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        updateWebpagePreview();
        previewModal.style.display = 'block';
    });
    
    closePreviewButton.addEventListener('click', function() {
        previewModal.style.display = 'none';
    });
    
    updatePreviewButton.addEventListener('click', function() {
        updateWebpagePreview();
    });

    confirmWebpageButton.addEventListener('click', function() {
        alert('Your webpage design has been saved!');
        previewModal.style.display = 'none';
        // Here you can add logic to save the webpage data to a server or local storage
    });

    function updateWebpagePreview() {
        const pageTitle = document.getElementById('page-title').value;
        const headerImage = document.getElementById('header-image').files[0];
        const backgroundColor = document.getElementById('background-color').value;
        const fontFamily = document.getElementById('font-family').value;
        const fontSize = document.getElementById('font-size').value;
        const layout = document.getElementById('layout').value;
        const content = document.getElementById('content').value;
        const socialMediaLinks = document.getElementById('social-media-links').value;

        previewContainer.innerHTML = '';

        const header = document.createElement('header');
        header.style.backgroundColor = '#f0f0f0';
        header.style.padding = '10px';
        header.style.textAlign = 'center';

        const title = document.createElement('h1');
        title.textContent = pageTitle;
        title.style.fontFamily = fontFamily;
        title.style.fontSize = `${fontSize}px`;
        header.appendChild(title);

        if (headerImage) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(headerImage);
            img.style.width = '100%';
            img.style.height = 'auto';
            header.appendChild(img);
        }

        previewContainer.appendChild(header);

        previewContainer.style.backgroundColor = backgroundColor;

        if (layout === 'single-column') {
            previewContainer.style.display = 'block';
        } else if (layout === 'two-column') {
            previewContainer.style.display = 'flex';
            previewContainer.style.flexDirection = 'row';
            previewContainer.style.justifyContent = 'space-between';
        } else if (layout === 'three-column') {
            previewContainer.style.display = 'flex';
            previewContainer.style.flexDirection = 'row';
            previewContainer.style.justifyContent = 'space-between';
        }

        const contentElement = document.createElement('p');
        contentElement.textContent = content;
        contentElement.style.fontFamily = fontFamily;
        contentElement.style.fontSize = `${fontSize}px`;
        previewContainer.appendChild(contentElement);

        if (socialMediaLinks) {
            const socialMediaElement = document.createElement('div');
            socialMediaElement.className = 'social-media-links';
            const links = socialMediaLinks.split(',');
            links.forEach(link => {
                const anchor = document.createElement('a');
                anchor.href = link.trim();
                anchor.textContent = link.trim();
                anchor.style.display = 'block';
                socialMediaElement.appendChild(anchor);
            });
            previewContainer.appendChild(socialMediaElement);
        }
    }
});
// scripts.js

document.addEventListener('DOMContentLoaded', function() {
    const templates = document.querySelectorAll('.template-btn');
    const sectionsContainer = document.getElementById('sections-container');
    const editControls = document.getElementById('edit-controls');
    const editModal = document.getElementById('edit-modal');
    const closeModal = document.querySelector('.modal .close');
    let selectedSection = null;

    // Function to create a new section based on the template type
    function createSection(type) {
        const section = document.createElement('div');
        section.className = 'section';
        section.dataset.type = type;
        let content = '';

        switch (type) {
            case 'text':
                content = `<h3>New Text Section</h3><p>This is a text section. You can edit its content.</p>`;
                break;
            case 'image':
                content = `<h3>New Image Section</h3><img src="https://via.placeholder.com/600x300" alt="Image Section"><p>Add your image URL in the edit modal.</p>`;
                break;
            case 'video':
                content = `<h3>New Video Section</h3><video controls src="https://www.example.com/sample.mp4"></video><p>Add your video URL in the edit modal.</p>`;
                break;
            case 'gallery':
                content = `<h3>New Gallery Section</h3><div class="gallery"><img src="https://via.placeholder.com/150" alt="Gallery Image"><img src="https://via.placeholder.com/150" alt="Gallery Image"><img src="https://via.placeholder.com/150" alt="Gallery Image"></div>`;
                break;
            case 'form':
                content = `<h3>New Form Section</h3><form><input type="text" placeholder="Your Name"><input type="email" placeholder="Your Email"><button type="submit">Submit</button></form>`;
                break;
        }

        section.innerHTML = content + '<div class="edit-controls"><button class="edit-btn">Edit</button><button class="delete-btn">Delete</button></div>';
        sectionsContainer.appendChild(section);

        // Add event listeners to the new section buttons
        section.querySelector('.edit-btn').addEventListener('click', function() {
            selectSection(section);
        });
        section.querySelector('.delete-btn').addEventListener('click', function() {
            section.remove();
            editControls.style.display = 'none';
        });
    }

    // Function to handle section selection
    function selectSection(section) {
        selectedSection = section;
        document.getElementById('section-title').value = section.querySelector('h3') ? section.querySelector('h3').textContent : '';
        document.getElementById('section-content').value = section.querySelector('p') ? section.querySelector('p').textContent : '';
        document.getElementById('section-image').value = section.querySelector('img') ? section.querySelector('img').src : '';
        document.getElementById('section-video').value = section.querySelector('video') ? section.querySelector('video').src : '';
        editControls.style.display = 'block';
        editModal.style.display = 'block';
    }

    // Event listener for template buttons
    templates.forEach(template => {
        template.addEventListener('click', function() {
            const type = this.dataset.template;
            createSection(type);
        });
    });

    // Event listener for save changes button in modal
    document.getElementById('edit-form').addEventListener('submit', function(e) {
        e.preventDefault();

        if (selectedSection) {
            const title = document.getElementById('section-title').value;
            const content = document.getElementById('section-content').value;
            const imageUrl = document.getElementById('section-image').value;
            const videoUrl = document.getElementById('section-video').value;

            const titleElement = selectedSection.querySelector('h3');
            if (titleElement) titleElement.textContent = title || '';

            const contentElement = selectedSection.querySelector('p');
            if (contentElement) contentElement.textContent = content || '';

            const imgElement = selectedSection.querySelector('img');
            if (imgElement) imgElement.src = imageUrl || '';

            const videoElement = selectedSection.querySelector('video');
            if (videoElement) videoElement.src = videoUrl || '';

            editModal.style.display = 'none';
            selectedSection = null;
        }
    });

    // Close modal event listener
    closeModal.addEventListener('click', function() {
        editModal.style.display = 'none';
    });
});

