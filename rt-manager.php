<?php
/*
Plugin Name: Custom Post Type and Taxonomy GUI Manager
Plugin URI: http://alnobody70.wordpress.com
Description:This Plugin gives ability to user who doesn't know programming in wordpress , to create custom post types, custom taxonomies, management and assosiation of these in backend etc.
Version: 1.0
Author: ankitgadertcampcom
Author URI: http://alnobody70.wordpress.com
License: A "Slug" license name e.g. GPL2
*/

// Includes PHP files located in 'rtm-core-functions' folder
foreach( glob ( plugin_dir_path(__FILE__). "rtm-core-functions/*.php" ) as $lib_filename ) {
    require_once( $lib_filename );
}

/**
 * Forms post types and custom taxonomies.
 * 
 * @since 1.0
 * 
 */
function rtm_form_new_post_types(){

        $post_type_form = get_option( 'rt_cptmn', null );

        if( $post_type_form!==null && is_array($post_type_form) ){

            foreach($post_type_form as $value){

                /* Taxonomy Associated */
                $taxonomy_assc = empty($value['tax_association']) ? array() : array_values($value['tax_association']);
                /* Menu icon for post type. */
                $menu_icon     = empty($post_type_form['menu_icon']) ? null : $post_type_form['menu_icon'];

                register_post_type( $value['slug'], array(
                        'labels' => array(
                        'name' => _x($value['name'], 'post type general name', 'cpt-ct'),
                        'singular_name' => _x($value['name'], 'post type singular name', 'cpt-ct'),
                        'add_new' => _x('Add New', $value['name'], 'cpt-ct'),
                        'add_new_item' => __('Add New '.$value['name'], 'cpt-ct'),
                        'edit_item' => __('Edit '.$value['name'], 'cpt-ct'),
                        'new_item' => __('New '.$value['name'], 'cpt-ct'),
                        'view_item' => __('View '.$value['name'], 'cpt-ct'),
                        'search_items' => __('Search '.$value['name'], 'cpt-ct'),
                        'not_found' => __('No '.$value['name'].' found.', 'cpt-ct'),
                        'not_found_in_trash' => __('No '.$value['name'].' found in Trash.', 'cpt-ct'),
                        'parent_item_colon' => array( null, __('Parent '.$value['name'].':', 'cpt-ct') ),
                        'all_items' => __( 'All '.$value['name'], 'cpt-ct' ) ),
                        'description' => __( $value['name'], 'cpt-ct' ),
                        'publicly_queryable' => null, 
                        'exclude_from_search' => null,
                        'capability_type' => 'post', 
                        'capabilities' => array(),
                        'map_meta_cap' => null,
                        '_builtin' => false, 
                        '_edit_link' => 'post.php?post=%d', 
                        'hierarchical' => false,
                        'public' => true, 
                        'rewrite' => true,
                        'has_archive' => true, 
                        'query_var' => true,
                        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author'  ),
                        'register_meta_box_cb' => null,
                        'taxonomies' => $taxonomy_assc,
                        'show_ui' => null, 
                        'menu_position' => null, 
                        'menu_icon' => $menu_icon,
                        'permalink_epmask' => EP_PERMALINK, 
                        'can_export' => true,
                        'show_in_nav_menus' => null, 
                        'show_in_menu' => null, 
                        'show_in_admin_bar' => null
                    )
                );

               unset($taxonomy_assc);
               unset($menu_icon);
            }
        }
        
        $custom_taxonomies = get_option('rtm_custom_tax',null);

        if( is_array($custom_taxonomies) ){

            foreach( $custom_taxonomies as $taxonomy ){

                register_taxonomy( $taxonomy['slug'], $taxonomy['associate_posts'], array(
                    'hierarchical' => true,
                    'update_count_callback' => '',
                    'rewrite' => true,
                    'query_var' => $taxonomy['slug'],
                    'public' => true,
                    'show_ui' => null,
                    'show_tagcloud' => null,
                    '_builtin' => false,
                    'labels' => array(
                    'name' => _x( $taxonomy['name'], 'taxonomy general name', 'cpt-ct' ),
                    'singular_name' => _x( $taxonomy['name'], 'taxonomy singular name', 'cpt-ct' ),
                    'search_items' => __( 'Search '.$taxonomy['name'], 'cpt-ct' ),
                    'all_items' => __( 'All '.$taxonomy['name'], 'cpt-ct' ),
                    'parent_item' => array( null, __( 'Parent '.$taxonomy['name'], 'cpt-ct' ) ),
                    'parent_item_colon' => array( null, __( 'Parent '.$taxonomy['name'].':', 'cpt-ct' ) ),
                    'edit_item' => __( 'Edit '.$taxonomy['name'], 'cpt-ct' ),
                    'view_item' => __( 'View '.$taxonomy['name'], 'cpt-ct' ),
                    'update_item' => __( 'Update '.$taxonomy['name'], 'cpt-ct' ),
                    'add_new_item' => __( 'Add New '.$taxonomy['name'], 'cpt-ct' ),
                    'new_item_name' => __( 'New '.$taxonomy['name'].' Name', 'cpt-ct' ) ),
                    'capabilities' => array(),
                    'show_in_nav_menus' => null,
                    'label' => __( 'Brands', 'cpt-ct' ),
                    'sort' => true,
                    'args' => array( 'orderby' => 'term_order' ) )
                );
            }
        }
}
add_action( 'init', 'rtm_form_new_post_types',9 ); 

register_activation_hook( __FILE__, 'cptm_activate_send_mail' );
register_deactivation_hook( __FILE__, 'cptm_deactivate_send_mail' ); ?>
