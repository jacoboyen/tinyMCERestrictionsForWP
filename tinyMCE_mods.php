<?php
/*
Plugin Name: TinyMCE restrictions for WordPress
Plugin URI: http://www.central.edu
Description: Restricts WordPress to only use defined tags in the editor for posts.
Version: 0.01
Author: Jacob Oyen

*/

$tinyMCE_restrictions = get_option( 'jco_tinyMCE_restrictions' );
if (is_null($tinyMCE_restrictions)){
	$tinyMCE_restrictions = '';	
}

/*Create an options page for the plugin */
add_action('admin_menu', 'jco_tinyMCE_admin_menu');

function jco_tinyMCE_admin_menu(){
	add_plugins_page('TinyMCE restrictions', 'TinyMCE restrictions', 'manage_options', 'jco-tinyMCE-restrictions', 'jco_tinyMCE_options');
}

function jco_tinyMCE_options(){
	if (!current_user_can('manage_options')){
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );	
	}
	$opt_name = 'jco_tinyMCE_restrictions';
	$hidden_field_name = 'jco_submit_hidden';
	$data_field_name = 'jco_current_status';
		
	$opt_value = get_option($opt_name);
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );
	}
	else{
		$opt_val = get_option( 'jco_tinyMCE_restrictions' );
	}
	
	//Use this HTML for the admin page:
	?>
    <div class="wrap">
    <?php screen_icon('options-general'); ?>
    <h2>TinyMCE restrictions</h2>	
    <p><strong>Current Restrictions:</strong> <?php echo get_option( $opt_name ); ?> </p>
    <hr size="1"/>
    <h3>Update Restrictions</h3></div>
    <p><a href="http://www.tinymce.com/wiki.php/configuration:valid_elements">See the TinyMCE doucmentation</a> for formatting of valid elements.</p>
    <p>Allow only these tags:</p>
	<form name="form1" method="post" action="">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
    <input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="150">
	
		<p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
	</p>
	
	</form>
    </div>
    <?php
}

/* Make the modifications to TinyMCE */
function jco_tinyMCE_modificatons($init_array){
	$init_array['valid_elements'] = get_option( 'jco_tinyMCE_restrictions' );
	
	return $init_array;
}
add_filter('tiny_mce_before_init', 'jco_tinyMCE_modificatons'); 

?>