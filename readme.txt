=== bootScore ===

Contributors: Bastian Kreiter, Justin Kruit, Martin Holzer, Tim Groeneveld, Laurent Binder, Patrick, Gustavo Silva, TershiXia, 
electronicsandprogramming, charly, Alexandros Kourmoulakis, Sven Geiß, ucalegonte, Benhaim Ido, Sean Emerson, Androidacy, Tamás Dohány,
David Vanderhaeghe, Karsten Reincke, Patrick Champoux, sweetappleuk, exlexv

Tags: featured-images, threaded-comments, translation-ready

Requires at least: 4.5
Tested up to: 6.3.1
Requires PHP: 7.4
Stable tag: 5.3.3
License: MIT License
License URI: https://github.com/bootscore/bootscore/blob/main/LICENSE

bootScore, Bootstrap 5 WordPress Theme, Copyright 2019 - 2023 The bootScore Contributors.


=== Plugin Name ===

A starter theme called bootScore.


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

bootScore includes support for WooCommerce and Infinite Scroll in Jetpack.


== Credits ==

* Based on Underscores https://underscores.me/, (C) 2012-2017 Automattic, Inc., [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)
* normalize.css https://necolas.github.io/normalize.css/, (C) 2012-2016 Nicolas Gallagher and Jonathan Neal, [MIT](https://opensource.org/licenses/MIT)
* Bootstrap https://getbootstrap.com/docs/5.0/about/license/
* Bootstrap 5 Wordpress Navbar Walker by AlexWebLab: https://github.com/AlexWebLab/bootstrap-5-wordpress-navbar-walker
* Comments Section Script by wp-bootstrap-starter https://github.com/afterimagedesigns/wp-bootstrap-starter
* Font Awesome https://fontawesome.com/license/free
* Plugin Update Checker by YahnisElsts, MIT License https://github.com/YahnisElsts/plugin-update-checker/blob/master/license.txt
* scssphp by Leaf Corcoran, MIT License https://github.com/scssphp/scssphp/blob/master/LICENSE.md


