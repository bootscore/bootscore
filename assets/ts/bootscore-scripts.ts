/*!
  * Bootscore JS
  *
  * @version 7.0.0
  */


// Search menu: focus the input directly in the tap, so mobile browsers (iOS) open the keyboard
window.addEventListener('click', (event) => {
  const toggle = (event.target as Element).closest<HTMLElement>('.search-toggler');
  if (!toggle || toggle.getAttribute('aria-expanded') !== 'true') return;

  const input = toggle.parentElement?.querySelector<HTMLInputElement>('.search-menu input:not([type="hidden"])');
  input?.focus({ preventScroll: true });
});

// Fallback for menus opened without a click (e.g. keyboard)
document.addEventListener('shown.bs.menu', (event) => {
  const toggle = event.target as HTMLElement;
  if (!toggle.classList.contains('search-toggler')) return;

  const input = toggle.parentElement?.querySelector<HTMLInputElement>('.search-menu input:not([type="hidden"])');
  input?.focus();
});


// Scroll to top Button
// Refactor to scroll-driven CSS?
var topButton = document.querySelector('.top-button');
if (topButton) {
  window.addEventListener('scroll', function () {
    if (window.scrollY >= 500) {
      topButton.classList.add('visible');
    } else {
      topButton.classList.remove('visible');
    }
  });
}
