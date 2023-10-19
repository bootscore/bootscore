/*--------------------------------------------------------------
Theme JS
--------------------------------------------------------------*/

function initializeScripts() {
    function isMobileDevice() {
        const collapseSearchButton = document.querySelector('.top-nav-search-md');
        return collapseSearchButton ? window.getComputedStyle(collapseSearchButton).display !== 'none' : false;
    }

    const offCanvasLinks = document.querySelectorAll('.offcanvas a:not(.dropdown-toggle, .remove_from_cart_button)');
    offCanvasLinks.forEach((link) => {
        link.addEventListener('click', function() {
            const offcanvasElement = document.querySelector('.offcanvas');
            if (offcanvasElement) {
                const bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
                bsOffcanvas.hide();
            }
        });
    });

    const collapseSearch = document.getElementById('collapse-search');
    if (collapseSearch && collapseSearch.children.length === 0) {
        document.querySelectorAll('.top-nav-search-md, .top-nav-search-lg').forEach((element) => element.remove());
    }

    if (collapseSearch) {
        collapseSearch.addEventListener('shown.bs.collapse', function() {
            const inputElement = document.querySelector('.top-nav-search input:first-of-type');
            if (inputElement) {
                inputElement.focus();
            }
        });
    }

    document.addEventListener('click', function(event) {
        if (collapseSearch && !collapseSearch.contains(event.target)) {
            const isMobile = isMobileDevice();
            if (isMobile) {
                const bsCollapse = new bootstrap.Collapse(collapseSearch, {
                    toggle: false
                });
                bsCollapse.hide();
            } else {
                const inputElement = document.querySelector('.top-nav-search-md input:first-of-type');
                if (inputElement) {
                    inputElement.blur();
                }
            }
        }
    });

    const topButton = document.querySelector('.top-button');
    if (topButton) {
        window.addEventListener('scroll', function() {
            topButton.classList.toggle('visible', window.scrollY >= 500);
        });
    }

    ['height-50', 'height-75', 'height-85', 'height-100'].forEach((cls) => {
        const factor = parseFloat(cls.split('-')[1]) / 100;
        document.querySelectorAll(`.${cls}`).forEach((element) => {
            element.style.height = `${window.innerHeight * factor}px`;
        });
    });

    if (window.document.documentMode && typeof bootscore !== 'undefined') {
        const IEWarningDiv = document.createElement('div');
        IEWarningDiv.className = 'position-fixed top-0 end-0 bottom-0 start-0 d-flex justify-content-center align-items-center';
        IEWarningDiv.style.backgroundColor = 'white';
        IEWarningDiv.style.zIndex = 1999;
        IEWarningDiv.innerHTML = `
        <div style="max-width: 90vw;">
            <h1>${bootscore.ie_title}</h1>
            <p class="lead">${bootscore.ie_limited_functionality}</p>
            <p class="lead">${bootscore.ie_modern_browsers_1}${bootscore.ie_modern_browsers_2}${bootscore.ie_modern_browsers_3}${bootscore.ie_modern_browsers_4}${bootscore.ie_modern_browsers_5}</p>
        </div>`;
        document.body.appendChild(IEWarningDiv);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeScripts);
} else {
    initializeScripts();
}
