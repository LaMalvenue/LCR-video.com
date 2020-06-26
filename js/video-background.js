var videoBackgroundArea = document.querySelector('#video-background-area');
const videoWidth = 1920;
var windowWidth = window.innerWidth;
var leftPosition = (windowWidth - videoWidth)/2 + ' px';

function setVideoBackground() {
    var video = document.createElement('video');
    video.id = "video-background";
    video.setAttribute('autoplay', '');
    video.setAttribute('playsinline', '');
    video.setAttribute('muted', '');
    video.setAttribute('loop', '');
    var sourceVideo = document.createElement('source');
    sourceVideo.type ="video/mp4";
    sourceVideo.src = "https://lcr-video.com/wp-content/uploads/2018/10/mix.mp4";
    sourceVideo.alt = "Video de fond";
    video.muted = true;
    video.play();
    video.appendChild(sourceVideo);

    videoBackgroundArea.setAttribute('style', 'left:'+leftPosition+';');
    videoBackgroundArea.appendChild(video);
}

function isEmpty(tag) {
    return document.querySelector(tag).innerHTML.trim() === "";
}
function onResize() {
    if("matchMedia" in window) {
        if(window.matchMedia("(min-width:768px)").matches)
        {
            videoBackgroundArea.style.display="block";
            if(isEmpty("#video-background-area"))
            {
                setVideoBackground();
            }
        }
        else if(window.matchMedia("(max-width:767px").matches)
        {
            videoBackgroundArea.style.display="none";
        }
    }
}

if(window.matchMedia("(min-width:768px)").matches)
{
    videoBackgroundArea.setAttribute('style', 'left:'+leftPosition+';');
    setVideoBackground();
}

window.addEventListener('resize', onResize, false);