<?php
/**
 * Template Name: Recipe Filter
 */

get_header(); ?>

<div class="content-area">
    <div class="container">
        <div class="recipe-filter-page">
            <div class="welcome-section">
                <h1>Find Recipes</h1>
                <p>Filter recipes by category and dietary preferences.</p>
            </div>

            <?php
            // Recently Viewed Recipes Section
            $recently_viewed = get_user_meta(get_current_user_id(), 'recently_viewed_recipes', true);
            if ($recently_viewed && !isset($_GET['recipe_category']) && !isset($_GET['dietary_preference'])) {
                $recently_viewed = array_slice($recently_viewed, 0, 6); // Show only last 6 recipes
                $args = array(
                    'post_type' => 'recipe',
                    'posts_per_page' => 6,
                    'post__in' => $recently_viewed,
                    'orderby' => 'post__in'
                );
                $query = new WP_Query($args);

                if ($query->have_posts()) : ?>
                    <div class="recently-viewed-section">
                        <h2>Recently Viewed Recipes</h2>
                        <div class="recipes-grid">
                            <?php while ($query->have_posts()) : $query->the_post(); ?>
                                <?php get_template_part('template-parts/content', 'recipe-card'); ?>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif;
                wp_reset_postdata();
            }
            ?>

            <div class="filter-container">
                <form id="recipe-filter" class="filter-form" method="get">
                    <div class="filter-section">
                        <button type="button" class="filter-section-toggle">
                            <span class="dashicons dashicons-filter"></span>
                            Filter Options
                            <span class="toggle-icon"></span>
                        </button>
                        <div class="filter-content">
                            <div class="filter-row">
                                <div class="filter-group">
                                    <label for="recipe_category">Category</label>
                                    <select id="recipe_category" name="recipe_category">
                                        <option value="">All Categories</option>
                                        <?php
                                        $categories = get_terms(array(
                                            'taxonomy' => 'recipe_category',
                                            'hide_empty' => false, // Show all categories, even if they have no recipes
                                        ));
                                        
                                        if (!is_wp_error($categories) && !empty($categories)) {
                                            foreach ($categories as $category) {
                                                $selected = isset($_GET['recipe_category']) && $_GET['recipe_category'] == $category->term_id ? 'selected' : '';
                                                echo sprintf(
                                                    '<option value="%s" %s>%s (%d)</option>',
                                                    esc_attr($category->term_id),
                                                    $selected,
                                                    esc_html($category->name),
                                                    $category->count
                                                );
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="filter-group">
                                    <label for="dietary_preference">Dietary Preference</label>
                                    <select id="dietary_preference" name="dietary_preference">
                                        <option value="">All Preferences</option>
                                        <?php
                                        $preferences = get_terms(array(
                                            'taxonomy' => 'dietary_preference',
                                            'hide_empty' => false, // Show all preferences, even if they have no recipes
                                        ));
                                        
                                        if (!is_wp_error($preferences) && !empty($preferences)) {
                                            foreach ($preferences as $preference) {
                                                $selected = isset($_GET['dietary_preference']) && $_GET['dietary_preference'] == $preference->term_id ? 'selected' : '';
                                                echo sprintf(
                                                    '<option value="%s" %s>%s (%d)</option>',
                                                    esc_attr($preference->term_id),
                                                    $selected,
                                                    esc_html($preference->name),
                                                    $preference->count
                                                );
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="filter-group">
                                    <label for="cooking_time">Cooking Time</label>
                                    <select id="cooking_time" name="cooking_time">
                                        <option value="">Any Time</option>
                                        <option value="15" <?php selected(isset($_GET['cooking_time']) && $_GET['cooking_time'] == '15'); ?>>15 minutes or less</option>
                                        <option value="30" <?php selected(isset($_GET['cooking_time']) && $_GET['cooking_time'] == '30'); ?>>30 minutes or less</option>
                                        <option value="45" <?php selected(isset($_GET['cooking_time']) && $_GET['cooking_time'] == '45'); ?>>45 minutes or less</option>
                                        <option value="60" <?php selected(isset($_GET['cooking_time']) && $_GET['cooking_time'] == '60'); ?>>1 hour or less</option>
                                        <option value="60+" <?php selected(isset($_GET['cooking_time']) && $_GET['cooking_time'] == '60+'); ?>>More than 1 hour</option>
                                    </select>
                                </div>

                                <div class="filter-group">
                                    <label for="difficulty">Difficulty Level</label>
                                    <select id="difficulty" name="difficulty">
                                        <option value="">Any Difficulty</option>
                                        <option value="easy" <?php selected(isset($_GET['difficulty']) && $_GET['difficulty'] == 'easy'); ?>>Easy</option>
                                        <option value="medium" <?php selected(isset($_GET['difficulty']) && $_GET['difficulty'] == 'medium'); ?>>Medium</option>
                                        <option value="hard" <?php selected(isset($_GET['difficulty']) && $_GET['difficulty'] == 'hard'); ?>>Hard</option>
                                    </select>
                                </div>

                                <div class="filter-submit">
                                    <button type="submit" class="filter-button">Apply Filters</button>
                                    <a href="<?php echo esc_url(get_permalink()); ?>" class="reset-button">Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <?php
            // Special sections for Salt-Free and Low-Sodium recipes
            if (!isset($_GET['recipe_category']) && !isset($_GET['dietary_preference'])) {
                $special_preferences = array(
                    'Salt-Free' => 'Salt-Free Recipes',
                    'Low-Sodium' => 'Low-Sodium Recipes'
                );

                foreach ($special_preferences as $slug => $title) {
                    $term = get_term_by('name', $slug, 'dietary_preference');
                    if ($term) {
                        $args = array(
                            'post_type' => 'recipe',
                            'posts_per_page' => 3,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'dietary_preference',
                                    'field' => 'term_id',
                                    'terms' => $term->term_id,
                                ),
                            ),
                        );
                        $query = new WP_Query($args);

                        if ($query->have_posts()) : ?>
                            <div class="special-recipe-section">
                                <h2><?php echo esc_html($title); ?> <span class="recipe-count">(<?php echo $term->count; ?> recipes)</span></h2>
                                <div class="recipes-grid">
                                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                                        <?php get_template_part('template-parts/content', 'recipe-card'); ?>
                                    <?php endwhile; ?>
                                </div>
                                <div class="view-all">
                                    <a href="<?php echo esc_url(add_query_arg('dietary_preference', $term->term_id, get_permalink())); ?>" 
                                       class="view-all-button">View All <?php echo esc_html($title); ?></a>
                                </div>
                            </div>
                        <?php endif;
                        wp_reset_postdata();
                    }
                }
            }

            // Main recipe query with filters
            $tax_query = array();
            
            if (isset($_GET['recipe_category']) && !empty($_GET['recipe_category'])) {
                $tax_query[] = array(
                    'taxonomy' => 'recipe_category',
                    'field' => 'term_id',
                    'terms' => intval($_GET['recipe_category']),
                );
            }

            if (isset($_GET['dietary_preference']) && !empty($_GET['dietary_preference'])) {
                $tax_query[] = array(
                    'taxonomy' => 'dietary_preference',
                    'field' => 'term_id',
                    'terms' => intval($_GET['dietary_preference']),
                );
            }

            $args = array(
                'post_type' => 'recipe',
                'posts_per_page' => 12,
                'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
            );

            if (!empty($tax_query)) {
                $args['tax_query'] = array_merge(array('relation' => 'AND'), $tax_query);
            }

            // Add cooking time filter
            if (isset($_GET['cooking_time']) && !empty($_GET['cooking_time'])) {
                $cooking_time = $_GET['cooking_time'];
                $args['meta_query'][] = array(
                    'key' => 'total_time',
                    'value' => $cooking_time === '60+' ? 60 : intval($cooking_time),
                    'compare' => $cooking_time === '60+' ? '>' : '<=',
                    'type' => 'NUMERIC'
                );
            }

            // Add difficulty filter
            if (isset($_GET['difficulty']) && !empty($_GET['difficulty'])) {
                $args['meta_query'][] = array(
                    'key' => 'difficulty',
                    'value' => sanitize_text_field($_GET['difficulty'])
                );
            }

            $query = new WP_Query($args);

            if ($query->have_posts()) : ?>
                <div class="filtered-recipes">
                    <h2>
                        <?php
                        if (!empty($tax_query) || isset($_GET['cooking_time']) || isset($_GET['difficulty'])) {
                            echo 'Filtered Recipes';
                        } else {
                            echo 'All Recipes';
                        }
                        ?> <span class="recipe-count">(<?php echo $query->found_posts; ?> recipes)</span>
                    </h2>
                    <div class="recipes-grid">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <?php get_template_part('template-parts/content', 'recipe-card'); ?>
                        <?php endwhile; ?>
                    </div>

                    <?php
                    echo paginate_links(array(
                        'total' => $query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'prev_text' => '← Previous',
                        'next_text' => 'Next →',
                    ));
                    ?>
                </div>
            <?php else : ?>
                <div class="no-recipes">
                    <p>No recipes found matching your criteria.</p>
                </div>
            <?php endif;
            wp_reset_postdata(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?> 