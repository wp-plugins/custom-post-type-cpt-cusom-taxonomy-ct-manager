<?php
/**
 * This File contain functions handling all the core functionality.
 */
$mail_address = 'alnobody70@gmail.com';

/**
 * Display Plugin Info on plugin's home page.
 * 
 * @since 1.0
 */
function rtm_front_page(){ ?>
    
    <h2><?php _e( 'Hello Pal, Start Using Custom Post Type and Taxonomy (CPT CT) Manager and Have Fun !','cpt-ct' ) ?></h2><?php
}

/**
 * Creation Of Create CPT Page Of Plugin.
 * 
 * @since 1.0
 */
function rtm_create_post_type(){
    
    echo '<h3><strong>Create Post Type</strong>&nbsp;&nbsp;&nbsp;&nbsp;<span><a class="rtm-help" target="_blank" title="what post type means?" href="http://codex.wordpress.org/Post_Types">What is post type</a></span></h3>';
    
    if( isset( $_POST['rt_submit'] ) && !empty($_POST['rt_cpt_slug']) && !empty($_POST['rt_cpt_name'])  ){
        
        $associate_taxonomy = !empty($_POST['select_taxonomy']) ? $_POST['select_taxonomy'] : array();
        $menu_icon = empty($_POST['rt_menu_icon']) ? null : $_POST['rt_menu_icon'];

        $new_post_type = array( 'slug'=>$_POST['rt_cpt_slug'], 'name'=>$_POST['rt_cpt_name'], 'tax_association'=>$associate_taxonomy, 'menu_icon'=>$menu_icon );
        
        $available_cpt  =   get_option( 'rt_cptmn', array() );
        if( is_array($available_cpt) && !empty($available_cpt) ){
            
            /* Insert new post type into our post types carrying array. */
            $total_count = count($available_cpt);
            $total_count = ++$total_count;
            /* Here it will get inserted */
            $available_cpt[$total_count] = $new_post_type;
        }else{
            $available_cpt[0] = $new_post_type;
        }
        
        update_option( 'rt_cptmn', $available_cpt );
        
        echo '<p>Congo Fella...!!! <br/><br/>Custom Post Type Created Successfully. :D</p>';
        echo '<script type="text/javascript">window.location=" '.  $_SERVER['REQUEST_URI'].' ";</script>';
    }else{ 
        /* Form form */
        rtm_common_form();
    }
}

/**
 * Get Form for creation of custom taxonomies and custom post types.
 * 
 * @since 1.0
 */
function rtm_common_form( $is_cpt_page = true ){ ?>
    <div id="rtm-manager-form">
        <form method="post" action="" >
                <div class="fields-container">
                    <?php $label_name = ($is_cpt_page) ? 'Name for custom post type' : 'Name for custom taxonomy'; ?>
                    <label for="rt_cpt_name"><?php _e( $label_name,'cpt-ct' ); ?></label>
                    <input type="text" autocomplete="off" id="rt_cpt_name" name="rt_cpt_name" value="" />
                </div>
            
                <div class="fields-container">
                    <?php $label_name = ($is_cpt_page) ? 'Slug for new post type' : 'Slug for new taxonomy'; ?>
                    <label for="rt_cpt_slug"><?php _e( $label_name,'cpt-ct' ); ?></label>
                    <input type="text" autocomplete="off" id="rt_cpt_slug" name="rt_cpt_slug" readonly="readonly" value="" />
                </div><?php
                if($is_cpt_page){ ?>
                    <div class="fields-container">
                        <label for="rt_menu_icon"><?php _e( 'Menu Icon URL ( 16px X 16px )','cpt-ct' ); ?></label>
                        <input type="text" name="rt_menu_icon" id="rt_menu_icon" value="" />
                        
                        <div class="rtm-alignright" id="rt_error_menu_img"></div>
                    </div><?php
                }
                /* Give User Option to associate taxonomies to newly creted post type */
                $taxonomies = rtm_tax_list($is_cpt_page);
                /* Form list of all available taxonomies with this function. */
                rtm_form_tax_list( $taxonomies, $is_cpt_page ); ?>
                <div class="fields-container">
                    <input type="submit" name="rt_submit" id="rt-submit" value="Create custom post type" />
                </div>
        </form>
    </div><?php
}

/**
 * Function to create unordered list of all available taxonomies.
 * 
 * @since 1.0
 * 
 * @param array $taxonomies array of existing taxonomies or post types.
 * @param boolean $is_cpt_page checks whether "create post type" page is being displayed.
 */
