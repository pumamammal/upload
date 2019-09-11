<?php

/*
*		Plugin Name: WP LinkedIn Auto Publish
*		Plugin URI: https://www.northernbeacheswebsites.com.au
*		Description: Publish your latest posts to LinkedIn profiles or companies automatically. 
*		Version: 6.15
*		Author: Martin Gibson
*		Author URI:  https://www.northernbeacheswebsites.com.au
*		Text Domain: wp-linkedin-auto-publish   
*		Support: https://www.northernbeacheswebsites.com.au/contact
*		Licence: GPL2
*/



/**
* 
*
*
* Create admin menu and add it to a global variable so that admin styles/scripts can hook into it
*/
add_action( 'admin_menu', 'wp_linkedin_autopublish_add_admin_menu' );
add_action( 'admin_init', 'wp_linkedin_autopublish_settings_init' );

function wp_linkedin_autopublish_add_admin_menu(  ) { 
    $menu_icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMiIgYmFzZVByb2ZpbGU9InRpbnkiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDIwIDIwIiBvdmVyZmxvdz0ic2Nyb2xsIiB4bWw6c3BhY2U9InByZXNlcnZlIj48Zz48Zz48Zz48cGF0aCBmaWxsPSIjOUVBM0E3IiBkPSJNMTcuNywxSDIuM0MxLjYsMSwxLDEuNiwxLDIuM3YxNS40QzEsMTguNCwxLjYsMTksMi4zLDE5aDE1LjNjMC43LDAsMS4zLTAuNiwxLjMtMS4zVjIuM0MxOSwxLjYsMTguNCwxLDE3LjcsMXogTTYuMywxNi4zSDMuN1Y3LjdoMi43VjE2LjN6IE01LDYuNkM0LjEsNi42LDMuNSw1LjksMy41LDVjMC0wLjksMC43LTEuNSwxLjUtMS41YzAuOSwwLDEuNSwwLjcsMS41LDEuNUM2LjYsNS45LDUuOSw2LjYsNSw2LjZ6IE0xNi4zLDE2LjNoLTIuN3YtNC4yYzAtMSwwLTIuMy0xLjQtMi4zYy0xLjQsMC0xLjYsMS4xLTEuNiwyLjJ2NC4ySDhWNy43aDIuNnYxLjJoMGMwLjQtMC43LDEuMi0xLjQsMi41LTEuNGMyLjcsMCwzLjIsMS44LDMuMiw0LjFWMTYuM3oiLz48L2c+PC9nPjwvZz48L3N2Zz4=';
    
    global $wp_linkedin_autopublish_settings_page;
	$wp_linkedin_autopublish_settings_page = add_menu_page( 'WP LinkedIn Auto Publish', 'WP LinkedIn Auto Publish', 'manage_options', 'wp_linkedin_auto_publish', 'wp_linkedin_autopublish_options_page',$menu_icon_svg);
}
/**
* 
*
*
* Gets, sets and renders options
*/
require('inc/options-output.php');
/**
* 
*
*
* Output the wrapper of the settings page and call the sections
*/
function wp_linkedin_autopublish_options_page(  ) { 
    require('inc/options-page-wrapper.php');
}
/**
* 
*
*
* Add custom links to plugin on plugins page
*/
function wp_linkedin_autopublish_plugin_links( $links, $file ) {
   if ( strpos( $file, 'wp-linkedin-autopublish.php' ) !== false ) {
      $new_links = array(
               '<a href="https://northernbeacheswebsites.com.au/product/donate-to-northern-beaches-websites/" target="_blank">' . __('Donate') . '</a>',
               '<a href="https://wordpress.org/support/plugin/wp-linkedin-auto-publish" target="_blank">' . __('Support Forum') . '</a>',
            );
      $links = array_merge( $links, $new_links );
   }
   return $links;
}
add_filter( 'plugin_row_meta', 'wp_linkedin_autopublish_plugin_links', 10, 2 );
/**
* 
*
*
* Add settings link to plugin on plugins page
*/
function wp_linkedin_autopublish_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=wp_linkedin_auto_publish">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'wp_linkedin_autopublish_settings_link' );
/**
* 
*
*
* Gets version number of plugin
*/
function wp_linkedin_autopublish_get_version() {
	if ( ! function_exists( 'get_plugins' ) )
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}
/**
* 
*
*
* Load admin styles and scripts
*/
function wp_linkedin_autopublish_register_admin($hook)
{
    
    //get settings page
    global $wp_linkedin_autopublish_settings_page;
    
    if(in_array($hook, array('post.php', 'post-new.php', 'edit.php') )){
        
        //scripts
        wp_enqueue_script( 'custom-admin-post-script-linkedin', plugins_url( '/inc/postscript.js', __FILE__ ), array( 'jquery'),wp_linkedin_autopublish_get_version());    
        
        //styles
        wp_enqueue_style( 'post-style-linkedin', plugins_url( '/inc/poststyle.css', __FILE__ ),array(),wp_linkedin_autopublish_get_version());
        wp_enqueue_style( 'font-awesome-icons-linkedin', plugins_url( '/inc/external/font-awesome.min.css', __FILE__ ));
        
        
    } elseif ($wp_linkedin_autopublish_settings_page == $hook){    
        
        //scripts
        wp_enqueue_script( 'custom-admin-script-linkedin', plugins_url( '/inc/adminscript.js', __FILE__ ), array( 'jquery','jquery-ui-accordion','jquery-ui-tabs','jquery-form','wp-color-picker' ),wp_linkedin_autopublish_get_version());
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-form');
        

        //styles
        wp_enqueue_style( 'custom-admin-style-linkedin', plugins_url( '/inc/adminstyle.css', __FILE__ ),array(),wp_linkedin_autopublish_get_version());
        wp_enqueue_style( 'font-awesome-icons-linkedin', plugins_url( '/inc/external/font-awesome.min.css', __FILE__ ));    
    } else {
        
        return;
    }    

}
add_action( 'admin_enqueue_scripts', 'wp_linkedin_autopublish_register_admin' );
/**
* 
*
*
* Function to get current page URL
*/
function wp_linkedin_autopublish_current_page_url() {
    
    
    $options = get_option( 'wp_linkedin_autopublish_settings' );
    
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') { 
        $serverType = 'https://';    
    } else {
        $serverType = 'http://'; 
    }
        
    $currentPageUrl = $serverType . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
    $findCurrentPageUrl = strpos($currentPageUrl,"wp_linkedin_auto_publish")+24;
    $trimCurrentPageUrl = substr($currentPageUrl,0,$findCurrentPageUrl);
    return $trimCurrentPageUrl;
}
/**
* 
*
*
* Function to get the posts URL
*/
function wp_linkedin_autopublish_posts_page_url() {

    $currentPageUrl = $_SERVER['REQUEST_URI']; 

    $findCurrentPageUrl = strpos($currentPageUrl,"admin.php");

    $trimCurrentPageUrl = substr($currentPageUrl,0,$findCurrentPageUrl)."edit.php";
    
    return $trimCurrentPageUrl;
}
/**
* 
*
*
* Function to generate random state for API call
*/
//function wp_linkedin_autopublish_state_generator($length = 21) {
//    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
//}
/**
* 
*
*
* Function that gets the access token
*/
function wp_linkedin_autopublish_save_access_token(){

    if ( ! current_user_can( 'manage_options') ){
		return;
	}
            
    //get options    
    $options = get_option( 'wp_linkedin_autopublish_settings' );    

    $code = $_POST['code']; 
    $redirectUrl = 'https%3A%2F%2Fnorthernbeacheswebsites.com.au%2Fredirectlinkedin%2F';

    $response = wp_remote_post( 'https://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&code='.$code.'&redirect_uri='.$redirectUrl.'&client_id=8640n1zn844brm&client_secret=IDRdaazTtBBuREGS', array(
        'headers' => array(
            'Content-Length' => 0,
        ),
    ));
    
    if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
            
        $decodedBody = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $response['body']), true);

         
        $pluginSettings = get_option('wp_linkedin_autopublish_auth_settings');
        $pluginSettings['access_token'] = $decodedBody['access_token'];
        $pluginSettings['access_token_expiry'] = date('d/m/Y',time() + 86400 * 60);
        update_option('wp_linkedin_autopublish_auth_settings', $pluginSettings);
        
        echo 'SUCCESS'; 
        
    } else {
        echo wp_remote_retrieve_response_code( $response ).' '.wp_remote_retrieve_response_message( $response ).' '.wp_remote_retrieve_body( $response );  
    }

    wp_die();
 
} 
add_action( 'wp_ajax_save_linkedin_access_token', 'wp_linkedin_autopublish_save_access_token' );
/**
* 
*
*
* Function that displays settings tab content
*/
function wp_linkedin_autopublish_tab_content ($tabName) {
    
    //get options    
    $options = get_option( 'wp_linkedin_autopublish_settings' ); 
    $optionsAuth = get_option( 'wp_linkedin_autopublish_auth_settings' ); 
    
    ?>
<div class="tab-content" id="<?php echo $tabName; ?>">
    <div class="meta-box-sortables ui-sortable">
        <div class="postbox">
            <div class="inside">
                
                
                <?php if($tabName == 'helpPage') { ?>
                
                <div id="accordion">
                    
                    
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> How does the plugin work and how do I set things up</h3>
                    <div>
                        <p>First you need to authenticate the plugin. This can be done by going to the <a class="open-tab" href="#authorisationPage">Connect</a> tab and clicking the "Connect with LinkedIn" button. You will be prompted to allow access to your profile to our application. You will then be redirected back to the plugin settings page and then you should be automatically redirected to the <a class="open-tab" href="#authorisationPage">Profile Selection</a> tab where you can select if you want use your profile and/or selected companies with the plugin. Then you will be redirected to the <a class="open-tab" href="#sharingOptionsPage">Sharing Options</a> tab where you can set the defaults for sharing to LinkedIn and some additional options.</p>
                        
                        
                        <p>Now on your post/page/custom post type you will see the "WP LinkedIn Auto Publish Settings" metabox (if you have enabled it from the <a class="open-tab" href="#sharingOptionsPage">Sharing Options</a> tab). In this metabox you can change the defaults you have just set for the specific post if need be. Now once you publish the post the data will be sent to your selected profile and/or company pages. You can also press the "Share Now" button in the metabox to sent the post to LinkedIn straight away without having to publish the post (just be careful not to press the share now button and then publish the post in one go otherwise the post could be sent twice).</p>
                        
                        <p>Remember in the <a class="open-tab" href="#additionalOptionsPage">Additional Options</a> tab you can choose not to share posts automatically with LinkedIn - by default posts will be shared to LinkedIn.</p>
                        
                    </div>
                    
                    
                    
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> I have successfuly authentication but posts aren't being sent to LinkedIn what's going on?</h3>
                    <div>
                        There could be several reasons why posts aren't going to LinkedIn, let's go through a few of these:
                        
                        <ol>
                            <li>Make sure on your post page that you haven't checked the 'Don't share this post' checkbox.</li>
                            <li>Make sure the category that your post belongs to hasn't been checked on the 'Don't Share Select Post Categories on LinkedIn' option on the <a class="open-tab" href="#sharingOptionsPage">sharing options tab</a>.</li>
                            <li>If you have shared the post to LinkedIn already and you haven't changed any of the shared content LinkedIn won't let you share it again because it detects that as duplicate content. So you will need to change the content before sharing again.</li>
                        </ol>
                        
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> How do I share an image to LinkedIn?</h3>
                    <div>
                        It depends on what your setting is on the <a class="open-tab" href="#sharingOptionsPage">sharing options tab</a>. If the "Share Method" is set to "Simple" LinkedIn will crawl your post/page and find an image to share with your post. If you have set the "Share Method" to "Advanced" you must set a feature imaged on your post or page. A featured image is a standard WordPress post field; to learn more about setting the featured image please click <a target="_blank" href="https://www.youtube.com/watch?v=9admKGpM3A0">here</a>. 
                    </div>
                    
                    
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> Why do I need to re-connect the plugin every 60 days?</h3>
                    <div>
                        Unfortunately this is not my choice. LinkedIn uses oAuth 2.0 and they expire access tokens every 60 days for what they must say is for 'security purposes'. I know this makes life suck even more than it already does. If LinkedIn should provide a way to enable access tokens that don't expire I will be onto this ASAP. However just before your access token will expire you will see a notice on your WordPress dashboard prompting you to renew the access token. Renewing the access token just requires clicking the 'Connect with LinkedIn' button on the <a class="open-tab" href="#authorisationPage">Connect</a> tab so you can ensure your posts always get shared to LinkedIn. 
                    </div>
                    
                    
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> Can I share to LinkedIn Articles?</h3>
                    <div>
                        No, the LinkedIn API doesn't provide the ability to share to LinkedIn Articles. You can only share to personal profiles or company pages. When or if LinkedIn provides the ability to share to LinkedIn articles you can be sure we'll be on it! 
                    </div>

                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> How do I clear all plugin settings?</h3>
                    <div>
                        Please click <a id="clear-all-linkedin-settings" href="#">here</a>.
                    </div>
                    
                    
                    <h3><i class="fa fa-question-circle-o" aria-hidden="true"></i> I am still having issues with the plugin, what can I Do?</h3>
                    <div>
                        Please visit the <a target="_blank" href="https://wordpress.org/support/plugin/wp-linkedin-auto-publish">forum</a>. <strong style="color: red !important;">Before writing on the forum make sure you have read the above FAQ and you have the latest version of this plugin installed and it would be a good idea to also make sure you have the latest version of WordPress installed and make sure your post has the below diagnostic information otherwise I won't respond. Also have you tried disabling all other plugins? Maybe there's a javascript issue caused by another plugin which is not allowing our plugin to work properly.</strong> Please be specific and screenshots often say a thousand words so please try and do this. I will try and resolve your issue from my end however sometimes I can't replicate every issue and in these circumstances I may ask you to provide access to your WordPress install so I can properly diagnose things. 
                        
                        <p><code><?php echo 'PHP Version: <strong>'.phpversion().'</strong>'; ?></br>
                        <?php echo 'Wordpress Version: <strong>'.get_bloginfo('version').'</strong>'; ?></br>
                        Plugin Version: <strong><?php echo wp_linkedin_autopublish_get_version(); ?></strong></br>
                    
                        <?php 
                                                                          
                        echo 'Do not share posts by default: <strong>'.$options['wp_linkedin_autopublish_default_publish'].'</strong></br>'; 
                                                  
                        echo 'Share method: <strong>'.$options['wp_linkedin_autopublish_share_method'].'</strong></br>'; 
                                                  
                                                                                                    
                        echo 'Currently authenticated: <strong>'.$optionsAuth['access_token_expiry'].'</strong></br>';     
                        
                        echo 'Default share profiles: <strong>'.$options['wp_linkedin_autopublish_default_share_profile'].'</strong></br>';  
                        
                        echo 'Default share message: <strong>'.$options['wp_linkedin_autopublish_default_share_message'].'</strong></br>';  

                        echo 'Sharing to post types: <strong>'.$options['wp_linkedin_autopublish_share_post_types'].'</strong></br>';  
              
                                                  
                                                  ?>
                                                  
        
                        Active Plugins:</br> 
                        <?php 
                        $active_plugins=get_option('active_plugins');
                        $plugins=get_plugins();
                        $activated_plugins=array();
                        foreach ($active_plugins as $plugin){           
                        array_push($activated_plugins, $plugins[$plugin]);     
                        } 

                        foreach ($activated_plugins as $key){  
                        echo '<strong>'.$key['Name'].'</strong></br>';
                        }

                        ?></code></p>
                        
                    </div>

                    

    
                
                    
                </div>
             
                <?php } elseif($tabName == 'authorisationPage') { ?>


                    <?php

                    $options = get_option( 'wp_linkedin_autopublish_settings' );
                    $optionsAuth = get_option( 'wp_linkedin_autopublish_auth_settings' );
    
                    $redirectUrl = 'https%3A%2F%2Fnorthernbeacheswebsites.com.au%2Fredirectlinkedin%2F';

                    $authorisationUrl = 'https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=8640n1zn844brm&redirect_uri='.$redirectUrl.'&state='.urlencode(wp_linkedin_autopublish_current_page_url()).'&scope=r_basicprofile%20r_emailaddress%20rw_company_admin%20w_share';
                    
                    //only do test if auth setting available
                    if(isset($optionsAuth['access_token'])){
                    
                    
                        $authenticationTest = wp_linkedin_autopublish_authentication_test();

                        if($authenticationTest == "SUCCESS"){

                            $authenticationMessage = __('You are successfuly authenticated! You will need to reauthenticate before: ', 'wp-linkedin-autopublish' ).$optionsAuth['access_token_expiry'].__('. To do so just click the button just below.', 'wp-linkedin-autopublish' );

                        } else {

                            $authenticationMessage = __('An error occured, the error reported by LinkedIn is: ', 'wp-linkedin-autopublish' ).' '.$authenticationTest;    
                        }

                        ?>

                        <div style="margin-top: 20px; margin-left: 20px; margin-right: 20px;" data-dismissible="disable-done-notice-forever" class="notice notice-info inline">
                            <p><h3><?php _e('Current Authentication Status', 'wp-linkedin-autopublish' ); ?></h3>

                        <?php echo $authenticationMessage; ?></p>
                        </div>

                    <?php } ?>

                    <a style="margin: 20px;" href="<?php echo esc_attr($authorisationUrl); ?>" name="linkedin_autopublish_get_authorisation" id="linkedin_autopublish_get_authorisation" class="button-secondary"><i style="color: #0077b5;" class="fa fa-linkedin-square" aria-hidden="true"></i> <?php _e('Connect with LinkedIn', 'wp-linkedin-autopublish' ); ?></a>    
                

                <?php } else { ?>
                
                <!--table-->
                <table class="form-table">


                <!--fields-->
                <?php
                settings_fields($tabName);
                do_settings_sections($tabName);
                ?>
                    
                <button type="submit" name="submit" id="submit" class="button button-primary linkedin-save-all-settings"><i class="fa fa-check-square" aria-hidden="true"></i>
 <?php _e('Save All Settings', 'wp-linkedin-autopublish' ); ?></button>  
                    
                </table>
                
                <?php } ?>
                

            </div> <!-- .inside -->
        </div> <!-- .postbox -->                      
    </div> <!-- .meta-box-sortables --> 
</div> <!-- .tab-content -->     
    <?php
}
/**
* 
*
*
* Add metabox to post
*/
function wp_linkedin_autopublish_metabox($postType){
    $options = get_option( 'wp_linkedin_autopublish_settings' );
    $explodedPostTypes = explode(",",$options['wp_linkedin_autopublish_share_post_types']);
    $explodedPostTypes = array_map('strtolower', $explodedPostTypes);
    
    if(in_array($postType,$explodedPostTypes)) {
        add_meta_box( 'wp_linkedin_autopublish_meta_box',__('WP LinkedIn Auto Publish Settings', 'wp-linkedin-autopublish' ), 'wp_linkedin_autopublish_build_meta_box',$postType,'side','high');      
    } 
}
add_action( 'add_meta_boxes', 'wp_linkedin_autopublish_metabox' );
/**
* 
*
*
* Add callback function to metabox content
*/
function wp_linkedin_autopublish_build_meta_box ($post) {
  $options = get_option( 'wp_linkedin_autopublish_settings' );
  wp_nonce_field( basename( __FILE__ ), 'wp_linkedin_autopublish_meta_box_nonce' );
    
    $current_custom_linkedin_share_message = get_post_meta( $post->ID, '_custom_linkedin_share_message', true );
    
    $current_dont_share_post_linkedin = get_post_meta( $post->ID, '_dont_share_post_linkedin', true );  
    
    $current_profile_selection_linkedin = get_post_meta( $post->ID, '_profile_selection_linkedin', true );    
    
    
?>
<div class='inside'>
    
    
    
    <p>        
    <?php if($current_dont_share_post_linkedin == "yes") $current_dont_share_post_linkedin_checked = 'checked="checked"'; ?>
    <div id="dont-sent-to-linkedin-checkbox-line">   
    <input id="dont-sent-to-linkedin-checkbox" <?php if(isset($options['wp_linkedin_autopublish_default_publish'])){echo 'data="dont-publish-by-default"';}?> type="checkbox" name="dont-share-post-linkedin" value="yes" <?php if(isset($current_dont_share_post_linkedin_checked)){ echo esc_attr($current_dont_share_post_linkedin_checked);} ?>> <?php echo __( 'Don\'t share this post', 'wp-linkedin-autopublish' ); ?></div>
    </p>
    
    
    
	<p class="custom-linkedin-metabox-setting"><?php echo __( 'Custom Share Message:', 'wp-linkedin-autopublish' ); ?><br>
        <textarea cols="29" rows="3" name="custom-linkedin-share-message" id="custom-share-message"><?php
    
        if(strlen($current_custom_linkedin_share_message)>0) {
           echo esc_attr($current_custom_linkedin_share_message); 
        } elseif (isset($options['wp_linkedin_autopublish_default_share_message'])) {
            echo esc_attr($options['wp_linkedin_autopublish_default_share_message']);
        } else {
            echo '';
        }  
    
        ?></textarea>
	</p>
    
    
    
    <div style="padding-top:5px;" class="custom-linkedin-metabox-setting"><?php echo __( 'Profile selection:', 'wp-linkedin-autopublish' ); ?><br>
        
        <ul id="post-meta-profile-list">

            <?php
            
            if(metadata_exists('post', $post->ID, '_profile_selection_linkedin')){
                
                $selectedItems = $current_profile_selection_linkedin;        
                $selectedItems = explode(",",$selectedItems);   
                
            } elseif(isset($options['wp_linkedin_autopublish_default_share_profile'])){
                $selectedItems = $options['wp_linkedin_autopublish_default_share_profile'];        
                $selectedItems = explode(",",$selectedItems);     
            } else {
                $selectedItems = array();    
            }                                            

            echo wp_linkedin_autopublish_get_companies_render_profile_list_items($selectedItems);     

            ?>                                            
        </ul>

        
        
        <input style="display:none;" name="profile-selection-linkedin"  id="profile-selection-linkedin" value="<?php
    
        if(metadata_exists('post', $post->ID, '_profile_selection_linkedin')) {
           echo esc_attr($current_profile_selection_linkedin); 
        } elseif(isset($options['wp_linkedin_autopublish_default_share_profile'])) {
            echo $options['wp_linkedin_autopublish_default_share_profile'];     
        } else {
            echo '';    
        }  
    
        ?>">
	</div>
    
    
    
    
    
  
    
    
    <?php if(metadata_exists('post', $post->ID, '_sent_to_linkedin')) {
    echo '<strong>Share History</strong></br>';
            
    foreach(array_reverse(get_post_meta($post->ID, '_sent_to_linkedin', true )) as $share){
            echo $share.'</br>';
    }                    
    }
    ?>
    <a href="" style="margin-top: 10px;" data="<?php echo $post->ID; ?>" class="custom-linkedin-metabox-setting button send-to-linkedin"><?php echo __( 'Share Now', 'wp_linkedin_autopublish' ); ?></a>

    <div style="display: none; margin-top:15px;" data-dismissible="disable-done-notice-forever" class="notice notice-success is-dismissible inline linkedin-settings-saved">
    <p><?php  _e('Settings saved', 'wp-linkedin-autopublish' ); ?></p>       
    </div>
    
    
</div>
<?php     
}
/**
* 
*
*
* Function to save meta box information
*/
function wp_linkedin_autopublish_save_meta_boxes_data($post_id,$post){
    if ( !isset( $_POST['wp_linkedin_autopublish_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['wp_linkedin_autopublish_meta_box_nonce'], basename( __FILE__ ) ) ){
	return;
    }
    //don't do anything for autosaves 
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}
    //check if user has permission to edit posts otherwise don't do anything 
    if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}
    
    //get and set options
    if ( isset( $_REQUEST['custom-linkedin-share-message'] ) ) {
		update_post_meta( $post_id, '_custom_linkedin_share_message', sanitize_text_field( $_POST['custom-linkedin-share-message'] ) );
	}
    
    if ( isset( $_REQUEST['profile-selection-linkedin'] ) ) {
		update_post_meta( $post_id, '_profile_selection_linkedin', sanitize_text_field( $_POST['profile-selection-linkedin'] ) );
	}
    
    
    if ( isset( $_REQUEST['dont-share-post-linkedin'] ) ) {
		update_post_meta( $post_id, '_dont_share_post_linkedin', sanitize_text_field( $_POST['dont-share-post-linkedin'] ) );
	} else {
        delete_post_meta($post_id, '_dont_share_post_linkedin');
    }
}
add_action( 'save_post', 'wp_linkedin_autopublish_save_meta_boxes_data',10,2);






















