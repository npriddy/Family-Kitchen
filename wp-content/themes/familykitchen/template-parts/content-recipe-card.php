<?php
/**
 * Template part for displaying recipe cards
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('recipe-card'); ?>>
    <a href="<?php the_permalink(); ?>" class="recipe-card-inner">
        <?php if (has_post_thumbnail()) : ?>
            <div class="recipe-thumbnail">
                <?php the_post_thumbnail('recipe-thumbnail'); ?>
            </div>
        <?php endif; ?>

        <div class="recipe-content">
            <h2 class="recipe-title">
                <?php the_title(); ?>
            </h2>
        </div>
    </a>
</article> 