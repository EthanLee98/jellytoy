// ratingJQ.js

$(document).ready(function() {
    let currentIndex = 0;
    const items = $('.review-item');
    const totalItems = items.length;

    // Disable buttons if no reviews are found
    if (totalItems === 0) {
        $('.prev, .next').prop('disabled', true); // Disable both buttons
        return; // Exit early
    }

    // Show the first item
    items.hide();
    $(items[currentIndex]).show();

    // Function to show the next item
    function showNext() {
        $(items[currentIndex]).fadeOut(300, function() {
            currentIndex = (currentIndex + 1) % totalItems;
            $(items[currentIndex]).fadeIn(300);
        });
    }

    // Set interval for automatic rotation (e.g., every 5 seconds)
    let autoRotate = setInterval(showNext, 5000);

    // Next button click event
    $('.next').click(function() {
        clearInterval(autoRotate); // Stop automatic rotation
        showNext(); // Show the next item
        autoRotate = setInterval(showNext, 5000); // Restart automatic rotation
    });

    // Previous button click event
    $('.prev').click(function() {
        clearInterval(autoRotate); // Stop automatic rotation
        $(items[currentIndex]).fadeOut(300, function() {
            currentIndex = (currentIndex - 1 + totalItems) % totalItems;
            $(items[currentIndex]).fadeIn(300);
        });
        autoRotate = setInterval(showNext, 5000); // Restart automatic rotation
    });
});
