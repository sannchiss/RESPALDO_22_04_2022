<?php
/**
 * Envo Online Store Theme Customizer
 *
 * @package Envo Online Store
 */

$envo_online_store_sections = array( 'info', 'demo' );

foreach( $envo_online_store_sections as $section ){
    require get_template_directory() . '/lib/customizer/' . $section . '.php';
}

function envo_online_store_customizer_scripts() {
    wp_enqueue_style( 'envo-online-store-customize',get_template_directory_uri().'/lib/customizer/css/customize.css', '', 'screen' );
    wp_enqueue_script( 'envo-online-store-customize', get_template_directory_uri() . '/lib/customizer/js/customize.js', array( 'jquery' ), '20170404', true );
}
add_action( 'customize_controls_enqueue_scripts', 'envo_online_store_customizer_scripts' );
