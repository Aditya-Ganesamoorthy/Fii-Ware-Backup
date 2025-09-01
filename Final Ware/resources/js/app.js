import './bootstrap';
import 'select2';
import 'select2/dist/css/select2.css';
import 'toastr/build/toastr.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll('.menu-btn');
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            button.classList.toggle('active');
            const submenu = button.nextElementSibling;
            if (submenu) submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
        });
    });
});
