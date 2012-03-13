<?php
/**
 * @package GG_prove
 * @version 1.0
 */
/*
Plugin Name: GEG_plugin01
Plugin URI: http://wordpress.org/extend/plugins/
Description: Questo è un plugin di prova realizzato da GiovanniG
Author: Giovanni Giannone
Version: 1.0
Author URI: http://
*/

//FOODER ========================================================================
function GEG_fooderCSS() {
  echo "
    <style type='text/css'>
      .GG_fooder{
	  position:relative;
	  width:200px;
	  margin:0px auto;
      }
    </style>
    ";
}

function GEG_fooder() {
  echo "<div class='GG_fooder'>GEG Giovanni fooder</div>";
}
function GEG_fooderERRORE() {
  echo "<div class='GG_fooder'>GEG ERRORE</div>";
}
function GEG_fooderOK() {
  echo "<div class='GG_fooder'>GEG OK</div>";
}
/*
  add_action( $tag, $function_to_add, $priority, $accepted_args ); 
  le azioni vengono chiamate con do_action o con do_action_ref_array
  add_action aggiunge all'azione il comando che noi vogliamo

*/
add_action('wp_head'	, 'GEG_fooderCSS');
//do un'ordine alla visualizzazione
add_action('wp_footer' 	, 'GEG_fooder',2);
//===============================================================================


//Creo una mia action

/*questa parte va nel core di wordpress*/

//$a = 'Paolo';
//$b = 'Rossi'; 

//do_action('GEG_action1',$a,$b);

/*======================================*/

/*
function GEG_myaction( $a, $b )
{
	echo '<code>';
		print_r( $a ); // `print_r` the array data inside the 1st argument
	echo '</code>';

	echo '<br />'.$b; // echo linebreak and value of 2nd argument
} 

add_action( 'GG_action1', 'GG_myaction', 10, 2 );
*/

//======================================================================


//add_filter( $tag, $function_to_add, $priority, $accepted_args );



 
function GEG_img_caption_shortcode($attr, $content = null) {

	// Allow plugins/themes to override the default caption template.
	$output = apply_filters('img_caption_shortcode', '', $attr, $content);
	if ( $output != '' )
	{
		add_action('wp_footer' 	, 'GEG_fooderERRORE',3);
		return $output;
	}
	add_action('wp_footer' 	, 'GEG_fooderOK',3);

	extract(shortcode_atts(array(
		'id'	=> '',
		'align'	=> 'alignnone',
		'width'	=> '',
		'caption' => ''
	), $attr));

	if ( 1 > (int) $width || empty($caption) )
		return $content;

	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . (10 + (int) $width) . 'px">'
	. do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption .'  GEG </p></div>';
	
}

function GEG_fooder3() {
  echo add_filter('img_caption_shortcode', 'GEG_img_caption_shortcode',10,3);
}

add_action('wp_footer' 	, 'GEG_fooder3',2);
function GEG_title($title) {

	// Allow plugins/themes to override the default caption template.
	return "GEG ".$title ;
	
}

add_filter('the_title', 'GEG_title',1,1);

//MENU ADMIN============================================================
function GEG_createmenu(){
	add_menu_page('GEG option page','GEG plugin', 'manage_options',__FILE__,'GEG_fooder',plugins_url('/image/rss.png',__FILE__));
	
	}
add_action('admin_menu','GEG_createmenu');
//======================================================================
?>