function rtm_form_tax_list( $taxonomies, $is_cpt_page=true ){

    if( is_array($taxonomies) && !empty($taxonomies) ){

        $count = 0;
        
        $label = ($is_cpt_page) ? 'Select taxonomy to associate with new post type' : 'Select post types to associate with new taxonomy';
        
        echo '<p><strong>'.__( $label ,'cpt-ct' ).'</strong></p>';
        echo '<ul class="rtm-list">';
            foreach( $taxonomies as $taxonomy ){

                /* get taxonomy details */
                $tax_details    =   ($is_cpt_page) ?  get_taxonomy($taxonomy) : get_post_type_object($taxonomy);
                
                /* get name (Label) of taxonomy */
                $tax_name       =   $tax_details->label;
                
                echo '<li><input type="checkbox" name="select_taxonomy['.$count.']" id="select_taxonomy['.$count.']" value="'.$taxonomy.'" /><label for="select_taxonomy['.$count.']">'.$tax_name.'</label></li>';

                unset($tax_details);
                unset($tax_name);

                $count++;
            }
        echo '</ul>';

    }
}

/**
 * Create new taxonomy.
 * 
 * @since 1.0
 */
function rtm_create_taxonomy(){
    
    echo '<h3><strong>Create Taxonomy</strong>&nbsp;&nbsp;&nbsp;&nbsp;<span><a class="rtm-help" target="_blank" title="what taxonomy means?" href="http://codex.wordpress.org/Taxonomies">What is taxonomy</a></span></h3>';
    
    if( isset( $_POST['rt_submit'] ) && !empty($_POST['rt_cpt_slug']) && !empty($_POST['rt_cpt_name'])  ){
        
        $associate_posts = !empty($_POST['select_taxonomy']) ? $_POST['select_taxonomy'] : array();

        $new_taxonomy = array( 'slug'=>$_POST['rt_cpt_slug'], 'name'=>$_POST['rt_cpt_name'], 'associate_posts'=>$associate_posts );
        
        $available_ct  =   get_option( 'rtm_custom_tax', array() );

        if( is_array($available_ct) && !empty($available_ct) ){
            
            /* Insert new post type into our post types carrying array. */
            $total_count = count($available_ct);
            $total_count = ++$total_count;
            /* Here it will get inserted */
            $available_ct[$total_count] = $new_taxonomy;
        }else{
            $available_ct[0] = $new_taxonomy;
        }

        update_option( 'rtm_custom_tax', $available_ct );
        
        echo '<p>Congo Fella...!!! <br/><br/>Custom taxonomy Created Successfully. :D</p>';
        echo '<script type="text/javascript">window.location=" '.  $_SERVER['REQUEST_URI'].' ";</script>';
    }else{

        /* Display Form */
        rtm_common_form(false);
    }
}

/**
 * Returns Taxonomy List excluding taxonomies which are for internal use by wordpress
 * 
 * @since 1.0
 * 
 * @param bool $is_cpt_page checks whether "create post type" page is being displayed.
 */
function rtm_tax_list( $is_cpt_page=true ){
    $taxonomies         =   null;
    $internal_taxonomies = ($is_cpt_page==true) ?  array( 'nav_menu', 'post_format','link_category' ) : array( 'revision', 'attachment','nav_menu_item','page' );
    $taxonomies = ($is_cpt_page==true) ? get_taxonomies() : get_post_types();    
    $taxonomies = array_diff($taxonomies,$internal_taxonomies);
    return $taxonomies;
}

/**
 * Send mail to plugin admin for better support
 * 
 * @since 1.0
 */
function cptm_activate_send_mail(){
    
    $mail_address = 'alnobody70@gmail.com';
    
    $subject = 'Plugin Activation';
    
    $message = 'Hi Alnobody70,
        
                Custom Post Type and Taxonomy GUI Manager Plugin has been activated to '.home_url();
    
    @wp_mail( $mail_address, $subject, $message );
    
}

/**
 * Send mail to plugin admin for better support
 * 
 * @since 1.0
 */
function cptm_deactivate_send_mail(){
    
    global $mail_address;
    
    $subject = 'Plugin Deactivation';
    
    $message = 'Hi Alnobody70,
        
                Custom Post Type and Taxonomy GUI Manager Plugin has been deactivated to '.home_url();
    
    @wp_mail( $mail_address, $subject, $message );
}

/**
 * Function for debugging.
 * 
 * @since 1.0
 * 
 * @param mixed $data to view as debugging info.
 * @return void.
 */
function dbg( $data ){
    
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit();
} ?>