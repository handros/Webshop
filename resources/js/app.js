import './bootstrap';
import Flickity from 'flickity';

// Initialize galery
document.addEventListener('DOMContentLoaded', function () {
    var elem = document.querySelector('.js-flickity');
    if (elem) {
        new Flickity(elem, {
            wrapAround: true,
            autoPlay: 3000,
        });
    }
});

// Set receiver name based on reply button
document.querySelectorAll('.reply-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        const receiverId = this.dataset.receiver;
        const receiverName = this.dataset.receiverName;

        document.querySelector('#receiver_id').value = receiverId;
        document.querySelector('#receiverLabel').textContent = ` Üzenet: ${receiverName}`;

        const messageForm = document.querySelector('#messageForm');
        messageForm.style.display = 'block';
        messageForm.scrollIntoView({ behavior: 'smooth' });
    });
});

// Display cover image
const coverImageInput = document.querySelector('input#image');
const coverPreviewContainer = document.querySelector('#cover_preview');
const coverPreviewImage = document.querySelector('img#cover_preview_image');

coverImageInput.onchange = event => {
    const [file] = coverImageInput.files;
    if (file) {
        coverPreviewContainer.classList.remove('d-none');
        coverPreviewImage.src = URL.createObjectURL(file);
    } else {
        coverPreviewContainer.classList.add('d-none');
    }
}
