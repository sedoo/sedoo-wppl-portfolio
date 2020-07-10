<?php

/**
 * ACF gutenberg Block
 */

function register_sedoo_portfoliocpt_block_types() {

    // register related block content.
    acf_register_block_type(array(
        'name'              => 'sedoo_labtools_portfoliocpt',
        'title'             => __('Sedoo Portfolio cpt'),
        'description'       => __('Ajout du portfolio de contenu organisé en cpt'),
        'render_callback'	=> 'sedoo_blocks_portfoliocpt_render_callback',
        'category'          => 'sedoo-block-category',
        'icon'              => 'category',
        'keywords'          => array( 'sedoo', 'cpt', 'Portfolio', 'posts' ),
    ));
}
// Check if function exists and hook into setup.
if( function_exists('acf_register_block_type') ) {
    add_action('acf/init', 'register_sedoo_portfoliocpt_block_types');
}

function sedoo_enqueue_portfoliocpt_style() {
    wp_register_style( 'sedoo_portfolio_style', plugins_url('css/portfolio.css', __FILE__) );
    wp_enqueue_style( 'sedoo_portfolio_style' );        
}
add_action( 'wp_head', 'sedoo_enqueue_portfoliocpt_style' );

?>