<?php get_header(); ?>

<div class="content-area">
    <div class="container">
        <?php if (is_home() && !is_front_page()) : ?>
            <header class="page-header">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <div class="recipes-grid">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('recipe-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('large'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="recipe-card-content">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        
                        <div class="recipe-meta">
                            <?php if (get_post_meta(get_the_ID(), 'prep_time', true)) : ?>
                                <span class="prep-time">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    Prep: <?php echo esc_html(get_post_meta(get_the_ID(), 'prep_time', true)); ?> min
                                </span>
                            <?php endif; ?>

                            <?php if (get_post_meta(get_the_ID(), 'cook_time', true)) : ?>
                                <span class="cook-time">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"></path>
                                    </svg>
                                    Cook: <?php echo esc_html(get_post_meta(get_the_ID(), 'cook_time', true)); ?> min
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="recipe-excerpt">
                            <?php the_excerpt(); ?>
                        </div>

                        <div class="recipe-footer">
                            <div class="recipe-meta-left">
                                <span class="recipe-date"><?php echo get_the_date(); ?></span>
                                <?php if (is_user_logged_in()) : ?>
                                    <a href="<?php echo esc_url(add_query_arg('id', get_the_ID(), home_url('/edit-recipe'))); ?>" class="recipe-edit">Edit Recipe</a>
                                <?php endif; ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="recipe-read-more">View Recipe →</a>
                        </div>
                    </div>
                </article>
            <?php endwhile; endif; ?>
        </div>

        <?php the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => '← Previous',
            'next_text' => 'Next →',
        )); ?>
    </div>
</div>

<?php get_footer(); ?> 