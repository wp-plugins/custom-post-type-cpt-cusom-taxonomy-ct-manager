/**
 * rtm Javascript.
 */

jQuery(document).ready(function(){
    
    /* Form Slug for new post type by sanitizing title */
    jQuery("#rt_cpt_name").blur(function(){
       
       if( jQuery("#rt_cpt_name").val().trim().length > 0 ){
           
                var ajaxdata = {
                    action: 'rtm_sanitize_title',
                    title:   jQuery("#rt_cpt_name").val().trim()
                };
                
                jQuery.post(rtm_ajaxurl, ajaxdata, function(res){
                    jQuery("#rt_cpt_slug").val(res);
                    jQuery("#rt_cpt_slug").attr('readonly','readonly');
                });
       }else{
           /* If name field is empty, make slug empty */
           jQuery("#rt_cpt_slug").val('');
           jQuery("#rt_cpt_name").val('');
       }
       
    });
    
    /* Validate Menu Icon URL */
    jQuery("#rt_menu_icon").blur(function(){
       
       if( jQuery("#rt_menu_icon").val().trim().length > 0 ){
           
                var ajaxdata = {
                    action: 'rtm_menu_icon',
                    menu_url:   jQuery("#rt_menu_icon").val().trim()
                };
                
                jQuery.post(rtm_ajaxurl, ajaxdata, function(res){
                    jQuery("#rt_error_menu_img").html(res);
                });
       }else{
           jQuery("#rt_error_menu_img").html('');
       }
       
    });
    
});