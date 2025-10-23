# Testing Guide for SayHello Suppress Comments

This document outlines how to test the plugin functionality.

## Installation Testing

1. **Install the Plugin**
   - Upload to `/wp-content/plugins/shp-suppress-comments/`
   - Activate via WordPress admin
   - Should activate without errors

## Functionality Testing

### Admin Area Tests

1. **Comments Menu**
   - Navigate to WordPress admin
   - Verify "Comments" menu is not visible in sidebar
   - Expected: No "Comments" menu item

2. **Discussion Settings**
   - Go to Settings menu
   - Verify "Discussion" submenu is not present
   - Expected: No "Discussion Settings" option

3. **Post Edit Screen**
   - Edit any post or page
   - Verify no comment-related meta boxes appear
   - Expected: No "Discussion", "Comments", or "Trackbacks" meta boxes

4. **Admin Bar**
   - Check WordPress admin bar (top of screen)
   - Verify no comment icon/link appears
   - Expected: No comment indicator in admin bar

5. **Dashboard Widgets**
   - View WordPress dashboard
   - Verify no "Recent Comments" widget
   - Verify no comment counts displayed
   - Expected: Comment-related widgets are hidden

### Frontend Tests

1. **Comment Forms**
   - View any post or page on frontend
   - Verify no comment form is displayed
   - Expected: No comment form visible

2. **Existing Comments**
   - View a post that previously had comments
   - Verify no comments are displayed
   - Expected: All comments hidden

3. **Comment Feeds**
   - Try to access `/feed/comments/`
   - Expected: Error message "Comments are completely disabled"

### API Tests

1. **REST API**
   ```bash
   # Test GET comments endpoint
   curl http://your-site.com/wp-json/wp/v2/comments
   # Expected: 404 or endpoint not found
   ```

2. **XML-RPC**
   - Attempt to call `wp.newComment` via XML-RPC
   - Expected: Method should not exist

3. **Programmatic Insertion**
   ```php
   // Test in WordPress environment
   wp_insert_comment(array(
       'comment_post_ID' => 1,
       'comment_content' => 'Test comment',
       'comment_author' => 'Test User'
   ));
   // Expected: Comment not inserted (marked as spam)
   ```

### Post Type Tests

1. **All Post Types**
   - Check posts, pages, and custom post types
   - Verify comment support is removed for all
   - Expected: `post_type_supports('post', 'comments')` returns false

## Deactivation Testing

1. **Deactivate Plugin**
   - Deactivate the plugin via WordPress admin
   - Verify comment functionality returns to normal
   - Expected: All comment features restored

## Security Testing

1. **Direct File Access**
   ```bash
   curl http://your-site.com/wp-content/plugins/shp-suppress-comments/shp-suppress-comments.php
   # Expected: Blank page or exit (no PHP code execution)
   ```

2. **Comment Submission Attempts**
   - Try submitting comments via various methods:
     - Frontend form (if not removed by theme)
     - REST API
     - XML-RPC
     - wp_insert_comment()
   - Expected: All attempts should fail

## Expected Results Summary

✅ All comment-related UI elements removed from admin
✅ Comments cannot be submitted via any method
✅ Existing comments are hidden
✅ Comment feeds disabled
✅ REST API comment endpoints removed
✅ XML-RPC comment methods removed
✅ No errors in WordPress debug log
✅ Plugin can be safely deactivated

## Notes

- No configuration needed - plugin works immediately upon activation
- No database changes - completely filter-based approach
- Compatible with all themes and most plugins
- Safe to activate/deactivate without data loss
