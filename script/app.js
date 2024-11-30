$(document).ready(function () {
    let slideIndex = 0;
    const slides = $(".slides");
    const totalSlides = slides.length;
    console.log(totalSlides)

    function showSlide(index) {
        // first hide all the slides
        slides.hide();
        $(slides[index]).show();
    }

    function changeSlide(n) {

        //modulus ensure it is within loop so that it dont exceed the total slides number
        slideIndex = (slideIndex + n + totalSlides) % totalSlides;

        showSlide(slideIndex);
    }

    // Show the first slide initially
    showSlide(slideIndex);

    // Next and Previous button click handlers
    $(".next").click(function () {
        changeSlide(1);

    });

    $(".prev").click(function () {
        changeSlide(-1);
    });
});
