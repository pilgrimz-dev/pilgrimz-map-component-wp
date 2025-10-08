# Pilgrimz Tour Map WordPress Plugin

A WordPress plugin that allows you to embed interactive Pilgrimz tour maps into your posts and pages using a simple shortcode.

## Description

The Pilgrimz Tour Map plugin integrates the Pilgrimz web component into your WordPress site, allowing you to display beautiful, interactive tour maps with minimal effort. The plugin automatically detects your WordPress site's language and applies it to the tour maps.

## Features

- ✅ Simple shortcode integration: `[pilgrimz-tour-map tour-id="123"]`
- ✅ Automatic language detection from WordPress settings
- ✅ Optional custom hub configuration for personalized styling
- ✅ Clean admin interface with clear instructions
- ✅ Follows WordPress coding standards and best practices
- ✅ Secure with proper sanitization and escaping
- ✅ Performance optimized - only loads scripts when shortcode is used

## Installation

1. Upload the `pilgrimz-tour-map` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Pilgrimz Tour Map to configure the default hub (optional)
4. Start using the shortcode in your posts and pages!

## Usage

### Basic Shortcode

Add a tour map to any post or page using the shortcode:

```
[pilgrimz-tour-map tour-id="123"]
```

### Shortcode Parameters

- **tour-id** (required): The ID of the tour you want to display
- **language** (optional): Language code (en, es, fr, etc.). Defaults to your WordPress site language
- **hub** (optional): Custom hub code for personalized styling. Defaults to the hub configured in settings

### Examples

**Basic usage with auto-detected language:**
```
[pilgrimz-tour-map tour-id="123"]
```

**Spanish language tour:**
```
[pilgrimz-tour-map tour-id="456" language="es"]
```

**French tour with custom hub:**
```
[pilgrimz-tour-map tour-id="789" language="fr" hub="custom-hub"]
```

## Admin Settings

Navigate to **Settings > Pilgrimz Tour Map** in your WordPress admin panel to:

1. View detailed usage instructions
2. Configure a default hub code (provided by the Pilgrimz team)
3. See shortcode examples

### Hub Configuration

The hub parameter allows for customized look and feel of your tour maps. The Pilgrimz team will provide you with a hub code if you need personalized styling. Leave this blank to use the default appearance.

## Supported Languages

The plugin supports any language code that the Pilgrimz tour map component supports, including:
- English (en)
- Spanish (es)
- French (fr)
- And more...

The plugin automatically detects your WordPress site language using `get_locale()` and extracts the appropriate language code.

## Technical Details

### Web Component

The plugin loads the Pilgrimz tour map web component from:
```
https://mapcomponent.pilgrimz.dev/pilgrimz-tour-map.min.js
```

The web component is a custom element `<pilgrimz-tour-map>` that accepts the following attributes:
- `tour-id`: The tour identifier
- `language`: The language code
- `hub`: Optional hub code for customization

### WordPress Integration

- Hooks used: `wp_enqueue_scripts`, `admin_menu`, `admin_init`, `plugins_loaded`
- Settings API for admin configuration
- Proper sanitization and escaping for security
- Translation-ready with textdomain support

## Development

### File Structure

```
pilgrimz-tour-map/
├── pilgrimz-tour-map.php  # Main plugin file
├── README.md              # This file
├── CLAUDE.md              # AI assistant guidance
├── composer.json          # PHP dependencies
├── phpcs.xml              # Code style configuration
├── phpstan.neon           # Static analysis configuration
└── languages/             # Translation files
```

### Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher

### Code Quality Tools

This plugin includes PHP code quality tools for linting, formatting, and static analysis.

#### Tools Included

- **PHP_CodeSniffer (PHPCS)** - Linting and code style checking
- **PHP Code Beautifier and Fixer (PHPCBF)** - Auto-fix code style issues
- **PHPStan** - Static analysis and type checking
- **WordPress Coding Standards (WPCS)** - WordPress-specific rules

#### Setup

**Prerequisites**: Install Composer (PHP package manager)

See https://getcomposer.org/download/


**Install dependencies:**
```bash
composer install
```

#### Available Commands

```bash
composer lint          # Check code style
composer lint:fix      # Auto-fix formatting issues
composer analyze       # Static analysis
composer check         # Run all checks (lint + analyze)
```

#### Configuration Files

- **phpcs.xml** - PHP_CodeSniffer rules (WordPress Coding Standards, PHP 7.4+ compatibility)
- **phpstan.neon** - PHPStan static analysis config (level 5)
- **composer.json** - Dependencies and script definitions

#### CI/CD Integration

Add to GitHub Actions workflow:
```yaml
- name: Install Composer dependencies
  run: composer install
- name: Run code quality checks
  run: composer check
```


## Frequently Asked Questions

**Q: Where do I get a tour ID?**
A: Tour IDs are provided by the Pilgrimz platform when you create a tour.

**Q: What is a hub code?**
A: A hub code is a customization identifier provided by the Pilgrimz team that applies personalized styling to your tour maps. It's optional and you can leave it blank to use the default styling.

**Q: Can I use multiple tour maps on the same page?**
A: Yes! You can use the shortcode multiple times on the same page with different tour IDs.

**Q: How do I change the language?**
A: The plugin automatically uses your WordPress site language. You can override it for specific tours using the `language` parameter in the shortcode.

## Support

For support, please contact the Pilgrimz team at https://pilgrimz.dev

## License

This plugin is licensed under the GPL v2 or later.

## Changelog

### 1.0.0
- Initial release
- Shortcode integration
- Auto language detection
- Admin settings page
- Hub configuration support
