document.addEventListener("DOMContentLoaded", function () {
    console.log("E-commerce website is ready!");

    // Add functionality for the "Add to Cart" buttons
    const buttons = document.querySelectorAll(".product-item button");

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            alert("Product added to cart!");
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const carouselSlide = document.querySelector('.carousel-slide');
    const carouselImages = document.querySelectorAll('.carousel-slide img');

    // Buttons
    const prevBtn = document.querySelector('#prevBtn');
    const nextBtn = document.querySelector('#nextBtn');

    // Counter
    let counter = 1;
    const size = carouselImages[0].clientWidth;

    carouselSlide.style.transform = 'translateX(' + (-size * counter) + 'px)';

    // Button Listeners
    nextBtn.addEventListener('click', () => {
        if (counter >= carouselImages.length - 1) return; // Prevent overflow
        carouselSlide.style.transition = "transform 0.4s ease-in-out";
        counter++;
        carouselSlide.style.transform = 'translateX(' + (-size * counter) + 'px)';
    });

    prevBtn.addEventListener('click', () => {
        if (counter <= 0) return;
        carouselSlide.style.transition = "transform 0.4s ease-in-out";
        counter--;
        carouselSlide.style.transform = 'translateX(' + (-size * counter) + 'px)';
    });

    // Loop the carousel back to the start/end
    carouselSlide.addEventListener('transitionend', () => {
        if (carouselImages[counter].alt === 'Slide 1') {
            carouselSlide.style.transition = "none";
            counter = carouselImages.length - 2;
            carouselSlide.style.transform = 'translateX(' + (-size * counter) + 'px)';
        }
        if (carouselImages[counter].alt === 'Slide 4') {
            carouselSlide.style.transition = "none";
            counter = 1;
            carouselSlide.style.transform = 'translateX(' + (-size * counter) + 'px)';
        }
    });

    // Auto slide every 5 seconds
    function autoSlide() {
        setInterval(() => {
            if (counter >= carouselImages.length - 1) return;
            carouselSlide.style.transition = "transform 0.4s ease-in-out";
            counter++;
            carouselSlide.style.transform = 'translateX(' + (-size * counter) + 'px)';
        }, 5000);
    }

    autoSlide();
});


document.addEventListener("DOMContentLoaded", function () {
    // Add functionality for the "Add to Cart" buttons
    const buttons = document.querySelectorAll(".product-item button");

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            alert("Product added to cart!");
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const addToCartButton = document.querySelector(".product-form button");
    
    addToCartButton.addEventListener("click", function (event) {
        event.preventDefault();
        
        const quantity = document.getElementById("quantity").value;
        
        if (quantity > 0) {
            alert(`Added ${quantity} items to your cart.`);
        } else {
            alert("Please select a valid quantity.");
        }
    });
});


