jQuery(document).ready(function($) {
    const filterForm = $('#recipe-filter');
    const filterContainer = $('.filter-container');
    const recipeGrid = $('.recipes-grid');
    const filterSections = $('.filter-section');
    let filterTimeout;

    // Load saved preferences if user is logged in
    if (recipeFilterAjax.isLoggedIn) {
        $.ajax({
            url: recipeFilterAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_filter_preferences',
                nonce: recipeFilterAjax.nonce
            },
            success: function(response) {
                if (response.success && response.data.preferences) {
                    const prefs = response.data.preferences;
                    $('#recipe_category').val(prefs.category);
                    $('#dietary_preference').val(prefs.preference);
                    $('#cooking_time').val(prefs.cooking_time);
                    $('#difficulty').val(prefs.difficulty);
                }
            }
        });
    }

    // Toggle filter sections
    $('.filter-section-toggle').on('click', function() {
        const section = $(this).closest('.filter-section');
        section.toggleClass('expanded');
    });

    // Handle filter changes with debounce
    filterForm.on('change', 'select', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            filterRecipes();
        }, 500);
    });

    // Handle pagination clicks
    $(document).on('click', '.pagination .page-numbers', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        filterRecipes(page);
    });

    function filterRecipes(page = 1) {
        const category = $('#recipe_category').val();
        const preference = $('#dietary_preference').val();
        const cookingTime = $('#cooking_time').val();
        const difficulty = $('#difficulty').val();

        // Show loading state
        recipeGrid.addClass('loading');

        $.ajax({
            url: recipeFilterAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'filter_recipes',
                nonce: recipeFilterAjax.nonce,
                category: category,
                preference: preference,
                cooking_time: cookingTime,
                difficulty: difficulty,
                page: page
            },
            success: function(response) {
                if (response.success) {
                    recipeGrid.html(response.data.html);
                    
                    // Save preferences if user is logged in
                    if (recipeFilterAjax.isLoggedIn) {
                        $.ajax({
                            url: recipeFilterAjax.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'save_filter_preferences',
                                nonce: recipeFilterAjax.nonce,
                                category: category,
                                preference: preference,
                                cooking_time: cookingTime,
                                difficulty: difficulty
                            }
                        });
                    }

                    // Update URL without page reload
                    const newUrl = new URL(window.location.href);
                    if (category) newUrl.searchParams.set('recipe_category', category);
                    else newUrl.searchParams.delete('recipe_category');
                    if (preference) newUrl.searchParams.set('dietary_preference', preference);
                    else newUrl.searchParams.delete('dietary_preference');
                    if (cookingTime) newUrl.searchParams.set('cooking_time', cookingTime);
                    else newUrl.searchParams.delete('cooking_time');
                    if (difficulty) newUrl.searchParams.set('difficulty', difficulty);
                    else newUrl.searchParams.delete('difficulty');
                    window.history.pushState({}, '', newUrl);
                }
            },
            complete: function() {
                recipeGrid.removeClass('loading');
            }
        });
    }

    // Handle browser back/forward buttons
    window.onpopstate = function() {
        const urlParams = new URLSearchParams(window.location.search);
        $('#recipe_category').val(urlParams.get('recipe_category') || '');
        $('#dietary_preference').val(urlParams.get('dietary_preference') || '');
        $('#cooking_time').val(urlParams.get('cooking_time') || '');
        $('#difficulty').val(urlParams.get('difficulty') || '');
        filterRecipes();
    };

    // Track recently viewed recipes
    if (recipeFilterAjax.isLoggedIn) {
        $(document).on('click', '.recipe-card a', function() {
            const recipeId = $(this).closest('.recipe-card').data('recipe-id');
            $.ajax({
                url: recipeFilterAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'track_recently_viewed',
                    nonce: recipeFilterAjax.nonce,
                    recipe_id: recipeId
                }
            });
        });
    }
}); 