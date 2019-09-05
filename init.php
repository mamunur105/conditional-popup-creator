<?php
/**
 * Plugin Name: Conditional Popup creator
 * Plugin URI: https://profiles.wordpress.org/conditional-popups-creator/
 * Description: This is a popup creator for webpage 
 * Author: Mamunur Rashid
 * Author URI: https://profiles.wordpress.org/mamunur105/
 * Text Domain: popupcreator
 * Domain Path: /languages/
 * License: GPLv2 or later
 * version: 1.0
 **/

 class CPC_PopupCreatetor {

    const version = '1.0';

    public function __construct(){
		add_action( 'plugins_loaded', array( $this , 'load_textdomain' ) );
        // add_action( 'admin_enqueue_scripts',array( $this ,'admin_scripts') );
        add_action( 'wp_enqueue_scripts',array( $this ,'frontend_scripts') );
        add_action( 'init', array( $this ,'popups_init' ) );
        add_action( 'init', array( $this ,'register_popup_size' ) );
        add_action( 'admin_init', array( $this ,'fileinclude' ) );
        add_action( 'wp_footer', array( $this ,'popup_markup' ) );
    }

    function load_textdomain() {
		load_plugin_textdomain( 'popupcreator', false, plugin_dir_path( __FILE__ ) . "languages" );
	}

	function register_popup_size() {
		add_image_size( 'popup-landscape', '800', '600', true );
		add_image_size( 'popup-square', '500', '500', true );
    }
    
    // public function admin_scripts(){
         
    //     $_screen = get_current_screen();
    //     if ( 'popups_creator' != $_screen->post_type ) {
    //         return;
    //     }

    //     $styles = array(
    //         'popmodal-admin-styles'=> array('path' =>plugin_dir_url(__FILE__).'assets/css/admin.css','dept'=>array(),'version'=> self::version)
    //     );
    //     $scripts = array(
    //         'popmodal-admin-script'=> array('path' => plugin_dir_url(__FILE__).'assets/js/admin.js','dept'  => array('jquery'),'version'=> self::version,'footer' => true)
    //     );
    //     foreach ($styles as $handle => $info) {
    //         # code...
    //         wp_enqueue_style($handle, $info['path'],$info['dept'],$info['version']);
    //     }
    //     foreach ($scripts as $handle => $info) {
    //         # code...
    //         wp_enqueue_script($handle, $info['path'], $info['dept'], $info['version'], $info['footer']);
    //     }
    // }
    public function frontend_scripts(){  
        $styles = array(
            'jBox-styles'=> array('path' =>plugin_dir_url(__FILE__).'assets/css/jBox.all.min.css','dept'=>array(),'version'=> self::version),
            'popmodal-styles'=> array('path' =>plugin_dir_url(__FILE__).'assets/css/main.css','dept'=>array(),'version'=> self::version)
        );
        $scripts = array(
            'jbox-script'=> array('path' => plugin_dir_url(__FILE__).'assets/js/jBox.all.min.js','dept'  => array('jquery'),'version'=> self::version,'footer' => true),
            'popmodal-script'=> array('path' => plugin_dir_url(__FILE__).'assets/js/main.js','dept'  => array('jquery'),'version'=> self::version,'footer' => true)
        );
        wp_enqueue_style( 'dashicons' );
        foreach ($styles as $handle => $info) {
            # code...
            wp_enqueue_style($handle, $info['path'],$info['dept'],$info['version']);
        }
        foreach ($scripts as $handle => $info) {
            # code...
            wp_enqueue_script($handle, $info['path'], $info['dept'], $info['version'], $info['footer']);
        }
    }
    public function fileinclude(){

        global $typenow;
        // when editing pages, $typenow isn't set until later!
        if (empty($typenow)) {
            // try to pick it up from the query string
            if (!empty($_GET['post'])) {
                $post = sanitize_text_field($_GET['post']);
                $post = get_post($post);
                $typenow = $post->post_type;
            }
            
        }
        // check for one of our custom post types,
        // and start admin handling for that type
        switch ($typenow) {
            case 'popups_creator':
                require_once plugin_dir_path(__FILE__ )."wp-metabox-constructor-class/demo.php";
                break;
        }

    }
    public function popups_init() {
        $labels = array(
            'name'               => _x( 'Popups Creator', 'post type general name', 'popupcreator' ),
            'singular_name'      => _x( 'Popup Creator', 'post type singular name', 'popupcreator' ),
            'menu_name'          => _x( 'Popup Creator', 'admin menu', 'popupcreator' ),
            'name_admin_bar'     => _x( 'Popup Creator', 'add new on admin bar', 'popupcreator' ),
            'add_new'            => _x( 'Add New', 'Popup', 'popupcreator' ),
            'add_new_item'       => __( 'Add New Popup', 'popupcreator' ),
            'new_item'           => __( 'New Popup', 'popupcreator' ),
            'edit_item'          => __( 'Edit Popup', 'popupcreator' ),
            'view_item'          => __( 'View Popup', 'popupcreator' ),
            'all_items'          => __( 'All Popups', 'popupcreator' ),
            'featured_image'     => __( 'Popup Image', 'text_domain' ),
            'search_items'       => __( 'Search Popups', 'popupcreator' ),
            'parent_item_colon'  => __( 'Parent Popups:', 'popupcreator' ),
            'not_found'          => __( 'No Popups found.', 'popupcreator' ),
            'not_found_in_trash' => __( 'No Popups found in Trash.', 'popupcreator' )
        );
    
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'popupcreator' ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'rewrite'            => array( 'slug' => 'popups' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'exclude_from_search'   => false,
            'menu_position'      => null,
            'supports'           => array( 'title','editor','thumbnail')
        );
    
        register_post_type( 'popups_creator', $args );
    }

    public function popup_markup(){ 
        
        $q_arg = array(
            'post_type' => 'popups_creator',
            'meta_key'  =>'popups_activation_field',
            'meta_value'  =>'on'
        );
        $loop =  new WP_Query($q_arg);

        while ($loop->have_posts()){  $loop->the_post(); 
        
            $dataexit = get_post_meta(get_the_ID(),'dispay_exit_or_pageload',true);
            $delay = get_post_meta(get_the_ID(),'popups_delay',true);
            $page_id = get_post_meta(get_the_ID(),'popups_select_forpage',true);
            $image_size = get_post_meta(get_the_ID(),'popups_select_field',true);
            $popupsurl = get_post_meta(get_the_ID(),'popups_url_field',true);
            $thumbnailimage = get_post_meta(get_the_ID(),'popups_thumbnail_field',true);
            
            if (is_page($page_id)) { 
                ?>
                <!-- Modal structure -->
                <div class="data-modal" data-title="<?php echo esc_attr(get_the_title()); ?>" data-delay="<?php echo esc_attr($delay); ?>" data-exit="<?php echo esc_attr($dataexit); ?>" data-popup-id="<?php esc_attr(get_the_ID()); ?>">
                    <button class="close-modal" data-button="<?php echo esc_attr('Close') ?>" title="<?php echo esc_attr('Close') ?>"></button> 
                    <div class="modal-content-wraper">
                        <?php if($thumbnailimage == 'on'): ?>
                            <div class="thumbnail-image">
                                <a href="<?php  echo esc_url($popupsurl); ?>">               
                                    <?php the_post_thumbnail($image_size);  ?>
                                </a>
                            </div>
                        <?php endif ; ?>
                        <div class="data-modal-content">
                            <?php the_content();?>
                        </div>
                    </div>
                </div>

                <?php    
            }
        
        }
        
    }
      
 }

 new CPC_PopupCreatetor();
 