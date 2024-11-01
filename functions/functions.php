<?php
if( ! function_exists( 'ultimate_wp_slider_get_placeholder_image' )){
    function ultimate_wp_slider_get_placeholder_image(){
        return "<img src='" . ULTIMATE_WP_SLIDER_URL . "assets/images/default.jpg' class='img-fluid wp-post-image' />";
    }
}

if( ! function_exists( 'ultimate_wp_slider_options' )){
    function ultimate_wp_slider_options(){
        $show_bullets = isset( Ultimate_WP_Slider_Settings::$options['ultimate_wp_slider_bullets'] ) && Ultimate_WP_Slider_Settings::$options['ultimate_wp_slider_bullets'] == 1 ? true : false;

        wp_enqueue_script( 'ultimate-wp-slider-options-js', ULTIMATE_WP_SLIDER_URL . 'vendor/flexslider/flexslider.js', array( 'jquery' ), ULTIMATE_WP_SLIDER_VERSION, true );
        wp_localize_script( 'ultimate-wp-slider-options-js', 'SLIDER_OPTIONS', array(
            'controlNav' => $show_bullets
        ) );
    }
}
