/*--------------------------------------------------------------
Theme JS
--------------------------------------------------------------*/

// Handle offcanvas links click event.
const offcanvasLinks = document.querySelectorAll( '.offcanvas a:not(.dropdown-toggle):not(.remove_from_cart_button)' );
offcanvasLinks.forEach( element => {
    element.addEventListener( 'click', () => {
        const offcanvasElement = document.querySelector( '.offcanvas' );
        const offcanvasEvent = new Event( 'hide.bs.offcanvas' );
        offcanvasElement.dispatchEvent( offcanvasEvent );
    });
});

// Remove top navigation search if no children in collapse-search.
const collapseSearch = document.getElementById( 'collapse-search' );
if ( collapseSearch && collapseSearch.children.length === 0 ) {
    const topNavSearch = document.querySelectorAll( '.top-nav-search-md, .top-nav-search-lg' );
    topNavSearch.forEach( element => element.remove() );
}

// Focus on the first input field when collapse-search is shown.
if ( collapseSearch ) {
    collapseSearch.addEventListener( 'shown.bs.collapse', () => {
        const inputElement = document.querySelector( '.top-nav-search input:first-of-type' );
        if ( inputElement ) {
            inputElement.focus();
        }
    });
}

// Hide collapse-search when clicking outside of it.
document.addEventListener( 'click', event => {
    if ( ! event.target.closest( '#collapse-search' ) ) {
        const collapseEvent = new Event( 'hide.bs.collapse' );
        collapseSearch.dispatchEvent( collapseEvent );
    }
});

// Show or hide the back-to-top button based on scroll position.
window.addEventListener( 'scroll', () => {
    const scroll = window.scrollY;
    const topButton = document.querySelector( '.top-button' );
    if ( topButton ) {
        scroll >= 500 ? topButton.classList.add( 'visible' ) : topButton.classList.remove( 'visible' );
    }
});

// Set height for elements with height-* classes.
[ '50', '75', '85', '100' ].forEach( percentage => {
    const heightElements = document.querySelectorAll( `.height-${percentage}` );
    heightElements.forEach( element => {
        element.style.height = `${( percentage / 100 ) * window.innerHeight}px`;
    });
});

// Display warning for Internet Explorer users.
if ( 'documentMode' in document ) {
    const IEWarningDiv = document.createElement( 'div' );
    IEWarningDiv.className = 'position-fixed top-0 end-0 bottom-0 start-0 d-flex justify-content-center align-items-center';
    IEWarningDiv.style = 'background:white;z-index:1999';
    IEWarningDiv.innerHTML = `<div style="max-width: 90vw;">
        <h1>${bootscore.ie_title}</h1>
        <p class="lead">${bootscore.ie_limited_functionality}</p>
        <p class="lead">${bootscore.ie_modern_browsers_1}${bootscore.ie_modern_browsers_2}${bootscore.ie_modern_browsers_3}${bootscore.ie_modern_browsers_4}${bootscore.ie_modern_browsers_5}</p>
    </div>`;
    document.body.appendChild( IEWarningDiv );
}
