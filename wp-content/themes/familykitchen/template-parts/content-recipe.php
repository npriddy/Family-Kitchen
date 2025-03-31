<?php
/**
 * Template part for displaying recipe content
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('recipe-single'); ?>>
    <div class="recipe-header">
        <h1 class="recipe-title"><?php the_title(); ?></h1>
        
        <div class="recipe-meta">
            <?php
            $prep_time = get_post_meta(get_the_ID(), 'prep_time', true);
            $cook_time = get_post_meta(get_the_ID(), 'cook_time', true);
            $total_time = get_post_meta(get_the_ID(), 'total_time', true);
            $servings = get_post_meta(get_the_ID(), 'servings', true);
            $difficulty = get_post_meta(get_the_ID(), 'difficulty', true);
            ?>
            
            <div class="recipe-details">
                <?php if ($prep_time) : ?>
                    <span><span class="dashicons dashicons-clock"></span> Prep: <?php echo esc_html($prep_time); ?> mins</span>
                <?php endif; ?>
                
                <?php if ($cook_time) : ?>
                    <span><span class="dashicons dashicons-clock"></span> Cook: <?php echo esc_html($cook_time); ?> mins</span>
                <?php endif; ?>
                
                <?php if ($total_time) : ?>
                    <span><span class="dashicons dashicons-clock"></span> Total: <?php echo esc_html($total_time); ?> mins</span>
                <?php endif; ?>
                
                <?php if ($servings) : ?>
                    <span><span class="dashicons dashicons-groups"></span> Serves: <?php echo esc_html($servings); ?></span>
                <?php endif; ?>
                
                <?php if ($difficulty) : ?>
                    <span class="difficulty <?php echo esc_attr($difficulty); ?>">
                        <span class="dashicons dashicons-chart-bar"></span>
                        <?php echo esc_html(ucfirst($difficulty)); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (has_post_thumbnail()) : ?>
        <div class="recipe-image">
            <?php the_post_thumbnail('large'); ?>
        </div>
    <?php endif; ?>

    <div class="recipe-content">
        <div class="ingredients">
            <h2>Ingredients</h2>
            <div class="recipe-content-text">
                <?php echo wp_kses_post(get_post_meta(get_the_ID(), 'ingredients', true)); ?>
            </div>
        </div>

        <div class="instructions">
            <h2>Instructions</h2>
            <div class="recipe-content-text">
                <?php the_content(); ?>
            </div>
        </div>

        <?php
        $notes = get_post_meta(get_the_ID(), 'notes', true);
        if ($notes) : ?>
            <div class="recipe-notes">
                <h2>Notes</h2>
                <div class="recipe-content-text">
                    <?php echo wp_kses_post($notes); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</article> 