# Advanced Product Review System

Complete WordPress plugin for product reviews with ratings, price comparison, specifications, and custom templates.

## Features

### 🌟 Review Components
- **Overall Rating Score** - Display with gradient background and optional Editor's Choice badge
- **Detailed Ratings** - Animated progress bars for multiple criteria (Design, Performance, Features, etc.)
- **Pros & Cons** - Side-by-side comparison boxes with checkmarks
- **Price Comparison** - Multiple store listings with stock status and buy buttons
- **Technical Specifications** - Collapsible table with all product details

### 🎨 Custom Templates
- **Product Review Template** - Full-featured layout with sidebar
- **Minimal Template** - Clean, magazine-style design
- **Template Selector** - Easy dropdown in post editor

### 💎 Premium Features
- Animated rating bars
- Collapsible sections
- Responsive mobile design
- Social sharing integration
- Best price finder
- Editor's Choice badges

## Installation

### Method 1: WordPress Admin
1. Download the `product-review-system.zip` file
2. Go to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin"
4. Choose the zip file and click "Install Now"
5. Activate the plugin

### Method 2: Manual Upload
1. Extract the zip file
2. Upload the `product-review-system` folder to `/wp-content/plugins/`
3. Activate the plugin through the WordPress admin panel

## Usage

### Adding Product Reviews

1. **Create/Edit a Post**
2. **Scroll down to "Advanced Product Information" meta box**
3. **Fill in the details:**
   - Overall Rating (0-10)
   - Check "Editor's Choice" if applicable
   - Add Detailed Ratings (e.g., Design: 8.5, Performance: 9.0)
   - Add Pros (multiple)
   - Add Cons (multiple)
   - Add Specifications (Title + Detail)
   - Add Price Comparison (Store, Price, URL, Stock)

### Selecting Custom Template

1. **In Post Editor**, find "Post Template" meta box (right sidebar)
2. **Select from dropdown:**
   - Default Template
   - Product Review Template (recommended)
   - Minimal Review Template
3. **Save/Update post**

## File Structure

```
product-review-system/
├── product-review-system.php      # Main plugin file
├── includes/
│   ├── class-meta-boxes.php       # Admin meta boxes
│   ├── class-frontend-display.php # Frontend output
│   └── class-template-selector.php # Template selector
├── assets/
│   ├── css/
│   │   └── frontend.css           # All styles
│   └── js/
│       ├── frontend.js            # With animations
│       └── frontend-simple.js     # No animations
├── templates/
│   ├── single-product-review.php  # Full template
│   └── single-minimal.php         # Minimal template
└── README.md
```

## Customization

### Switch to Simple JavaScript (No Animations)

Edit `product-review-system.php` line ~55:

```php
// Change from:
wp_enqueue_script('prs-scripts', PRS_PLUGIN_URL . 'assets/js/frontend.js', ...);

// To:
wp_enqueue_script('prs-scripts', PRS_PLUGIN_URL . 'assets/js/frontend-simple.js', ...);
```

### Customize Colors

Edit `assets/css/frontend.css` to change:
- Gradient colors
- Rating bar colors
- Button styles
- Badge colors

### Add Custom Fields

Edit `includes/class-meta-boxes.php` to add more fields in the meta box.

## Helper Functions

Use these functions in your theme:

```php
// Get overall rating
$rating = prs_get_rating($post_id);

// Get detailed ratings
$ratings = prs_get_ratings($post_id);

// Get features/specs
$features = prs_get_features($post_id);

// Get prices
$prices = prs_get_prices($post_id);

// Get pros
$pros = prs_get_pros($post_id);

// Get cons
$cons = prs_get_cons($post_id);

// Check if editor's choice
if (prs_is_editors_choice($post_id)) {
    echo "⭐ Editor's Choice";
}
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- No additional plugins required

## Support

For support and updates, contact:
- LinkedIn: [Muhammad Sajid Iqbal](https://www.linkedin.com/in/muhammad-sajid-iqbal-7bb56a1a1/)

## Changelog

### Version 2.1.0
- Enhanced Security: Implemented proper escaping for all dynamic frontend and admin output.
- Performance: Added `wp_unslash()` and rigorous sanitization to all input handling logic.
- Bug Fixes: Corrected syntax errors in `esc_html_e` calls and fixed undefined function typos.
- WordPress 4.6+ Compatibility: Removed discouraged `load_plugin_textdomain()` call (WP 4.6 handles this automatically).
- Repository Compliance: Created missing `languages/` directory and removed hidden `.git`/`.gitignore` files.
- Security: Upgraded redirect logic to use `wp_safe_redirect()` with proper exit calls.

### Version 2.0.0
- Revamped Settings UI with modern aesthetics.
- Added WordPress Color Picker integration.
- Added "Reset to Defaults" functionality.
- Fixed "Cannot load" and PHP Deprecation errors.
- Improved dynamic CSS injection.
- Added Global Review Settings page.

### Version 1.0.0
- Initial release.

## License

GPL v2 or later

## Credits

Developed by: [Muhammad Sajid Iqbal](https://www.linkedin.com/in/muhammad-sajid-iqbal-7bb56a1a1/)
LinkedIn: [Profile](https://www.linkedin.com/in/muhammad-sajid-iqbal-7bb56a1a1/)

---

**Enjoy creating amazing product reviews! 🎉**
