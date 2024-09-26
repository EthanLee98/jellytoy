$(document).ready(function() {
    let timeout; // Variable to track the hover timeout

    // Show dropdown when hovering over the profile or the dropdown menu itself
    $('.header-user-profile, .header-dropdown-menu').on('mouseenter', function() {
        clearTimeout(timeout); // Cancel any hiding timeout
        $(this).closest('.header-user-profile').find('.header-dropdown-menu').stop(true, true).slideDown(200);
    });

    // Hide dropdown with delay when mouse leaves both profile and dropdown
    $('.header-user-profile, .header-dropdown-menu').on('mouseleave', function() {
        timeout = setTimeout(() => {
            $(this).closest('.header-user-profile').find('.header-dropdown-menu').stop(true, true).slideUp(200);
        }, 500); // Delay hiding the menu by 500ms
    });
});
