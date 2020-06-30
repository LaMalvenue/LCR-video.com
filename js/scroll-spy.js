const ratio = .6;
const spies = document.querySelectorAll('[data-spy]');
let observer = null;

/**
 * @param {HTMLElement} element
 */
const activate = function (element) {
    const id = element.getAttribute('id');
    const anchor = document.querySelector('a.nav-link[href="#'+id+'"]');
    const menu = document.querySelector('.navbar-nav');
    menu.querySelectorAll('.activeSection')
        .forEach(node => node.classList.remove('activeSection'));
    anchor.classList.add('activeSection');
}
/**
* @param {IntersectionObserverEntry[]} entries
*/
const callback = function (entries) {
    entries.forEach(function (entry) {
        if (entry.intersectionRatio > 0) {
            activate(entry.target);
        }
    })
}
/**
 * @param {NodeListOf.<HTMLElement>} elements
 */
const observe = function (elements) {
    if (observer !== null){
        elements.forEach(element => observer.unobserve(element));
    }
    const y = Math.round(window.innerHeight * ratio);
    observer = new IntersectionObserver(callback, {
        rootMargin: '-'+(window.innerHeight - y - 1)+'px 0px -'+y+'px 0px'
    });
    spies.forEach(element => observer.observe(element));
}
/**
 * @param {Function} callback
 * @param {number} delay
 * @return {Function}
 */
function debounce(callback, delay){
    let timer;
    return function(){
        let args = arguments;
        let context = this;
        clearTimeout(timer);
        timer = setTimeout(function(){
            callback.apply(context, args);
        }, delay)
    }
}
if (spies.length > 0) {
    observe(spies);
    let windowH = window.innerHeight;
    window.addEventListener('resize', debounce(function () {
        if (window.innerHeight !== windowH) {
            observe(spies);
            windowH = window.innerHeight;
        }
    }, 500))
}
