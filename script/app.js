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


// confirmation 
$('[data-confirm]').on('click', e => {
    const text = e.target.dataset.confirm || 'Are you sure?';
    if (!confirm(text)) {
        e.preventDefault();
        e.stopImmediatePropagation();
    }
});
//confirmation update selected user
$('[data-confirm-update]').on('click', e => {
    const text = e.target.dataset.confirm || 'Are you sure you want to update the status of the selected users?';
    if (!confirm(text)) {
        e.preventDefault();
        e.stopImmediatePropagation();
    }
});

// Set or get temporary session variable

function temp($key, $value = null) {
    if ($value !== null) {
        $_SESSION["temp_$key"] = $value;
    } else {
        $value = $_SESSION["temp_$key"] ?? null;
        unset($_SESSION["temp_$key"]);
        return $value;
    }
}