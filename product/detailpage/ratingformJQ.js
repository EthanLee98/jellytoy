$(document).ready(function() {
    // Handle form submission
    $('#rating-form').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        // Check if a rating has been selected
        var currentRating = $('#rating').val();
        if (!currentRating) {
            alert("Please select a rating before submitting your review.");
            return; // Prevent form submission
        }

        // Confirmation alert
        if (confirm("Are you sure you want to submit your review?")) {
            // Perform an AJAX request to submit the form data
            $.ajax({
                url: '', // The URL where the form data will be submitted (same page in this case)
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Customize your success message here
                    var successMessage = "Thank you for your review! Your rating of " + currentRating + " stars has been submitted.";
                    alert(successMessage); // Display the customized success message
                    
                    // Refresh the page
                    location.reload(); // Refresh the page
                },
                error: function() {
                    alert('Error submitting the review. Please try again.');
                }
            });
        } else {
            // If the user cancels, do nothing (the form won't submit)
            return false;
        }
    });

    // Star rating hover effect
    $('.star').hover(
        function() {
            $(this).prevAll().addBack().addClass('hover'); // Highlight stars on hover
        },
        function() {
            $(this).prevAll().addBack().removeClass('hover'); // Remove hover effect
            if ($(this).hasClass('selected')) {
                $(this).prevAll().addBack().addClass('selected'); // Keep selected stars colored
            }
        }
    );

    // Star rating click event
    $('.star').click(function() {
        var ratingValue = $(this).data('value');
        var currentRating = $('#rating').val();

        if (currentRating == ratingValue) {
            // If the clicked star is already selected, unselect it
            $('#rating').val(''); // Clear the hidden input value
            $('.star').removeClass('selected'); // Clear all selections
        } else {
            // Set the new rating
            $('#rating').val(ratingValue); // Set the hidden input value
            $('.star').removeClass('selected'); // Clear previous selections
            $(this).prevAll().addBack().addClass('selected'); // Highlight selected stars
        }
    });
});
