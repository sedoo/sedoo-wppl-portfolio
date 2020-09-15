<script>
    ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>
<?php

$typedefiltre = get_field('type_de_filtre');
$layout = get_field('affichage');
$tri = get_field('tri');
$ordre = get_field('ordre');

$code_color = get_theme_mod( 'labs_by_sedoo_color_code' );

// Create class attribute allowing for custom "className" and "align" values.
$className = 'sedoo_blocks_portfolio';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

?>

<section class="'<?php echo $className; ?>'">
<style>
    .sedoo_port_action_btn li:hover {
        background-color: <?php echo $code_color; ?> !important;
    }

    .sedoo_port_action_btn li.active {
        background-color: <?php echo $code_color; ?> !important;
    }
</style>
<?php 

///////////////////
// Function to display items depend on the $layout
///////////
if ( ! function_exists('sedoo_portfolio_display_items') ) { // because of issue when editing in backoffice
    function sedoo_portfolio_display_items($layout) {
        switch ($layout) {
            case 'grid':
                include('grid.php');
                break;
            case 'grid-no-image':
                include('gridnoimage.php');
                break;
            case 'list':
                include('list.php');
            break;
            default:
                break;
        }
    }
}

///////////////
// IF DISPLAY BY CPT
///////
if($typedefiltre == 'cpt') {
    $cpt = get_field('choix_du_post_type');
    $taxo = get_field('field_5f05b5841a7fa'); // issue with field name idk why but getting taxonomy selected by users
    $terms = get_terms( array(
        'taxonomy' => $taxo,
        'hide_empty' => false,
    ) ); 


    //
    // DISPLAY BUTTON LIST (ONE BY TERM)
    //
    echo '<ul class="sedoo_port_action_btn cpt_button">';
        foreach($terms as $term) {
            echo '<li cpt="'.$cpt.'" order="'.$ordre.'" orderby="'.$tri.'" taxo="'.$taxo.'" term="'.$term->slug.'" layout="'.$layout.'"><p>'.$term->name.'</p></li>';
        }
    echo '</ul>';

    echo '<section class="sedoo_portfolio_section section_cpt">';
        $items = new WP_Query(array('post_type' => $cpt, 'orderby' => $tri, 'order' => $ordre));
        if ( $items->have_posts() ) {
            while ( $items->have_posts() ) {
                $items->the_post();
                sedoo_portfolio_display_items($layout);
            }
        }
    echo '</section>';
} 



///////////////
// IF DISPLAY BY CTX
///////
else {
    $ctx = get_field('field_5f05b3fd83516');
    $terme = get_field('choix_du_terme');
    $args = array(
        "numberposts" => 50,
        "posts_per_page" => 15,
        'tax_query' => array(
            array(
                'taxonomy' => $ctx,
                'field' => 'term_id',
                'terms' => $terme,
            ),
        ),
        'orderby' => $tri, 
        'order' => $ordre,
    );

    $cpt_array = [];
    $content_array;
    $loop = new WP_Query($args);
    if($loop->have_posts()) {
        $boutons = '<ul class="sedoo_port_action_btn ctx_button">';
            while($loop->have_posts()) : $loop->the_post();
                if (!in_array(get_post_type(), $cpt_array)) { 
                    $cpt_array[]  = get_post_type();    
                    $cpt_name = get_post_type_object( get_post_type() )->labels->name;
                    $boutons .= '<li cpt="'.get_post_type().'" order="'.$ordre.'" orderby="'.$tri.'" ctx="'.$ctx.'" term="'.$terme.'" layout="'.$layout.'"><p>'.$cpt_name.'</p></li>';
                }
                ob_start();
                sedoo_portfolio_display_items($layout);

                $content_array .= ob_get_contents();
                
                ob_end_clean();
            endwhile;
        echo $boutons.'</ul>';
        echo '<section class="sedoo_portfolio_section section_ctx">';
        echo $content_array;
        echo '</section>';
    }
}
?>
</section>
<?php 