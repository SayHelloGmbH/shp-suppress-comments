<?php
/**
 * Plugin Name: SayHello Suppress Comments
 * Plugin URI: https://github.com/SayHelloGmbH/shp-suppress-comments
 * Description: Completely suppress all WordPress commenting functions including programmatic submissions and API access. Removes comment-related views and links from the admin area.
 * Version: 1.0.0
 * Author: Say Hello GmbH
 * Author URI: https://sayhello.ch
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: shp-suppress-comments
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class to suppress all comment functionality
 */
class SHP_Suppress_Comments {
    
    /**
     * Initialize the plugin
     */
    public function __construct() {
        // Disable comment support for all post types
        add_action('init', [$this, 'disableCommentsPostTypes']);
        
        // Remove comment-related admin menus
        add_action('admin_menu', [$this, 'removeCommentMenus']);
        
        // Disable comments on the frontend
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);
        
        // Hide existing comments
        add_filter('comments_array', '__return_empty_array', 10, 2);
        
        // Remove comment support from post types
        add_action('admin_init', [$this, 'removeCommentSupport']);
        
        // Disable comment-related widgets
        add_action('widgets_init', [$this, 'disableCommentWidgets']);
        
        // Remove comment links from admin bar
        add_action('admin_bar_menu', [$this, 'removeCommentsAdminBar'], 999);
        
        // Hide comment-related dashboard widgets
        add_action('wp_dashboard_setup', [$this, 'removeCommentDashboardWidgets']);
        
        // Disable comment REST API endpoints
        add_filter('rest_endpoints', [$this, 'disableCommentRestApi']);
        
        // Prevent programmatic comment insertion
        add_filter('pre_comment_approved', [$this, 'preventCommentInsertion'], 10, 2);
        
        // Block wp_insert_comment and wp_new_comment
        add_action('pre_comment_on_post', [$this, 'blockCommentOnPost']);
        
        // Remove comment-related meta boxes from post edit screens
        add_action('admin_menu', [$this, 'removeCommentMetaBoxes']);
        
        // Disable XML-RPC comment methods
        add_filter('xmlrpc_methods', [$this, 'disableXmlrpcComments']);
        
        // Remove comment count from admin menu
        add_action('admin_print_styles-index.php', [$this, 'hideDashboardCommentCounts']);
        
        // Intercept comment form submissions
        add_action('comment_form', '__return_false');
        
        // Disable comment feeds
        add_action('do_feed_rss2_comments', [$this, 'disableCommentFeeds']);
        add_action('do_feed_atom_comments', [$this, 'disableCommentFeeds']);
    }
    
    /**
     * Disable comment support for all post types
     */
    public function disableCommentsPostTypes() {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }
    
    /**
     * Remove comment-related admin menus
     */
    public function removeCommentMenus() {
        // Remove Comments menu
        remove_menu_page('edit-comments.php');
        
        // Remove Discussion Settings submenu
        remove_submenu_page('options-general.php', 'options-discussion.php');
    }
    
    /**
     * Remove comment support during admin initialization
     */
    public function removeCommentSupport() {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
    
    /**
     * Disable comment-related widgets
     */
    public function disableCommentWidgets() {
        unregister_widget('WP_Widget_Recent_Comments');
    }
    
    /**
     * Remove comments from admin bar
     */
    public function removeCommentsAdminBar($wp_admin_bar) {
        $wp_admin_bar->remove_node('comments');
    }
    
    /**
     * Remove comment-related dashboard widgets
     */
    public function removeCommentDashboardWidgets() {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    }
    
    /**
     * Disable comment REST API endpoints
     */
    public function disableCommentRestApi($endpoints) {
        if (isset($endpoints['/wp/v2/comments'])) {
            unset($endpoints['/wp/v2/comments']);
        }
        if (isset($endpoints['/wp/v2/comments/(?P<id>[\d]+)'])) {
            unset($endpoints['/wp/v2/comments/(?P<id>[\d]+)']);
        }
        return $endpoints;
    }
    
    /**
     * Prevent programmatic comment insertion
     */
    public function preventCommentInsertion($approved, $commentdata) {
        // Return 'spam' to prevent comment from being inserted
        return 'spam';
    }
    
    /**
     * Block comment submissions on posts
     */
    public function blockCommentOnPost($post_id) {
        wp_die(__('Comments are completely disabled on this site.', 'shp-suppress-comments'));
    }
    
    /**
     * Remove comment meta boxes from post edit screens
     */
    public function removeCommentMetaBoxes() {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            remove_meta_box('commentstatusdiv', $post_type, 'normal');
            remove_meta_box('commentsdiv', $post_type, 'normal');
            remove_meta_box('trackbacksdiv', $post_type, 'normal');
        }
    }
    
    /**
     * Disable XML-RPC comment methods
     */
    public function disableXmlrpcComments($methods) {
        unset($methods['wp.newComment']);
        unset($methods['wp.getComments']);
        unset($methods['wp.getComment']);
        unset($methods['wp.deleteComment']);
        unset($methods['wp.editComment']);
        return $methods;
    }
    
    /**
     * Hide dashboard comment counts
     */
    public function hideDashboardCommentCounts() {
        echo '<style>
            #dashboard_right_now .comment-count,
            #dashboard_right_now .comment-mod-count,
            #latest-comments,
            #activity-widget .comment-count {
                display: none !important;
            }
        </style>';
    }
    
    /**
     * Disable comment feeds
     */
    public function disableCommentFeeds() {
        wp_die(__('Comments are completely disabled on this site.', 'shp-suppress-comments'));
    }
}

// Initialize the plugin
new SHP_Suppress_Comments();
