<?php
if (!defined('ABSPATH')) exit;

function familykitchen_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'familykitchen'),
        'footer' => esc_html__('Footer Menu', 'familykitchen'),
    ));
}
add_action('after_setup_theme', 'familykitchen_setup');

// Enqueue scripts and styles
function familykitchen_scripts() {
    wp_enqueue_style('familykitchen-style', get_stylesheet_uri());
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'familykitchen_scripts');

// Register custom post type for recipes
function familykitchen_register_recipe_post_type() {
    $labels = array(
        'name'               => 'Recipes',
        'singular_name'      => 'Recipe',
        'menu_name'          => 'Recipes',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Recipe',
        'edit_item'          => 'Edit Recipe',
        'new_item'           => 'New Recipe',
        'view_item'          => 'View Recipe',
        'search_items'       => 'Search Recipes',
        'not_found'          => 'No recipes found',
        'not_found_in_trash' => 'No recipes found in Trash',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'recipes'),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-food',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
    );

    register_post_type('recipe', $args);
}
add_action('init', 'familykitchen_register_recipe_post_type');

// Include recipe meta boxes
require_once get_template_directory() . '/inc/recipe-meta-boxes.php';

// Add custom image sizes
function familykitchen_add_image_sizes() {
    add_image_size('recipe-thumbnail', 400, 300, true);
    add_image_size('recipe-featured', 1200, 600, true);
}
add_action('after_setup_theme', 'familykitchen_add_image_sizes');

// Handle recipe submission
function handle_recipe_submission() {
    if (!isset($_POST['recipe_nonce']) || !wp_verify_nonce($_POST['recipe_nonce'], 'add_recipe_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }

    $recipe_title = sanitize_text_field($_POST['recipe_title']);
    $prep_time = intval($_POST['prep_time']);
    $cook_time = intval($_POST['cook_time']);
    $servings = intval($_POST['servings']);
    $ingredients = wp_kses_post($_POST['ingredients']);
    $instructions = wp_kses_post($_POST['instructions']);
    $notes = wp_kses_post($_POST['notes']);

    // Create post object
    $recipe_data = array(
        'post_title'    => $recipe_title,
        'post_content'  => $instructions,
        'post_status'   => 'publish',
        'post_type'     => 'recipe',
        'post_author'   => get_current_user_id()
    );

    // Insert the post into the database
    $recipe_id = wp_insert_post($recipe_data);

    if (is_wp_error($recipe_id)) {
        wp_send_json_error('Error creating recipe');
    }

    // Add custom fields
    update_post_meta($recipe_id, 'prep_time', $prep_time);
    update_post_meta($recipe_id, 'cook_time', $cook_time);
    update_post_meta($recipe_id, 'servings', $servings);
    update_post_meta($recipe_id, 'ingredients', $ingredients);
    update_post_meta($recipe_id, 'notes', $notes);

    // Handle categories
    if (isset($_POST['recipe_category']) && is_array($_POST['recipe_category'])) {
        $categories = array_map('intval', $_POST['recipe_category']);
        wp_set_object_terms($recipe_id, $categories, 'recipe_category');
    }

    // Handle dietary preferences
    if (isset($_POST['dietary_preference']) && is_array($_POST['dietary_preference'])) {
        $preferences = array_map('intval', $_POST['dietary_preference']);
        wp_set_object_terms($recipe_id, $preferences, 'dietary_preference');
    }

    // Handle image upload
    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] === UPLOAD_ERR_OK) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('recipe_image', $recipe_id);
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($recipe_id, $attachment_id);
        }
    }

    wp_send_json_success(array(
        'redirect_url' => get_permalink($recipe_id)
    ));
}
add_action('wp_ajax_add_recipe', 'handle_recipe_submission');

