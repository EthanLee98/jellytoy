$(document).ready(function() {
    const $allHoverImages = $('.hover-container div img');
    const $imgContainer = $('.img-container');

    // Set the initial active image
    $allHoverImages.first().parent().addClass('active');

    // Change the main image on mouseover
    $allHoverImages.each(function() {
        $(this).on('mouseover', () => {
            $imgContainer.find('img').attr('src', $(this).attr('src'));
            resetActiveImg();
            $(this).parent().addClass('active');
        });
    });

    // Reset active image classes
    function resetActiveImg() {
        $allHoverImages.each(function() {
            $(this).parent().removeClass('active');
        });
    }

    // Handle Add to Cart button click
    $('.add-cart-btn').click(function() {
        event.preventDefault();
        const productId = $(this).data('product-id');
        const quantity = $('#quantity').val(); // Get the quantity input value

        $.ajax({
            url: 'detail.php',
            type: 'POST',
            data: { action: 'add_to_cart', id: productId, quantity: quantity },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.error) {
                    alert('In order to purchase or add to cart, you are required to login.');
                    window.location.href = 'login.php'; // Redirect to login page
                } else {
                    alert(result.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error:", textStatus, errorThrown);
                alert('An error occurred while adding to cart.');
            }
        });
    });

    // Handle Buy Now button click
    $('.buy-now-btn').click(function() {
        event.preventDefault();
        const productId = $(this).data('product-id'); // Get product ID from data attribute
        const quantity = $('#quantity').val(); // Get the quantity input value

        $.ajax({
            url: 'detail.php',
            type: 'POST',
            data: { action: 'buy_now', id: productId, quantity: quantity },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.error) {
                    alert('In order to purchase or add to cart, you are required to login.');
                    window.location.href = 'login.php'; // Redirect to login page
                } else {
                    alert(result.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error:", textStatus, errorThrown);
                alert('An error occurred while completing the purchase.');
            }
        });
    });
});
