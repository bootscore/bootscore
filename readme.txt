=== Bootscore ===

Contributors: Bastian Kreiter, Justin Kruit, Martin Holzer, Tim Groeneveld, Laurent Binder, Patrick, Gustavo Silva, TershiXia, 
electronicsandprogramming, charly, Alexandros Kourmoulakis, Sven Geiß, ucalegonte, Benhaim Ido, Sean Emerson, Androidacy, Tamás Dohány,
David Vanderhaeghe, Karsten Reincke, Patrick Champoux, sweetappleuk, exlexv, Beda Schmid, JWestarp, sir-lexonarkz, xoneill007, 
Anisur Rahman

Tags: featured-images, threaded-comments, translation-ready

Requires at least: 4.5
Tested up to: 6.4.3
Requires PHP: 7.4
Stable tag: 6.0.0-dev
License: MIT License
License URI: https://github.com/bootscore/bootscore/blob/main/LICENSE

Copyright 2019 - 2024 The Bootscore Contributors.


=== Plugin Name ===

A starter-theme called Bootscore.


== Description ==

Flexible Bootstrap WordPress starter-theme for developers with full WooCommerce support.


== Installation ==

1. In your admin panel, go to Appearance > Themes and click the Add New button.
2. Click Upload Theme and Choose File, then select the theme's .zip file. Click Install Now.
3. Click Activate to use your new theme right away.


== Documentation ==

https://bootscore.me/category/documentation/


== Frequently Asked Questions ==

= Does this theme support any plugins? =

Bootscore includes support for WooCommerce and Infinite Scroll in Jetpack.


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

= 5.3.4 - November 14 2023 =

#### Feature

* Enable cross sell products #577

#### Improvement

* Add HTML5 support for styles, scripts and searchform #586
* Add .tags-heading-none selector to hide tags heading in loop heroes 3ccb2c1 #574
* Hide user-toggler in dashboard, mini-cart-toggler in cart and checkout page #602
* Replace card-im-top function in WC loop with Sass variables #609
* Added CODE_OF_CONDUCT.md #611
* Rewrite README.md #618
* Update Pagination for HTML5 compliance 4f89354
* Removed Internet Explorer warning alert 5808169 a3ca6aa

#### Bugfix

* Add missing offcanvas cart .woocommerce-info selector 07ec79f #574
* Exclude empty cart message from remove if offcanvas cart gets closed #580
* .top-button is clickable when not visible #590
* Plugin displayed incorrectly if hooked in form.cart product pages 2264033
* Accessibility behavior #605
* Replace sr-only with visually-hidden #616

#### Update

* scssphp 1.11.1 #581
* Bootstrap 5.3.2 5c4c2a4 #574

= 5.3.3 - September 12 2023 =

#### Improvement

* Split functions.php into smaller files #545
* Split wc-functions.php into smaller files #555
* Refactor checkout form validation #563
* Use function for sidebar col width and responsive offcanvas #560

#### Removed

* thankyou.php 25d5d62
* terms.php 49eaf4e
* payment-method.php 8cc4d88

#### Update

* Fontawesome 6.4.2 fc953d3
* Update translation template 962bf8f

= 5.3.2 - August 11 2023 =

#### Removed

* woocommerce/cart/cart.php f9ba9a8 18c656a 1a51736 ff1be80
* woocommerce/global/breadcrumb.php 7eb3892 74766e7
* Closed default ship different adress filter (can be changed in Woo settings) 90da697

#### Bugfix

* Quantity input if product is sold individually or only 1 in stock left e44e81e

#### Update

* Translation template #529
* Bootstrap 5.3.1 #534

= 5.3.1 - July 10 2023 =

#### Feature

* Added a hook to all single-*.php's for related posts 367724b

#### Improvement

* Changing ms-2 to ms-1 ms-md-2 in top-nav-widget #512
* Replace PHP echo with shorthand 4f5e9b7
* Wrap *-full-width-image.php h1 in div cdb8a60

