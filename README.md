# SayHello Suppress Comments

A WordPress plugin that completely suppresses all commenting functionality across your WordPress site.

## Description

This plugin provides comprehensive comment suppression for WordPress, including:

- **Complete Comment Blocking**: Disables comment support for all post types
- **API Protection**: Blocks comment submissions via REST API, XML-RPC, and all programmatic methods
- **Admin Area Cleanup**: Removes all comment-related menus, links, and UI elements from WordPress admin
- **Frontend Suppression**: Disables comment forms, existing comment display, and comment feeds
- **Dashboard Cleanup**: Removes comment widgets and counts from the WordPress dashboard

## Features

### Comment Functionality Suppression
- Disables comments and trackbacks for all post types
- Blocks programmatic comment insertion via `wp_insert_comment()` and `wp_new_comment()`
- Prevents comment submissions through forms
- Disables comment and ping status for all content

### API Protection
- Disables WordPress REST API comment endpoints (`/wp/v2/comments`)
- Removes XML-RPC comment methods (`wp.newComment`, `wp.getComments`, etc.)
- Blocks comment feeds (RSS and Atom)

### Admin Area Cleanup
- Removes "Comments" menu from admin sidebar
- Removes "Discussion Settings" from Settings menu
- Hides comment-related meta boxes from post edit screens
- Removes comments from admin bar
- Removes comment dashboard widgets
- Hides comment counts throughout the admin interface

### Widget Management
- Unregisters the "Recent Comments" widget

## Installation

1. Upload the `shp-suppress-comments` directory to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Comments are now completely suppressed - no configuration needed!

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## Usage

Simply activate the plugin. There are no settings to configure - all comment functionality is automatically suppressed site-wide.

To re-enable comments, simply deactivate the plugin.

## Technical Details

### Hooks and Filters Used

The plugin uses multiple WordPress hooks to ensure comprehensive comment suppression:

- `init` - Removes comment support from post types
- `admin_menu` - Removes comment-related admin menus
- `comments_open` - Forces comments closed
- `pings_open` - Forces pings closed
- `comments_array` - Returns empty array for existing comments
- `admin_init` - Additional comment support removal
- `widgets_init` - Disables comment widgets
- `admin_bar_menu` - Removes comments from admin bar
- `wp_dashboard_setup` - Removes comment dashboard widgets
- `rest_endpoints` - Disables REST API comment endpoints
- `pre_comment_approved` - Prevents comment approval
- `pre_comment_on_post` - Blocks comment submissions
- `xmlrpc_methods` - Disables XML-RPC comment methods

### Security

This plugin provides multiple layers of protection to ensure comments cannot be submitted through any method:

1. **Filter Layer**: Uses WordPress filters to disable comment functionality
2. **Action Layer**: Intercepts comment submissions and blocks them
3. **API Layer**: Removes comment endpoints from REST API and XML-RPC
4. **UI Layer**: Removes all comment-related interfaces

## Support

For issues, questions, or contributions, please visit:
https://github.com/SayHelloGmbH/shp-suppress-comments

## License

GPL-3.0-or-later

## Author

Say Hello GmbH
https://sayhello.ch

## Changelog

### 1.0.0
- Initial release
- Complete comment suppression functionality
- Admin area cleanup
- API endpoint protection
