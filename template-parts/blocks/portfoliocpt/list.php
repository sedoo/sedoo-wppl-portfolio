<article id="post-<?php the_ID(); ?>" <?php post_class('post '.$classParameter.''); ?>>
    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> 
        <h3><?php echo $titleItem; ?></h3>
    </a>
</article>