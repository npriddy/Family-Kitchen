<?php get_header(); ?>

<div class="content-area">
    <div class="container">
        <div class="welcome-section">
            <h1>Welcome to Our Family Hub</h1>
            <p>Access all your favorite family resources and Synology apps in one place.</p>
        </div>

        <div class="synology-apps">
            <!-- File Management Apps -->
            <div class="app-card">
                <h3>File Station</h3>
                <p>Access and manage your family files</p>
                <a href="http://your-nas:5000" target="_blank">Open File Station</a>
            </div>

            <div class="app-card">
                <h3>Photo Station</h3>
                <p>View and organize family photos</p>
                <a href="http://your-nas:5000/photo" target="_blank">Open Photo Station</a>
            </div>

            <div class="app-card">
                <h3>Video Station</h3>
                <p>Stream family videos</p>
                <a href="http://your-nas:5000/video" target="_blank">Open Video Station</a>
            </div>

            <!-- Recipe Section -->
            <div class="app-card">
                <h3>Family Recipes</h3>
                <p>Browse our collection of family recipes</p>
                <a href="<?php echo esc_url(get_post_type_archive_link('recipe')); ?>">View Recipes</a>
            </div>

            <!-- Additional Synology Apps -->
            <div class="app-card">
                <h3>Download Station</h3>
                <p>Manage your downloads</p>
                <a href="http://your-nas:5000/download" target="_blank">Open Download Station</a>
            </div>

            <div class="app-card">
                <h3>Note Station</h3>
                <p>Access family notes and documents</p>
                <a href="http://your-nas:5000/note" target="_blank">Open Note Station</a>
            </div>

            <div class="app-card">
                <h3>Calendar</h3>
                <p>Family events and schedules</p>
                <a href="http://your-nas:5000/calendar" target="_blank">Open Calendar</a>
            </div>

            <div class="app-card">
                <h3>Contacts</h3>
                <p>Family contact information</p>
                <a href="http://your-nas:5000/contacts" target="_blank">Open Contacts</a>
            </div>
        </div>

        <div class="quick-links">
            <h2>Quick Links</h2>
            <div class="links-grid">
                <a href="<?php echo esc_url(get_post_type_archive_link('recipe')); ?>" class="quick-link-card">
                    <span class="dashicons dashicons-food"></span>
                    <span>View All Recipes</span>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('add-new-recipe'))); ?>" class="quick-link-card">
                    <span class="dashicons dashicons-plus-alt"></span>
                    <span>Add New Recipe</span>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('favorites'))); ?>" class="quick-link-card">
                    <span class="dashicons dashicons-heart"></span>
                    <span>Favorite Recipes</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?> 