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


//////////////////
/// Function to display items depend on $layout, but only for ajax call
/////////////////
function sedoo_portfolio_display_items_ajax($layout) {
	switch ($layout) {
		case 'grid':
			include('template-parts/blocks/portfoliocpt/grid.php');
			break;
		case 'grid-no-image':
			include('template-parts/blocks/portfoliocpt/gridnoimage.php');
			break;
		case 'list':
			include('template-parts/blocks/portfoliocpt/list.php');
			break;
		default:
			break;
	}	
}


//////////////////
/// Function to da ajax query depending on which type of call (cpt or ctx filters)
/////////////////
function sedoo_portfolio_do_ajax_query($page, $cpt, $order, $orderby, $taxo, $term_data, $term_field) {
	
	$items = new WP_Query(array(
		'post_type' => $cpt,
		'order' => $order,
		'paged' => $page,
		'orderby' => $orderby,
		'post_per_page' => 5,
		'post_status' => 'publish',
		'tax_query' => array(
		  array(
			'taxonomy' => $taxo,
			'field' => $term_field, 
			'terms' => $term_data, /// Where term_id of Term 1 is "1".
			'include_children' => true
		  )
		)
	));
	return $items;
}




/*************
* FILTERS FOR FRONT END
*/

/***  * FRONT END FILTER */
function sedoo_portfolio_filter_display() {
	if($_POST['sedoo_portfolio_filter'] == 'cpt') {
		$term_field = 'slug';
	} else {
		$term_field = 'id';
	}
	$page = $_POST['page'];
	$cpt = $_POST['cpt'];
	$term = $_POST['term'];
	$taxo = $_POST['taxo'];
	$layout = $_POST['layout'];
	$order = $_POST['order'];
	$orderby = $_POST['orderby'];

	$items = sedoo_portfolio_do_ajax_query($page,$cpt, $order, $orderby, $taxo, $term, $term_field);

	    if ( $items->have_posts() ) {
			while ( $items->have_posts() ) {
				$items->the_post();   	
				sedoo_portfolio_display_items_ajax($layout);
			}
		}			
	  wp_die();
}
add_action('wp_ajax_sedoo_portfolio_filter_display', 'sedoo_portfolio_filter_display');
add_action('wp_ajax_nopriv_sedoo_portfolio_filter_display', 'sedoo_portfolio_filter_display');



/******************************************************************
* DISPLAY PORTFOLIO FOR TERMS WHERE PORTFOLIO DISPLAY IS CHECKED
*/
function archive_do_portfolio_display($term){
	$code_color = get_theme_mod( 'labs_by_sedoo_color_code' );
	?>
	<style>
		.sedoo_port_action_btn li:hover {
			background-color: <?php echo $code_color; ?> !important;
		}

		.sedoo_port_action_btn li.active {
			background-color: <?php echo $code_color; ?> !important;
		}
	</style>
	<div id="portfolio_ajax_infos" order="date" orderby="DESC" layout="grid" ctx="<?php echo $term->taxonomy; ?>" term="<?php echo $term->term_id; ?>"></div>
	<?php 
	$args = array(
	  "posts_per_page" => 5,
	  'post_type' => 'any',
	  'tax_query' => array(
		array(
		  'taxonomy' => $term->taxonomy,
		  'field' => 'term_id',
		  'terms' => $term->term_id,
		),
	  ),
	  'orderby' => 'date', 
	  'order' => 'DESC',
	);
	$cpt_array = [];
	$content_array;
	$boutons = '<ul class="sedoo_port_action_btn ctx_button">';
	$loop = new WP_Query($args);
	$count = $loop->found_posts;
	if($loop->have_posts()) {
	  while($loop->have_posts()) : $loop->the_post();
		if (!in_array(get_post_type(), $cpt_array)) { 
		  $cpt_array[]  = get_post_type();    
		  $cpt_name = get_post_type_object( get_post_type() )->labels->name;
		  $boutons .= '<li cpt="'.get_post_type().'" order="date" orderby="DESC" ctx="'.$term->taxonomy.'" term="'.$term->term_id.'" layout="grid">'.$cpt_name.'</li>';
		}
		ob_start();
		include(get_template_directory().'/template-parts/content-portfolio-grid.php');
  
		$content_array .= ob_get_contents();
		
		ob_end_clean();
  
	  endwhile;
	  echo $boutons.'</ul>';
	  echo '<section class="sedoo_portfolio_section section_ctx">';
	  echo $content_array;
	  echo '</section>';
	}
  }