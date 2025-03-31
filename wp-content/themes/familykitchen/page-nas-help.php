<?php
/**
 * Template Name: NAS Help Dashboard
 */

get_header(); ?>

<div class="content-area">
    <div class="container">
        <div class="nas-dashboard">
            <div class="welcome-section">
                <h1>NAS Help Dashboard</h1>
                <p>Everything you need to know about our family NAS setup and how to use it.</p>
            </div>

            <div class="nas-grid">
                <!-- Quick Access Section -->
                <div class="nas-section">
                    <h2>Quick Access</h2>
                    <div class="nas-cards">
                        <div class="nas-card">
                            <span class="dashicons dashicons-admin-users"></span>
                            <h3>User Access</h3>
                            <p>How to access the NAS and manage user accounts</p>
                            <ul>
                                <li>Default URL: http://priddy-nas:5000</li>
                                <li>Admin credentials in family safe</li>
                                <li>Individual user accounts available</li>
                            </ul>
                        </div>

                        <div class="nas-card">
                            <span class="dashicons dashicons-backup"></span>
                            <h3>Backup Status</h3>
                            <p>Current backup information and schedule</p>
                            <ul>
                                <li>Daily backups at 2 AM</li>
                                <li>Weekly backups on Sunday</li>
                                <li>Monthly backups on 1st of month</li>
                            </ul>
                        </div>

                        <div class="nas-card">
                            <span class="dashicons dashicons-shield"></span>
                            <h3>Security</h3>
                            <p>Security settings and best practices</p>
                            <ul>
                                <li>2FA enabled for admin</li>
                                <li>Regular security updates</li>
                                <li>Firewall rules in place</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- App Status Section -->
                <div class="nas-section">
                    <h2>App Status</h2>
                    <div class="nas-cards">
                        <div class="nas-card">
                            <span class="dashicons dashicons-media-default"></span>
                            <h3>File Station</h3>
                            <p>File sharing and management</p>
                            <ul>
                                <li>Status: Active</li>
                                <li>Storage: 8TB available</li>
                                <li>Access: Family members only</li>
                            </ul>
                        </div>

                        <div class="nas-card">
                            <span class="dashicons dashicons-camera"></span>
                            <h3>Photo Station</h3>
                            <p>Photo storage and sharing</p>
                            <ul>
                                <li>Status: Active</li>
                                <li>Storage: 2TB used</li>
                                <li>Auto-upload enabled</li>
                            </ul>
                        </div>

                        <div class="nas-card">
                            <span class="dashicons dashicons-video-alt3"></span>
                            <h3>Video Station</h3>
                            <p>Video streaming and storage</p>
                            <ul>
                                <li>Status: Active</li>
                                <li>Transcoding: Enabled</li>
                                <li>Remote access: Available</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Troubleshooting Section -->
                <div class="nas-section">
                    <h2>Troubleshooting</h2>
                    <div class="nas-cards">
                        <div class="nas-card">
                            <span class="dashicons dashicons-warning"></span>
                            <h3>Common Issues</h3>
                            <p>Solutions for frequent problems</p>
                            <ul>
                                <li>Can't access NAS: Check network connection</li>
                                <li>Slow performance: Check other users' activity</li>
                                <li>Backup failed: Check drive space</li>
                            </ul>
                        </div>

                        <div class="nas-card">
                            <span class="dashicons dashicons-phone"></span>
                            <h3>Support Contacts</h3>
                            <p>Who to contact for help</p>
                            <ul>
                                <li>Primary: [Family Member Name]</li>
                                <li>Backup: [Family Member Name]</li>
                                <li>Emergency: [Contact Info]</li>
                            </ul>
                        </div>

                        <div class="nas-card">
                            <span class="dashicons dashicons-book"></span>
                            <h3>Documentation</h3>
                            <p>Helpful resources and guides</p>
                            <ul>
                                <li>User Manual: [Link]</li>
                                <li>Setup Guide: [Link]</li>
                                <li>FAQ: [Link]</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?> 