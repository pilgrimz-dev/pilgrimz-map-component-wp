# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress plugin that embeds the Pilgrimz tour map web component into WordPress sites via a shortcode. The plugin is contained in a single PHP file with no build process required.

## Architecture

**Single-File Plugin Structure**: All functionality is in `pilgrimz-tour-map.php` with no separate classes or files. This is intentional for simplicity and follows the WordPress pattern for lightweight plugins.

**Key Components**:
1. **Shortcode Handler** (`pilgrimz_tour_map_shortcode`): Converts `[pilgrimz-tour-map]` shortcodes into `<pilgrimz-tour-map>` web component tags
2. **Script Enqueuing** (`pilgrimz_tour_map_enqueue_scripts`): Conditionally loads the external web component script only when the shortcode is present on a page
3. **Settings API Integration**: Uses WordPress Settings API for the admin configuration page (Settings > Pilgrimz Tour Map)
4. **Language Detection** (`pilgrimz_tour_map_get_language`): Auto-detects WordPress locale and extracts 2-letter language code

## External Dependencies

**Web Component**: The plugin loads an external web component from:
- URL: `https://mapcomponent.pilgrimz.dev/pilgrimz-tour-map.min.js`
- Component tag: `<pilgrimz-tour-map>`
- Component attributes: `tour-id`, `language`, `hub`

When updating the web component URL or tag name, update both:
- Line 36: `wp_enqueue_script()` URL parameter
- Lines 93-102: Component tag generation in `pilgrimz_tour_map_shortcode()`

## Settings Hierarchy

The plugin has a 3-tier settings hierarchy for the `hub` parameter:
1. Shortcode attribute (highest priority): `[pilgrimz-tour-map hub="custom"]`
2. WordPress admin setting (fallback): Stored in `pilgrimz_tour_map_hub` option
3. Component default (final fallback): Hub attribute omitted entirely

## WordPress Standards

This plugin follows WordPress coding standards:
- **Security**: All output uses `esc_attr()`, `esc_html()`, and `esc_html__()`
- **Translation-ready**: Text domain `pilgrimz-tour-map` with `__()` functions
- **Settings API**: Uses `register_setting()`, `add_settings_section()`, `add_settings_field()`
- **Hooks**: Uses standard WordPress actions: `wp_enqueue_scripts`, `admin_menu`, `admin_init`, `plugins_loaded`

## Installation for Testing

To test this plugin:
1. Copy entire directory to WordPress `/wp-content/plugins/`
2. Activate via WordPress admin Plugins page
3. Test shortcode: `[pilgrimz-tour-map tour-id="123"]`
4. Admin settings: Settings > Pilgrimz Tour Map

## Key Functions

- `pilgrimz_tour_map_shortcode()`: Main shortcode handler - generates web component HTML
- `pilgrimz_tour_map_enqueue_scripts()`: Conditional script loading (only when shortcode detected)
- `pilgrimz_tour_map_get_language()`: Extracts 2-char language code from WordPress locale
- `pilgrimz_tour_map_settings_page()`: Renders admin settings page with usage instructions

## Version Updates

When updating the plugin version:
1. Line 6: Plugin header comment `Version:`
2. Line 23: `PILGRIMZ_TOUR_MAP_VERSION` constant
3. README.md Changelog section

## Code Quality Tools

The plugin uses Composer-based PHP code quality tools for linting and static analysis.

### Available Commands

```bash
composer install      # Install dev dependencies (one-time setup)
composer lint         # Check code style with PHP_CodeSniffer (like ESLint)
composer lint:fix     # Auto-fix code style issues with PHPCBF (like Prettier)
composer analyze      # Static analysis with PHPStan (like TypeScript)
composer check        # Run both lint + analyze (recommended before commits)
```

### Configuration Files

- **composer.json**: Defines dev dependencies (PHPCS, WPCS, PHPStan, WordPress stubs) and scripts
- **phpcs.xml**: PHP_CodeSniffer configuration
  - WordPress Coding Standards
  - PHP compatibility checks (7.4+)
  - Text domain validation (`pilgrimz-tour-map`)
  - Function prefix enforcement (`pilgrimz_tour_map_`)
- **phpstan.neon**: PHPStan configuration
  - Analysis level 5 (out of 9)
  - WordPress function stubs to prevent false positives
  - Memory limit: 512M

### Development Workflow

**Before committing changes:**
```bash
composer check
```

**If linting errors are found:**
```bash
composer lint:fix     # Auto-fix formatting
composer lint         # Verify remaining issues
```

**Common linting issues that can't be auto-fixed:**
- Missing `@package` tag in file header comment
- Inline comments without punctuation (must end with `.`, `!`, or `?`)
- Missing nonce verification warnings (expected for read-only operations)

### Tool Equivalents

| JavaScript (map-component) | PHP (wp plugin) |
|---------------------------|-----------------|
| `pnpm run lint` | `composer lint` |
| `pnpm run format` | `composer lint:fix` |
| `pnpm run typecheck` | `composer analyze` |
| `pnpm run verify` | `composer check` |
