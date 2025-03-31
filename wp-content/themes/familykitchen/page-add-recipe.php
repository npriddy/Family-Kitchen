<?php
/**
 * Template Name: Add New Recipe
 */

get_header(); ?>

<div class="content-area">
    <div class="container">
        <div class="add-recipe-page">
            <div class="welcome-section">
                <h1>Add New Recipe</h1>
                <p>Share your favorite family recipe with everyone.</p>
            </div>

            <div class="recipe-form-container">
                <form id="add-recipe-form" class="recipe-form" method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field('add_recipe_nonce', 'recipe_nonce'); ?>
                    
                    <div class="form-section">
                        <h2>Basic Information</h2>
                        <div class="form-group">
                            <label for="recipe_title">Recipe Name</label>
                            <input type="text" id="recipe_title" name="recipe_title" required>
                        </div>

                        <div class="form-group">
                            <label for="recipe_image">Recipe Photo</label>
                            <input type="file" id="recipe_image" name="recipe_image" accept="image/*" required>
                            <p class="description">Upload a photo of the finished dish</p>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="prep_time">Prep Time (minutes)</label>
                                <input type="number" id="prep_time" name="prep_time" min="0" required>
                            </div>

                            <div class="form-group">
                                <label for="cook_time">Cook Time (minutes)</label>
                                <input type="number" id="cook_time" name="cook_time" min="0" required>
                            </div>

                            <div class="form-group">
                                <label for="servings">Number of Servings</label>
                                <input type="number" id="servings" name="servings" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Ingredients</h2>
                        <div class="form-group">
                            <label for="ingredients">Ingredients List</label>
                            <textarea id="ingredients" name="ingredients" rows="10" required></textarea>
                            <p class="description">Enter each ingredient on a new line. You can use bullet points or numbers.</p>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Instructions</h2>
                        <div class="form-group">
                            <label for="instructions">Step-by-Step Instructions</label>
                            <textarea id="instructions" name="instructions" rows="15" required></textarea>
                            <p class="description">Be as detailed as possible with each step.</p>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Additional Notes</h2>
                        <div class="form-group">
                            <label for="notes">Recipe Notes</label>
                            <textarea id="notes" name="notes" rows="5"></textarea>
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
                                if (!empty($categories) && !is_wp_error($categories)) : ?>
                                    <select id="recipe_category" name="recipe_category[]" multiple required>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?php echo esc_attr($category->term_id); ?>">
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
                                    if (!empty($preferences) && !is_wp_error($preferences)) :
                                        foreach ($preferences as $preference) : ?>
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="dietary_preference[]" 
                                                       value="<?php echo esc_attr($preference->term_id); ?>">
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
                        <button type="submit" class="submit-button">Add Recipe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('add-recipe-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'add_recipe');
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
            alert('Error adding recipe. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding recipe. Please try again.');
    });
});
</script>

<?php get_footer(); ?> 