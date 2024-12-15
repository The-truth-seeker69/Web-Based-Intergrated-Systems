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
    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        console.log('Button clicked! URL:', url); // Debugging output
        location = url || location;
    });


    // Initiate POST request
    $('[data-post]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.post;
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    // Confirmation message
    $('[data-confirm]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

    // Validate the password strength
    let password = document.getElementById("new_password");  // Input for new password
    let power = document.getElementById("power-point");  // Element that will display the password strength 
    let strengthText = document.getElementById("strength-text");  // Element to display "Weak", "Medium", or "Strong" text

    password.oninput = function () {
        let value = password.value;
        let point = 0;

        // Hide the text if the iput is empty
        if (value === "") {
            power.style.width = "0%";  // Reset strength bar to 0%
            strengthText.style.display = "none";
            return;
        }

        // show the strenth if the input is not empty
        strengthText.style.display = "inline";  // Make sure the strength text is visible

        // password validation
        let arrayTest = [
            /^.{8,}$/,     // Check if the password has at least 8 characters
            /[0-9]/,
            /[a-z]/,
            /[A-Z]/,
            /[\W_]/
        ];

        // based on the password meet condition and increse the point
        arrayTest.forEach((item) => {
            if (item.test(value)) {
                point += 1;
            }
        });

        // show the strength
        if (point === 1) {
            strengthText.textContent = "Weak";
            strengthText.style.color = "#D73F40";
        } else if (point === 3) {
            strengthText.textContent = "Medium";
            strengthText.style.color = "#F2B84F";
        } else if (point >= 4) {
            strengthText.textContent = "Strong";
            strengthText.style.color = "#3ba62f";
        }
    };




});
