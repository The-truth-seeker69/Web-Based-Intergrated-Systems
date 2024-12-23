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

$(() => {
    // Initiate POST request
    $('[data-post]').on('click', function (e) {
        e.preventDefault();

        const confirmMessage = $(this).data('confirm'); // Get confirmation message
        if (confirmMessage) {
            const isConfirmed = confirm(confirmMessage); // Show confirmation dialog
            if (!isConfirmed) {
                return; // Exit if the user cancels
            }
        }

        const url = $(this).data('post'); // Get the URL from data-post
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        console.log('Button clicked! URL:', url); // Debugging output
        location = url || location;
    });

   // Confirmation message
   $('[data-confirmation]').on('click', e => {
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



// photo preview
$('label.upload input[type=file]').on('change', e => {
    const f = e.target.files[0];
    const img = $(e.target).siblings('img')[0];

    if (!img) return;

    img.dataset.src ??= img.src;

    if (f?.type.startsWith('image/')) {
        img.src = URL.createObjectURL(f);
    }
    else {
        img.src = img.dataset.src;
        e.target.value = '';
    }
});
});