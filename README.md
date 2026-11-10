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

## Requirements / deploy order

Plugin 1.1.0 needs the map component release that ships `pilgrimz-tour-map-preview.min.js` and the `map-type` values `streets`, `outdoors` and `light`. Publish it to mapcomponent.pilgrimz.dev before installing plugin 1.1.0.

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
- **map-type** (optional): Base map style: `standard` (3D default), `streets` (flat colour), `outdoors` (flat colour with relief and trails), `light` (flat grey) or `satellite`. Omitted by default (component default)
- **preview** (optional): `true`, `1` or `yes` renders `<pilgrimz-tour-map-preview>`, a static image that loads the full map only when the visitor clicks it. Defaults to the site-wide setting (off); `preview="false"` forces the full map
- **width** / **height** (optional): CSS size of the map, e.g. `100%` / `500px`

The preview avoids downloading the map script and tiles for visitors who never interact with it, which cuts page weight and energy use (eco-design). It can be enabled for every map in **Settings > Pilgrimz Tour Map**.

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

**Light map style:**
```
[pilgrimz-tour-map tour-id="123" map-type="light"]
```

**Lightweight preview (map loads on click):**
```
[pilgrimz-tour-map tour-id="123" preview="true" height="400px"]
```

## Admin Settings

Navigate to **Settings > Pilgrimz Tour Map** in your WordPress admin panel to:

1. View detailed usage instructions
2. Configure a default hub code (provided by the Pilgrimz team)
   and the site-wide lightweight preview mode
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
- `map-type`: Optional base map style (`standard`, `streets`, `outdoors`, `light`, `satellite`)

With `preview`, the plugin loads `https://mapcomponent.pilgrimz.dev/pilgrimz-tour-map-preview.min.js` instead and renders `<pilgrimz-tour-map-preview>` with the same attributes. Each script is enqueued only when at least one shortcode on the page needs it.

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

### Publishing

This plugin includes an automated publishing workflow to create releases following semantic versioning.

#### Prerequisites

**Node.js** - Required to run the publish script (v16 or higher)

Install from https://nodejs.org/ or use a version manager like `nvm`.

#### Publishing a New Version

Run the interactive publishing workflow:

```bash
pnpm run publish-plugin
```

**The script will:**
1. ✅ Check if git working tree is clean
2. ✅ Run `composer check` (lint + static analysis)
3. ✅ Prompt you to select version type (patch/minor/major) following semver
4. ✅ Update version in `pilgrimz-tour-map.php` (header + constant)
5. ✅ Commit changes with message: `chore: publish version X.Y.Z`
6. ✅ Create git tag `vX.Y.Z`
7. ✅ Push commits and tags to GitHub
8. ✅ **GitHub Actions automatically creates the release** (no local `gh` CLI needed!)

**Example:**
```bash
$ pnpm run publish-plugin

🚀 WordPress Plugin Publishing Workflow
==========================================

Step 1: Checking git status...
✅ Git working tree is clean

Step 2: Running composer check...
✅ Composer check passed

Step 3: Version selection

📦 Current version: 1.0.0

Select version update type:
1. Patch (1.0.1) - Bug fixes and minor changes
2. Minor (1.1.0) - New features, backwards compatible
3. Major (2.0.0) - Breaking changes
4. Cancel

Enter your choice (1-4): 1

📋 Summary:
   Version type: patch
   New version: 1.0.1
   Will commit, tag, and push to GitHub
   GitHub Actions will automatically create the release

Proceed with publishing? (y/n): y

✨ Success! ✨
==========================================
📦 Version 1.0.1 has been published
🏷️  Tag v1.0.1 created and pushed
🚀 GitHub Actions will create the release automatically

View releases: https://github.com/pilgrimz-dev/pilgrimz-tour-map-wp/releases
```

#### How It Works

1. **Local script** - Updates version, commits, creates tag, and pushes to GitHub
2. **GitHub Actions** - Automatically triggered when a version tag is pushed (`v*.*.*`)
3. **Release created** - GitHub Actions creates the release with installation instructions

See [.github/workflows/release.yml](.github/workflows/release.yml) for the automated release workflow.

#### Semantic Versioning Guide

- **Patch (X.Y.Z+1)**: Bug fixes, minor tweaks, no new features
- **Minor (X.Y+1.0)**: New features, backwards compatible
- **Major (X+1.0.0)**: Breaking changes, incompatible API changes


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

### 1.1.0
- Add `map-type` shortcode attribute
- Add `preview` shortcode attribute and site-wide setting (lightweight map loaded on click)

### 1.0.1, 1.0.2
- Version-only publishes; no changes recorded

### 1.0.0
- Initial release
- Shortcode integration
- Auto language detection
- Admin settings page
- Hub configuration support
