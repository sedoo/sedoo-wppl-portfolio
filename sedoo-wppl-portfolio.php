<?php

/**
 * Plugin Name: Sedoo - Portfolio
 * Description: Affiche un bloc portfolio
 * Version: 0.3
 * Author: Nicolas Gruwe  - SEDOO DATA CENTER
 * Author URI:      https://www.sedoo.fr 
 * GitHub Plugin URI: sedoo/sedoo-wppl-portfolio
 * GitHub Branch:     master
 */

function sedoo_portfolio_enqueu_script() {
    // le fichier js qui contient les fonctions tirgger au change des select
    $src_ctp = plugins_url().'/sedoo-wppl-portfolio/js/back.js';
    wp_enqueue_script('sedoo_portfolio', $src_ctp,  array ( 'jquery' ));                 
    wp_localize_script( 'sedoo_portfolio', 'ajaxurl', admin_url( 'admin-ajax.php' ) );      
}
add_action( 'admin_head', 'sedoo_portfolio_enqueu_script' );

function sedoo_portfolio_front_js() {
    // le fichier js qui contient les fonctions tirgger au change des select
    $scrpt_portfoliocpt =  plugin_dir_url( __FILE__ ).'/js/front.js';
    wp_enqueue_script('sedoo_portfolio_front', $scrpt_portfoliocpt,  array ( 'jquery' ));   
  }
  add_action( 'wp_head', 'sedoo_portfolio_front_js' );
  
  

include 'sedoo-wppl-portfoliocpt-functions.php';
include 'sedoo-wppl-portfoliocpt-acf.php';
include 'inc/sedoo-wppl-portfolio-acf-fields.php';
