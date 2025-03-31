<?php
/*
Plugin Name: Recipe Setup
Plugin URI: 
Description: Sets up recipe taxonomies and default terms
Version: 1.0
Author: Your Name
*/

// Force register taxonomies on plugin activation
function recipe_setup_activate() {
    // Recipe Categories
    if (!taxonomy_exists('recipe_category')) {
        register_taxonomy('recipe_category', 'recipe', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Recipe Categories',
                'singular_name' => 'Recipe Category'
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'recipe-category')
        ));
    }

    // Dietary Preferences
    if (!taxonomy_exists('dietary_preference')) {
        register_taxonomy('dietary_preference', 'recipe', array(
            'hierarchical' => false,
            'labels' => array(
                'name' => 'Dietary Preferences',
                'singular_name' => 'Dietary Preference'
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'dietary-preference')
        ));
    }

    // Add default categories
    $default_categories = array(
        'Main Dishes',
        'Appetizers',
        'Soups',
        'Salads',
        'Side Dishes',
        'Desserts',
        'Breakfast',
        'Snacks'
    );

    foreach ($default_categories as $category) {
        if (!term_exists($category, 'recipe_category')) {
            wp_insert_term($category, 'recipe_category');
        }
    }

    // Add default dietary preferences
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

    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'recipe_setup_activate');

// Force refresh terms
function recipe_setup_init() {
    if (get_option('recipe_setup_version') != '1.0') {
        recipe_setup_activate();
        update_option('recipe_setup_version', '1.0');
    }
}
add_action('init', 'recipe_setup_init', 0); 