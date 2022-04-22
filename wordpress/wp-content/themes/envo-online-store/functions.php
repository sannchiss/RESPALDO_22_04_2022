<?php
/**
 * The current version of the theme.
 */
define('ENVO_ONLINE_STORE_VERSION', '1.0.6');

add_action('after_setup_theme', 'envo_online_store_setup');

if (!function_exists('envo_online_store_setup')) :

    /**
     * Global functions
     */
    function envo_online_store_setup() {

        // Theme lang.
        load_theme_textdomain('envo-online-store', get_template_directory() . '/languages');

        // Add Title Tag Support.
        add_theme_support('title-tag');

        // Register Menus.
        register_nav_menus(
                array(
                    'main_menu' => esc_html__('Main Menu', 'envo-online-store'),
                )
        );

        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(300, 300, true);
        add_image_size('envo-online-store-single', 1140, 641, true);
        add_image_size('envo-online-store-med', 720, 405, true);

        // Add Custom Background Support.
        $args = array(
            'default-color' => 'ffffff',
        );
        add_theme_support('custom-background', $args);

        add_theme_support('custom-logo', array(
            'height' => 60,
            'width' => 200,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => array('site-title', 'site-description'),
        ));

        // Adds RSS feed links to for posts and comments.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         */
        add_theme_support('title-tag');

        // Set the default content width.
        $GLOBALS['content_width'] = 1140;

        add_theme_support('custom-header', apply_filters('envo_online_store_custom_header_args', array(
            'width' => 2000,
            'height' => 500,
            'default-text-color' => '',
            'wp-head-callback' => 'envo_online_store_header_style',
            'default-image' 		=> esc_url( get_template_directory_uri() ) .'/img/bg.jpg',
        )));

        // WooCommerce support.
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        add_theme_support('html5', array('search-form'));
        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style(array('css/bootstrap.css', envo_online_store_fonts_url(), 'css/editor-style.css'));

        // Recommend plugins.
        add_theme_support('recommend-plugins', array(
            'envothemes-demo-import' => array(
                'name' => 'EnvoThemes Demo Import',
                'active_filename' => 'envothemes-demo-import/envothemes-demo-import.php',
                'description' => esc_html__('Save time by importing our demo data: your website will be set up and ready to be customized in minutes.', 'envo-online-store'),
            ),
            'woocommerce' => array(
                'name' => 'WooCommerce',
                'active_filename' => 'woocommerce/woocommerce.php',
                /* translators: %s plugin name string */
                'description' => sprintf(esc_attr__('To enable shop features, please install and activate the %s plugin.', 'envo-online-store'), '<strong>WooCommerce</strong>'),
            ),
            'elementor' => array(
                'name' => 'Elementor',
                'active_filename' => 'elementor/elementor.php',
                /* translators: %s plugin name string */
                'description' => sprintf(esc_attr__('The most advanced frontend drag & drop page builder.', 'envo-online-store'), '<strong>Elementor</strong>'),
            ),
        ));
    }

endif;

if (!function_exists('envo_online_store_header_style')) :

    /**
     * Styles the header image and text displayed on the blog.
     */
    function envo_online_store_header_style() {
        $header_image = get_header_image();
        $header_text_color = get_header_textcolor();
        if (get_theme_support('custom-header', 'default-text-color') !== $header_text_color || !empty($header_image)) {
            ?>
            <style type="text/css" id="envo-online-store-header-css">
            <?php
// Has a Custom Header been added?
            if (!empty($header_image)) :
                ?>
                    .site-header {
                        background-image: url(<?php header_image(); ?>);
                        background-repeat: no-repeat;
                        background-position: 50% 50%;
                        -webkit-background-size: cover;
                        -moz-background-size:    cover;
                        -o-background-size:      cover;
                        background-size:         cover;
                    }
            <?php endif; ?>	
            <?php
// Has the text been hidden?
            if ('blank' === $header_text_color) :
                ?>
                    .site-title,
                    .site-description {
                        position: absolute;
                        clip: rect(1px, 1px, 1px, 1px);
                    }
            <?php elseif ('' !== $header_text_color) : ?>
                    .site-title a, 
                    .site-title, 
                    .site-description {
                        color: #<?php echo esc_attr($header_text_color); ?>;
                    }
            <?php endif; ?>	
            </style>
            <?php
        }
    }

