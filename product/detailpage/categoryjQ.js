$(document).ready(function() {
    const $relatedBtn = $('#relatedBtn');
    const $trendingBtn = $('#trendingBtn');
    const $relatedSection = $('#related-section');
    const $trendingSection = $('#trending-section');
    const $relatedCards = $relatedSection.find('.product-card');
    const $trendingCards = $trendingSection.find('.product-card');

    const limit = 4; // Number of products to display at a time
    let currentRelatedIndex = 0;
    let currentTrendingIndex = 0;

    // Function to update the display of related products
    function updateRelatedProductDisplay() {
        $relatedCards.hide();
        $relatedCards.slice(currentRelatedIndex, currentRelatedIndex + limit).show();
        $('.prev-btn').prop('disabled', currentRelatedIndex === 0);
        $('.next-btn').prop('disabled', currentRelatedIndex + limit >= $relatedCards.length);
    }

    // Function to update the display of trending products
    function updateTrendingProductDisplay() {
        $trendingCards.hide();
        $trendingCards.slice(currentTrendingIndex, currentTrendingIndex + limit).show();
        $('.prev-btn').prop('disabled', currentTrendingIndex === 0);
        $('.next-btn').prop('disabled', currentTrendingIndex + limit >= $trendingCards.length);
    }

    // Click event for "Related Product" button
    $relatedBtn.on('click', function() {
        $relatedBtn.addClass('active');
        $trendingBtn.removeClass('active');
        $relatedSection.addClass('active');
        $trendingSection.removeClass('active');
        currentRelatedIndex = 0;
        updateRelatedProductDisplay();
    });

    // Click event for "Trending" button
    $trendingBtn.on('click', function() {
        $trendingBtn.addClass('active');
        $relatedBtn.removeClass('active');
        $trendingSection.addClass('active');
        $relatedSection.removeClass('active');
        currentTrendingIndex = 0;
        updateTrendingProductDisplay();
    });

    // Handle click event for next button
    $('.next-btn').click(function() {
        if ($relatedSection.hasClass('active')) {
            if (currentRelatedIndex + limit < $relatedCards.length) {
                currentRelatedIndex += limit;
                updateRelatedProductDisplay();
            }
        } else if ($trendingSection.hasClass('active')) {
            if (currentTrendingIndex + limit < $trendingCards.length) {
                currentTrendingIndex += limit;
                updateTrendingProductDisplay();
            }
        }
    });

    // Handle click event for previous button
    $('.prev-btn').click(function() {
        if ($relatedSection.hasClass('active')) {
            if (currentRelatedIndex - limit >= 0) {
                currentRelatedIndex -= limit;
                updateRelatedProductDisplay();
            }
        } else if ($trendingSection.hasClass('active')) {
            if (currentTrendingIndex - limit >= 0) {
                currentTrendingIndex -= limit;
                updateTrendingProductDisplay();
            }
        }
    });

    // Add to Cart functionality
    $(document).on('click', '.add-to-cart', function() {
        const productId = $(this).data('product-id');
        const quantity = 1; // You can change this to allow user input if needed

        $.ajax({
            type: 'POST',
            url: 'path/to/your/cart-handler.php', // Adjust this URL as needed
            data: {
                action: 'add_to_cart',
                id: productId,
                quantity: quantity
            },
            success: function(response) {
                const data = JSON.parse(response);
                alert(data.message || data.error);
            },
            error: function() {
                alert('An error occurred while adding the product to the cart.');
            }
        });
    });

    // Initial load of Related Products when the page loads
    updateRelatedProductDisplay();
});
