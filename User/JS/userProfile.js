$(() => {
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

    // Autofocus
    $('form :input:not(button):first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();

    // Confirmation message
    $('[data-confirm]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

    // Initiate GET request
    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
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

    // Reset form
    $('[type=reset]').on('click', e => {
        e.preventDefault();
        location = location;
    });

    // Auto uppercase
    $('[data-upper]').on('input', e => {
        const a = e.target.selectionStart;
        const b = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(a, b);
    });

    // Handle file input change and photo preview
    $('label.upload input[type=file]').on('change', e => {
        const f = e.target.files[0];
        const img = $(e.target).siblings('img')[0]; // Profile image element
        if (!img) return; // If no image element, return early

        // Store the original/default image source if not already stored
        img.dataset.src ??= img.src;

        if (f?.type.startsWith('image/')) {
            // Set the new image URL as preview
            img.src = URL.createObjectURL(f);
        } else {
            // Reset to the default image if the file is not valid
            img.src = img.dataset.src;
            e.target.value = ''; // Clear the file input value
        }
    });

});