endif; // envo_online_store_header_style

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function envo_online_store_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">' . "\n", esc_url(get_bloginfo('pingback_url')));
    }
}

add_action('wp_head', 'envo_online_store_pingback_header');

/**
 * Set Content Width
 */
function envo_online_store_content_width() {

    $content_width = $GLOBALS['content_width'];

    if (is_active_sidebar('envo-online-store-right-sidebar')) {
        $content_width = 750;
    } else {
        $content_width = 1040;
    }

    /**
     * Filter content width of the theme.
     */
    $GLOBALS['content_width'] = apply_filters('envo_online_store_content_width', $content_width);
}

add_action('template_redirect', 'envo_online_store_content_width', 0);

/**
 * Register custom fonts.
 */
function envo_online_store_fonts_url() {
    $fonts_url = '';

    /**
     * Translators: If there are characters in your language that are not
     * supported by Open Sans Condensed, translate this to 'off'. Do not translate
     * into your own language.
     */
    $font = _x('on', 'Montserrat font: on or off', 'envo-online-store');

    if ('off' !== $font) {
        $font_families = array();

        $font_families[] = 'Montserrat:300,500,700';

        $query_args = array(
            'family' => urlencode(implode('|', $font_families)),
            'subset' => urlencode('cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese'),
        );

        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
    }

    return esc_url_raw($fonts_url);
}

/**
 * Add preconnect for Google Fonts.
 */
