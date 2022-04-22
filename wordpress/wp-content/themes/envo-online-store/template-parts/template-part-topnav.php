<?php if (is_active_sidebar('envo-online-store-top-bar-area')) { ?>
    <div class="top-bar-section container-fluid">
        <div class="<?php echo esc_attr(get_theme_mod('top_bar_content_width', 'container')); ?>">
            <div class="row">
                <?php dynamic_sidebar('envo-online-store-top-bar-area'); ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php do_action('envo_online_store_before_menu'); ?> 
<div class="main-menu">
    <nav id="site-navigation" class="navbar navbar-default">     
        <div class="container">   
            <div class="navbar-header">
                <div class="site-heading mobile-heading" >
                    <?php envo_online_store_title_logo(); ?>
                </div>
            </div>
                <div class="woo-head-icons">
                    <?php if (function_exists('envo_online_store_header_cart') && class_exists('WooCommerce')) { ?>
                        <div class="header-icons-cart header-icons" >
                            <?php envo_online_store_header_cart(); ?>
                        </div>	
                    <?php } ?>
                    <?php if (function_exists('envo_online_store_my_account') && class_exists('WooCommerce')) { ?>
                        <div class="header-icons-account header-icons" >
                            <?php envo_online_store_my_account(); ?>
                        </div>
                    <?php } ?>
                    <?php if (function_exists('envo_online_store_head_wishlist') && class_exists('WooCommerce')) { ?>
                        <div class="header-icons-wishlist header-icons" >
                            <?php envo_online_store_head_wishlist(); ?>
                        </div>
                    <?php } ?>
                    <?php if (function_exists('envo_online_store_head_compare') && class_exists('WooCommerce')) { ?>
                        <div class="header-icons-compare header-icons" >
                            <?php envo_online_store_head_compare(); ?>
                        </div>
                    <?php } ?>
                    <?php if (function_exists('envo_online_store_head_search') && class_exists('WooCommerce')) { ?>
                        <div class="header-icons-search header-icons" >
                            <?php envo_online_store_head_search(); ?>
                        </div>
                    <?php } ?>
                    <?php if (function_exists('max_mega_menu_is_enabled') && max_mega_menu_is_enabled('main_menu')) : ?>
                    </div>
                    <?php else : ?>
                        <a href="#my-menu" id="main-menu-panel" class="open-panel" data-panel="main-menu-panel">
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                        <span class="navbar-brand brand-absolute visible-xs"><?php esc_html_e('Menu', 'envo-online-store'); ?></span>
                    <?php endif; ?>
            <?php           
            wp_nav_menu(array(
                'theme_location' => 'main_menu',
                'depth' => 5,
                'container_id' => 'my-menu',
                'container' => 'div',
                'container_class' => 'menu-container',
                'menu_class' => 'nav navbar-nav navbar-left',
                'fallback_cb' => 'Envo_Shop_WP_Bootstrap_Navwalker::fallback',
                'walker' => new Envo_Shop_WP_Bootstrap_Navwalker(),
            ));
            ?>
        </div>
        <?php do_action('envo_online_store_menu'); ?>
    </nav> 
</div>
<?php do_action('envo_online_store_after_menu'); ?>
