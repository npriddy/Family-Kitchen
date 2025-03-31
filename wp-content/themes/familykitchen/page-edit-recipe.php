<?php
/**
 * Template Name: Edit Recipe
 */

if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}

$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$recipe = get_post($recipe_id);

if (!$recipe || $recipe->post_type !== 'recipe') {
    wp_redirect(home_url());
    exit;
}

get_header(); ?>

<div class="content-area">
    <div class="container">
        <div class="add-recipe-page">
            <div class="welcome-section">
                <h1>Edit Recipe: <?php echo esc_html($recipe->post_title); ?></h1>
                <p>Update your recipe details below.</p>
            </div>

            <div class="recipe-form-container">
                <form id="edit-recipe-form" class="recipe-form" method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field('edit_recipe_nonce', 'recipe_nonce'); ?>
                    <input type="hidden" name="recipe_id" value="<?php echo esc_attr($recipe_id); ?>">
                    
                    <div class="form-section">
                        <h2>Basic Information</h2>
                        <div class="form-group">
                            <label for="recipe_title">Recipe Name</label>
                            <input type="text" id="recipe_title" name="recipe_title" value="<?php echo esc_attr($recipe->post_title); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="recipe_image">Recipe Photo</label>
                            <?php if (has_post_thumbnail($recipe_id)) : ?>
                                <div class="current-image">
                                    <img src="<?php echo get_the_post_thumbnail_url($recipe_id, 'medium'); ?>" alt="Current recipe image">
                                </div>
                            <?php endif; ?>
                            <input type="file" id="recipe_image" name="recipe_image" accept="image/*">
                            <p class="description">Upload a new photo to replace the current one (optional)</p>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="prep_time">Prep Time (minutes)</label>
                                <input type="number" id="prep_time" name="prep_time" min="0" value="<?php echo esc_attr(get_post_meta($recipe_id, 'prep_time', true)); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="cook_time">Cook Time (minutes)</label>
                                <input type="number" id="cook_time" name="cook_time" min="0" value="<?php echo esc_attr(get_post_meta($recipe_id, 'cook_time', true)); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="servings">Number of Servings</label>
                                <input type="number" id="servings" name="servings" min="1" value="<?php echo esc_attr(get_post_meta($recipe_id, 'servings', true)); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Ingredients</h2>
                        <div class="form-group">
                            <label for="ingredients">Ingredients List</label>
                            <textarea id="ingredients" name="ingredients" rows="10" required><?php echo esc_textarea(get_post_meta($recipe_id, 'ingredients', true)); ?></textarea>
                            <p class="description">Enter each ingredient on a new line. You can use bullet points or numbers.</p>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Instructions</h2>
                        <div class="form-group">
                            <label for="instructions">Step-by-Step Instructions</label>
                            <textarea id="instructions" name="instructions" rows="15" required><?php echo esc_textarea($recipe->post_content); ?></textarea>
                            <p class="description">Be as detailed as possible with each step.</p>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Additional Notes</h2>
                        <div class="form-group">
                            <label for="notes">Recipe Notes</label>
                            <textarea id="notes" name="notes" rows="5"><?php echo esc_textarea(get_post_meta($recipe_id, 'notes', true)); ?></textarea>
                            <p class="description">Add any tips, variations, or special instructions.</p>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Recipe Classification</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="recipe_category">Recipe Category</label>
                                <?php
                                $categories = get_terms(array(
                                    'taxonomy' => 'recipe_category',
                                    'hide_empty' => false,
                                ));
                                $selected_categories = wp_get_object_terms($recipe_id, 'recipe_category', array('fields' => 'ids'));
                                if (!empty($categories) && !is_wp_error($categories)) : ?>
                                    <select id="recipe_category" name="recipe_category[]" multiple required>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?php echo esc_attr($category->term_id); ?>"
                                                <?php echo in_array($category->term_id, $selected_categories) ? 'selected' : ''; ?>>
                                                <?php echo esc_html($category->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="description">Select one or more categories for your recipe</p>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="dietary_preference">Dietary Preferences</label>
                                <div class="dietary-preferences-grid">
                                    <?php
                                    $preferences = get_terms(array(
                                        'taxonomy' => 'dietary_preference',
                                        'hide_empty' => false,
                                    ));
                                    $selected_preferences = wp_get_object_terms($recipe_id, 'dietary_preference', array('fields' => 'ids'));
                                    if (!empty($preferences) && !is_wp_error($preferences)) :
                                        foreach ($preferences as $preference) : ?>
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="dietary_preference[]" 
                                                       value="<?php echo esc_attr($preference->term_id); ?>"
                                                       <?php echo in_array($preference->term_id, $selected_preferences) ? 'checked' : ''; ?>>
                                                <?php echo esc_html($preference->name); ?>
                                            </label>
                                        <?php endforeach;
                                    endif; ?>
                                </div>
                                <p class="description">Check all that apply</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-submit">
                        <button type="submit" class="submit-button">Update Recipe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('edit-recipe-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'edit_recipe');
    formData.append('recipe_nonce', document.getElementById('recipe_nonce').value);

    fetch(ajaxurl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect_url;
        } else {
            alert('Error updating recipe. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating recipe. Please try again.');
    });
});
</script>

<?php get_footer(); ?> 