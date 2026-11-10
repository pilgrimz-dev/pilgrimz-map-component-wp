# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## MANDATORY: Maintain backward compatibility

When working on **any** functionality, agents MUST ALWAYS preserve backward compatibility. Do **NOT** break API contracts, change behavior that other apps depend on (especially **Creators** and **Expo**), or introduce any breaking change unless the operator has **explicitly approved and asked** for it. This applies to every project in this monorepo and to every spawned agent/teammate.

A "breaking change" is anything that could make an existing consumer stop working without changes on their side:

- Removing, renaming, or retyping a field, column, RPC parameter, endpoint, query/header param, or response shape
- Changing the meaning, units, defaults, or nullability of an existing value
- Tightening validation, auth, permissions, or rate limits on an existing path
- Removing or renaming a shared DB object (table, column, view, RPC, enum value) that other apps read
- Changing event/payload formats consumed elsewhere (offline SQLite bundles, analytics ingest, webhooks, deep links)

Prefer additive, non-destructive changes (new optional fields, new endpoints, versioned routes, defaults that preserve old behavior). When a change **cannot** avoid breaking compatibility, STOP and surface the trade-off plus a migration/additive alternative to the operator, then wait for explicit approval before proceeding.

## MANDATORY: Public repository

This repository is public: its code, commit messages, issues, pull requests and comments are visible to anyone, including clients. Write all of them for a public reader:

- No client or prospect names, no people's names, no internal contacts.
- No secrets, credentials, internal hostnames, vault paths or prod data.
- No internal workflow notes (autonomous runs, unsigned commits, re-signing, parked items, operator steps); keep those in `pilgrimz-workspace`.
- Links to private repos (e.g. `pilgrimz-workspace` epics) are fine as references but must not be needed to understand the issue.
- Describe security fixes by their effect once released, without exploit steps.

## Project Overview

This is a WordPress plugin that embeds the Pilgrimz tour map web component into WordPress sites via a shortcode. The plugin is contained in a single PHP file with no build process required.

## Cross-Project Work

**If a task requires changes in a different project** (e.g., a Cloudflare worker, the Express API, Creators, the Expo app, etc.), you MUST:

1. **Suggest spawning a dedicated agent** with its working directory set to the target project folder — this ensures the agent loads that project's CLAUDE.md, skills, and MCP servers automatically.
2. **Let the user decide** — they can accept (recommended for full context) or decline and have you work without the project-specific context.

## Architecture

**Single-File Plugin Structure**: All functionality is in `pilgrimz-tour-map.php` with no separate classes or files. This is intentional for simplicity and follows the WordPress pattern for lightweight plugins.

**Key Components**:
1. **Shortcode Handler** (`pilgrimz_tour_map_shortcode`): Converts `[pilgrimz-tour-map]` shortcodes into `<pilgrimz-tour-map>` or `<pilgrimz-tour-map-preview>` web component tags
2. **Script Enqueuing** (`pilgrimz_tour_map_enqueue_scripts`): Pre-scans the shortcodes in `post_content` and enqueues only the script(s) the page needs (full, preview, or both)
3. **Settings API Integration**: Uses WordPress Settings API for the admin configuration page (Settings > Pilgrimz Tour Map), "Default Settings" section with the hub and lightweight preview options
4. **Language Detection** (`pilgrimz_tour_map_get_language`): Auto-detects WordPress locale and extracts 2-letter language code

## External Dependencies

**Web Component**: The plugin loads external scripts from `mapcomponent.pilgrimz.dev`:

| Script handle | URL | Tag |
| --- | --- | --- |
| `pilgrimz-tour-map-component` (full) | `https://mapcomponent.pilgrimz.dev/pilgrimz-tour-map.min.js` | `<pilgrimz-tour-map>` |
| `pilgrimz-tour-map-preview-component` | `https://mapcomponent.pilgrimz.dev/pilgrimz-tour-map-preview.min.js` | `<pilgrimz-tour-map-preview>` |

Shortcode attributes: `tour-id`, `language`, `hub`, `width`, `height`, `map-type` (`standard`, `streets`, `outdoors`, `light`, `satellite`), `preview`. Both tags receive the same attributes.

- Attribute defaults: `pilgrimz_tour_map_parse_atts()`
- `preview` rule: `pilgrimz_tour_map_is_preview()` (empty uses the site option `pilgrimz_tour_map_preview`; `false`/`0`/`no` is off; any other value is on)
- `width`/`height` are filtered by `pilgrimz_tour_map_css_length()`

When changing a script URL, handle or tag name, update both `pilgrimz_tour_map_enqueue_scripts()` and the tag generation in `pilgrimz_tour_map_shortcode()`.

**Deploy order**: the map component release must be published to mapcomponent.pilgrimz.dev before the plugin release that relies on it.

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
- `pilgrimz_tour_map_parse_atts()`: Merges shortcode attributes with their defaults
- `pilgrimz_tour_map_is_preview()`: Decides full map vs preview for a `preview` value
- `pilgrimz_tour_map_css_length()`: Rejects `width`/`height` values that could inject CSS
- `pilgrimz_tour_map_enqueue_scripts()`: Enqueues only the script(s) the page's shortcodes need
- `pilgrimz_tour_map_get_language()`: Extracts 2-char language code from WordPress locale
- `pilgrimz_tour_map_settings_page()`: Renders admin settings page with usage instructions

## Version Updates

When updating the plugin version:
1. Plugin header comment `Version:`
2. `PILGRIMZ_TOUR_MAP_VERSION` constant
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