#### Removed

* price.php ad1eba5
* form-lost-password.php 3c2f6c6
* form-edit-account.php fd863e2
* form-reset-password.php 617d663
* my-adress.php e78a5a3
* loop/sale-flash.php 6106302
* single-product/sale-flash.php 9246c3d
* my-account/form-login.php bd82f53
* global/form-login.php 8a2f1db
* order/order-details-customer.php 400631a
* cart/shipping-calculator.php f5449f1
* cart-shipping.php 15a4462
* form-shipping.php 9a9cc73
* form-billing.php 1dd17b5

#### Bugfix

* Enqueue cart fragments script #508

#### Update

* WooCommerce 7.9 templates #525
* Turkish translation #507

= 5.3.0 - June 2 2023 =

#### Improvement

* Checkout payment checks & radios #456
* Make main col width dynamic #458
* Replace PHP echo's with shorthand #459
* Refactor header.php and enable Woo parts if WooCommerce is installed #461
* Enqueue scripts if WooCommerce is installed #473
* Password protected post form as input-group #476
* Formatting the php files #495

#### Bugfix

* WooCommerce searchform language string #464
* bootscore_category() failing to output categories #465
* Duplicate class property in product-searchform.php 4c9daef
* JavaScript block HTML validation fail in ajax-add-to-cart.php c29635d
* Minor fixes 494

#### Update

* WooCommerce 7.8.0 templates #483
* Translation template #469
* Bootstrap 5.3.0 #487

= 5.2.3.4 - April 17 2023 =

#### Feature

* New bootscore_container_class() function #439
* 2 more widget areas and improve existing ones #449

#### Improvement

* Comments section #446

#### Bugfix

* Add missing fallback for previous horizontal loop card images #450

= 5.2.3.3 - March 31 2023 =

#### Feature

* Add a shortcode for inserting Fontawesome icons #413

#### Improvement

* Make icons in alerts optional and use masks instead of FA font #403
* Use WC strings instead of bootScore in mini cart #410
* Refactor quantity input #405
* Make pagination translation-ready #404
* Horizontal loop cards #428
* Add card-header to sidebar widget-cards #431

#### Removed 

* WooCommerce table templates #420
* Temporary prefix webkit browsers checks, select and form-range defe598

#### Bugfix

* Searchform collapse behaviour #411
* Hide duplicate empty mini-cart message in cart page #434

#### Update

* Translation template #422
* Font Awesome 6.4.0 #435

= 5.2.3.2 - February 17 2023 =

#### Improvement

* Remove WooCommerce notice templates #372
* Remove WooCommerce btn templates #386
* Add more control over scss compiler #375
* Offcanvas cart notices b2a822b, 3872704, eccb9af

#### Bugfix

* Extend compound selectors #395
* Remove duplicated nav closing tag in the_breadcrumb #398

#### Update

* screenshot.png 016bfc7
* Fontawesome 6.3.0 #394
* quantity-input.php (WooCommerce 7.4.0) 15f84aa
* cart.php (WooCommerce 7.4.0) 6f3fa58

= 5.2.3.1 - January 16 2023 =

#### Improvement

* Refactored ajax cart #329
* Clean-up single-*.php's and page-*.php's #332
* Prevent self-closing offcanvas if dropdown has nested dropdowns (modified nav-walker with 3 or more levels depth) #350
* Make use of admin bar height css var for offcanvas and modal #360

#### Bugfix

* Add show-password to all pages in WC user offcanvas #344

#### Update

* quantity-input.php (WooCommerce 7.2) #331
* cart-shipping.php (WooCommerce 7.3) #354
* Password message for my-account-offcanvas.php #341

= 5.2.3.0 - December 16 2022 =

#### Feature

* Language: Magyar, thanks to iamdtms

#### Improvement

* Language: Türkçe

#### Update