function envo_online_store_resource_hints($urls, $relation_type) {
    if (wp_style_is('envo-online-store-fonts', 'queue') && 'preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}

add_filter('wp_resource_hints', 'envo_online_store_resource_hints', 10, 2);

/**
 * Enqueue Styles (normal style.css and bootstrap.css)
 */
function envo_online_store_theme_stylesheets() {
    // Add custom fonts, used in the main stylesheet.
    wp_enqueue_style('envo-online-store-fonts', envo_online_store_fonts_url(), array(), null);
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '3.3.7');
    wp_enqueue_style('mmenu-light', get_template_directory_uri() . '/css/mmenu-light.min.css', array(), ENVO_ONLINE_STORE_VERSION);
    // Theme stylesheet.
    wp_enqueue_style('envo-online-store-stylesheet', get_stylesheet_uri(), array('bootstrap'), ENVO_ONLINE_STORE_VERSION);
    // Load Font Awesome css.
    wp_enqueue_style('line-awesome', get_template_directory_uri() . '/css/line-awesome.min.css', array(), '1.3.0');
}

add_action('wp_enqueue_scripts', 'envo_online_store_theme_stylesheets');

/**
 * Register Bootstrap JS with jquery
 */
function envo_online_store_theme_js() {
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.3.7', true);
    wp_enqueue_script('envo-online-store-theme-js', get_template_directory_uri() . '/js/customscript.js', array('jquery'), ENVO_ONLINE_STORE_VERSION, true);
    wp_enqueue_script('mmenu', get_template_directory_uri() . '/js/mmenu-light.min.js', array('jquery'), ENVO_ONLINE_STORE_VERSION, true);
}

add_action('wp_enqueue_scripts', 'envo_online_store_theme_js');

if (!function_exists('envo_online_store_is_pro_activated')) {

    /**
     * Query Envo Online Store activation
     */
    function envo_online_store_is_pro_activated() {
        return defined('ENVO_ONLINE_STORE_PRO_CURRENT_VERSION') ? true : false;
    }

}

if (!function_exists('envo_online_store_title_logo')) {

    /**
     * Title, logo code
     */
    function envo_online_store_title_logo() {
        ?>
        <div class="site-branding-logo">
            <?php the_custom_logo(); ?>
        </div>
        <div class="site-branding-text">
            <?php if (is_front_page()) : ?>
                <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
            <?php else : ?>
                <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
            <?php endif; ?>

            <?php
            $description = get_bloginfo('description', 'display');
            if ($description || is_customize_preview()) :
                ?>
                <p class="site-description">
                    <?php echo esc_html($description); ?>
                </p>
            <?php endif; ?>
        </div><!-- .site-branding-text -->
        <?php
    }

}
/**
 * Register Custom Navigation Walker include custom menu widget to use walkerclass
 */
require_once( trailingslashit(get_template_directory()) . 'lib/wp_bootstrap_navwalker.php' );

/**
 * Register Theme Info Page
 */
require_once( trailingslashit(get_template_directory()) . 'lib/dashboard.php' );

/**
 * Customizer options
 */
require_once( trailingslashit(get_template_directory()) . 'lib/customizer.php' );

if (class_exists('WooCommerce')) {

    /**
     * WooCommerce options
     */
    require_once( trailingslashit(get_template_directory()) . 'lib/woocommerce.php' );
}

add_action('widgets_init', 'envo_online_store_widgets_init');

/**
 * Register the Sidebar(s)
 */
function envo_online_store_widgets_init() {
    register_sidebar(
            array(
                'name' => esc_html__('Sidebar', 'envo-online-store'),
                'id' => 'envo-online-store-right-sidebar',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-title"><h3>',
                'after_title' => '</h3></div>',
            )
    );
    register_sidebar(
            array(
                'name' => esc_html__('Top Bar Section', 'envo-online-store'),
                'id' => 'envo-online-store-top-bar-area',
                'before_widget' => '<div id="%1$s" class="widget %2$s col-sm-4">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-title"><h3>',
                'after_title' => '</h3></div>',
            )
    );
    register_sidebar(
            array(
                'name' => esc_html__('Footer Section', 'envo-online-store'),
                'id' => 'envo-online-store-footer-area',
                'before_widget' => '<div id="%1$s" class="widget %2$s col-md-3">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-title"><h3>',
                'after_title' => '</h3></div>',
            )
    );
}

function envo_online_store_main_content_width_columns() {

    $columns = '12';

    if (is_active_sidebar('envo-online-store-right-sidebar')) {
        $columns = $columns - 3;
    }

    echo absint($columns);
}

if (!function_exists('envo_online_store_entry_footer')) :

    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function envo_online_store_entry_footer() {

        // Get Categories for posts.
        $categories_list = get_the_category_list(' ');

        // Get Tags for posts.
        $tags_list = get_the_tag_list('', ' ');

        // We don't want to output .entry-footer if it will be empty, so make sure its not.
        if ($categories_list || $tags_list || get_edit_post_link()) {

            echo '<div class="entry-footer">';

            if ('post' === get_post_type()) {
                if ($categories_list || $tags_list) {

                    // Make sure there's more than one category before displaying.
                    if ($categories_list) {
                        echo '<div class="cat-links"><span class="space-right">' . esc_html__('Category', 'envo-online-store') . '</span>' . wp_kses_data($categories_list) . '</div>';
                    }

                    if ($tags_list) {
                        echo '<div class="tags-links"><span class="space-right">' . esc_html__('Tags', 'envo-online-store') . '</span>' . wp_kses_data($tags_list) . '</div>';
                    }
                }
            }

            edit_post_link();

            echo '</div>';
        }
    }

endif;

if (!function_exists('envo_online_store_generate_construct_footer')) :
    /**
     * Build footer
     */
    add_action('envo_online_store_generate_footer', 'envo_online_store_generate_construct_footer');

    function envo_online_store_generate_construct_footer() {
        ?>
        <div class="footer-credits-text text-center">
            <?php
            /* translators: %s: WordPress name with wordpress.org URL */
            printf(esc_html__('Proudly powered by %s', 'envo-online-store'), '<a href="' . esc_url(__('https://wordpress.org/', 'envo-online-store')) . '">' . esc_html__('WordPress', 'envo-online-store') . '</a>');
            ?>
            <span class="sep"> | </span>
            <?php
            /* translators: %1$s: Envo Online Store theme name (do not translate) with envothemes.com URL */
            printf(esc_html__('Theme: %1$s', 'envo-online-store'), '<a href="' . esc_url('https://envothemes.com/free-envo-online-store/') . '">' . esc_html_x('Envo Online Store', 'Theme name, do not translate', 'envo-online-store') . '</a>');
            ?>
        </div> 
        <?php
    }

endif;


if (!function_exists('envo_online_store_widget_date')) :

    /**
     * Returns date for widgets.
     */
    function envo_online_store_widget_date() {
        ?>
        <span class="posted-date">
            <?php echo esc_html(get_the_date()); ?>
        </span>
        <?php
    }

endif;

if (!function_exists('envo_online_store_widget_comments')) :

    /**
     * Returns date for widgets.
     */
    function envo_online_store_widget_comments() {
        ?>
        <span class="comments-meta">
            <?php
            if (!comments_open()) {
                esc_html_e('Off', 'envo-online-store');
            } else {
                ?>
                <a href="<?php the_permalink(); ?>#comments" rel="nofollow" title="<?php esc_attr_e('Comment on ', 'envo-online-store') . the_title_attribute(); ?>">
                    <?php echo absint(get_comments_number()); ?>
                </a>
            <?php } ?>
            <i class="la la-comments-o"></i>
        </span>
        <?php
    }

endif;

if (!function_exists('envo_online_store_excerpt_length')) :

    /**
     * Excerpt limit.
     */
    function envo_online_store_excerpt_length($length) {
        return 35;
    }

    add_filter('excerpt_length', 'envo_online_store_excerpt_length', 999);

endif;

if (!function_exists('envo_online_store_excerpt_more')) :

    /**
     * Excerpt more.
     */
    function envo_online_store_excerpt_more($more) {
        return '&hellip;';
    }

    add_filter('excerpt_more', 'envo_online_store_excerpt_more');

endif;

if (!function_exists('envo_online_store_thumb_img')) :

    /**
     * Returns widget thumbnail.
     */
    function envo_online_store_thumb_img($img = 'full', $col = '', $link = true, $single = false) {
        if (function_exists('envo_online_store_pro_thumb_img')) {
            envo_online_store_pro_thumb_img($img, $col, $link, $single);
        } elseif (( has_post_thumbnail() && $link == true)) {
            ?>
            <div class="news-thumb <?php echo esc_attr($col); ?>">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <?php the_post_thumbnail($img); ?>
                </a>
            </div><!-- .news-thumb -->
        <?php } elseif (has_post_thumbnail()) { ?>
            <div class="news-thumb <?php echo esc_attr($col); ?>">
                <?php the_post_thumbnail($img); ?>
            </div><!-- .news-thumb -->	
            <?php
        }
    }

endif;

/**
 * Single previous next links
 */
if (!function_exists('envo_online_store_prev_next_links')) :

    function envo_online_store_prev_next_links() {
        the_post_navigation(
                array(
                    'prev_text' => '<span class="screen-reader-text">' . __('Previous Post', 'envo-online-store') . '</span><span aria-hidden="true" class="nav-subtitle">' . __('Previous', 'envo-online-store') . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper"><i class="la la-angle-double-left" aria-hidden="true"></i></span>%title</span>',
                    'next_text' => '<span class="screen-reader-text">' . __('Next Post', 'envo-online-store') . '</span><span aria-hidden="true" class="nav-subtitle">' . __('Next', 'envo-online-store') . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper"><i class="la la-angle-double-right" aria-hidden="true"></i></span></span>',
                )
        );
    }

endif;

/**
 * Post author meta funciton
 */
if (!function_exists('envo_online_store_author_meta')) :

    function envo_online_store_author_meta() {
        global $post;
        $author_id = $post->post_author;
        ?>
        <span class="author-meta">
            <span class="author-meta-by"><?php esc_html_e('By', 'envo-online-store'); ?></span>   
            <?php echo esc_html( get_the_author_meta('display_name', $author_id) ); ?>
        </span>
        <?php
    }

endif;

if (!function_exists('wp_body_open')) :

    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
     *
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         *
         */
        do_action('wp_body_open');
    }

