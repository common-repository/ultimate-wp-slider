<?php 

if( ! class_exists( 'Ultimate_WP_Slider_Settings' )){
    class Ultimate_WP_Slider_Settings{

        public static $options;

        public function __construct(){
            self::$options = get_option( 'ultimate_wp_slider_options' );
            add_action( 'admin_init', array( $this, 'admin_init') );
        }

        public function admin_init(){
            
            register_setting( 'ultimate_wp_slider_group', 'ultimate_wp_slider_options', array( $this, 'ultimate_wp_slider_validate' ) );

            add_settings_section(
                'ultimate_wp_slider_main_section',
                esc_html__( 'How does it work?', 'ultimate-wp-slider' ),
                null,
                'ultimate_wp_slider_page1'
            );

            add_settings_section(
                'ultimate_wp_slider_second_section',
                esc_html__( 'Other Plugin Options', 'ultimate-wp-slider' ),
                null,
                'ultimate_wp_slider_page2'
            );

            add_settings_field(
                'ultimate_wp_slider_shortcode',
                esc_html__( 'Shortcode', 'ultimate-wp-slider' ),
                array( $this, 'ultimate_wp_slider_shortcode_callback' ),
                'ultimate_wp_slider_page1',
                'ultimate_wp_slider_main_section'
            );

            add_settings_field(
                'ultimate_wp_slider_title',
                esc_html__( 'Slider Title', 'ultimate-wp-slider' ),
                array( $this, 'ultimate_wp_slider_title_callback' ),
                'ultimate_wp_slider_page2',
                'ultimate_wp_slider_second_section',
                array(
                    'label_for' => 'ultimate_wp_slider_title'
                )
            );

            add_settings_field(
                'ultimate_wp_slider_bullets',
                esc_html__( 'Display Bullets', 'ultimate-wp-slider' ),
                array( $this, 'ultimate_wp_slider_bullets_callback' ),
                'ultimate_wp_slider_page2',
                'ultimate_wp_slider_second_section',
                array(
                    'label_for' => 'ultimate_wp_slider_bullets'
                )
            );

            add_settings_field(
                'ultimate_wp_slider_style',
                esc_html__( 'Slider Style', 'ultimate-wp-slider' ),
                array( $this, 'ultimate_wp_slider_style_callback' ),
                'ultimate_wp_slider_page2',
                'ultimate_wp_slider_second_section',
                array(
                    'items' => array(
                        'style-1',
                        'style-2'
                    ),
                    'label_for' => 'ultimate_wp_slider_style'
                )
                
            );
        }

        public function ultimate_wp_slider_shortcode_callback(){
            ?>
            <span><?php esc_html_e( 'Use the shortcode [ultimate_wp_slider] to display the slider in any page/post/widget', 'ultimate-wp-slider' ); ?></span>
            <?php
        }

        public function ultimate_wp_slider_title_callback( $args ){
            ?>
                <input 
                type="text" 
                name="ultimate_wp_slider_options[ultimate_wp_slider_title]" 
                id="ultimate_wp_slider_title"
                value="<?php echo isset( self::$options['ultimate_wp_slider_title'] ) ? esc_attr( self::$options['ultimate_wp_slider_title'] ) : ''; ?>"
                >
            <?php
        }
        
        public function ultimate_wp_slider_bullets_callback( $args ){
            ?>
                <input 
                    type="checkbox"
                    name="ultimate_wp_slider_options[ultimate_wp_slider_bullets]"
                    id="ultimate_wp_slider_bullets"
                    value="1"
                    <?php 
                        if( isset( self::$options['ultimate_wp_slider_bullets'] ) ){
                            checked( "1", self::$options['ultimate_wp_slider_bullets'], true );
                        }    
                    ?>
                />
                <label for="ultimate_wp_slider_bullets"><?php esc_html_e( 'Whether to display bullets or not', 'ultimate-wp-slider' ); ?></label>
                
            <?php
        }

        public function ultimate_wp_slider_style_callback( $args ){
            ?>
            <select 
                id="ultimate_wp_slider_style" 
                name="ultimate_wp_slider_options[ultimate_wp_slider_style]">
                <?php 
                foreach( $args['items'] as $item ):
                ?>
                    <option value="<?php echo esc_attr( $item ); ?>" 
                        <?php 
                        isset( self::$options['ultimate_wp_slider_style'] ) ? selected( $item, self::$options['ultimate_wp_slider_style'], true ) : ''; 
                        ?>
                    >
                        <?php echo esc_html( ucfirst( $item ) ); ?>
                    </option>                
                <?php endforeach; ?>
            </select>
            <?php
        }

        public function ultimate_wp_slider_validate( $input ){
            $new_input = array();
            foreach( $input as $key => $value ){
                switch ($key){
                    case 'ultimate_wp_slider_title':
                        if( empty( $value )){
                            add_settings_error( 'ultimate_wp_slider_options', 'ultimate_wp_slider_message', esc_html__( 'The title field can not be left empty', 'ultimate-wp-slider' ), 'error' );
                            $value = esc_html__( 'Please, type some text', 'ultimate-wp-slider' );
                        }
                        $new_input[$key] = sanitize_text_field( $value );
                    break;
                    default:
                        $new_input[$key] = sanitize_text_field( $value );
                    break;
                }
            }
            return $new_input;
        }

    }
}