* WC 7.2.0 templates
* Bootstrap 5.2.3

= 5.2.2.1 - November 03 2022 =

#### Improvement

* Fix nonstandard prefix for ::selection

#### Update

* WooCommerce 7.0.1 templates

= 5.2.2.0 - October 17 2022 =

#### Improvement

* scss compile, expanded for dev #279
* Bootstrap 5.2.2 breadcrumb component #274
* Feature package.json extra categories #276

#### Update

* Bootstrap 5.2.2 #275

= 5.2.1.1 - September 15 2022 =

#### Improvement

* Same author meta function in single-*.php as in loop #251

#### Bugfix

* Readded empty IE alert function #258

#### Update

* form-login.php (WooCommerce 6.9.0) #255

= 5.2.1.0 - September 08 2022 =

#### Feature

Language: עברית, thanks to Benhaim Ido #204

#### Improvement

* Offcanvas cart-loader #199
* Load style.css after main.css #201
* Make comment button and pw protected form function pluggable #193
* Offcanvas cart-items to list-group-flush #194
* WC product offcanvas #205

#### Removed

* #nav-main nav-link last-child padding #233
* Pagination border hotfix 5628280
* bS Cookie Settings modal fallback #220
* Empty IE alert function #197

#### Bugfix

* Coupon input in cart page #203
* border-radius in checkout #payment card #214
* Set checkout shipping table heading to top #236
* Hide "View cart" in loop and product pages #237

#### Update

scssphp 1.11.0 #246
Font Awesome 6.1.2 #196
Bootstrap 5.2.1

= 5.2.0.0 - July 22 2022 =

#### Feature

* Language: Português, thanks to Gustavo Silva #81
* Language: 繁體中文, thanks to TershiXia #112
* Language: ελληνική, thanks to Alexandros Kourmoulakis #161
* Add composer.json b546a16

#### Improvement

* Add scss sourcemap support when theme is in dev_mode #98
* Set compiled output to css/main.css #99
* WooCommerce CSS to SCSS #82
* Clean-up bootscore styles scss #90
* Removed Paypal img and link in checkout 61ff0eb
* Extend select and alerts instead adding classes by JavaScript #89
* Make footer stick to bottom #145
* Admin bar #149
* Moved Font Awesome to own folder #167
* Font Awesome handling #168
* Bootstrap to single-product tabs #87
* Added -/+ Buttons to WC quantity input #91
* Register ajax cart to pluggable function #123
* Removed () from WC product count #138
* Added page-blank.php #163
* Moved page/single templates to subfolders #163
* Responsive offcanvas sidebar #171
* Removed unused loop templates #176

#### Removed

* Empty _fontawesome.scss b5d2bf6
* Custom _offcanvas.scss #159
* Optional telephone in checkout snippet. Can be made by WC customizer settings now 8e71711
* Smooth-scroll script #114
* Dropdown slide effect script #115

#### Bugfix

* Fixed star-rating in related products 9e4779e
* Fixed responsive tables and buttons in cart page #93
* Fixed related products if no product tab (disabled reviews, no product description, no attributes) is used #121
* Invoice button in orders #136
* WooCommerce sub categories in loop #84
* input-group in checkout form-coupon.php f52963b
* Pagination detect first page #148
* Replaced Internet Explorer Alert PHP function by JavaScript 37
* Removed self-closing offcanvas in navbar on resize #102

#### Update

* Bootstrap https://github.com/bootscore/bootscore/commits/5.2.0.0-release
* Font Awesome 6.1.1 free #120
* scssphp 1.10.3 #158

= 5.1.3.2 - June 03 2022 =

#### Bugfix

* Offcanvas backdrop in Chrome/Edge

= 5.1.3.1 - January 25 2022 =

#### Feature

* Language: Español de Colombia, thanks to Osdeibi Acurero
* Language: Español de Venezuela, thanks to Osdeibi Acurero

#### Improvement

