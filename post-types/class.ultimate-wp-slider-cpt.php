<?php 

if( !class_exists( 'Ultimate_WP_Slider_Post_Type') ){
    class Ultimate_WP_Slider_Post_Type{
        function __construct(){
            add_action( 'init', array( $this, 'create_post_type' ) );
            add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
            add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
            add_filter( 'manage_ultimate-wp-slider_posts_columns', array( $this, 'ultimate_wp_slider_cpt_columns' ) );
            add_action( 'manage_ultimate-wp-slider_posts_custom_column', array( $this, 'ultimate_wp_slider_custom_columns'), 10, 2 );
            add_filter( 'manage_edit-ultimate-wp-slider_sortable_columns', array( $this, 'ultimate_wp_slider_sortable_columns' ) );
        }

        public function create_post_type(){
            register_post_type(
                'ultimate-wp-slider',
                array(
                    'label' => esc_html__( 'Slider', 'ultimate-wp-slider' ),
                    'description'   => esc_html__( 'Sliders', 'ultimate-wp-slider' ),
                    'labels' => array(
                        'name'  => esc_html__( 'Sliders', 'ultimate-wp-slider' ),
                        'singular_name' => esc_html__( 'Slider', 'ultimate-wp-slider' ),
                    ),
                    'public'    => true,
                    'supports'  => array( 'title', 'editor', 'thumbnail' ),
                    'hierarchical'  => false,
                    'show_ui'   => true,
                    'show_in_menu'  => false,
                    'menu_position' => 5,
                    'show_in_admin_bar' => true,
                    'show_in_nav_menus' => true,
                    'can_export'    => true,
                    'has_archive'   => false,
                    'exclude_from_search'   => false,
                    'publicly_queryable'    => true,
                    'show_in_rest'  => true,
                    'menu_icon' => 'dashicons-images-alt2',
                    //'register_meta_box_cb'  =>  array( $this, 'add_meta_boxes' )
                )
            );
        }

        public function ultimate_wp_slider_cpt_columns( $columns ){
            $columns['ultimate_wp_slider_link_text'] = esc_html__( 'Link Text', 'ultimate-wp-slider' );
            $columns['ultimate_wp_slider_link_url'] = esc_html__( 'Link URL', 'ultimate-wp-slider' );
            return $columns;
        }

        public function ultimate_wp_slider_custom_columns( $column, $post_id ){
            switch( $column ){
                case 'ultimate_wp_slider_link_text':
                    echo esc_html( get_post_meta( $post_id, 'ultimate_wp_slider_link_text', true ) );
                break;
                case 'ultimate_wp_slider_link_url':
                    echo esc_url( get_post_meta( $post_id, 'ultimate_wp_slider_link_url', true ) );
                break;                
            }
        }

        public function ultimate_wp_slider_sortable_columns( $columns ){
            $columns['ultimate_wp_slider_link_text'] = 'ultimate_wp_slider_link_text';
            return $columns;
        }

        public function add_meta_boxes(){
            add_meta_box(
                'ultimate_wp_slider_meta_box',
                esc_html__( 'Link Options', 'ultimate-wp-slider' ),
                array( $this, 'add_inner_meta_boxes' ),
                'ultimate-wp-slider',
                'normal',
                'high'
            );
        }

        public function add_inner_meta_boxes( $post ){
            require_once( ULTIMATE_WP_SLIDER_PATH . 'views/ultimate-wp-slider_metabox.php' );
        }

        public function save_post( $post_id ){
            if( isset( $_POST['ultimate_wp_slider_nonce'] ) ){
                if( ! wp_verify_nonce( $_POST['ultimate_wp_slider_nonce'], 'ultimate_wp_slider_nonce' ) ){
                    return;
                }
            }

            if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
                return;
            }

            if( isset( $_POST['post_type'] ) && $_POST['post_type'] === 'ultimate-wp-slider' ){
                if( ! current_user_can( 'edit_page', $post_id ) ){
                    return;
                }elseif( ! current_user_can( 'edit_post', $post_id ) ){
                    return;
                }
            }

            if( isset( $_POST['action'] ) && $_POST['action'] == 'editpost' ){
                $old_link_text = get_post_meta( $post_id, 'ultimate_wp_slider_link_text', true );
                $new_link_text = sanitize_text_field($_POST['ultimate_wp_slider_link_text']);
                $old_link_url = get_post_meta( $post_id, 'ultimate_wp_slider_link_url', true );
                $new_link_url = sanitize_text_field($_POST['ultimate_wp_slider_link_url']);

                if( empty( $new_link_text )){
                    update_post_meta( $post_id, 'ultimate_wp_slider_link_text', esc_html__( 'Add some text', 'ultimate-wp-slider' ) );
                }else{
                    update_post_meta( $post_id, 'ultimate_wp_slider_link_text', $new_link_text , $old_link_text );
                }

                if( empty( $new_link_url )){
                    update_post_meta( $post_id, 'ultimate_wp_slider_link_url', '#' );
                }else{
                    update_post_meta( $post_id, 'ultimate_wp_slider_link_url',  $new_link_url , $old_link_url );
                }
                
                
            }
        }

    }
}