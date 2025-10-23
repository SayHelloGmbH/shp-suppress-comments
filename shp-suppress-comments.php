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
        add_action('init', array($this, 'disable_comments_post_types'));
        
        // Remove comment-related admin menus
        add_action('admin_menu', array($this, 'remove_comment_menus'));
        
        // Disable comments on the frontend
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);
        
        // Hide existing comments
        add_filter('comments_array', '__return_empty_array', 10, 2);
        
        // Remove comment support from post types
        add_action('admin_init', array($this, 'remove_comment_support'));
        
        // Disable comment-related widgets
        add_action('widgets_init', array($this, 'disable_comment_widgets'));
        
        // Remove comment links from admin bar
        add_action('admin_bar_menu', array($this, 'remove_comments_admin_bar'), 999);
        
        // Hide comment-related dashboard widgets
        add_action('wp_dashboard_setup', array($this, 'remove_comment_dashboard_widgets'));
        
        // Disable comment REST API endpoints
        add_filter('rest_endpoints', array($this, 'disable_comment_rest_api'));
        
        // Prevent programmatic comment insertion
        add_filter('pre_comment_approved', array($this, 'prevent_comment_insertion'), 10, 2);
        
        // Block wp_insert_comment and wp_new_comment
        add_action('pre_comment_on_post', array($this, 'block_comment_on_post'));
        
        // Remove comment-related meta boxes from post edit screens
        add_action('admin_menu', array($this, 'remove_comment_meta_boxes'));
        
        // Disable XML-RPC comment methods
        add_filter('xmlrpc_methods', array($this, 'disable_xmlrpc_comments'));
        
        // Remove comment count from admin menu
        add_action('admin_print_styles-index.php', array($this, 'hide_dashboard_comment_counts'));
        
        // Intercept comment form submissions
        add_action('comment_form', '__return_false');
        
        // Disable comment feeds
        add_action('do_feed_rss2_comments', array($this, 'disable_comment_feeds'));
        add_action('do_feed_atom_comments', array($this, 'disable_comment_feeds'));
    }
    
    /**
     * Disable comment support for all post types
     */
    public function disable_comments_post_types() {
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
    public function remove_comment_menus() {
        // Remove Comments menu
        remove_menu_page('edit-comments.php');
        
        // Remove Discussion Settings submenu
        remove_submenu_page('options-general.php', 'options-discussion.php');
    }
    
    /**
     * Remove comment support during admin initialization
     */
    public function remove_comment_support() {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
    
    /**
     * Disable comment-related widgets
     */
    public function disable_comment_widgets() {
        unregister_widget('WP_Widget_Recent_Comments');
    }
    
    /**
     * Remove comments from admin bar
     */
    public function remove_comments_admin_bar($wp_admin_bar) {
        $wp_admin_bar->remove_node('comments');
    }
    
    /**
     * Remove comment-related dashboard widgets
     */
    public function remove_comment_dashboard_widgets() {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    }
    
    /**
     * Disable comment REST API endpoints
     */
    public function disable_comment_rest_api($endpoints) {
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
    public function prevent_comment_insertion($approved, $commentdata) {
        // Return 'spam' to prevent comment from being inserted
        return 'spam';
    }
    
    /**
     * Block comment submissions on posts
     */
    public function block_comment_on_post($post_id) {
        wp_die(__('Comments are completely disabled on this site.', 'shp-suppress-comments'));
    }
    
    /**
     * Remove comment meta boxes from post edit screens
     */
    public function remove_comment_meta_boxes() {
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
    public function disable_xmlrpc_comments($methods) {
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
    public function hide_dashboard_comment_counts() {
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
    public function disable_comment_feeds() {
        wp_die(__('Comments are completely disabled on this site.', 'shp-suppress-comments'));
    }
}

// Initialize the plugin
new SHP_Suppress_Comments();
