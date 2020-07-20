<?php 
    if ( get_post_type() !== 'post') { $classParameter="isNotPost";} else { $classParameter="post";}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post sedoo_port_list '.$classParameter.''); ?>>
    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> 
        <h3><?php the_title(); ?></h3>
    </a>
</article>