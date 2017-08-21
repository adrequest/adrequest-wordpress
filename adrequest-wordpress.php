<?php
/*
Plugin Name: Adrequest
Plugin URI: https://github.com/adrequest/adrequest-wordpress/
Description: Voegt Adrequest Advertensie functionaliteit toe aan uw website
Author: Jaap Marcus
Version: 1.2.0
Author URI: http://adrequest.com
Text Domain: adrequest-inarticle
*/

add_action( 'admin_menu', 'adrequest_inarticle_add_admin_menu' );
add_action( 'admin_init', 'adrequest_inarticle_settings_init' );
add_action( 'wp_head', 'adrequest_inarticle_get_license' );
add_action( 'wp_footer', 'adrequest_preroll_get_license' );
add_action( 'plugins_loaded', 'wan_load_textdomain' );

function wan_load_textdomain() {
	load_plugin_textdomain( 'adrequest-inarticle', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

// Functions:
function adrequest_inarticle_get_license( ){
	
	$options = get_option( 'adrequest_inarticle_settings' );
	echo '<!-- BEGIN Adrequest -->
<script>
 var resource = document.createElement("script"); 
  resource.src = "//ad.adrequest.net/js/'.$options['license'].'";
  var script = document.getElementsByTagName("script")[0];
  script.parentNode.insertBefore(resource, script);  
</script>
<!-- END Adrequest -->';	

}

function adrequest_preroll_get_license( ){
	
	$options = get_option( 'adrequest_preroll_settings' );
	echo '<!-- BEGIN Adrequest -->
<script>
  var License = "'.$options['license'].'";
  var resource = document.createElement("script"); 
   resource.src = "//dl.adrequest.net/'.str_replace(array('www.','.'), array('','_'), $_SERVER['SERVER_NAME']).'.js"; 
   var script = document.getElementsByTagName("script")[0];
  script.parentNode.insertBefore(resource, script);  
</script>
<!-- END Adrequest -->';	

}

function adrequest_inarticle_add_admin_menu(  ) { 

	add_submenu_page( 'options-general.php', 'Adrequest', 'Adrequest', 'manage_options', 'adrequest-inarticle', 'adrequest_inarticle_options_page' );

}


function adrequest_inarticle_settings_init(  ) { 
// Translations:

$licencetranslation = __( 'Licentie', 'adrequest-inarticle' );
$paragraphtranslation = __( 'Plaats na alinea', 'adrequest-inarticle' );
$minimumtranslation = __( 'Minimaal', 'adrequest-inarticle' );
$belowarticletranslation = __( 'Op de index onder x artikel een advertentie (99 voor geen)', 'adrequest-inarticle' );

	register_setting( 'inarticlePlugin', 'adrequest_inarticle_settings' );
	register_setting( 'pluginPage', 'adrequest_preroll_settings' );
	
	add_settings_section(
		'adrequest_inarticle_pluginPage_section', 
		__( 'InArticle', 'wordpress' ), 
		'adrequest_inarticle_settings_section_callback', 
		'inarticlePlugin'
	);
	add_settings_section(
		'adrequest_preroll_pluginPage_section', 
		__( 'Youtube Preroll', 'wordpress' ), 
		'adrequest_preroll_settings_section_callback', 
		'pluginPage'
	);
	
	add_settings_field( 
		'adrequest_inarticle_license', 
		$licencetranslation, 
		'adrequest_inarticle_license', 
		'inarticlePlugin', 
		'adrequest_inarticle_pluginPage_section' 
	);
	add_settings_field( 
		'adrequest_inarticle_paragraph', 
		$paragraphtranslation,
		'adrequest_inarticle_paragraph', 
		'inarticlePlugin', 
		'adrequest_inarticle_pluginPage_section' 
	);
	add_settings_field( 
		'adrequest_inarticle_min_paragraph', 
		$minimumtranslation, 
		'adrequest_inarticle_min_paragraph', 
		'inarticlePlugin', 
		'adrequest_inarticle_pluginPage_section' 
	);
	add_settings_field( 
		'adrequest_inarticle_article_posts', 
		$belowarticletranslation, 
		'adrequest_inarticle_article_posts', 
		'inarticlePlugin', 
		'adrequest_inarticle_pluginPage_section' 
	);	
	
	add_settings_field( 
		'adrequest_preroll_license',
		$licencetranslation,
		'adrequest_preroll_license',
		'pluginPage',
		'adrequest_preroll_pluginPage_section'
	);	

}

function adrequest_inarticle_banner($query){
	static $count = 0;
	$options = get_option( 'adrequest_inarticle_settings' );
	if(!is_single()){
	if($query->is_main_query()){
		add_action( 'the_post', 'adrequest_inarticle_banner_between_articles' );
        add_action( 'loop_end', 'adrequest_inarticle_banner_loop_end' );	 
	}
	}
	
};
function adrequest_inarticle_banner_between_articles($content){
	static $nr =  0;
	$options = get_option( 'adrequest_inarticle_settings' );	
	if($options['article-pos'] == 0 || $options['article-pos'] > 90){
	
	}else{
	if($options['article-pos'] == $nr){
		echo '<div id="inarticle-watcher" class="inarticle-article"></div>';
	}
	}
	$nr++;
}
function adrequest_inarticle_banner_loop_end(){
	// remove_action('the_post', 'adrequest_inarticle_banner_between_articles'); 
}



add_action ( 'loop_start', 'adrequest_inarticle_banner');

function adrequest_inarticle( $atts ) {
	if(is_single()){
	return '<div id="inarticle-watcher"></div>';
	}
}
add_shortcode( 'adrequest', 'adrequest_inarticle' );


function adrequest_inarticle_license(  ) { 
	$options = get_option( 'adrequest_inarticle_settings' );
	?>
	<input type='text' name='adrequest_inarticle_settings[license]' value='<?php echo $options['license']; ?>'>
	<?php
}

function adrequest_inarticle_min_paragraph(  ) { 
	$options = get_option( 'adrequest_inarticle_settings' );
	?>
	<input type='text' name='adrequest_inarticle_settings[min-paragraph]' value='<?php echo $options['min-paragraph']; ?>'>
	<?php
}
function adrequest_inarticle_paragraph(  ) { 
	$options = get_option( 'adrequest_inarticle_settings' );
	?>
	<input type='text' name='adrequest_inarticle_settings[paragraph]' value='<?php echo $options['paragraph']; ?>'>
	<?php
}


function adrequest_inarticle_article_posts(  ) { 
	$options = get_option( 'adrequest_inarticle_settings' );
	?>
	<input type='text' name='adrequest_inarticle_settings[article-pos]' value='<?php echo $options['article-pos']; ?>'>
	<?php
}
function adrequest_preroll_license(  ) { 
	$options = get_option( 'adrequest_preroll_settings' );
	?>
	<input type='text' name='adrequest_preroll_settings[license]' value='<?php echo $options['license']; ?>'>
	<?php
}

function __check_paragraph_count_blog( $content ) {
    global $post;
    if ( $post->post_type == 'post' ) {
        $count = substr_count( $content, '</p>' );
        return $count;
    } else {
        return 0;
    }
}

// Parent Function that makes the magic happen
function prefix_insert_after_paragraph( $insertion, $paragraph_id, $content ) {
    $closing_p = '</p>';
    $paragraphs = explode( $closing_p, $content );
    foreach ($paragraphs as $index => $paragraph) {

        if ( trim( $paragraph ) ) {
            $paragraphs[$index] .= $closing_p;
        }

        if ( $paragraph_id == $index + 1 ) {
            $paragraphs[$index] .= $insertion;
        }
    }

    return implode( '', $paragraphs );
}

function adrequest_inarticle_find_pos($content){
	$options = get_option( 'adrequest_inarticle_settings' );
	if(is_single()){
	$c = __check_paragraph_count_blog($content);
	if($c > $options['min-paragraph']){
		$content = prefix_insert_after_paragraph('<div id="inarticle-watcher"></div>', $options['paragraph'], $content);
	}else{
		//doe niets
	}
	}
	
	return $content;
}

add_filter( 'the_content', 'adrequest_inarticle_find_pos' );


function adrequest_inarticle_settings_section_callback(  ) { 
//	echo __( 'Licenstie', 'wordpress' );
}
function adrequest_preroll_settings_section_callback(  ) { 
//	echo __( 'Licenstie', 'wordpress' );
}

function adrequest_inarticle_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h1>Adrequest</h1>
		
		<?php
		settings_fields( 'inarticlePlugin' );
		do_settings_sections( 'inarticlePlugin' );
		submit_button();
		?>
		
	</form>
	
	<form action='options.php' method="post">
	<?php 		
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
	</form>
	<?php

}

function adrequest_preroll_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<?php

		submit_button();
		?>
		
	</form>
	<?php

}

?>