* Pushed style.css to scss files #33
* Added _bscore_woocommerce.scss file to prepare merging css to scss #41
* @extend alert alert-info to comments .must-log-in and .no-comment
* Force scss compile when environment is development #39
* Added hook bs_after_primary to page-blank-with-container.php and page-blank-without-container.php
* Added btn-outline-primary to payment-methods.php #42
* Added btn-outline-primary to payment.php #43
* Added btn-outline-primary to thankyou.php #44
* Added visually-hidden-focusable class to buttons in header.php and footer.php to fix accessibility issues #73

#### Removed

* @import "fontawesome"; in css/scss/bootstrap.min.scss

#### Bugfix

* form-range in webkit browsers
* Rating stars in product loop
* Modal height and position if body has classes logged-in admin-bar
* Added missing jquery on new install in enqueue script #34
* Added redirect if offcanvas login failed #36
* Added redirect to my-account after registration #68
* Built a "filter" for terms.php #31
* Fixed class typo in header-woocommerce.php #9

#### Update

* scssphp 1.10.0 #66

= 5.1.3.0 - October 11 2021 =

#### Update

* Bootstrap 5.1.3

= 5.1.2.0 - October 06 2021 =

#### Improvement

* jQuery in header.php
* Added missing wp_body_open hook
* Changed offcanvas header <h5> to <span class="h5"> for SEO reason
* Compile scss when Bootstrap has been updated

#### Update

* Bootstrap 5.1.2

= 5.1.1.1 - September 29 2021 =

#### Feature

* Added Bootstrap source and compiler to theme
* Translation: Türkçe (thanks to Murat Esgin)

#### Improvement

* WooCommerce colors to variables
* Reformatted all files

= 5.1.1.0 - September 08 2021 =

#### Feature

* Renamed folder to bootscore-main (Github)
* Language: Pусский (thanks to Vladislav)

#### Improvement

* Ajax product notice alerts (ajax-add-to-cart.php, thanks to Martin Holzer)
* Close collapse if searchform loses focus
* Filter class card-img-top to product loop img (woocommerce-functions.php, woocommerce-style.css)
* Changed all WooCommerce colors to variables
* Bootstrap form validation in WooCommerce checkout
* Removed custom-validation from checkout checkboxes and readded form-row (terms.php, woocommerce-js, woocommerce-style.css)
* Refactored checkout (form-checkout.php, form-billing.php, form-shipping.php, Klarna checkout)
* pointer-events variation-add-to-cart-button.php (thanks to Martin Holzer)
* Searchform focus (theme.js, thanks to Martin Holzer)
* Readded and improved timestrap to scripts & styles (functions.php, woocommerce-functions.php, thanks to Martin Holzer)
* top-nav-search widget. Every widget can be shown there now theme.js, thanks to Martin Holzer)
* bootscore_pagination is a pluggable function now (functions.php, thanks to Martin Holzer)

#### Update

* Updated order-details-customer.php
* Updated terms.php (thanks to Emil Linden)
* Update Font Awesome Free 5.15.4
* Bootstrap 5.1.1

= 5.1.0.1 - August 11 2021 =

#### Improvement

* Added d-lg-none to search collapse (header.php)
* "current_page_parent" and "current-post-ancestor" to nav_walker, thanks Justin Kruit https://justinkruit.com
* Dutch translation, thanks Justin Kruit https://justinkruit.com

#### Removed

* Timestrap from enqueue styles and scripts (CSS overrides needed !important rule, functions.php, woocommerce-functions.php)

= 5.1.0.0 - August 05 2021 =

#### Removed

* Self-coded offcanvas navbar implementation (header.php, header-woocommerce.php, style.css, theme.js)

#### Update

* Uses Bootstrap 5.1.0 offcanvas navbar implementation now
* Bootstrap 5.1.0

= 5.0.2.5 - August 04 2021 =

#### Bugfix

