=== bootScore ===

Contributors: craftwerk
Tags: featured-images, threaded-comments, translation-ready

Requires at least: 4.5
Tested up to: 5.7
Requires PHP: 5.6
Stable tag: 5.0.1.2
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

bootScore, Bootstrap 5 WordPress Theme, Copyright 2019 - 2021 Bastian Kreiter.


=== Plugin Name ===

A starter theme called bootScore.

Contributors: craftwerk


== Description ==

A powerful free Bootstrap 5 WordPress Starter Theme


== Installation ==

1. In your admin panel, go to Appearance > Themes and click the Add New button.
2. Click Upload Theme and Choose File, then select the theme's .zip file. Click Install Now.
3. Click Activate to use your new theme right away.


== Documentation ==

https://bootscore.me/category/documentation/


== Frequently Asked Questions ==

= Does this theme support any plugins? =

bootScore includes support for Infinite Scroll in Jetpack.

== Credits ==

* Based on Underscores https://underscores.me/, (C) 2012-2017 Automattic, Inc., [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)
* normalize.css https://necolas.github.io/normalize.css/, (C) 2012-2016 Nicolas Gallagher and Jonathan Neal, [MIT](https://opensource.org/licenses/MIT)
* Bootstrap https://getbootstrap.com/docs/5.0/about/license/
* WP Bootstrap Navwalker by Edward McIntyre: https://github.com/twittem/wp-bootstrap-navwalker
* Comments Section Script by wp-bootstrap-starter https://github.com/afterimagedesigns/wp-bootstrap-starter
* Font Awesome https://fontawesome.com/license/free


== Changelog ==

    = 5.0.1.2 - April 16 2021 =
    
        * [REMOVED] backdrop-filter in image gallery caption (style.css)
        * [BUGFIX] Custom checkboxes, radios, range, and select on Mac/iOS Safari if custom Sass compiled bootstrap.min.css is used (style.css)
        * [BUGFIX] Footer menu class and id (twice, thnx Konstantinos Tzimas, footer.php)

    = 5.0.1.1 - April 15 2021 =
    
        * [UPDATE] mini-cart.php (WooCommerce 5.2.1)
        * [UPDATE] form-pay.php (WooCommerce 5.2.1)

    = 5.0.1.0 - April 12 2021 =
    
        * [IMPROVEMENT] Font size WooCommerce prices
        * [BUGFIX] Remove autop (WP 5.7 bug near shortcode, style.css) https://wordpress.org/support/topic/how-to-stop-wp-from-adding-p-tag-automatically/
        * [BUGFIX] Radios cart-shipping.php on devices below 768px
        * [BUGFIX] Offcanvas cart footer (iOS)
        * [UPDATE] Replaced all Offcanvas with Bootstrap component
        * [UPDATE] Bootstrap v5.0.0-beta3

    = 5.0.0.10 - March 29 2021 =
    
        * [NEW] Translation: Français (thnx Mike Collignon)
        * [BUGFIX] Offcanvas user will not close when filling out the form (woocommerce.js, thnx Mike Collignon)
        * [BUGFIX] Removed autop in theme.js again (Bug with CF7)  
        * [IMPROVEMENT] Custom radios to shipping (cart-shipping.php, woocommerce-style.css)
        * [IMPROVEMENT] New checkout page (form-checkout.php, form-billing.php, form-shipping.php, thnx Mike Collignon)
        * [IMPROVEMENT] Close offcanvas cart and user dashboard on click <a> (woocommerce.js, not working MacOS Safari)
        * [IMPROVEMENT] Using Semantic Versioning https://semver.org

    = 5.0.0.9 - March 17 2021 =
    
        * [BUGFIX] Missing line break tags in single-*.php
        * [NEW] Translation: Dutch, Dutch (Formal), (thnx Jan Revet)

    = 5.0.0.8 - March 14 2021 =
    
        * [NEW] Translation: Polski (thnx Marshall Reyher)

    = 5.0.0.7 - March 13 2021 =
    
        * [IMPROVEMENT] Changed theme description
        * [BUGFIX] <pre> in comments will not crash columns anymore (comment-list.php, style.css)
        
    = 5.0.0.6 - March 10 2021 =
    
        * [BUGFIX] Remove autop (WP 5.7, theme.js)
        * [NEW] Add hover effect to offcanvas-header (style.css)

    = 5.0.0.5 - March 08 2021 =
    
        * [NEW] Reusable .zi-10X0 classes for z-index

    = 5.0.0.4 - March 04 2021 =
    
        * [IMPROVEMENT] Removed position CSS on .dropdown-search.dropdown-menu, added Bootstrap classes start-0 and end-0 to display search in full browser-width (style.css, header.php). 
        * [BUGFIX] Search dropdown on Android Chrome (theme.js)

    = 5.0.0.3 - February 26 2021 =
    
        * [NEW] Translation: Português do Brasil (thnx Junio Jose)
        * [BUGFIX] margin undefined Github issue https://github.com/craftwerkberlin/bootscore-5/issues/1 (thnx djcowan)
        * [IMPROVEMENT] Reorder CSS load (thnx Daniel Munoz Rey)

    = 5.0.0.2 - February 23 2021 =
    
        * [BUGFIX] Remove .active from all .nav-link if page is search-result
        * [NEW] HTML Markups (Theme Unit Test Data)
        * [NEW] Translation: Italiano (thnx Domenico Carbone)
        * [BUGFIX] Add overflow-x: hidden to body to hide horizontal scrollbars on Windows Chrome and Firefox if width-100 class is used.
        * [TEST] jQuery 3.5.1 ready
        * [SEO] Merged all CSS in /woocommerce/css into one file woocommerce-style.css
        * [SEO] Links in comments opens in new tab with rel=”nofollow” attribute (pluggable function, functions.php)
        * [SEO] Add <link rel=preload> to Fontawesome (functions.php)
        * [SEO] Merged theme-header.js and theme.js to reduce file requests
        * [SEO] Moved all CSS from all.css to style.css to reduce file requests
        * [SEO] Load all JS in footer
        * [BUGFIX] Workaround to highlight menu links when active. (theme.js, WP Bootstrap Navwalker is still v4)
        * [NEW] Hook after #primary in WooCommerce files
        * [NEW] Time updated separator has an own class to hide simply via CSS if updated time is not needed, template-tags.php.
        * [NEW] Removed Preloader from theme and made a plugin of it
        
    = 5.0.0.1 - February 11 2021 =
    
        * [UPDATE] Bootstrap 5.0.0-beta-2

    = 5.0.0.0 - January 29 2021 =
    
        * Initial release


