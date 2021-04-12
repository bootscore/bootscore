<?php
	/**
	 * The header for our WooCommerce theme
	 *
	 * This is the template that displays all of the <head> section and everything up until <div id="content">
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
	 *
	 * @package Bootscore
	 */
	
	?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri();?>/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_stylesheet_directory_uri();?>/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_stylesheet_directory_uri();?>/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_stylesheet_directory_uri();?>/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?php echo get_stylesheet_directory_uri();?>/img/favicon/safari-pinned-tab.svg" color="#0d6efd">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Loads the internal WP jQuery -->
    <?php wp_enqueue_script('jquery'); ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <div id="to-top"></div>

    <div id="page" class="site">

        <header id="masthead" class="site-header">

            <nav id="nav-main" class="navbar navbar-expand-lg navbar-light bg-light fixed-top">

                <div class="container">

                    <button class="navbar-toggler border-0 focus-0 py-2 ps-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-navbar" aria-controls="offcanvas-navbar">
                        <i class="text-secondary fas fa-bars"></i>
                    </button>

                    <!-- Top Nav Search Mobile -->
                    <div class="top-nav-search-md d-lg-none ms-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary" type="button" id="dropdown-search" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-search"></i>
                            </button>
                            <div class="dropdown-search dropdown-menu position-fixed border-0 bg-light rounded-0 start-0 end-0" aria-labelledby="dropdown-search">
                                <div class="container">
                                    <?php if ( is_active_sidebar( 'top-nav-search' )) : ?>
                                    <div class="mb-2">
                                        <?php dynamic_sidebar( 'top-nav-search' ); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a class="navbar-brand d-md-none" href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/img/logo/logo-sm.svg" alt="logo" class="logo sm"></a>
                    <a class="navbar-brand d-none d-md-block" href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/img/logo/logo.svg" alt="logo" class="logo md"></a>

                    <!-- Top Nav Widget -->
                    <div class="top-nav order-lg-1 flex-grow-1 flex-lg-grow-0 d-flex justify-content-start">
                        <?php if ( is_active_sidebar( 'top-nav' )) : ?>
                        <div>
                            <?php dynamic_sidebar( 'top-nav' ); ?>
                        </div>
                        <?php endif; ?>
                    </div>


                    <div class="offcanvas offcanvas-start" tabindex="-1" data-bs-hideresize="true" id="offcanvas-navbar" aria-labelledby="offcanvas-navbarLabel">
                        <div class="offcanvas-header cursor-pointer hover bg-light text-primary" data-bs-dismiss="offcanvas">
                            <?php esc_html_e('Close menu' , 'bootscore'); ?> <i class="fas fa-chevron-right"></i>
                        </div>
                        <div class="offcanvas-body">
                            <!-- Wp Bootstrap Nav Walker -->
                            <?php
                                wp_nav_menu( array(
                                    'theme_location'    => 'primary',
                                    'depth'             => 2,
                                    'container'         => 'div',
                                    'container_class'   => 'ms-auto',
                                    'container_id'      => 'bootscore-navbar',
                                    'menu_class'        => 'nav navbar-nav justify-content-end',
                                    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                                    'walker'            => new WP_Bootstrap_Navwalker(),
                                ) );
                            ?>
                        </div>
                    </div>

                    <!-- Top Nav Search lg -->
                    <div class="top-nav-search-lg d-none d-lg-block ms-2 order-lg-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary" type="button" id="dropdown-search" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-search"></i>
                            </button>
                            <div class="dropdown-search dropdown-menu position-fixed border-0 bg-light rounded-0 start-0 end-0" aria-labelledby="dropdown-search">
                                <div class="container">
                                    <?php if ( is_active_sidebar( 'top-nav-search' )) : ?>
                                    <div class="mb-2">
                                        <?php dynamic_sidebar( 'top-nav-search' ); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <button class="user-toggler right btn btn-outline-secondary ms-2 order-lg-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-user" aria-controls="offcanvas-user">
                        <i class="fas fa-user"></i>
                    </button>

                    <button class="cart-toggler right btn btn-outline-secondary ms-2 order-lg-3 position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-cart" aria-controls="offcanvas-cart">
                        <i class="fas fa-shopping-bag"></i>
                        <?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				            $count = WC()->cart->cart_contents_count;
				        ?>
                        <span class="cart-content">
                            <?php if ( $count > 0 ) { ?>
                            <?php echo esc_html( $count ); ?>
                            <?php
				                }
				            ?></span>
                        <?php } ?>
                    </button>

                </div><!-- container -->

            </nav>

            <!-- offcanvas user -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-user" aria-labelledby="offcanvas-userLabel">
                <div class="offcanvas-header cursor-pointer hover bg-light text-primary" data-bs-dismiss="offcanvas">
                    <?php esc_html_e('Close account' , 'bootscore'); ?> <i class="fas fa-chevron-right"></i>
                </div>
                <div class="offcanvas-body">
                    <div class="my-offcancas-account">
                        <?php include get_template_directory() . '/woocommerce/myaccount/my-account-offcanvas.php'; ?>
                    </div>
                </div>
            </div>

            <!-- offcanvas cart -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-cart" aria-labelledby="offcanvas-cartLabel">
                <div class="offcanvas-header cursor-pointer hover bg-light text-primary" data-bs-dismiss="offcanvas">
                    <i class="fas fa-chevron-left"></i> <?php esc_html_e('Continue shopping' , 'bootscore'); ?>
                </div>
                <div class="offcanvas-body p-0">
                    <div class="cart-loader bg-white position-absolute end-0 bottom-0 start-0 d-flex align-items-center justify-content-center">
                        <div class="loader-icon ">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                    <div class="cart-list">
                        <h2 class="p-3"><?php esc_html_e('Cart' , 'bootscore'); ?></h2>
                        <div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
                    </div>
                </div>
            </div>

        </header><!-- #masthead -->

        <?php bootscore_ie_alert(); ?>
