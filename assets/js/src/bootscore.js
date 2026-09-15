/*!
  * Bootscore JS
  *
  * @version 7.0.0
  */

// Search
document.addEventListener('DOMContentLoaded', function () {

  var collapseSearch = document.getElementById('collapse-search');

  if (collapseSearch) {

    // Searchform focus
    collapseSearch.addEventListener('shown.bs.collapse', function () {
      var input = collapseSearch.querySelector('.top-nav-search input:first-of-type');
      setTimeout(function () {
        input.focus();
      }, 0);
    });

    // Close collapse if click outside searchform
    document.addEventListener('click', function (event) {
      if (!event.target.closest('#collapse-search')) {
        var bsCollapse = bootstrap.Collapse.getInstance(collapseSearch);
        if (bsCollapse) {
          bsCollapse.hide();
        }
      }
    });

  }

});


// Scroll to top Button
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
