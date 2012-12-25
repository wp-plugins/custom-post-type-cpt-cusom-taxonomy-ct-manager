<?php
/**
 * All the hooked function used in plugin.
 */

function rtm_plugin_init() {
  load_plugin_textdomain( 'cpt-ct', false, dirname( plugin_basename( __FILE__ ) ) ); 
}
add_action( 'plugins_loaded', 'rtm_plugin_init' );

/**
 * Add scripts and styles.
 * 
 * @since 1.0
 */
function rt_add_styles_and_scripts(){
    wp_enqueue_script( 'rtm', plugins_url().'custom-post-type-cpt-cusom-taxonomy-ct-manager/js/rtm.js', array(), false, true );
    
    wp_enqueue_style('rtm-style', plugins_url().'custom-post-type-cpt-cusom-taxonomy-ct-manager/styles/rtm-style.css');
}
add_action( 'admin_enqueue_scripts', 'rt_add_styles_and_scripts', 9 );

/**
 * Add Options Page when plugin activated.
 *
 * @since 1.0
 */
function rt_create_menu(){

    $rtmanager = add_menu_page( __( 'CPT CT Manager','cpt-ct' ), __( 'CPT CT Manager','cpt-ct' ), 'administrator', 'cptctm', 'rtm_front_page', plugins_url('custom-post-type-cpt-cusom-taxonomy-ct-manager/images/icon.ico') );
    add_submenu_page( 'cptctm', __( 'Create CPT','cpt-ct' ), __('Create Custom Post Type','cpt-ct'), 'administrator', 'cptctm-create-cpt', 'rtm_create_post_type' );
    add_submenu_page( 'cptctm', __('Create CT','cpt-ct'),  __('Create Custom Taxonomy','cpt-ct'), 'administrator', 'cptctm-create-ct', 'rtm_create_taxonomy' );

}
add_action('admin_menu','rt_create_menu');

/**
 * Add ajaxurl.
 * 
 * @since 1.0
 */
function rtm_ajaxurl() { ?>
<script type="text/javascript">
    var rtm_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script><?php
}
add_action('admin_head','rtm_ajaxurl');

/**
 * Form slug from Post Type title ( Invoked by ajax call )
 *
 * @since 1.0
 */
function rtm_sanitize_post_title(){

    if( !empty($_POST['title']) ){
        
        if( function_exists('sanitize_title') ){
            echo $slug = sanitize_title( $_POST['title'] );
            die(1);
        }
    }    
}
add_action('wp_ajax_rtm_sanitize_title', 'rtm_sanitize_post_title');
add_action('wp_ajax_nopriv_rtm_sanitize_title', 'rtm_sanitize_post_title');

/**
 * Checks for valid menu icon for custom post type like icon size and valid url.
 * 
 * @since 1.0
 */
function rtm_validate_menu_icon(){

    if( !empty($_POST['menu_url']) ){

        $url = $_POST['menu_url'];

        if(!filter_var($url, FILTER_VALIDATE_URL)){
            echo '<p>'.__('URL is not valid','cpt-ct').'</p>';
            die(1);
        }else{
         
            $size = @getimagesize($url);
            
            if( is_array($size) ){
                
                $width  = $size[0];
                $height = $size[1];
                
                $width  = ($width <= 16)  ? true : false;
                $height = ($height <= 16) ? true : false;
                if( !($width && $height) ){
                    
                    echo 'Dimension of image not as per requirement.';
                    die(1);
                }                    
            }
        }
    }
    echo '';
    die(1);
}
add_action('wp_ajax_rtm_menu_icon', 'rtm_validate_menu_icon');
add_action('wp_ajax_nopriv_rtm_menu_icon', 'rtm_validate_menu_icon');

/**
 * Associated Taxonomies To newly created Post Types
 * 
 * @since 1.0
 */
function rtm_associate_taxonomies(){

    $post_type_form = get_option( 'rt_cptmn', null );
    if( is_array($post_type_form) && !empty($post_type_form) ){

        foreach( $post_type_form as $key=>$val ){

            $taxonomies = empty($val['tax_association']) ? null : array_values($val['tax_association']);

            if($taxonomies){
                foreach($taxonomies as $tax){
                     register_taxonomy_for_object_type( $tax, $val['slug'] );
                }
            }
        }
    }

    $custom_tax = get_option( 'rtm_custom_tax', null );

    if(!empty($custom_tax)){

        global $wp_post_types;
        foreach( $custom_tax as $key=>$val ){

            $post_types = empty($val['associate_posts']) ? null : array_values($val['associate_posts']);

            if($post_types){
                foreach( $post_types as $post_type ){

                    if( !in_array( $val['slug'], $wp_post_types[$post_type]->taxonomies ) ){

                        array_push( $wp_post_types[$post_type]->taxonomies, $val['slug'] );

                    }                    
                }
            }
        }
    }

}
add_action( 'init' , 'rtm_associate_taxonomies',11 ); ?>