// Handle recipe editing
function handle_recipe_edit() {
    if (!isset($_POST['recipe_nonce']) || !wp_verify_nonce($_POST['recipe_nonce'], 'edit_recipe_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }

    $recipe_id = intval($_POST['recipe_id']);
    $recipe = get_post($recipe_id);

    if (!$recipe || $recipe->post_type !== 'recipe') {
        wp_send_json_error('Invalid recipe');
    }

    $recipe_title = sanitize_text_field($_POST['recipe_title']);
    $prep_time = intval($_POST['prep_time']);
    $cook_time = intval($_POST['cook_time']);
    $servings = intval($_POST['servings']);
    $ingredients = wp_kses_post($_POST['ingredients']);
    $instructions = wp_kses_post($_POST['instructions']);
    $notes = wp_kses_post($_POST['notes']);

    // Update post
    $recipe_data = array(
        'ID' => $recipe_id,
        'post_title' => $recipe_title,
        'post_content' => $instructions,
    );

    wp_update_post($recipe_data);

    // Update custom fields
    update_post_meta($recipe_id, 'prep_time', $prep_time);
    update_post_meta($recipe_id, 'cook_time', $cook_time);
    update_post_meta($recipe_id, 'servings', $servings);
    update_post_meta($recipe_id, 'ingredients', $ingredients);
    update_post_meta($recipe_id, 'notes', $notes);

    // Update categories
    if (isset($_POST['recipe_category']) && is_array($_POST['recipe_category'])) {
        $categories = array_map('intval', $_POST['recipe_category']);
        wp_set_object_terms($recipe_id, $categories, 'recipe_category');
    }

    // Update dietary preferences
    if (isset($_POST['dietary_preference']) && is_array($_POST['dietary_preference'])) {
        $preferences = array_map('intval', $_POST['dietary_preference']);
        wp_set_object_terms($recipe_id, $preferences, 'dietary_preference');
    }

    // Handle image upload
    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] === UPLOAD_ERR_OK) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        // Delete old featured image if it exists
        $old_thumbnail_id = get_post_thumbnail_id($recipe_id);
        if ($old_thumbnail_id) {
            wp_delete_attachment($old_thumbnail_id, true);
        }

        // Add new featured image
        $attachment_id = media_handle_upload('recipe_image', $recipe_id);
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($recipe_id, $attachment_id);
        }
    }

    wp_send_json_success(array(
        'redirect_url' => get_permalink($recipe_id)
    ));
}
add_action('wp_ajax_edit_recipe', 'handle_recipe_edit');

// Add AJAX URL to frontend
function add_ajax_url() {
    ?>
    <script>
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
    <?php
}
add_action('wp_head', 'add_ajax_url');

