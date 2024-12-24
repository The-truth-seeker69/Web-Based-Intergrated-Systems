$(()=>{
    $('label.profilepic input[type=file]').on('change', e => {
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

$(document).ready(function () {
    $('input[name="otpInput"]').focus();
});