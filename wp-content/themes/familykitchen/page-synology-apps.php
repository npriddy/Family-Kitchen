<?php
/**
 * Template Name: Synology Apps
 */

get_header(); ?>

<div class="content-area">
    <div class="container">
        <h1 class="page-title"><?php the_title(); ?></h1>
        
        <div class="synology-apps">
            <!-- Example Synology app cards - customize these with your actual apps -->
            <div class="app-card">
                <h3>File Station</h3>
                <p>Access and manage your files</p>
                <a href="http://your-nas:5000" target="_blank">Open File Station</a>
            </div>

            <div class="app-card">
                <h3>Photo Station</h3>
                <p>View and organize your photos</p>
                <a href="http://your-nas:5000/photo" target="_blank">Open Photo Station</a>
            </div>

            <div class="app-card">
                <h3>Video Station</h3>
                <p>Stream your videos</p>
                <a href="http://your-nas:5000/video" target="_blank">Open Video Station</a>
            </div>

            <div class="app-card">
                <h3>Download Station</h3>
                <p>Manage your downloads</p>
                <a href="http://your-nas:5000/download" target="_blank">Open Download Station</a>
            </div>
        </div>

        <?php while (have_posts()) : the_post(); ?>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?> 