endif;

/**
 * Skip to content link
 */
function envo_online_store_skip_link() {
    echo '<a class="skip-link screen-reader-text" href="#site-content">' . esc_html__('Skip to the content', 'envo-online-store') . '</a>';
}

add_action('wp_body_open', 'envo_online_store_skip_link', 5);

/**
 * Header image function
 */
if (!function_exists('envo_online_store_header_image')) :

    function envo_online_store_header_image($post_id = null) {
        $post = get_queried_object_id();
        $thumbnail_id = get_post_thumbnail_id($post);
        if (is_singular(array('post', 'page')) && has_post_thumbnail($post)) {
            echo esc_url( wp_get_attachment_url($thumbnail_id) );
        } else {
            return header_image();
        }
    }

endif;

/**
 * Header image title
 */
if (!function_exists('envo_online_store_header_title')) :

    function envo_online_store_header_title() {

        //if (is_singular( array( 'post', 'page'))) {
        echo '<div class="head-image-heading">';
        envo_online_store_generate_title();
        if (is_singular( array( 'post', 'page'))) {
            envo_online_store_widget_date();
            envo_online_store_author_meta();
            envo_online_store_widget_comments();
        }
        if (function_exists('yoast_breadcrumb') && (!is_page_template('template-parts/template-page-builders.php') && !is_home() && !is_front_page() )) {
            yoast_breadcrumb('<p id="breadcrumbs" class="text-center">', '</p>');
        }
        echo '</div>';
        //} 
    }

