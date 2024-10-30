<?php
/*
Plugin Name: iDonate Donation
Plugin URI: http://idonate.ie/
Description: iDonate.ie Online charity Fundraising
Author: Alan Coyne
Version: 1.0.1
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

ini_set('max_execution_time', 300);
add_action('wp_logout', 'iDonateD_myEndSession');
add_action('wp_login', 'iDonateD_myEndSession');

define( 'iDonateD_DOMAIN', 'https://dev.idonatetest.com/' );

function iDonateD_is_connected()
{

    $connected = @fsockopen("www.google.com", 80); 
                                        //website, port  (try 80 or 443)
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;

}
	

function iDonateD_myEndSession() {
	$iDonateD_active = '';
	$iDonateD_chid = '';
}

function iDonateD_getDbAccess() {
	global $wpdb;	//required global declaration of WP variable	
	global $iDonateD_active;
	global $iDonateD_chid;
	$table_name = $wpdb->prefix . 'idonateie';
	
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
	
		$myrows = $wpdb->get_results( "SELECT charity_id FROM $table_name" );
		if(count($myrows) > 0) {
			return $iDonateD_active = 'active|'.$myrows[0]->charity_id;
		}
	} else {
		iDonateD_myEndSession();
		remove_all_shortcodes();
		return $iDonateD_active = '|';
		//$_SESSION['active'] = '';
	}
	
}

function iDonateD_remove_my_shortcode() {

	remove_shortcode( 'getFundraiserList' );

}

define( 'IDONATED_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'IDONATED_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

function iDonateD_apikey_menu() {
	add_menu_page( 'iDonate', 'iDonate', 'manage_options', 'idonate-donation', 'iDonateD_edd_apikey_page' , plugins_url( 'idonate-donation/media/index.ico' ) );
}
add_action('admin_menu', 'iDonateD_apikey_menu');



function wptuts_scripts_basic()
{
	wp_enqueue_script( 'jquery');

    // Register the script like this for a plugin:
    wp_enqueue_script( 'custom-script', plugins_url( 'idonate-donation/media/js/js.js' ), true  );
    wp_enqueue_script( 'custom-script-datatable', plugins_url( 'idonate-donation/media/js/datatable.js' ), true );
    wp_enqueue_script( 'custom-script-tipso', plugins_url( 'idonate-donation/media/js/tipso.min.js' ), true );
	wp_enqueue_script( 'custom-script-simplebox', plugins_url( 'idonate-donation/media/js/simplbox.js' ), '', '1.0', true );
	wp_enqueue_script( 'custom-script-scroll', plugins_url( 'idonate-donation/media/js/jquery.mCustomScrollbar.concat.min.js' ),  true );
	//wp_enqueue_script( 'custom-script-sharepagejshere', plugins_url( 'idonate-donation/media/js/page.js' ), true );
    
	// For either a plugin or a theme, you can then enqueue the script:
    //wp_enqueue_script( 'custom-script' );
}
add_action( 'wp_enqueue_scripts', 'wptuts_scripts_basic' );

function wptuts_styles_with_the_lot()
{
    // Register the style like this for a plugin:
    wp_enqueue_style( 'custom-style', plugins_url( 'idonate-donation/media/css/style.css'), false );
    wp_enqueue_style( 'custom-style-css', plugins_url( 'idonate-donation/media/css/datatable.css'), false );
    wp_enqueue_style( 'custom-style-tipso', plugins_url( 'idonate-donation/media/css/tipso.min.css'), false );
    wp_enqueue_style( 'custom-style-scroll', plugins_url( 'idonate-donation/media/css/jquery.mCustomScrollbar.css'), false );
	
    // For either a plugin or a theme, you can then enqueue the style:
    //wp_enqueue_style( 'custom-style' );
}
add_action( 'wp_enqueue_scripts', 'wptuts_styles_with_the_lot' );


function iDonateD_edd_apikey_page() {
	global $wpdb;
	global $iDonateD_active;

?>


<script type="text/javascript">
        jQuery(document).ready(function() {
			jQuery("#submit").click(function(){
				var apival = jQuery("#check_api_key").val();
				if(apival == '') {
					alert('Please enter valid API key');
					return false;
				} 
				//jQuery("#activationForm").submit();
			});
			
		});
    </script>
<?php 

if($iDonateD_active != '') {

	$table_name = $wpdb->prefix . 'idonateie';
	
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
	
		$myrows = $wpdb->get_results( "SELECT charity_id FROM $table_name" );
		if(count($myrows) > 0) {
			$iDonateD_active = 'active';
		}
	} else {
		iDonateD_myEndSession();
		remove_all_shortcodes();
		$iDonateD_active = '';
	}
} else {

if($iDonateD_active == '' && isset($_POST) && $_POST['check_api_key'] != '') {

	$data = file_get_contents(iDonateD_DOMAIN."api/approveCharityApiKey?apikey=".$_POST['check_api_key']);
	$data = json_decode($data, true); // Turns it into an array, change the last argument to false to make it an object
	
	if($data != '') {
		$iDonateD_active = 'active';
	//Variables
	$charityid = $data['apiResponse']['charity_id'];
	
	
	$charset_collate = $wpdb->get_charset_collate();
	
	$table_name = $wpdb->prefix . 'idonateie';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  charity_id mediumint(9) NULL,
		  api_key text NULL,
		  created datetime NULL
		) $charset_collate;";
		//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		//dbDelta( $sql );
		$wpdb->query($sql);
	}
	
	$wpdb->insert( 
	$table_name, 
		array( 
			'id' => NULL, 
			'charity_id' => $data['apiResponse']['charity_id'],
			'api_key' => $data['apiResponse']['apikey'],
			'created' => current_time('mysql', 1)
		) 
	);

	} else {
	    $iDonateD_active = '';
		remove_all_shortcodes();
		$msg = 'Invalid API key.';
	}
	
} else {

	$table_name = $wpdb->prefix . 'idonateie';
	
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
	
		$myrows = $wpdb->get_results( "SELECT charity_id FROM $table_name" );
		count($myrows);
		if(count($myrows) > 0) {
			$iDonateD_active = 'active';
		}
	} else {
		remove_all_shortcodes();
		$iDonateD_active = '';
	}

}

}

?>

	
	<div class="wrap">
		<h2><?php _e('iDonate Api Key'); ?></h2>
		<br />
		<?php //echo $_SERVER['PHP_SELF'].'?page=idonate-donation'; ?>
		<?php if($iDonateD_active == 'active') { ?>
			<div class="media-toolbar"><div class="updated notice " id="message"><p>Plugin <strong>Activated</strong>.</p></div></div>
			
			<div class="activeShortCodeNote notice">
				<p>
					Please user below shortcode to active your fundraisers list
				</p>
				
				<p>
					Put this code in text editor in post or page <font color="#3366CC">[getFundraiserList]</font>
				</p>
				
				<p>
				Or
				</p>
				
				<p>
					Use in php file  <font color="#3366CC"> &lt;&#63;php echo do_shortcode( '[getFundraiserList]' ); &#63;&gt; </font>
				</p>
				
			</div>
			
			
		<?php 
		} else { ?>
		<form name="activationForm" id="activationForm" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?page=idonate-donation'; ?>">
		
			<div class="postbox ">
			<div class="inside">
			<div class="form-field form-required term-name-wrap">
				<label for="tag-name"><strong><?php _e('Api Key : '); ?></strong></label>
				<input id="check_api_key" name="check_api_key" type="text" aria-required="true" value="" class="regular-text"  />						
				<input id="hideChid" name="hideChid" type="hidden"  />
				<input id="pluginaction" name="pluginaction" type="hidden" />
				<?php if($msg != '') { echo "<p>".$msg."</p>"; }  ?>
				<p>Please enter API key which provided by <strong>iDonate.ie</strong>.</p>
			</div>
			<?php submit_button(); ?>
			</div>
			</div>
		
		</form>
		
	<?php
	} 



}

function iDonateD_POD_deactivate()
{
	global $wpdb;	//required global declaration of WP variable

	$table_name = $wpdb->prefix.'idonateie';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
		$sql = "DROP TABLE ". $table_name;
		$wpdb->query($sql);
		$iDonateD_active = '';
		remove_all_shortcodes();
	}

}

function iDonateD_POD_activate()
{
		remove_all_shortcodes();
		$iDonateD_active = '';
}

register_deactivation_hook( __FILE__ , 'iDonateD_POD_deactivate' );
register_activation_hook( __FILE__ , 'iDonateD_POD_activate' );

require_once( IDONATED_PLUGIN_DIR . 'getFundraisersList.php' );

?>