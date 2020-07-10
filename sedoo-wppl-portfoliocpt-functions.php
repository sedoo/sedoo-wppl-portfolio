<?php


function sedoo_blocks_portfoliocpt_render_callback( $block ) {
	
	// convert name ("acf/testimonial") into path friendly slug ("testimonial")
	$slug = str_replace('acf/', '', $block['name']);

	$templateURL = plugin_dir_path(__FILE__) . "template-parts/blocks/portfoliocpt/portfoliocpt.php";
    // include a template part from within the "template-parts/block" folder
    
	if( file_exists( $templateURL)) {
		include $templateURL;
    }
}


/*************
* POPULATE FIELDS
*/


/***  * CHOOSE POST TYPE IN POST TYPE LIST * field field_5f05b51fd0006 ***/
function sedoo_portfolio_populate_field_choix_du_post_type($field) {
        
	$content_type_list = [];

	$args = array(
		// 'name' => array('sedoo-platform', 'sedoo-research-team'),
		// 'labels' => array('Research team', 'Platform'),
		'public'   => true,
		'_builtin' => true
	);
	$output = 'object'; // names or objects, note names is the default
	$operator = 'or'; // 'and' or 'or'
	
	$post_types = get_post_types( $args, $output, $operator );    
	foreach ( $post_types as $post_type ) {        
		// array_push($content_type_list, $post_type->label);
		$content_type_list[$post_type->name] = $post_type->label;
	}    
	
	$field['choices'] = $content_type_list;
	return $field;
}
add_filter('acf/load_field/name=choix_du_post_type', 'sedoo_portfolio_populate_field_choix_du_post_type');


/***  * CHOOSE TAXO IN THE TAXO LIST * field field_5f05b3fd83516 ***/
function sedoo_portfolio_populate_field_choix_de_taxonomy_full($field) {
        
	$taxonomies_list = [];

	$args = array(
		// 'name' => array('sedoo-platform', 'sedoo-research-team'),
		// 'labels' => array('Research team', 'Platform'),
		'public'   => true,
		'_builtin' => false
	);
	$output = 'object'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'
	
	$taxonomies = get_taxonomies( $args, $output, $operator ); 
	// $taxonomies = get_taxonomies();
	foreach ( $taxonomies as $taxonomy ) {
		$taxonomies_list[$taxonomy->name] = $taxonomy->label;
	} 
	
	$field['choices'] = $taxonomies_list;
	return $field;
}
add_filter('acf/load_field/name=taxonomie_a_filtrer', 'sedoo_portfolio_populate_field_choix_de_taxonomy_full');


/***  * POPULATE TERM FIELD DEPEND ON TAXO FIELD (AJAX CALL) * field field_5f05b47483517 ***/
function sedoo_portfolio_populate_term_depend_on_taxo() {
	$taxo = $_POST['taxonomie'];
	$term_array = [];
	$termlist = get_terms($taxo);

	foreach($termlist as $term) {
		$term_array[$term->term_id] = $term->name;
	}
	echo json_encode($term_array);
	wp_die();
}
add_action('wp_ajax_sedoo_portfolio_populate_term_depend_on_taxo', 'sedoo_portfolio_populate_term_depend_on_taxo');


/***  * POPULATE TAXO FIELD DEPEND ON POST TYPE FIELD (AJAX CALL) * field field_5f05b5841a7fa ***/
function sedoo_portfolio_populate_taxo_depend_on_post_type() {
	$post_type = $_POST['post_type'];
	$taxo_array = [];
	$taxonomies = get_object_taxonomies( $post_type , 'objects');
	foreach($taxonomies as $taxo) {
		$taxo_array[$taxo->name] = $taxo->label;
	}
	echo json_encode($taxo_array);	
	wp_die();
}
add_action('wp_ajax_sedoo_portfolio_populate_taxo_depend_on_post_type', 'sedoo_portfolio_populate_taxo_depend_on_post_type');


/*************
* FILTERS FOR FRONT END
*/



/***  * FRONT END FILTER (DISPLAY BY CPT)  / AJAX CALL FROM FRONT.JS LINE 12 ***/
function sedoo_portfolio_filter_display_cpt() {
	$cpt = $_POST['cpt'];
	$term = $_POST['term'];
	$taxo = $_POST['taxo'];
	$layout = $_POST['layout'];

	$items = new WP_Query(array(
		'post_type' => $cpt,
		'numberposts' => -1,
		'post_status' => 'publish',
		'tax_query' => array(
		  array(
			'taxonomy' => $taxo,
			'field' => 'slug', 
			'terms' => $term, /// Where term_id of Term 1 is "1".
			'include_children' => false
		  )
		)
	  ));
	    if ( $items->have_posts() ) {
			while ( $items->have_posts() ) {
				$items->the_post();   	
				switch ($layout) {
					case 'grid':
						include('template-parts/blocks/portfoliocpt/grid.php');
						break;
					case 'grid-no-image':
						include('template-parts/blocks/portfoliocpt/grid.php');
						break;
					case 'list':
						include('template-parts/blocks/portfoliocpt/list.php');
						break;
					default:
						break;
				}		
			}
		}			
	  wp_die();
}
add_action('wp_ajax_sedoo_portfolio_filter_display_cpt', 'sedoo_portfolio_filter_display_cpt');
add_action('wp_ajax_nopriv_sedoo_portfolio_filter_display_cpt', 'sedoo_portfolio_filter_display_cpt');

/***  * FRONT END FILTER (DISPLAY BY CTX)  / AJAX CALL FROM FRONT.JS LINE 40 ***/
function sedoo_portfolio_filter_display_ctx() {
	$cpt = $_POST['cpt'];
	$term = $_POST['term'];
	$taxo = $_POST['taxo'];
	$layout = $_POST['layout'];

	$items = new WP_Query(array(
		'post_type' => $cpt,
		'numberposts' => -1,
		'post_status' => 'publish',
		'tax_query' => array(
		  array(
			'taxonomy' => $taxo,
			'field' => 'id', 
			'terms' => $term, /// Where term_id of Term 1 is "1".
			'include_children' => false
		  )
		)
	  ));
	    if ( $items->have_posts() ) {
			while ( $items->have_posts() ) {
				$items->the_post();    
				switch ($layout) {
					case 'grid':
						include('template-parts/blocks/portfoliocpt/grid.php');
						break;
					case 'grid-no-image':
						include('template-parts/blocks/portfoliocpt/grid.php');
						break;
					case 'list':
						include('template-parts/blocks/portfoliocpt/list.php');
						break;
					default:
						break;
				}	
			}
		}			
	  wp_die();
}
add_action('wp_ajax_sedoo_portfolio_filter_display_ctx', 'sedoo_portfolio_filter_display_ctx');
add_action('wp_ajax_nopriv_sedoo_portfolio_filter_display_ctx', 'sedoo_portfolio_filter_display_ctx');