(function () {
    var navbar = document.querySelector('.navbar');

    var onScroll = function () {
        var top = document.querySelector('body').getBoundingClientRect().top;
        if (top === 0) {
            navbar.style.height = '40px';
            navbar.style.opacity = '0';
        } else if (top < 0) {
            navbar.style.height = '90px';
            navbar.style.opacity = '1';
        }
    };

    window.addEventListener('scroll', onScroll);

})();