* Disable Gutenberg blocks in widgets and enable classic mode. Widgets like search won't work (WordPress 5.8, functions.php).

= 5.0.2.4 - August 03 2021 =

#### Feature

* Added README.md
* Default Bootstrap offcanvas-header (header.php, header-woocommerce.php, mini-cart-php)
* GNU General Public License v2 to MIT License

#### Improvement

* Changed remove from cart button btn &times; to trash icon (cart.php, mini-cart.php)
* Fixed a.badge color by text-* class instead of CSS (style.css, template-tags.php)
* Breadcrumb padding and font-size (functions.php, woocommerce-functions.php)

#### Removed

* bootstrap.min.css.map and bootstrap.bundle.min.js.map

#### Bugfix

* .form-select (shipping-calculator.php Thnx Martin Holzer risingbytes.at)
* Keep offcanvas-user open on reload if contains login or register error alert (woocommerce.js)
* Error alert if login is failed (my-account-offcanvas.php, thanks Sean VanderMolen https://techpad.biz)
* Changed duplicate id's in offcanvas user (my-account-offcanvas.php, thanks cemmos https://github.com/craftwerkberlin/bootscore-5/issues/8)
* Add col-md-8 col-xxl-9 classes to post list in index.php (Thanks Tim Groeneveld, https://github.com/craftwerkberlin/bootscore-5/pull/7)

= 5.0.2.3 - July 14 2021 =

#### Feature

* Language: Čeština, thnx Petr Žaloudek https://webhned.eu

#### Improvement

* Cleanup Scroll To Top Button (style.css, footer.php)
* Search collapse button hide if empty removed by JS (theme.js, style.css)
* Focus on collapsed show searchform input
* header.php and header-woocommerce.php are completely new, more simple. Search button uses collapse component now instead of dropdown. Old header can still be used.

#### Feature

* Data attribute data-bs-hideresize="true". Close menu offcanvas in navbar on resize direct (theme.js, header.php, header-woocommerce.php)
* index-woocommerce.php
* wp-block-button reset (style.css)
* Quote block (style.css)

#### Bugfix

* Height modal fullscreen if .logged-in.admin-bar (style.css)
* Missing author and breadcrumb on single-full-width-image.php
* The “next page” pagination work properly on the 1st page (functions.php, thnx Mike Collignon, https://www.alox.co)
* Searchform can be placed in any widget positions without showing search button in navbar

= 5.0.2.2 - June 23 2021 =

#### Feature

* Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes. (Thnx Martin Holzer risingbytes.at)
* Added transition-delay to cart-loader to prevent flickering loading

#### Bugfix

* Added mt-4 to #reply-title (comment-list.php)

#### Update

* Language Deutsch (Du/Sie)
* Bootstrap 5.0.2

= 5.0.2.1 - June 05 2021 =

#### Improvement

* transition-delay on offcanvas-cart loader https://github.com/craftwerkberlin/bootscore-5/issues/6 (woocommerce-style.css, woocommerce.js, ajax-add-to-cart.php. Thnx Martin Holzer risingbytes.at)
* Tags is a complete pluggable function now (template-tags.php)
* Register comment-list.php is a pluggable function now. You can override file by register comment-list.php and place a copy in child .
* Nav Walker and Menu register are a complete pluggable function now. You can create your own menus and use a different nav walker by override them in child-theme.

= 5.0.2.0 - May 31 2021 =

#### Removed

* JavaScript workaround to set .active class to .nav-link (done by new nav-walker now)

#### Bugfix

* Added .w-100 class to offcanvas cart footer

#### Update

* All languages
* Dropdown menu slide animation (theme.js)
* Replaced modified Bootstrap 4 Nav Walker with Bootstrap 5 Nav Walker https://github.com/AlexWebLab/bootstrap-5-wordpress-navbar-walker

= 5.0.1.12 - May 28 2021 =

#### Bugfix

* Checkout Stripe validation (<li> payment-method.php, thnx John https://bootscore.me/documentation/woocommerce/#comment-3880)

#### Update

* Swedish language
* Changed WooCommerce comments with Bootstrap custom component (review.php, single-product-reviews.php, woocommerce-style.css)

= 5.0.1.11 - May 28 2021 =

#### Feature

* Translation: Svenska (thnx Emil Lindén)

#### Improvement

* Added .img-thumbnail.rounded-circle to avatar (comment-list.php, all author-*.php)

#### Removed

* Language backup files

#### Bugfix

* Two Factor Authentication support form-login.php. Does not work in offcanvas, must use my-account static page (Thnx cemmos, Github issue https://github.com/craftwerkberlin/bootscore-5/issues/5)

= 5.0.1.10 - May 16 2021 =

#### Bugfix

* Readded jQuery in header.php. Required if a 3rd party plugin loads jQuery in header instead in footer.

= 5.0.1.9 - May 16 2021 =

#### Bugfix

* Added missing close </div> tag in page-full-width-image.php and single-full-width-image.php (thnx Trishah Woolley)

= 5.0.1.8 - May 14 2021 =

#### Bugfix

* Typo in index.php (thnx Whitetower-lloyd, https://github.com/craftwerkberlin/bootscore-5/issues/4)

#### Update

* Bootstrap 5.0.1

= 5.0.1.7 - May 12 2021 =

#### Update

* grouped.php (WooCommerce 5.3.0)

= 5.0.1.6 - May 11 2021 =

#### Feature

* Added offcanvas-top to offcanvas navbar implementation (style.css)

#### Improvement

* Set offcanvas backdrop fade transition to .4s ease-in-out for smoother transition (style.css)

#### Removed

* Removed WP jQuery from header.php


= 5.0.1.5 - May 07 2021 =

#### Bugfix

* Added missing spaces in dutch translations
* Added [data-bs-popper] to search dropdown to override popper.js inline-style (Bootstrap 5 stable, dropdown could not be opened when using child-theme with bootstrap.min.css from parent theme, style.css). 

= 5.0.1.4 - May 06 2021 =

#### Feature

* .zi-n1 helper class to set negative z-index

#### Removed

* Bugfix custom checkboxes, radios, range, and select on Mac/iOS Safari if custom Sass compiled bootstrap.min.css is used (style.css, fixed in compiler settings)
* Workaround icon in offcanvas toggler https://github.com/twbs/bootstrap/issues/33457 (Bugfix Bootstrap 5.0.0, theme.js, woocommerce.js)
* Backdrop if navbar has class .fixed-top. Uses Bootstrap offcanvas backdrop now (style.css, theme.js)
* Offcanvas header align-items-center (style.css, Bootstrap included)

#### Bugfix

* text-decoration: none hover product card (woocommerce-style.css)

#### Update

* Bootstrap 5.0.0

= 5.0.1.3 - April 29 2021 =

#### Improvement

* Replaced col in all author-*.php with custom media-object component (author-*.php)
* Replaced col in comments with custom media-object component (comments-list.php, style.css)
* Changed alert-primary to alert-info (payment-methods.php)
* Removed aria-labelledby="" from offcanvas (thnx Mike Collignon)

#### Bugfix

* Moved offcanvas backdrop from .navbar.fixed-top to .fixed-top. Now it's possible to wrap the navbar in a fixed-top div.


= 5.0.1.2 - April 16 2021 =

#### removed

* backdrop-filter in image gallery caption (style.css)

#### Bugfix

* Custom checkboxes, radios, range, and select on Mac/iOS Safari if custom Sass compiled bootstrap.min.css is used (style.css)
* Footer menu class and id (twice, thnx Konstantinos Tzimas, footer.php)

= 5.0.1.1 - April 15 2021 =

#### Update

* mini-cart.php (WooCommerce 5.2.1)
* form-pay.php (WooCommerce 5.2.1)

= 5.0.1.0 - April 12 2021 =

#### Improvement

* Font size WooCommerce prices

#### Bugfix

* Remove autop (WP 5.7 bug near shortcode, style.css) https://wordpress.org/support/topic/how-to-stop-wp-from-adding-p-tag-automatically/
* Radios cart-shipping.php on devices below 768px
* Offcanvas cart footer (iOS)

#### Update

* Replaced all Offcanvas with Bootstrap component
* Bootstrap v5.0.0-beta3

= 5.0.0.10 - March 29 2021 =

#### Feature

* Translation: Français (thnx Mike Collignon)
  
#### Improvement

* Custom radios to shipping (cart-shipping.php, woocommerce-style.css)
* New checkout page (form-checkout.php, form-billing.php, form-shipping.php, thnx Mike Collignon)
* Close offcanvas cart and user dashboard on click <a> (woocommerce.js, not working MacOS Safari)
* Using Semantic Versioning https://semver.org

#### Bugfix

* Offcanvas user will not close when filling out the form (woocommerce.js, thnx Mike Collignon)
* Removed autop in theme.js again (Bug with CF7)

= 5.0.0.9 - March 17 2021 =

#### Feature

* Translation: Dutch, Dutch (Formal), (thnx Jan Revet)

#### Bugfix

* Missing line break tags in single-*.php


= 5.0.0.8 - March 14 2021 =

#### Feature

* Translation: Polski (thnx Marshall Reyher)

= 5.0.0.7 - March 13 2021 =

#### Improvement

* Changed theme description

#### Bugfix

* <pre> in comments will not crash columns anymore (comment-list.php, style.css)

= 5.0.0.6 - March 10 2021 =

#### Feature

* Add hover effect to offcanvas-header (style.css)

#### Bugfix

* Remove autop (WP 5.7, theme.js)

= 5.0.0.5 - March 08 2021 =

#### Feature

* Reusable .zi-10X0 classes for z-index

= 5.0.0.4 - March 04 2021 =

#### Improvement

* Removed position CSS on .dropdown-search.dropdown-menu, added Bootstrap classes start-0 and end-0 to display search in full browser-width (style.css, header.php). 

#### Bugfix

* Search dropdown on Android Chrome (theme.js)

= 5.0.0.3 - February 26 2021 =

#### Feature

* Translation: Português do Brasil (thnx Junio Jose)

#### Improvement

* Reorder CSS load (thnx Daniel Munoz Rey)

#### Bugfix

* margin undefined Github issue https://github.com/craftwerkberlin/bootscore-5/issues/1 (thnx djcowan)

= 5.0.0.2 - February 23 2021 =

#### Feature

* Hook after #primary in WooCommerce files
* Time updated separator has an own class to hide simply via CSS if updated time is not needed, template-tags.php.
* Removed Preloader from theme and made a plugin of it
* HTML Markups (Theme Unit Test Data)
* Translation: Italiano (thnx Domenico Carbone)

#### Improvement

* Merged all CSS in /woocommerce/css into one file woocommerce-style.css
* Links in comments opens in new tab with rel=”nofollow” attribute (pluggable function, functions.php)
* Add <link rel=preload> to Fontawesome (functions.php)
* Merged theme-header.js and theme.js to reduce file requests
* Moved all CSS from all.css to style.css to reduce file requests
* Load all JS in footer
* jQuery 3.5.1 ready

#### Bugfix

* Remove .active from all .nav-link if page is search-result
* Add overflow-x: hidden to body to hide horizontal scrollbars on Windows Chrome and Firefox if width-100 class is used.
* Workaround to highlight menu links when active. (theme.js, WP Bootstrap Navwalker is still v4)

= 5.0.0.1 - February 11 2021 =

#### Update

* Bootstrap 5.0.0-beta-2

= 5.0.0.0 - January 29 2021 =

* Initial release