/**
* 
*
*
* Function share post on linkedin
*/
function wp_linkedin_autopublish_post_to_linkedin ($new_status, $old_status, $post) {

    //if the old status isn't published and the new statusis carry out the share to linkedin
    if ('publish' === $new_status) {
        
        //get options
        $options = get_option( 'wp_linkedin_autopublish_settings' );

        //get categories user has chosen not to share and separate comma values and turn it into an array
        $explodedCategories = explode(",",$options['wp_linkedin_autopublish_dont_share_categories']);

        //get the current category
        $thePostCategory = get_the_category($post->ID);
        $thePostCategoryArray = array();

        foreach($thePostCategory as $categoryName){
            array_push($thePostCategoryArray,$categoryName->name);       
        }

        //compare the 2 arrays and count how many duplicates there are 
        $thePostCategoryComparison = count(array_intersect($explodedCategories,$thePostCategoryArray));    

        //get the custom post types the user has nominated to share    
        $explodedPostTypes = explode(",",$options['wp_linkedin_autopublish_share_post_types']);
        $explodedPostTypes = array_map('strtolower', $explodedPostTypes);
        $postType = $post->post_type;

        //first check if the user has decided to not share the post and check if the user has nominated to not share category belonging to the post and then check if the user has nominated to share the post type whether this be a post, page or custom post type
        if(get_post_meta($post->ID, '_dont_share_post_linkedin', true ) !== "yes" && $thePostCategoryComparison == 0 && in_array($postType,$explodedPostTypes)) {  

            wp_linkedin_autopublish_post_to_linkedin_common ($post->ID);

        } //end if user has decided to share post
    } //end if post transition has gone to published
}
add_action( 'transition_post_status', 'wp_linkedin_autopublish_post_to_linkedin', 10, 3 );
/**
* 
*
*
* This function shares a post to LinkedIn by pressing the share to linkedin button
*/
function wp_linkedin_autopublish_post_to_linkedin_common ($postId){
    
    $options = get_option( 'wp_linkedin_autopublish_settings' );
    $optionsAuth = get_option( 'wp_linkedin_autopublish_auth_settings' );
    
    
    
    //create an associative array to be used for shortcode replacement    
    $variables = array("post_title"=>html_entity_decode(get_the_title($postId)),
                       "post_link"=>get_permalink($postId),
                       "post_excerpt"=>get_the_excerpt($postId),
                       "post_content"=>preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '',strip_tags(get_post_field('post_content',$postId))),
                       "post_author"=>get_the_author_meta('display_name',get_post_field('post_author',$postId)),
                       "website_title"=>html_entity_decode(get_bloginfo('name'))
                      );    
       
    
    //if the custom comment has been blanked out try getting the default message otherwise get the custom comment
    if(strlen(get_post_meta($postId, '_custom_linkedin_share_message', true ))<1) {
        $linkedinComment = $options['wp_linkedin_autopublish_default_share_message'];   
    } else {
        $linkedinComment = get_post_meta($postId, '_custom_linkedin_share_message', true ); 
    }
    
    
    
    //for each variable used replace it with the actual value  
    foreach($variables as $key => $value){
        $linkedinComment = str_replace('['.strtoupper($key).']', $value, $linkedinComment); 
    }
    
    
    //limit the comment to 700 characters total
    $linkedinComment = substr($linkedinComment, 0, 700);    
    
    
    
           
    //if simple share method is selected just post the visibility and custom message
    if($options['wp_linkedin_autopublish_share_method'] == "simple"){
        $json = json_encode( array(
        'visibility' => array(
            'code' => 'anyone'
        ),
        'comment' => $linkedinComment,
    ));
        
        

    } else {


        if(get_the_post_thumbnail_url($postId, 'full') == false){
            $thumbnailUrl = get_the_post_thumbnail_url($postId);
        } else {
            $thumbnailUrl = get_the_post_thumbnail_url($postId, 'full');
        }

        $json = json_encode(array(
        'visibility' => array(
            'code' => 'anyone'
        ),
        'comment' => $linkedinComment,
        'content' => array(
            'submitted‐image-url' => $thumbnailUrl,
            'title' => html_entity_decode(get_the_title($postId)),
            'submitted-url' => get_permalink($postId),
            'description' => get_the_excerpt($postId)
            ),
        ));  
    }
    
    

    //foreach starts here
    if(metadata_exists('post', $postId, '_profile_selection_linkedin')){
        
        $profilesToShareTo = get_post_meta($postId, '_profile_selection_linkedin', true ); 
    } else {
        $profilesToShareTo = $options['wp_linkedin_autopublish_default_share_profile'];
    }

    
    $profilesToShareToArray = explode(",",$profilesToShareTo);
    
    //if the array is empty return back the number
    //$countOfItemsInArray = count($profilesToShareToArray)-1;

    if(strlen($profilesToShareTo) < 1){
        return "no profile";
    }
    
    //get companies and profiles
    $getCompanies = wp_linkedin_autopublish_get_companies();
    $getProfile = wp_linkedin_autopublish_get_profile();
    
    
    //lets create an associative array to get company names 
    $companyNames = array();
    
    if($getCompanies !== 'ERROR'  && count($getCompanies['values']) > 0){
        foreach($getCompanies['values'] as $company){
            $companyNames[$company['id']] = $company['name'];    
        }
    }

    
    
    
    
    //loop through locations
    foreach($profilesToShareToArray as $profile){
    
    
        //we need to determine whether we are sharing to a profile or a company as the endpoint is different
        //to achieve this we are going to see if the profile is in the profile 
        if($profile == $getProfile['id']){ 
            $endPoint = "people/~";
            $shareName = $getProfile['firstName'].' '.$getProfile['lastName'];
            
        } else {
            $endPoint = "companies/".$profile; 
            $shareName = $companyNames[$profile];
        }
        

        //do API call to LinkedIn    
        $response = wp_remote_post( 'https://api.linkedin.com/v1/'.$endPoint.'/shares?format=json&oauth2_access_token='.$optionsAuth['access_token'], array(
            'headers' => array(
                'x-li-format' => 'json',
                'Content-Type' => 'application/json; charset=utf-8',
            ),
            'body' => $json,
        )); 
        

        //we should only be doing stuff if the call is successful!
        //we do 201 because hey thats what linkedin has decided to to do in their wisdom
        if ( 201 == wp_remote_retrieve_response_code($response) ) {

            //save the response to a new meta option 
            //get and decode the response    
            $decodedBody = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $response['body']), true); 

            //get current date and time in the wordpress format and to the wordpress timezone    
            $dateTime = date(get_option('date_format').' '.get_option('time_format'),strtotime(get_option('gmt_offset').' hours'));    


            $sharedUrl = explode("-",$decodedBody['updateKey']);
            $sharedUrl = $sharedUrl[2];    
            $sharedUrl = 'https://www.linkedin.com/feed/update/urn:li:activity:'.$sharedUrl;    

            //get the current time and create a link that goes to the post    
            $linkedinResponse = '<a target="_blank" href="'.$sharedUrl.'">'.$dateTime.' ('.$shareName.')</a>';   

            //update the post meta with time and URL        
            //if the post hasn't been shared before send an array with the data if it has been shared get the existing array and append the new item to the array
            if(metadata_exists('post',$postId,'_sent_to_linkedin')){

                $existingShares = array();
                foreach(get_post_meta($postId, '_sent_to_linkedin', true ) as $share){
                    array_push($existingShares,$share); 
                }
                array_push($existingShares,$linkedinResponse);
                update_post_meta($postId, '_sent_to_linkedin',$existingShares);

            } else {
                update_post_meta($postId, '_sent_to_linkedin',array($linkedinResponse));     
            } 
            
            update_post_meta($postId, '_dont_share_post_linkedin','yes');
        
        } //end status 200


    } //end for each
    
    return "success";
    
} //end function

