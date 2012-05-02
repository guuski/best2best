<?php 
/**
 * @Author	Giovanni Giannone
 * @link http://
 * @Package Wordpress
 * @SubPackage Widgets
 * @copyright 
 * @Since 1.0.0
 * 
 * Plugin Name: bp-custom
 * Plugin URI: 
 * Description: Questo plugin aggiunge un widget per gli utenti buddypress che visualizza gli avatar degli amici.
 * Version: 1.0.0
 * Author: Giovanni Giannone
 * Author URI: http://www.
 * 
 * 
 */
 

defined('ABSPATH') or die("Cannot access pages directly.");

/**
 * Initializing 
 * 
 * The directory separator is different between linux and microsoft servers.
 * Thankfully php sets the DIRECTORY_SEPARATOR constant so that we know what
 * to use.
 */
defined("DS") or define("DS", DIRECTORY_SEPARATOR);

/**
 * Actions and Filters
 * 
 * Register any and all actions here. Nothing should actually be called 
 * directly, the entire system will be based on these actions and hooks.
 */
 
//localizzazione
load_plugin_textdomain( 'custom', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


//=========================================================================================================================
//===============LISTA AMICI===============================================================================================
//=========================================================================================================================
include_once('listaamici.php');
add_action( 'widgets_init', create_function( '', 'register_widget("listaAmici_Widget");' ) );

//=========================================================================================================================
//===============COMPLETA PROFILO==========================================================================================
//=========================================================================================================================

include_once('completaprofilo.php');
add_action( 'widgets_init', create_function( '', 'register_widget("completaProfilo_Widget");' ) );

 
//=========================================================================================================================
//===============LISTA FORNITORI E ALBERGHI================================================================================
//=========================================================================================================================
include_once('fornitorialberghi.php');
add_action( 'widgets_init', create_function( '', 'register_widget("FornitoriAlberghi_Widget");' ) );


//=========================================================================================================================
//===============MESSAGGI ADMIN============================================================================================
//=========================================================================================================================
include_once('messaggiadmin.php');
add_action( 'widgets_init', create_function( '', 'register_widget("MessaggiAdmin_Widget");' ) );
