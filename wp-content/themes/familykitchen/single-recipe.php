<?php get_header(); ?>

<div class="recipe-single">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="recipe-header">
            <h1 class="recipe-title"><?php the_title(); ?></h1>
            
            <?php if (is_user_logged_in()) : ?>
                <div class="recipe-actions">
                    <a href="<?php echo esc_url(add_query_arg('id', get_the_ID(), home_url('/edit-recipe'))); ?>" class="edit-recipe-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Recipe
                    </a>
                </div>
            <?php endif; ?>

            <div class="recipe-details">
                <?php if (get_post_meta(get_the_ID(), 'prep_time', true)) : ?>
                    <span class="prep-time">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Prep Time: <?php echo esc_html(get_post_meta(get_the_ID(), 'prep_time', true)); ?> min
                    </span>
                <?php endif; ?>

                <?php if (get_post_meta(get_the_ID(), 'cook_time', true)) : ?>
                    <span class="cook-time">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"></path>
                        </svg>
                        Cook Time: <?php echo esc_html(get_post_meta(get_the_ID(), 'cook_time', true)); ?> min
                    </span>
                <?php endif; ?>

                <?php if (get_post_meta(get_the_ID(), 'servings', true)) : ?>
                    <span class="servings">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Servings: <?php echo esc_html(get_post_meta(get_the_ID(), 'servings', true)); ?>
                    </span>
                <?php endif; ?>
            </div>
        </header>

        <?php if (has_post_thumbnail()) : ?>
            <div class="recipe-image">
                <?php the_post_thumbnail('recipe-featured'); ?>
            </div>
        <?php endif; ?>

        <div class="recipe-content">
            <div class="ingredients">
                <h2>Ingredients</h2>
                <?php echo wp_kses_post(get_post_meta(get_the_ID(), 'ingredients', true)); ?>
            </div>

            <div class="instructions">
                <h2>Instructions</h2>
                <?php the_content(); ?>
            </div>

            <?php if (get_post_meta(get_the_ID(), 'notes', true)) : ?>
                <div class="recipe-notes">
                    <h2>Notes</h2>
                    <?php echo wp_kses_post(get_post_meta(get_the_ID(), 'notes', true)); ?>
                </div>
            <?php endif; ?>
        </div>
    </article>
</div>

<?php get_footer(); ?> 