/**
* 
*
*
* This function shares a post to LinkedIn by pressing the share to linkedin button
*/
function wp_linkedin_autopublish_post_to_linkedin_instantly (){
    
    //set php variables from ajax variables
    $postID = intval($_POST['postID']);

    if ( ! current_user_can( 'edit_post', $postID ) ){
		wp_die();
	}
  
    
    //call share method
    echo wp_linkedin_autopublish_post_to_linkedin_common ($postID);


    //return success
    //echo "success";
    wp_die(); // this is required to terminate immediately and return a proper response
    
}
add_action( 'wp_ajax_post_to_linkedin', 'wp_linkedin_autopublish_post_to_linkedin_instantly' );






/**
* 
*
*
* Function to prevent republishing post that has already been sent to linkedin by default
*/
function wp_linkedin_autopublish_dont_republish($post_id,$post){
if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}
    
    //check to see if post is published
    if('publish' == $post->post_status) { 
        update_post_meta( $post_id, '_dont_share_post_linkedin', 'yes');    
    }  
}
add_action( 'save_post', 'wp_linkedin_autopublish_dont_republish',11,2);
/**
* 
*
*
* This function makes the above function only run the first time
*/
function wp_linkedin_autopublish_remove_function_except_first_publish()
{
  remove_action('save_post','wp_linkedin_autopublish_dont_republish',11,2);
}
add_action('publish_to_publish','wp_linkedin_autopublish_remove_function_except_first_publish');
/**
* 
*
*
* Display warning message that the access token is about to expire
*/
function wp_linkedin_autopublish_token_expiry_warning() {
    
    //only show if current user can manage options as re-authentication can only occur on the settings page and only admin users can access this
    if (current_user_can('manage_options')) {

        $options = get_option( 'wp_linkedin_autopublish_auth_settings' );

        //if the user hasn't saved any settings yet there's no need to display this message
        if(isset($options['access_token_expiry']) && strlen($options['access_token_expiry'])>0){
            //get expiry date
            $expiryDate = $options['access_token_expiry'];
            $newExpiryDate = date_format(date_create_from_format('d/m/Y', $expiryDate), 'm/d/Y');
            $expiryDateUnix = strtotime($newExpiryDate);
            //get todays date
            $todaysDate = date('m/d/Y', time());
            $todaysDateUnix = strtotime($todaysDate);
            //get difference between dates
            $daysBetweenDates = ceil(($expiryDateUnix - $todaysDateUnix) / 86400);
            //show expiry date in a format based on users selected Wordpress date format
            $newExpiryDateLocalised = date_format(date_create_from_format('d/m/Y', $expiryDate), get_option('date_format'));

            $menuPage = menu_page_url('wp_linkedin_auto_publish',0);
            
            if(abs($daysBetweenDates) == 1) {
                $dayPlural = "day";    
            } else {
                $dayPlural = "days";    
            }
                
            if($daysBetweenDates < 8 && $daysBetweenDates > 0){
                $class = 'notice notice-error';
                $message = '<h3 style="margin-top: 0px;">WP LinkedIn Auto Publish Notice</h3> WP LinkedIn Auto Publish needs to be re-authenticated! If the plugin isn\'t re-authenticated the autopublish feature will stop working on: <strong>'. $newExpiryDateLocalised.'</strong> (that\'s just '.$daysBetweenDates.' '.$dayPlural.' away). <a style="font-weight:bold;" href="'.$menuPage.'">Click here</a> to re-authenticate.';

                printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
            }
            
            if($daysBetweenDates == 0){
                $class = 'notice notice-error';
                $message = '<h3 style="margin-top: 0px;">WP LinkedIn Auto Publish Notice</h3> WP LinkedIn Auto Publish needs to be re-authenticated! Automatic publishing of your posts to LinkedIn will stop today. <a style="font-weight:bold;" href="'.$menuPage.'">Click here</a> to re-authenticate.';

                printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
            }
            
            if($daysBetweenDates < 0){
                $class = 'notice notice-error';
                $message = '<h3 style="margin-top: 0px;">WP LinkedIn Auto Publish Notice</h3> WP LinkedIn Auto Publish needs to be re-authenticated! Automatic publishing of your posts to LinkedIn stopped working on: <strong>'. $newExpiryDateLocalised.'</strong> (that was '.abs($daysBetweenDates).' '.$dayPlural.' ago.). <a style="font-weight:bold;" href="'.$menuPage.'">Click here</a> to re-authenticate.';

                printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
            }
            
        } 
    }     

}
add_action( 'admin_notices', 'wp_linkedin_autopublish_token_expiry_warning' );
/**
* 
*
*
* Check if it's necessary to add a column to the all pages listing
*/
function wp_linkedin_autopublish_page_column_required(){
    //get option of what post types to share
    $options = get_option( 'wp_linkedin_autopublish_settings' );
    $explodedPostTypes = explode(",",$options['wp_linkedin_autopublish_share_post_types']);
    $explodedPostTypes = array_map('strtolower', $explodedPostTypes);
    if(in_array("page",$explodedPostTypes)){
        return true;    
    } else {
        return false;
    }
}
/**
* 
*
*
* Create new column on the posts page
*/
function wp_linkedin_autopublish_additional_posts_column($columns) {
    
    $options = get_option( 'wp_linkedin_autopublish_settings' );
    
    if(isset($options['wp_linkedin_autopublish_hide_posts_column'])){
        return $columns;
    } else {
        $new_columns = array(
        'shared_on_linkedin' => __( 'Shared on LinkedIn', 'wp-linkedin-autopublish' ),
        );
        $filtered_columns = array_merge( $columns, $new_columns );
        return $filtered_columns;       
    }
}
add_filter('manage_posts_columns', 'wp_linkedin_autopublish_additional_posts_column');
if(wp_linkedin_autopublish_page_column_required()==true){
    add_filter('manage_page_posts_columns', 'wp_linkedin_autopublish_additional_posts_column');   
}
/**
* 
*
*
* Add content to the new posts page column
*/
function wp_linkedin_autopublish_additional_posts_column_data( $column ) {
    
    $options = get_option( 'wp_linkedin_autopublish_settings' );
    
    // Get the post object for this row so we can output relevant data
    global $post;
  
    // Check to see if $column matches our custom column names
    switch ( $column ) {

    case 'shared_on_linkedin' :
    if(metadata_exists('post', $post->ID, '_sent_to_linkedin')) {
    foreach(array_reverse(get_post_meta($post->ID, '_sent_to_linkedin', true )) as $share){
            echo $share.'</br>';
    }   
    } else {
       
        echo 'Not shared <a class="send-to-linkedin" href="" data="'.$post->ID.'">Share now</a>';    
                
       //edit_post_link( 'share now', 'Not shared ', '', $post->ID, '');
        
        
    } 
      break;    
    }
}
add_action( 'manage_posts_custom_column', 'wp_linkedin_autopublish_additional_posts_column_data' );
// if pages have been opted not to be shared hide the column on the all pages listing
if(wp_linkedin_autopublish_page_column_required()==true){
    add_action('manage_page_posts_custom_column', 'wp_linkedin_autopublish_additional_posts_column_data');
}
/**
* 
*
*
* Add translation
*/
add_action('plugins_loaded', 'wp_linkedin_autopublish_translations');
function wp_linkedin_autopublish_translations() {
	load_plugin_textdomain( 'wp-linkedin-autopublish', false, dirname( plugin_basename(__FILE__) ) . '/inc/lang/' );
}
/**
* 
*
*
* Function to get companies
*/
function wp_linkedin_autopublish_get_companies() {
	
    $getTransient = get_transient('wp_linkedin_autopublish_get_companies'); 
    
    //if the transient exists
    if ($getTransient != false){

        return $getTransient;

    } else {
    
        $options = get_option( 'wp_linkedin_autopublish_auth_settings' );

        $json_feed = wp_remote_get( 'https://api.linkedin.com/v1/companies?oauth2_access_token='.$options['access_token'].'&format=json&is-company-admin=true', array(
            'headers' => array(
                'Connection
        ' => 'Keep-Alive',
                'X-Target-URI' => 'https://api.linkedin.com',
            ),
        ));

        $decodedBody = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $json_feed['body']), true);
        $json_response = wp_remote_retrieve_response_code($json_feed); 

        if($json_response == 200) {
            
            set_transient( 'wp_linkedin_autopublish_get_companies',$decodedBody,MINUTE_IN_SECONDS*5);
            
            return $decodedBody;    
        } else {
            return 'ERROR';
        }
        
    }

}
/**
* 
*
*
* Function to get profile
*/
function wp_linkedin_autopublish_get_profile() {
	
    
    $getTransient = get_transient('wp_linkedin_autopublish_get_profile'); 
    
    
    //if the transient exists
    if ($getTransient != false){

        return $getTransient;

    } else {
    
    
        $options = get_option( 'wp_linkedin_autopublish_auth_settings' );

        $json_feed = wp_remote_get( 'https://api.linkedin.com/v1/people/~?oauth2_access_token='.$options['access_token'].'&format=json', array(
            'headers' => array(
                'Connection
        ' => 'Keep-Alive',
                'X-Target-URI' => 'https://api.linkedin.com',
            ),
        ));

        $decodedBody = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $json_feed['body']), true);
        $json_response = wp_remote_retrieve_response_code($json_feed); 

        if($json_response == 200) {
            
            set_transient( 'wp_linkedin_autopublish_get_profile',$decodedBody,MINUTE_IN_SECONDS*5);
            
            return $decodedBody;    
        } else {
            return 'ERROR';
        }

   
        
        
    }
    
}
/**
* 
*
*
* function to dismiss welcome message for current version
*/
function wp_linkedin_autopublish_dismiss_welcome_message() {
    
    if (!current_user_can('manage_options')) {
        wp_die();    
    }
    
    
	//get options
    $options = get_option( 'wp_linkedin_autopublish_settings' );
    
    $pluginVersion = sanitize_text_field($_POST['pluginVersion']);
    
    $options['wp_linkedin_autopublish_dismiss_welcome_message'] = $pluginVersion;
    
    //update the options
    update_option('wp_linkedin_autopublish_settings', $options);
    
    echo 'SUCCESS';
    wp_die();    
    
    
}
add_action( 'wp_ajax_dismiss_welcome_message', 'wp_linkedin_autopublish_dismiss_welcome_message' );
/**
* 
*
*
* Function to get profile
*/
function wp_linkedin_autopublish_authentication_test() {
	
    $options = get_option('wp_linkedin_autopublish_auth_settings' );
   
    $json_feed = wp_remote_get( 'https://api.linkedin.com/v1/people/~?oauth2_access_token='.$options['access_token'].'&format=json', array(
        'headers' => array(
            'Connection
    ' => 'Keep-Alive',
            'X-Target-URI' => 'https://api.linkedin.com',
        ),
    ));

    $decodedBody = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $json_feed['body']), true);
    $json_response = wp_remote_retrieve_response_code($json_feed); 

    if($json_response == 200) {
        return 'SUCCESS';    
    } else {
        return $json_response.' '.$decodedBody['message'];
    }

}
/**
* 
*
*
* Function to get a list of profiles and company used in meta and plugin settings
*/
function wp_linkedin_autopublish_get_companies_render_profile_list_items($selectedItems) {
    
    $options = get_option( 'wp_linkedin_autopublish_settings' );
    $existingSetting = $options['wp_linkedin_autopublish_profile_selection'];
    if(isset($existingSetting)){
        $settingToArray = explode(",",$existingSetting);     
    } else {
        $settingToArray = array();    
    }
    
    
    $getCompanies = wp_linkedin_autopublish_get_companies();
    $getProfile = wp_linkedin_autopublish_get_profile();
    




    //set the variable initially
    $html = '';


    if($getProfile !== "ERROR"){

        if(in_array($getProfile['id'], $settingToArray)){
            
            if(in_array($getProfile['id'], $selectedItems)){
                $listClass = 'selected';
                $iconClass = 'fa-check-circle-o';
            } else {
                $listClass = ''; 
                $iconClass = 'fa-times-circle-o';
            }


            $html .= '<li class="profile-selection-list-item-small '.$listClass.'" data="'.$getProfile['id'].'">';

        //                //image 
        //                $html .= '<img src="'.$locationImage.'" class="location-image" height="42" width="42">';

                //location information
                $html .= '<div class="profile-information">';
                    
                    //address
                    $html .= '<span class="profile-name">'.$getProfile['firstName'].' '.$getProfile['lastName'].'</span>';
            
                    //name
                    $html .= '<span class="profile-description">Profile</span>';


                $html .= '</div>';

                //render appropriate icon
                $html .= '<i class="profile-selected-icon fa '.$iconClass.'" aria-hidden="true"></i>';

            $html .= '</li>';    
  
        }
        
    } //end if profile    
        
        
        
        
        



    if($getCompanies !== "ERROR" && count($getCompanies['values']) > 0){


        foreach ($getCompanies['values'] as $company) {
            
            if(in_array($company['id'], $settingToArray)){

                if(in_array($company['id'], $selectedItems)){
                    $listClass = 'selected';
                    $iconClass = 'fa-check-circle-o';
                } else {
                    $listClass = ''; 
                    $iconClass = 'fa-times-circle-o';
                }

                $html .= '<li class="profile-selection-list-item-small '.$listClass.'" data="'.$company['id'].'">';

        //                //image 
        //                $html .= '<img src="'.$locationImage.'" class="location-image" height="42" width="42">';

                    //location information
                    $html .= '<div class="profile-information">';
                        
                        //address
                        $html .= '<span class="profile-name">'.$company['name'].'</span>';
                
                        //name
                        $html .= '<span class="profile-description">Company</span>';

                        

                    $html .= '</div>';

                    //render appropriate icon
                    $html .= '<i class="profile-selected-icon fa '.$iconClass.'" aria-hidden="true"></i>';

                $html .= '</li>';    
            }
                
        }
    }  //end companies  
    
    
    return $html;

}
/**
* 
*
*
* This function updates the post meta when changed on the post
*/
function wp_linkedin_autopublish_update_meta_on_post(){
    
    $post = intval($_POST['postID']);
    
    if ( ! current_user_can( 'edit_post', $post ) ){
		wp_die();
	}
    
    
    $updatedShareMessage = sanitize_text_field($_POST['updatedShareMessage']);
    $dontShareAction = sanitize_text_field($_POST['dontShareAction']);
    $profiles = sanitize_text_field($_POST['profiles']);


    update_post_meta($post, '_custom_linkedin_share_message',$updatedShareMessage);
    update_post_meta($post, '_profile_selection_linkedin',$profiles);

    
    if($dontShareAction == "update"){
        update_post_meta($post, '_dont_share_post_linkedin','yes');     
    } else {
        delete_post_meta($post, '_dont_share_post_linkedin');    
    }



    echo "success";
    wp_die();
    

}
add_action( 'wp_ajax_update_linkedin_post_meta', 'wp_linkedin_autopublish_update_meta_on_post' );
/**
* 
*
*
* Display warning message about version 6.0
*/
function wp_linkedin_autopublish_version_six_notice() {
    $optionsAuth = get_option( 'wp_linkedin_autopublish_auth_settings' );
    $options = get_option( 'wp_linkedin_autopublish_settings' );
    
    $menuPage = menu_page_url('wp_linkedin_auto_publish',0);
    
    //if the user has existing settings but dont have the new auth settings it means we should show this message
    
    if(!isset($optionsAuth['access_token']) && isset($options['wp_linkedin_autopublish_default_share_message']) ){
              
    
        $class = 'notice notice-error';
        $message = '<h3 style="margin-top: 0px;">WP LinkedIn Auto Publish Important Upgrade Notice - PLEASE READ</h3> 
        
        <p>Thanks for upgrading to version 6. <strong>Version 6 is a big update to the plugin and it requires that you re-authenticate the plugin immidiately and also review the new settings that are available otherwise things won\'t work</strong>. There are 2 big changes in version 6 which resolve major pain points of the plugin, so we think this version is a big win for everyone. Firstly you no longer need to create a LinkedIn application anymore, you can just connect to mine. This is going to be heaps easier for new users as creating the application can be a bit fiddly and took a lot of support time. So if you have re-authenticated the plugin feel free to remove your existing LinkedIn application you created (that is providing you are not using it anywhere else).</p>
        
        <p>Secondly we have added a commonly requested feature, now you can share to a profile and/or companies in one go! You will now see the new "Profile Selection" tab in the settings where you can select what profile and/or companies you want to use with the plugin. Then in the "Sharing Options" tab you can choose the default profile and/or companies you want to share with, and this profile/and or companies will show in the post/page/custom post meta box.</p>
        
        
        <p>Also you might be interested in a new plugin we released called WP Google My Business Auto Publish. It enables you to do all the great things WP LinkedIn Auto Publish does but for Google My Business, and like this plugin it\'s fully free. Check it out <a href="'.get_admin_url().'plugin-install.php?tab=plugin-information&plugin=wp-google-my-business-auto-publish">here</a>.</p>
        
        <p>To remove this message please <a href="'.$menuPage.'">re-authenticate the plugin</a>.</p>
        
    
        
        
        
        
        ';

        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
     

    }      
}
add_action( 'admin_notices', 'wp_linkedin_autopublish_version_six_notice' );
/**
* 
*
*
* This function deletes all plugin settings
*/
function wp_linkedin_autopublish_delete_all_linkedin_settings(){
    
    //delete options
    delete_option( 'wp_linkedin_autopublish_auth_settings' );
    delete_option( 'wp_linkedin_autopublish_settings' );

    //delete transients
    delete_transient( 'wp_linkedin_autopublish_get_companies' );
    delete_transient( 'wp_linkedin_autopublish_get_profile' );

    echo 'SUCCESS';

    wp_die();
    

}
add_action( 'wp_ajax_delete_all_linkedin_settings', 'wp_linkedin_autopublish_delete_all_linkedin_settings' );



?>