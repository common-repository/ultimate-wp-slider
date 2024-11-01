<?php 

if( ! class_exists('Ultimate_WP_Slider_Shortcode')){
    class Ultimate_WP_Slider_Shortcode{
        public function __construct(){
            add_shortcode( 'ultimate_wp_slider', array( $this, 'add_shortcode' ) );
        }

        public function add_shortcode( $atts = array(), $content = null, $tag = '' ){

            $atts = array_change_key_case( (array) $atts, CASE_LOWER );

            extract( shortcode_atts(
                array(
                    'id' => '',
                    'orderby' => 'date'
                ),
                $atts,
                $tag
            ));

            if( !empty( $id ) ){
                $id = array_map( 'absint', explode( ',', $id ) );
            }
            
            ob_start();
            require( ULTIMATE_WP_SLIDER_PATH . 'views/ultimate-wp-slider_shortcode.php' );
            wp_enqueue_script( 'ultimate-wp-slider-main-jq' );
            wp_enqueue_style( 'ultimate-wp-slider-main-css' );
            wp_enqueue_style( 'ultimate-wp-slider-style-css' );
            ultimate_wp_slider_options();
            return ob_get_clean();
        }
    }
}
