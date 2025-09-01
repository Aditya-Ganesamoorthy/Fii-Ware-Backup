$(document).ready(function() {
    // Menu dropdown functionality
    $('#users-menu').click(function(e) {
        e.preventDefault();
        $('#users-submenu').toggleClass('active');
        $(this).find('.dropdown-icon').toggleClass('fa-chevron-down fa-chevron-up');
    });
    
    $('#products-menu').click(function(e) {
        e.preventDefault();
        $('#products-submenu').toggleClass('active');
        $(this).find('.dropdown-icon').toggleClass('fa-chevron-down fa-chevron-up');
    });

    $('#transport-menu').click(function(e) {
        e.preventDefault();
        $('#transport-submenu').toggleClass('active');
        $(this).find('.dropdown-icon').toggleClass('fa-chevron-down fa-chevron-up');
    });
    
    // Active menu item highlighting
    $('.menu-link').click(function() {
        $('.menu-link').removeClass('active');
        $(this).addClass('active');
    });
    
    // Submenu item click
    $('.submenu li a').click(function() {
        $('.submenu li a').removeClass('active');
        $(this).addClass('active');
    });
    
    // Touch effect for buttons
    $('.btn').on('touchstart', function() {
        $(this).addClass('btn-touch');
    });
    
    $('.btn').on('touchend', function() {
        $(this).removeClass('btn-touch');
    });
    
    // Menu link touch effect
    $('.menu-link').on('touchstart', function() {
        $(this).addClass('menu-touch');
    });
    
    $('.menu-link').on('touchend', function() {
        $(this).removeClass('menu-touch');
    });
    
    
    // Load content via AJAX (for demonstration)
    $('.menu-link:not([id$="-menu"])').click(function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        
        // Show loading animation
        $('.content-area').html('<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
        
        // Simulate AJAX load
        setTimeout(() => {
            if (url.includes('vendors')) {
                window.location.href = url;
            } else {
                $('.content-area').html('<div class="card"><div class="card-header"><h3 class="card-title">' + $(this).find('span').text() + '</h3></div><div class="card-body"><p>This page is under construction.</p></div></div>');
            }
        }, 500);
    });
    
    document.addEventListener('click', function() {
        var dropdown = document.getElementById('profile-dropdown-content');
        if (dropdown) dropdown.classList.remove('active');
    });
});