// Register Recipe Categories
function register_recipe_taxonomies() {
    // Recipe Categories (hierarchical, like categories)
    register_taxonomy('recipe_category', 'recipe', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Recipe Categories',
            'singular_name' => 'Recipe Category',
            'search_items' => 'Search Categories',
            'all_items' => 'All Categories',
            'parent_item' => 'Parent Category',
            'parent_item_colon' => 'Parent Category:',
            'edit_item' => 'Edit Category',
            'update_item' => 'Update Category',
            'add_new_item' => 'Add New Category',
            'new_item_name' => 'New Category Name',
            'menu_name' => 'Categories'
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'recipe-category'),
        'show_in_rest' => true,
    ));

    // Dietary Preferences (non-hierarchical, like tags)
    register_taxonomy('dietary_preference', 'recipe', array(
        'hierarchical' => false,
        'labels' => array(
            'name' => 'Dietary Preferences',
            'singular_name' => 'Dietary Preference',
            'search_items' => 'Search Preferences',
            'all_items' => 'All Preferences',
            'edit_item' => 'Edit Preference',
            'update_item' => 'Update Preference',
            'add_new_item' => 'Add New Preference',
            'new_item_name' => 'New Preference Name',
            'menu_name' => 'Dietary Preferences'
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'dietary-preference'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'register_recipe_taxonomies');

// Add default dietary preferences
function add_default_dietary_preferences() {
    $default_preferences = array(
        'Salt-Free',
        'Low-Sodium',
        'Gluten-Free',
        'Dairy-Free',
        'Vegetarian',
        'Vegan',
        'Keto',
        'Low-Carb',
        'Nut-Free'
    );

    foreach ($default_preferences as $preference) {
        if (!term_exists($preference, 'dietary_preference')) {
            wp_insert_term($preference, 'dietary_preference');
        }
    }
}
add_action('after_switch_theme', 'add_default_dietary_preferences');

// Add AJAX functionality for recipe filtering
function enqueue_recipe_filter_scripts() {
    if (is_page_template('page-recipe-filter.php')) {
        wp_enqueue_script('recipe-filter', get_template_directory_uri() . '/js/recipe-filter.js', array('jquery'), '1.0', true);
        wp_localize_script('recipe-filter', 'recipeFilterAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('recipe_filter_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_recipe_filter_scripts');

// Add total time calculation for recipes
function calculate_recipe_total_time($post_id) {
    $prep_time = get_post_meta($post_id, 'prep_time', true);
    $cook_time = get_post_meta($post_id, 'cook_time', true);
    $total_time = intval($prep_time) + intval($cook_time);
    update_post_meta($post_id, 'total_time', $total_time);
}
add_action('save_post_recipe', 'calculate_recipe_total_time');

// Track recently viewed recipes
function track_recently_viewed_recipe() {
    check_ajax_referer('recipe_filter_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }
    
    $recipe_id = intval($_POST['recipe_id']);
    $user_id = get_current_user_id();
    
    $recently_viewed = get_user_meta($user_id, 'recently_viewed_recipes', true);
    if (!is_array($recently_viewed)) {
        $recently_viewed = array();
    }
    
    // Remove the recipe if it exists
    $recently_viewed = array_diff($recently_viewed, array($recipe_id));
    
    // Add to the beginning
    array_unshift($recently_viewed, $recipe_id);
    
    // Keep only the last 20 recipes
    $recently_viewed = array_slice($recently_viewed, 0, 20);
    
    update_user_meta($user_id, 'recently_viewed_recipes', $recently_viewed);
    wp_send_json_success();
}
add_action('wp_ajax_track_recently_viewed', 'track_recently_viewed_recipe');

// Update filter_recipes_ajax function to handle new filters
function filter_recipes_ajax() {
    check_ajax_referer('recipe_filter_nonce', 'nonce');
    
    $category = isset($_POST['category']) ? intval($_POST['category']) : '';
    $preference = isset($_POST['preference']) ? intval($_POST['preference']) : '';
    $cooking_time = isset($_POST['cooking_time']) ? sanitize_text_field($_POST['cooking_time']) : '';
    $difficulty = isset($_POST['difficulty']) ? sanitize_text_field($_POST['difficulty']) : '';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    
    $args = array(
        'post_type' => 'recipe',
        'posts_per_page' => 12,
        'paged' => $page,
    );
    
    $tax_query = array();
    $meta_query = array();
    
    if ($category) {
        $tax_query[] = array(
            'taxonomy' => 'recipe_category',
            'field' => 'term_id',
            'terms' => $category,
        );
    }
    
    if ($preference) {
        $tax_query[] = array(
            'taxonomy' => 'dietary_preference',
            'field' => 'term_id',
            'terms' => $preference,
        );
    }
    
    if ($cooking_time) {
        $meta_query[] = array(
            'key' => 'total_time',
            'value' => $cooking_time === '60+' ? 60 : intval($cooking_time),
            'compare' => $cooking_time === '60+' ? '>' : '<=',
            'type' => 'NUMERIC'
        );
    }
    
    if ($difficulty) {
        $meta_query[] = array(
            'key' => 'difficulty',
            'value' => $difficulty
        );
    }
    
    if (!empty($tax_query)) {
        $args['tax_query'] = array_merge(array('relation' => 'AND'), $tax_query);
    }
    
    if (!empty($meta_query)) {
        $args['meta_query'] = array_merge(array('relation' => 'AND'), $meta_query);
    }
    
    $query = new WP_Query($args);
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', 'recipe-card');
        }
        
        echo '<div class="pagination">';
        echo paginate_links(array(
            'total' => $query->max_num_pages,
            'current' => $page,
            'prev_text' => '← Previous',
            'next_text' => 'Next →',
        ));
        echo '</div>';
    } else {
        echo '<div class="no-recipes"><p>No recipes found matching your criteria.</p></div>';
    }
    
    wp_reset_postdata();
    
    $html = ob_get_clean();
    wp_send_json_success(array('html' => $html));
}

// Update save_filter_preferences function to include new filters
function save_filter_preferences() {
    check_ajax_referer('recipe_filter_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }
    
    $preferences = array(
        'category' => isset($_POST['category']) ? intval($_POST['category']) : '',
        'preference' => isset($_POST['preference']) ? intval($_POST['preference']) : '',
        'cooking_time' => isset($_POST['cooking_time']) ? sanitize_text_field($_POST['cooking_time']) : '',
        'difficulty' => isset($_POST['difficulty']) ? sanitize_text_field($_POST['difficulty']) : '',
    );
    
    update_user_meta(get_current_user_id(), 'recipe_filter_preferences', $preferences);
    wp_send_json_success();
} 