== Changelog ==

    = 5.3.3 - September 12 2023 =

        * [IMPROVEMENT] Split functions.php into smaller files #545
        * [IMPROVEMENT] Split wc-functions.php into smaller files #555
        * [IMPROVEMENT] Refactor checkout form validation #563
        * [IMPROVEMENT] Use function for sidebar col width and responsive offcanvas #560
        * [DELETED] thankyou.php 25d5d62
        * [DELETED] terms.php 49eaf4e
        * [DELETED] payment-method.php 8cc4d88
        * [UPDATE] Fontawesome 6.4.2 fc953d3
        * [UPDATE] Update translation template 962bf8f

    = 5.3.2 - August 11 2023 =
    
        * [DELETED] woocommerce/cart/cart.php f9ba9a8 18c656a 1a51736 ff1be80
        * [DELETED] woocommerce/global/breadcrumb.php 7eb3892 74766e7
        * [DELETED] Closed default ship different adress filter (can be changed in Woo settings) 90da697
        * [BUGFIX] Quantity input if product is sold individually or only 1 in stock left e44e81e
        * [UPDATE] Translation template #529
        * [UPDATE] Bootstrap 5.3.1 #534

    = 5.3.1 - July 10 2023 =
    
        * [DELETED] price.php ad1eba5
        * [DELETED] form-lost-password.php 3c2f6c6
        * [DELETED] form-edit-account.php fd863e2
        * [DELETED] form-reset-password.php 617d663
        * [DELETED] my-adress.php e78a5a3
        * [DELETED] loop/sale-flash.php 6106302
        * [DELETED] single-product/sale-flash.php 9246c3d
        * [DELETED] my-account/form-login.php bd82f53
        * [DELETED] global/form-login.php 8a2f1db
        * [DELETED] order/order-details-customer.php 400631a
        * [DELETED] cart/shipping-calculator.php f5449f1
        * [DELETED] cart-shipping.php 15a4462
        * [DELETED] form-shipping.php 9a9cc73
        * [DELETED] form-billing.php 1dd17b5
        * [FEATURE] Added a hook to all single-*.php's for related posts 367724b
        * [UPDATE] WooCommerce 7.9 templates #525
        * [UPDATE] Turkish translation #507
        * [BUGFIX] Enqueue cart fragments script #508
        * [IMPROVEMENT] Changing ms-2 to ms-1 ms-md-2 in top-nav-widget #512
        * [IMPROVEMENT] Replace PHP echo with shorthand 4f5e9b7
        * [IMPROVEMENT] Wrap *-full-width-image.php h1 in div cdb8a60

    = 5.3.0 - June 2 2023 =
    
        * [IMPROVEMENT] Checkout payment checks & radios #456
        * [IMPROVEMENT] Make main col width dynamic #458
        * [IMPROVEMENT] Replace PHP echo's with shorthand #459
        * [IMPROVEMENT] Refactor header.php and enable Woo parts if WooCommerce is installed #461
        * [IMPROVEMENT] Enqueue scripts if WooCommerce is installed #473
        * [IMPROVEMENT] Password protected post form as input-group #476
        * [IMPROVEMENT] Formatting the php files #495
        * [BUGFIX] WooCommerce searchform language string #464
        * [BUGFIX] bootscore_category() failing to output categories #465
        * [BUGFIX] Duplicate class property in product-searchform.php 4c9daef
        * [BUGFIX] JavaScript block HTML validation fail in ajax-add-to-cart.php c29635d
        * [BUGFIX] Minor fixes 494
        * [UPDATE] WooCommerce 7.8.0 templates #483
        * [UPDATE] Translation template #469
        * [UPDATE] Bootstrap 5.3.0 #487

    = 5.2.3.4 - April 17 2023 =
    
        * [FEATURE] New bootscore_container_class() function #439
        * [FEATURE] 2 more widget areas and improve existing ones #449
        * [IMPROVEMENT] Comments section #446
        * [BUGFIX] Add missing fallback for previous horizontal loop card images #450

    = 5.2.3.3 - March 31 2023 =
    
        * [IMPROVEMENT] Make icons in alerts optional and use masks instead of FA font #403
        * [IMPROVEMENT] Use WC strings instead of bootScore in mini cart #410
        * [IMPROVEMENT] Refactor quantity input #405
        * [IMPROVEMENT] Make pagination translation-ready #404
        * [IMPROVEMENT] Horizontal loop cards #428
        * [IMPROVEMENT] Add card-header to sidebar widget-cards #431
        * [REMOVED] WooCommerce table templates #420
        * [REMOVED] Temporary prefix webkit browsers checks, select and form-range defe598
        * [FEATURE] Add a shortcode for inserting Fontawesome icons #413
        * [BUGFIX] Searchform collapse behaviour #411
        * [BUGFIX] Hide duplicate empty mini-cart message in cart page #434
        * [UPDATE] Translation template #422
        * [UPDATE] Font Awesome 6.4.0 #435

    = 5.2.3.2 - February 17 2023 =
    
        * [IMPROVEMENT] Remove WooCommerce notice templates #372
        * [IMPROVEMENT] Remove WooCommerce btn templates #386
        * [IMPROVEMENT] Add more control over scss compiler #375
        * [IMPROVEMENT] Offcanvas cart notices b2a822b, 3872704, eccb9af
        * [UPDATE] screenshot.png 016bfc7
        * [UPDATE] Fontawesome 6.3.0 #394
        * [UPDATE] quantity-input.php (WooCommerce 7.4.0) 15f84aa
        * [UPDATE] cart.php (WooCommerce 7.4.0) 6f3fa58
        * [BUGFIX] Extend compound selectors #395
        * [BUGFIX] Remove duplicated nav closing tag in the_breadcrumb #398

    = 5.2.3.1 - January 16 2023 =
    
        * [UPDATE] quantity-input.php (WooCommerce 7.2) #331
        * [UPDATE] cart-shipping.php (WooCommerce 7.3) #354
        * [UPDATE] Password message for my-account-offcanvas.php #341
        * [IMPROVEMENT] Refactored ajax cart #329
        * [IMPROVEMENT] Clean-up single-*.php's and page-*.php's #332
        * [IMPROVEMENT] Prevent self-closing offcanvas if dropdown has nested dropdowns (modified nav-walker with 3 or more levels depth) #350
        * [IMPROVEMENT] Make use of admin bar height css var for offcanvas and modal #360
        * [BUGFIX] Add show-password to all pages in WC user offcanvas #344

    = 5.2.3.0 - December 16 2022 =

        * [UPDATE] WC 7.2.0 templates
        * [UPDATE] Bootstrap 5.2.3
        * [IMPROVEMENT] Türkçe
        * [NEW] Magyar, thanks to iamdtms

    = 5.2.2.1 - November 03 2022 =
    
        PHP

        * [UPDATE] WooCommerce 7.0.1 templates
        
        CSS
        
        * [IMPROVEMENT] Fix nonstandard prefix for ::selection

    = 5.2.2.0 - October 17 2022 =
    
        PHP

        * [IMPROVEMENT] scss compile, expanded for dev #279
        * [IMPROVEMENT] Bootstrap 5.2.2 breadcrumb component #274
        * [IMPROVEMENT] Feature package.json extra categories #276
        
        Updates

        * [UPDATE] Bootstrap 5.2.2 #275

    = 5.2.1.1 - September 15 2022 =
    
        PHP
    
        * [IMPROVEMENT] Same author meta function in single-*.php as in loop #251
        * [UPDATE] form-login.php (WooCommerce 6.9.0) #255
        * [BUGFIX] Readded empty IE alert function #258

    = 5.2.1.0 - September 08 2022 =
    
        CSS

        * [IMPROVEMENT] Offcanvas cart-loader #199
        * [IMPROVEMENT] Load style.css after main.css #201
        * [BUGFIX] Coupon input in cart page #203
        * [BUGFIX] border-radius in checkout #payment card #214
        * [BUGFIX] Set checkout shipping table heading to top #236
        * [BUGFIX] Hide "View cart" in loop and product pages #237
        * [REMOVED] #nav-main nav-link last-child padding #233
        * [REMOVED] Pagination border hotfix 5628280
        * [REMOVED] bS Cookie Settings modal fallback #220
        
        PHP

        * [IMPROVEMENT] Make comment button and pw protected form function pluggable #193
        * [IMPROVEMENT] Offcanvas cart-items to list-group-flush #194
        * [REMOVED] Empty IE alert function #197
        
        JavaScript

        * [IMPROVEMENT] WC product offcanvas #205
        
        Languages

        [NEW] עברית, thanks to Benhaim Ido #204
        
        Updates

        [UPDATE] scssphp 1.11.0 #246
        [UPDATE] Font Awesome 6.1.2 #196
        [UPDATE] Bootstrap 5.2.1

    = 5.2.0.0 - July 22 2022 =

        CSS

        * [IMPROVEMENT] Add scss sourcemap support when theme is in dev_mode #98
        * [IMPROVEMENT] Set compiled output to css/main.css #99
        * [IMPROVEMENT] WooCommerce CSS to SCSS #82
        * [IMPROVEMENT] Clean-up bootscore styles scss #90
        * [IMPROVEMENT] Removed Paypal img and link in checkout 61ff0eb
        * [IMPROVEMENT] Extend select and alerts instead adding classes by JavaScript #89
        * [IMPROVEMENT] Make footer stick to bottom #145
        * [IMPROVEMENT] Admin bar #149
        * [IMPROVEMENT] Moved Font Awesome to own folder #167
        * [IMPROVEMENT] Font Awesome handling #168
        * [IMPROVEMENT] Add composer.json b546a16
        * [REMOVED] Empty _fontawesome.scss b5d2bf6
        * [REMOVED] Custom _offcanvas.scss #159
        * [BUGFIX] Fixed star-rating in related products 9e4779e
        * [BUGFIX] Fixed responsive tables and buttons in cart page #93
        * [BUGFIX] Fixed related products if no product tab (disabled reviews, no product description, no attributes) is used #121
        * [BUGFIX] Invoice button in orders #136
        
        PHP

        * [IMPROVEMENT] Bootstrap to single-product tabs #87
        * [IMPROVEMENT] Added -/+ Buttons to WC quantity input #91
        * [IMPROVEMENT] Register ajax cart to pluggable function #123
        * [IMPROVEMENT] Removed () from WC product count #138
        * [IMPROVEMENT] Added page-blank.php #163
        * [IMPROVEMENT] Moved page/single templates to subfolders #163
        * [IMPROVEMENT] Responsive offcanvas sidebar #171
        * [IMPROVEMENT] Removed unused loop templates #176
        * [BUGFIX] WooCommerce sub categories in loop #84
        * [BUGFIX] input-group in checkout form-coupon.php f52963b
        * [BUGFIX] Pagination detect first page #148
        * [REMOVED] Optional telephone in checkout snippet. Can be made by WC customizer settings now 8e71711
        
        JS

        * [BUGFIX] Replaced Internet Explorer Alert PHP function by JavaScript 37
        * [BUGFIX] Removed self-closing offcanvas in navbar on resize #102
        * [REMOVED] Smooth-scroll script #114
        * [REMOVED] Dropdown slide effect script #115
        
        Languages

        * [NEW] Português, thanks to Gustavo Silva #81
        * [NEW] 繁體中文, thanks to TershiXia #112
        * [NEW] ελληνική, thanks to Alexandros Kourmoulakis #161
        
        Updates

        * [UPDATE] Bootstrap https://github.com/bootscore/bootscore/commits/5.2.0.0-release
        * [UPDATE] Font Awesome 6.1.1 free #120
        * [UPDATE] scssphp 1.10.3 #158

    = 5.1.3.2 - June 03 2022 =
    
        * [BUGFIX] Offcanvas backdrop in Chrome/Edge

    = 5.1.3.1 - January 25 2022 =
    
        CSS

        * [IMPROVEMENT] Pushed style.css to scss files #33
        * [IMPROVEMENT] Added _bscore_woocommerce.scss file to prepare merging css to scss #41
        * [IMPROVEMENT] @extend alert alert-info to comments .must-log-in and .no-comment
        * [BUGFIX] form-range in webkit browsers
        * [BUGFIX] Rating stars in product loop
        * [BUGFIX] Modal height and position if body has classes logged-in admin-bar
        * [REMOVED] @import "fontawesome"; in css/scss/bootstrap.min.scss
        
        PHP

        * [IMPROVEMENT] Force scss compile when environment is development #39
        * [IMPROVEMENT] Added hook bs_after_primary to page-blank-with-container.php and page-blank-without-container.php
        * [IMPROVEMENT] Added btn-outline-primary to payment-methods.php #42
        * [IMPROVEMENT] Added btn-outline-primary to payment.php #43
        * [IMPROVEMENT] Added btn-outline-primary to thankyou.php #44
        * [IMPROVEMENT] Added visually-hidden-focusable class to buttons in header.php and footer.php to fix accessibility issues #73
        * [BUGFIX] Added missing jquery on new install in enqueue script #34
        * [BUGFIX] Added redirect if offcanvas login failed #36
        * [BUGFIX] Added redirect to my-account after registration #68
        * [BUGFIX] Built a "filter" for terms.php #31
        * [BUGFIX] Fixed class typo in header-woocommerce.php #9
        
        Languages

        * [NEW] Language: Español de Colombia, thanks to Osdeibi Acurero
        * [NEW] Language: Español de Venezuela, thanks to Osdeibi Acurero
        
        Updates

        * [UPDATE] scssphp 1.10.0 #66

    = 5.1.3.0 - October 11 2021 =

        * [UPDATE] Bootstrap 5.1.3

    = 5.1.2.0 - October 06 2021 =

        * [REMOVED] jQuery in header.php
        * [NEW] Added missing wp_body_open hook
        * [SEO] Changed offcanvas header <h5> to <span class="h5"> for SEO reason
        * [UPDATE] Bootstrap 5.1.2
        * [IMPROVEMENT] Compile scss when Bootstrap has been updated


    = 5.1.1.1 - September 29 2021 =

        * [IMPROVEMENT] WooCommerce colors to variables
        * [IMPROVEMENT] Reformatted all files
        * [NEW] Translation: Türkçe (thanks to Murat Esgin)
        * [NEW] Added Bootstrap source and compiler to theme


    = 5.1.1.0 - September 08 2021 =
    
        * [IMPROVEMENT] Ajax product notice alerts (ajax-add-to-cart.php, thanks to Martin Holzer)
        * [IMPROVEMENT] Close collapse if searchform loses focus
        * [IMPROVEMENT] Filter class card-img-top to product loop img (woocommerce-functions.php, woocommerce-style.css)
        * [IMPROVEMENT] Changed all WooCommerce colors to variables
        * [IMPROVEMENT] Bootstrap form validation in WooCommerce checkout
        * [IMPROVEMENT] Removed custom-validation from checkout checkboxes and readded form-row (terms.php, woocommerce-js, woocommerce-style.css)
        * [IMPROVEMENT] Refactored checkout (form-checkout.php, form-billing.php, form-shipping.php, Klarna checkout)
        * [IMPROVEMENT] pointer-events variation-add-to-cart-button.php (thanks to Martin Holzer)
        * [IMPROVEMENT] Searchform focus (theme.js, thanks to Martin Holzer)
        * [IMPROVEMENT] Readded and improved timestrap to scripts & styles (functions.php, woocommerce-functions.php, thanks to Martin Holzer)
        * [IMPROVEMENT] top-nav-search widget. Every widget can be shown there now theme.js, thanks to Martin Holzer)
        * [IMPROVEMENT] bootscore_pagination is a pluggable function now (functions.php, thanks to Martin Holzer)
        * [NEW] Renamed folder to bootscore-main (Github)
        * [NEW] Language: Pусский (thanks to Vladislav)
        * [UPDATE] Updated order-details-customer.php
        * [UPDATE] Updated terms.php (thanks to Emil Linden)
        * [UPDATE] Update Font Awesome Free 5.15.4
        * [UPDATE] Bootstrap 5.1.1


    = 5.1.0.1 - August 11 2021 =
    
        * [IMPROVEMENT] Added d-lg-none to search collapse (header.php)
        * [IMPROVEMENT] "current_page_parent" and "current-post-ancestor" to nav_walker, thanks Justin Kruit https://justinkruit.com
        * [IMPROVEMENT] Dutch translation, thanks Justin Kruit https://justinkruit.com
        * [REMOVED] Timestrap from enqueue styles and scripts (CSS overrides needed !important rule, functions.php, woocommerce-functions.php)

    = 5.1.0.0 - August 05 2021 =
    
        * [REMOVED] Self-coded offcanvas navbar implementation (header.php, header-woocommerce.php, style.css, theme.js)
        * [UPDATE] Uses Bootstrap 5.1.0 offcanvas navbar implementation now
        * [UPDATE] Bootstrap 5.1.0

    = 5.0.2.5 - August 04 2021 =
    
        * [BUGFIX] Disable Gutenberg blocks in widgets and enable classic mode. Widgets like search won't work (WordPress 5.8, functions.php).

    = 5.0.2.4 - August 03 2021 =
    
        * [NEW] README.md
        * [IMPROVEMENT] Changed remove from cart button btn &times; to trash icon (cart.php, mini-cart.php)
        * [IMPROVEMENT] Fixed a.badge color by text-* class instead of CSS (style.css, template-tags.php)
        * [IMPROVEMENT] Breadcrumb padding and font-size (functions.php, woocommerce-functions.php)
        * [BUGFIX] .form-select (shipping-calculator.php Thnx Martin Holzer risingbytes.at)
        * [BUGFIX] Keep offcanvas-user open on reload if contains login or register error alert (woocommerce.js)
        * [BUGFIX] Error alert if login is failed (my-account-offcanvas.php, thanks Sean VanderMolen https://techpad.biz)
        * [BUGFIX] Changed duplicate id's in offcanvas user (my-account-offcanvas.php, thanks cemmos https://github.com/craftwerkberlin/bootscore-5/issues/8)
        * [BUGFIX] Add col-md-8 col-xxl-9 classes to post list in index.php (Thanks Tim Groeneveld, https://github.com/craftwerkberlin/bootscore-5/pull/7)
        * [CHANGED] Default Bootstrap offcanvas-header (header.php, header-woocommerce.php, mini-cart-php)
        * [CHANGED] GNU General Public License v2 to MIT License
        * [REMOVED] bootstrap.min.css.map and bootstrap.bundle.min.js.map

    = 5.0.2.3 - July 14 2021 =
    
        * [NEW] Language Čeština, thnx Petr Žaloudek https://webhned.eu
        * [BUGFIX] Height modal fullscreen if .logged-in.admin-bar (style.css)
        * [BUGFIX] Missing author and breadcrumb on single-full-width-image.php
        * [BUGFIX] The “next page” pagination work properly on the 1st page (functions.php, thnx Mike Collignon, https://www.alox.co)
        * [BUGFIX] Searchform can be placed in any widget positions without showing search button in navbar
        * [IMPROVEMENT] Cleanup Scroll To Top Button (style.css, footer.php)
        * [IMPROVEMENT] Search collapse button hide if empty removed by JS (theme.js, style.css)
        * [IMPROVEMENT] Focus on collapsed show searchform input
        * [IMPROVEMENT] header.php and header-woocommerce.php are completely new, more simple. Search button uses collapse component now instead of dropdown. Old header can still be used.
        * [REMOVED] Data attribute data-bs-hideresize="true". Close menu offcanvas in navbar on resize direct (theme.js, header.php, header-woocommerce.php)
        * [REMOVED] index-woocommerce.php
        * [REMOVED] wp-block-button reset (style.css)
        * [REMOVED] Quote block (style.css)

    = 5.0.2.2 - June 23 2021 =
        
        * [IMPROVEMENT] Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes. (Thnx Martin Holzer risingbytes.at)
        * [IMPROVEMENT] Added transition-delay to cart-loader to prevent flickering loading
        * [BUGFIX] Added mt-4 to #reply-title (comment-list.php)
        * [UPDATE] Language Deutsch (Du/Sie)
        * [UPDATE] Bootstrap 5.0.2

    = 5.0.2.1 - June 05 2021 =
        
        * [IMPROVEMENT] transition-delay on offcanvas-cart loader https://github.com/craftwerkberlin/bootscore-5/issues/6 (woocommerce-style.css, woocommerce.js, ajax-add-to-cart.php. Thnx Martin Holzer risingbytes.at)
        * [IMPROVEMENT] Tags is a complete pluggable function now (template-tags.php)
        * [IMPROVEMENT] Register comment-list.php is a pluggable function now. You can override file by register comment-list.php and place a copy in child .
        * [IMPROVEMENT] Nav Walker and Menu register are a complete pluggable function now. You can create your own menus and use a different nav walker by override them in child-theme.

    = 5.0.2.0 - May 31 2021 =
        
        * [BUGFIX] Added .w-100 class to offcanvas cart footer
        * [REMOVED] JavaScript workaround to set .active class to .nav-link (done by new nav-walker now)
        * [UPDATE] All languages
        * [UPDATE] Dropdown menu slide animation (theme.js)
        * [UPDATE] Replaced modified Bootstrap 4 Nav Walker with Bootstrap 5 Nav Walker https://github.com/AlexWebLab/bootstrap-5-wordpress-navbar-walker

    = 5.0.1.12 - May 28 2021 =
        
        * [BUGFIX] Checkout Stripe validation (<li> payment-method.php, thnx John https://bootscore.me/documentation/woocommerce/#comment-3880)
        * [UPDATE] Swedish language
        * [UPDATE] Changed WooCommerce comments with Bootstrap custom component (review.php, single-product-reviews.php, woocommerce-style.css)

    = 5.0.1.11 - May 28 2021 =
        
        * [REMOVED] Language backup files
        * [NEW] Translation: Svenska (thnx Emil Lindén)
        * [BUGFIX] Two Factor Authentication support form-login.php. Does not work in offcanvas, must use my-account static page (Thnx cemmos, Github issue https://github.com/craftwerkberlin/bootscore-5/issues/5)
        * [IMPROVEMENT] Added .img-thumbnail.rounded-circle to avatar (comment-list.php, all author-*.php)

    = 5.0.1.10 - May 16 2021 =
        
        * [BUGFIX] Readded jQuery in header.php. Required if a 3rd party plugin loads jQuery in header instead in footer.

    = 5.0.1.9 - May 16 2021 =
        
        * [BUGFIX] Added missing close </div> tag in page-full-width-image.php and single-full-width-image.php (thnx Trishah Woolley)

    = 5.0.1.8 - May 14 2021 =
        
        * [BUGFIX] Typo in index.php (thnx Whitetower-lloyd, https://github.com/craftwerkberlin/bootscore-5/issues/4)
        * [UPDATE] Bootstrap 5.0.1

    = 5.0.1.7 - May 12 2021 =
        
        * [UPDATE] grouped.php (WooCommerce 5.3.0)

    = 5.0.1.6 - May 11 2021 =
        
        * [NEW] Added offcanvas-top to offcanvas navbar implementation (style.css)
        * [REMOVED] Removed WP jQuery from header.php
        * [IMPROVEMENT] Set offcanvas backdrop fade transition to .4s ease-in-out for smoother transition (style.css)

    = 5.0.1.5 - May 07 2021 =
        
        * [BUGFIX] Added missing spaces in dutch translations
        * [BUGFIX] Added [data-bs-popper] to search dropdown to override popper.js inline-style (Bootstrap 5 stable, dropdown could not be opened when using child-theme with bootstrap.min.css from parent theme, style.css). 

    = 5.0.1.4 - May 06 2021 =
        
        * [NEW] .zi-n1 helper class to set negative z-index
        * [BUGFIX] text-decoration: none hover product card (woocommerce-style.css)
        * [REMOVED] Bugfix custom checkboxes, radios, range, and select on Mac/iOS Safari if custom Sass compiled bootstrap.min.css is used (style.css, fixed in compiler settings)
        * [REMOVED] Workaround icon in offcanvas toggler https://github.com/twbs/bootstrap/issues/33457 (Bugfix Bootstrap 5.0.0, theme.js, woocommerce.js)
        * [REMOVED] Backdrop if navbar has class .fixed-top. Uses Bootstrap offcanvas backdrop now (style.css, theme.js)
        * [REMOVED] Offcanvas header align-items-center (style.css, Bootstrap included)
        * [UPDATE] Bootstrap 5.0.0

    = 5.0.1.3 - April 29 2021 =
    
        * [IMPROVEMENT] Replaced col in all author-*.php with custom media-object component (author-*.php)
        * [IMPROVEMENT] Replaced col in comments with custom media-object component (comments-list.php, style.css)
        * [IMPROVEMENT] Changed alert-primary to alert-info (payment-methods.php)
        * [BUGFIX] Moved offcanvas backdrop from .navbar.fixed-top to .fixed-top. Now it's possible to wrap the navbar in a fixed-top div.
        * [SEO] Removed aria-labelledby="" from offcanvas (thnx Mike Collignon)

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

