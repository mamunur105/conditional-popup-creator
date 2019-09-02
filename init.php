<?php
/**
 * Plugin Name: Popups creator
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: A brief description of the Plugin.
 * Version: The Plugin's Version Number, e.g.: 1.0
 * Author: Name Of The Plugin Author
 * Author URI: http://URI_Of_The_Plugin_Author
 * Text Domain: popupcreator
 * Domain Path: /languages/
 * version: 1.0
 */


 class PopupCreatetor {

    const version = '1.0';

    public function __construct(){
		add_action( 'plugins_loaded', array( $this , 'load_textdomain' ) );
        add_action( 'admin_enqueue_scripts',array( $this ,'admin_scripts') );
        add_action( 'wp_enqueue_scripts',array( $this ,'frontend_scripts') );
        add_action( 'init', array( $this ,'cx_popups_init' ) );
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
    
    public function admin_scripts(){
         
        $_screen = get_current_screen();
        if ( 'popups_creator' != $_screen->post_type ) {
            return;
        }

        $styles = array(
            'popmodal-admin-styles'=> array('path' =>plugin_dir_url(__FILE__).'assets/css/admin.css','dept'=>array(),'version'=> self::version)
        );
        $scripts = array(
            'popmodal-admin-script'=> array('path' => plugin_dir_url(__FILE__).'assets/js/admin.js','dept'  => array('jquery'),'version'=> self::version,'footer' => true)
        );
        foreach ($styles as $handle => $info) {
            # code...
            wp_enqueue_style($handle, $info['path'],$info['dept'],$info['version']);
        }
        foreach ($scripts as $handle => $info) {
            # code...
            wp_enqueue_script($handle, $info['path'], $info['dept'], $info['version'], $info['footer']);
        }

    
    }
    public function frontend_scripts(){
        
        $styles = array(
            'jBox-styles'=> array('path' =>plugin_dir_url(__FILE__).'assets/css/jBox.all.min.css','dept'=>array(),'version'=> self::version),
            'popmodal-styles'=> array('path' =>plugin_dir_url(__FILE__).'assets/css/main.css','dept'=>array(),'version'=> self::version)
        );
        $scripts = array(
            'izimodal-script'=> array('path' => plugin_dir_url(__FILE__).'assets/js/jBox.all.min.js','dept'  => array('jquery'),'version'=> self::version,'footer' => true),
            'popmodal-script'=> array('path' => plugin_dir_url(__FILE__).'assets/js/main.js','dept'  => array('jquery'),'version'=> self::version,'footer' => true)
        );
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
                $post = get_post($_GET['post']);
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
    public function cx_popups_init() {
        $labels = array(
            'name'               => _x( 'Popups Creator', 'post type general name', 'codexin' ),
            'singular_name'      => _x( 'Popup', 'post type singular name', 'codexin' ),
            'menu_name'          => _x( 'Popups', 'admin menu', 'codexin' ),
            'name_admin_bar'     => _x( 'Popup', 'add new on admin bar', 'codexin' ),
            'add_new'            => _x( 'Add New', 'Popup', 'codexin' ),
            'add_new_item'       => __( 'Add New Popup', 'codexin' ),
            'new_item'           => __( 'New Popup', 'codexin' ),
            'edit_item'          => __( 'Edit Popup', 'codexin' ),
            'view_item'          => __( 'View Popup', 'codexin' ),
            'all_items'          => __( 'All Popups', 'codexin' ),
            'featured_image'        => __( 'Popup Image', 'text_domain' ),
            'search_items'       => __( 'Search Popups', 'codexin' ),
            'parent_item_colon'  => __( 'Parent Popups:', 'codexin' ),
            'not_found'          => __( 'No Popups found.', 'codexin' ),
            'not_found_in_trash' => __( 'No Popups found in Trash.', 'codexin' )
        );
    
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'codexin' ),
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
           // $htmlmarkup = get_post_meta(get_the_ID(),'metabox_wysiwyg_custom',true);
            
            if (is_page($page_id)) { 
            ?>
                <!-- Modal structure -->
                <div class="data-modal" data-title="<?php echo get_the_title(); ?>" data-delay="<?php echo $delay; ?>" data-exit="<?php echo $dataexit; ?>" data-popup-id="<?php the_ID(); ?>">
                    <button class="close-modal" data-button="Close" title="Close"></button> 
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

 new PopupCreatetor();
 