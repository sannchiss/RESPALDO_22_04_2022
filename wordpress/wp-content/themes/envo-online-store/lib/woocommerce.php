<?php
if (!function_exists('envo_online_store_cart_link')) {

    function envo_online_store_cart_link() {
        ?>	
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" data-tooltip="<?php esc_attr_e('Cart', 'envo-online-store'); ?>" title="<?php esc_attr_e('Cart', 'envo-online-store'); ?>">
            <i class="la la-shopping-bag"><span class="count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span></i>
            <div class="amount-cart hidden-xs"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></div> 
        </a>
        <?php
    }

}

if (!function_exists('envo_online_store_header_cart')) {

    function envo_online_store_header_cart() {
        if (get_theme_mod('woo_header_cart', 1) == 1) {
            ?>
            <div class="header-cart">
                <div class="header-cart-block">
                    <div class="header-cart-inner">
                        <?php envo_online_store_cart_link(); ?>
                        <ul class="site-header-cart menu list-unstyled text-center">
                            <li>
                                <?php the_widget('WC_Widget_Cart', 'title='); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }
    }

}

if (!function_exists('envo_online_store_header_add_to_cart_fragment')) {
    add_filter('woocommerce_add_to_cart_fragments', 'envo_online_store_header_add_to_cart_fragment');

    function envo_online_store_header_add_to_cart_fragment($fragments) {
        ob_start();

        envo_online_store_cart_link();

        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }

}

if (!function_exists('envo_online_store_my_account')) {

    function envo_online_store_my_account() {
        if (get_theme_mod('woo_account', 1) == 1) {
            ?>
            <div class="header-my-account">
                <div class="header-login"> 
                    <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" data-tooltip="<?php esc_attr_e('My Account', 'envo-online-store'); ?>" title="<?php esc_attr_e('My Account', 'envo-online-store'); ?>">
                        <i class="la la-user"></i>
                    </a>
                </div>
            </div>
            <?php
        }
    }

}
add_action('woocommerce_before_add_to_cart_quantity', 'envo_online_store_display_quantity_minus');

function envo_online_store_display_quantity_minus() {
    global $product;
    if ( ($product->get_stock_quantity() > 1 && !$product->managing_stock() ) || !$product->is_sold_individually( ) ) {
        echo '<button type="button" class="minus" >-</button>';
    }
}

add_action('woocommerce_after_add_to_cart_quantity', 'envo_online_store_display_quantity_plus');

function envo_online_store_display_quantity_plus() {
    global $product;
    if ( ($product->get_stock_quantity() > 1 && !$product->managing_stock() ) || !$product->is_sold_individually( ) ) {
        echo '<button type="button" class="plus" >+</button>';
    }
}

if (!function_exists('envo_online_store_head_wishlist')) {

    function envo_online_store_head_wishlist() {
        if (function_exists('YITH_WCWL')) {
            $wishlist_url = YITH_WCWL()->get_wishlist_url();
            ?>
            <div class="header-wishlist">
                <a href="<?php echo esc_url($wishlist_url); ?>" data-tooltip="<?php esc_attr_e('Wishlist', 'envo-online-store'); ?>" title="<?php esc_attr_e('Wishlist', 'envo-online-store'); ?>">
                    <i class="lar la-heart"></i>
                </a>
            </div>
            <?php
        }
    }

}

if (!function_exists('envo_online_store_head_compare')) {

    function envo_online_store_head_compare() {
        if (function_exists('yith_woocompare_constructor')) {
            global $yith_woocompare;
            ?>
            <div class="header-compare product">
                <a class="compare added" rel="nofollow" href="<?php echo esc_url($yith_woocompare->obj->view_table_url()); ?>" data-tooltip="<?php esc_attr_e('Compare', 'envo-online-store'); ?>" title="<?php esc_attr_e('Compare', 'envo-online-store'); ?>">
                    <i class="la la-sync"></i>
                </a>
            </div>
            <?php
        }
    }

}

if (!function_exists('envo_online_store_head_search')) {

    function envo_online_store_head_search() {
        if (get_theme_mod('woo_search', 1) == 1) {
            ?>
            <div class="header-search">
                <a id="header-search-icon" href="#" data-tooltip="<?php esc_attr_e('Search', 'envo-online-store'); ?>" title="<?php esc_attr_e('Search', 'envo-online-store'); ?>">
                    <i class="la la-search"></i>
                </a>   
                <div class="search-heading">
                    <div class="header-search-form">
                        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                            <input type="hidden" name="post_type" value="product" />
                            <input class="header-search-input" name="s" type="text" placeholder="<?php esc_attr_e('Search products...', 'envo-online-store'); ?>"/>
                            <button class="header-search-button" type="submit"><i class="la la-search" aria-hidden="true"></i></button>
                        </form>
                    </div>
                </div>
            </div>    
            <?php
        }
    }

}

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

add_action('woocommerce_before_main_content', 'envo_online_store_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'envo_online_store_wrapper_end', 10);

function envo_online_store_wrapper_start() {
    ?>
    <div id="site-content" class="container main-container" role="main">
        <div class="page-area">
            <!-- start content container -->
            <div class="row">
                <article class="col-md-<?php envo_online_store_main_content_width_columns(); ?>">
    <?php
}

function envo_online_store_wrapper_end() {
    ?>
            </article>       
        <?php get_sidebar('right'); ?>
    </div>
    <!-- end content container -->

    <?php
}

        