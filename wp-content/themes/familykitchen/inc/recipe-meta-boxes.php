<?php
if (!defined('ABSPATH')) exit;

function familykitchen_add_recipe_meta_boxes() {
    add_meta_box(
        'recipe_details',
        'Recipe Details',
        'familykitchen_recipe_details_callback',
        'recipe',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'familykitchen_add_recipe_meta_boxes');

function familykitchen_recipe_details_callback($post) {
    wp_nonce_field('familykitchen_recipe_details', 'recipe_details_nonce');

    $prep_time = get_post_meta($post->ID, 'prep_time', true);
    $cook_time = get_post_meta($post->ID, 'cook_time', true);
    $servings = get_post_meta($post->ID, 'servings', true);
    $ingredients = get_post_meta($post->ID, 'ingredients', true);
    $notes = get_post_meta($post->ID, 'notes', true);
    ?>
    <div class="recipe-meta-box">
        <p>
            <label for="prep_time">Prep Time (minutes):</label>
            <input type="number" id="prep_time" name="prep_time" value="<?php echo esc_attr($prep_time); ?>" min="0">
        </p>
        <p>
            <label for="cook_time">Cook Time (minutes):</label>
            <input type="number" id="cook_time" name="cook_time" value="<?php echo esc_attr($cook_time); ?>" min="0">
        </p>
        <p>
            <label for="servings">Number of Servings:</label>
            <input type="number" id="servings" name="servings" value="<?php echo esc_attr($servings); ?>" min="1">
        </p>
        <p>
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" rows="10" style="width: 100%;"><?php echo esc_textarea($ingredients); ?></textarea>
            <span class="description">Enter each ingredient on a new line. You can use bullet points or numbers.</span>
        </p>
        <p>
            <label for="notes">Recipe Notes:</label>
            <textarea id="notes" name="notes" rows="5" style="width: 100%;"><?php echo esc_textarea($notes); ?></textarea>
            <span class="description">Add any special notes, tips, or variations for this recipe.</span>
        </p>
    </div>
    <?php
}

function familykitchen_save_recipe_details($post_id) {
    if (!isset($_POST['recipe_details_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['recipe_details_nonce'], 'familykitchen_recipe_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array('prep_time', 'cook_time', 'servings', 'ingredients', 'notes');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_recipe', 'familykitchen_save_recipe_details'); 