endif;

/**
 * Generate title
 */
if (!function_exists('envo_online_store_generate_title')) :

    function envo_online_store_generate_title() {
        if (is_home() || is_front_page()) {
            echo '<div class="page-header-title text-center"><h1>';
            single_post_title();
            echo '</h1></div>';
        } elseif (is_archive()) {
            echo '<div class="archive-page-header text-center">';
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="taxonomy-description">', '</div>');
            echo '</div>';
        } elseif (is_404()) {
            echo '<div class="error-template text-center"><h1>';
            esc_html_e('Oops! That page can&rsquo;t be found.', 'envo-online-store');
            echo '</h1></div>';
        } elseif (is_search()) {
            /* translators: %s: search result string */
            echo "<h1 class='search-head text-center'>" . sprintf(esc_html__('Search Results for: %s', 'envo-online-store'), get_search_query()) . "</h1>";
        } else {
            echo '<h1 class="single-title">' . esc_html(get_the_title()) . '</h1>';
        }
    }

endif;

/**
 * Generate header image
 */
if (!function_exists('envo_online_store_generate_header_image')) :

    function envo_online_store_generate_header_image() {
        if (is_home() || is_front_page() || is_singular(array('page', 'post') ) || is_search() || is_404() || is_archive() ) {
            ?>
            <div class="site-header container-fluid" style="background-image: url(<?php esc_url(envo_online_store_header_image()); ?>)">
                <div class="overlay"></div>
                <?php envo_online_store_header_title(); ?>
            </div>
            <?php
        }
    }

endif;