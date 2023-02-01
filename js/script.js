//Add class to body on load
const bodyEl = document.getElementsByTagName("body")[0];
function siteLoaded() {
    bodyEl.classList.add('loaded');
}
window.addEventListener('load', siteLoaded);