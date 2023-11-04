const BACK_BTNS = document.querySelectorAll('.app-back');

BACK_BTNS.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        window.history.back();
    });
});
