import './bootstrap';
import Flickity from 'flickity';

// Initialize galery
document.addEventListener('DOMContentLoaded', function () {
    var elem = document.querySelector('.js-flickity');
    if (elem) {
        new Flickity(elem, {
            wrapAround: true,
            autoPlay: 3000, // Automatikus lejátszás 3 másodpercenként
        });
    }
});
