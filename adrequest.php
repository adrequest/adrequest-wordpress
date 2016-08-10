<?php
/*
Plugin Name: Adrequest
Plugin URI: http://www.adrequest.net/
Description: Adds functinality of Adrequest Advertising to your website
Author: Jaap Marcus
Version: 1.0
Author URI: http://mino.to
*/

add_action( 'admin_menu', 'adrequest_add_admin_menu' );
add_action( 'admin_init', 'adrequest_settings_init' );
add_action('wp_head', 'adrequest_get_license');

function adrequest_get_license( ){
	$options = get_option( 'adrequest_settings' );
	echo '<!-- BEGIN Adrequest -->
<script>
  var LicenseCode = {code: "'.$options['license'].'"}
  var resource = document.createElement("script"); 
  resource.src = "//dl.adrequest.net/'.str_replace(array('www.','.'), array('','_'), $_SERVER['SERVER_NAME']).'.php";
  var script = document.getElementsByTagName("script")[0];
  script.parentNode.insertBefore(resource, script);  
</script>
<!-- END Adrequest -->';

}

function adrequest_add_admin_menu(  ) { 

	add_submenu_page( 'options-general.php', 'Adrequest', 'Adrequest', 'manage_options', 'adrequest', 'adrequest_options_page' );

}


function adrequest_settings_init(  ) { 

	register_setting( 'pluginPage', 'adrequest_settings' );

	add_settings_section(
		'adrequest_pluginPage_section', 
		__( 'Licenstie', 'wordpress' ), 
		'adrequest_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'adrequest_license', 
		__( 'Licenstie', 'wordpress' ), 
		'adrequest_license', 
		'pluginPage', 
		'adrequest_pluginPage_section' 
	);


}


function adrequest_license(  ) { 
	$options = get_option( 'adrequest_settings' );
	?>
	<input type='text' name='adrequest_settings[license]' value='<?php echo $options['license']; ?>'>
	<?php

}


function adrequest_settings_section_callback(  ) { 
//	echo __( 'Licenstie', 'wordpress' );
}


function adrequest_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>Adrequest</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}

?>