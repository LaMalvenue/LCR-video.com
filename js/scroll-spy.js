var scrollY = 0;
var distance = 40;
var speed = 15;

function autoScrollTo(el){
	var currentY = window.pageYOffset;
	var targetY = document.querySelector(el).offsetTop;
	var bodyHeight = document.body.offsetHeight;
	var yPos = currentY + window.innerHeight;
	var animator = setTimeout('autoScrollTo(\''+el+'\')', speed);

	if(currentY > targetY) {
		scrollY = currentY-distance;
		window.scroll(0, scrollY);
	} else if(yPos > bodyHeight) {
		clearTimeout(animator);
	} else {
		if(currentY < targetY-distance) {
			scrollY = currentY+distance;
			window.scroll(0, scrollY);
		} else {
			clearTimeout(animator);
		}
	}
}

var navLinks = document.querySelectorAll('.nav-link');
for (var i=0; i<navLinks.length;i++){
	var navLink = navLinks[i];
	navLink.addEventListener('click', autoScrollTo(document.querySelector(navLink.hash)));
}