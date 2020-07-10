<script>
    ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>
<?php

$titre = get_field('titre_du_bloc');

if($titre) {
    echo '<h2>'.$titre.'</h2>';
}


$layout = get_field('affichage');
$typedefiltre = get_field('type_de_filtre');



$tri = get_field('tri');
$ordre = get_field('ordre');


///////////////
// IF DISPLAY BY CPT
///////
if($typedefiltre == 'cpt') {
    $cpt = get_field('choix_du_post_type');
    $taxo = get_field('field_5f05b5841a7fa'); // issue with field name idk why
    $terms = get_terms( array(
        'taxonomy' => $taxo,
        'hide_empty' => false,
    ) ); 


    //
    // DISPLAY BUTTON LIST (ONE BY TERM)
    //
    echo '<ul class="sedoo_port_action_btn cpt_button">';
    foreach($terms as $term) {
        echo '<li cpt="'.$cpt.'" order="'.$ordre.'" orderby="'.$tri.'" taxo="'.$taxo.'" term="'.$term->slug.'" layout="'.$layout.'">'.$term->name.'</li>';
    }
    echo '</ul>';

    echo '<section class="sedoo_portfolio_section section_cpt">';

    $items = new WP_Query(array('post_type' => $cpt, 'orderby' => $tri, 'order' => $ordre));
    if ( $items->have_posts() ) {
        while ( $items->have_posts() ) {
            $items->the_post();
            switch ($layout) {
                case 'grid':
                    include('grid.php');
                    break;
                case 'grid-no-image':
                    include('grid.php');
                    break;
                case 'list':
                    include('list.php');
                    break;
                default:
                    break;
            }
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
    $boutons = '<ul class="sedoo_port_action_btn ctx_button">';
    $loop = new WP_Query($args);
    if($loop->have_posts()) {
    while($loop->have_posts()) : $loop->the_post();
        if (!in_array(get_post_type(), $cpt_array)) { 
            $cpt_array[]  = get_post_type();    
            $cpt_name = get_post_type_object( get_post_type() )->labels->name;
            $boutons .= '<li cpt="'.get_post_type().'" order="'.$ordre.'" orderby="'.$tri.'" ctx="'.$ctx.'" term="'.$terme.'" layout="'.$layout.'">'.$cpt_name.'</li>';
        }
        ob_start();
        switch ($layout) {
            case 'grid':
                include('grid.php');
                break;
            case 'grid-no-image':
                include('grid.php');
                break;
            case 'list':
                include('list.php');
                break;
            default:
                break;
        }
        
        $content_array .= ob_get_contents();
        
        ob_end_clean();

    endwhile;
    echo $boutons.'</ul>';
    echo '<section class="sedoo_portfolio_section section_ctx">';
    echo $content_array;
    echo '</section>';
     }
}
