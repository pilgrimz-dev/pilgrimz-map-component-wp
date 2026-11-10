<?php
/**
 * Plugin Name: Pilgrimz Tour Map
 * Plugin URI: https://pilgrimz.dev
 * Description: A WordPress plugin to embed Pilgrimz tour maps using a shortcode. Simply use [pilgrimz-tour-map tour-id="123"] to add an interactive tour map to your posts and pages.
 * Version: 1.1.0
 * Author: Pilgrimz
 * Author URI: https://pilgrimz.dev
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pilgrimz-tour-map
 * Domain Path: /languages
 *
 * @package Pilgrimz_Tour_Map
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'PILGRIMZ_TOUR_MAP_VERSION', '1.1.0' );
define( 'PILGRIMZ_TOUR_MAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PILGRIMZ_TOUR_MAP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Whether a shortcode `preview` value turns the lightweight preview on.
 * An empty value falls back to the site-wide setting; "false", "0" and "no"
 * turn it off; any other value turns it on.
 *
 * @param string $value Raw `preview` shortcode attribute.
 * @return bool
 */
function pilgrimz_tour_map_is_preview( $value ) {
	$value = strtolower( trim( $value ) );
	if ( '' === $value ) {
		return (bool) get_option( 'pilgrimz_tour_map_preview', false );
	}
	return ! in_array( $value, array( 'false', '0', 'no' ), true );
}

/**
 * Parse raw shortcode attributes against the shortcode defaults.
 *
 * @param array|string $atts Raw shortcode attributes.
 * @return array Attributes merged with defaults.
 */
function pilgrimz_tour_map_parse_atts( $atts ) {
	return shortcode_atts(
		array(
			'tour-id'  => '',
			'language' => pilgrimz_tour_map_get_language(), // Auto-detect from WordPress.
			'hub'      => '', // Optional, defaults to empty (will use component's default).
			'width'    => '', // Optional, for custom width.
			'height'   => '', // Optional, for custom height.
			'map-type' => '', // Optional: standard, satellite or light.
			'preview'  => '', // Optional: true to load the map on click; empty uses the site setting.
		),
		$atts,
		'pilgrimz-tour-map'
	);
}

/**
 * Enqueue the web component scripts on the frontend.
 * The full script loads when at least one shortcode renders the full map,
 * the preview script when at least one renders the preview.
 */
function pilgrimz_tour_map_enqueue_scripts() {
	global $post;
	if ( ! is_a( $post, 'WP_Post' ) || ! has_shortcode( $post->post_content, 'pilgrimz-tour-map' ) ) {
		return;
	}

	$needs_full    = false;
	$needs_preview = false;
	preg_match_all( '/' . get_shortcode_regex( array( 'pilgrimz-tour-map' ) ) . '/', $post->post_content, $matches, PREG_SET_ORDER );
	foreach ( $matches as $match ) {
		// "[[shortcode]]" is an escaped literal and renders no map.
		if ( '[' === $match[1] && ']' === $match[6] ) {
			continue;
		}
		$atts = pilgrimz_tour_map_parse_atts( shortcode_parse_atts( $match[3] ) );
		if ( pilgrimz_tour_map_is_preview( $atts['preview'] ) ) {
			$needs_preview = true;
		} else {
			$needs_full = true;
		}
	}

	$script_args = array(
		'strategy'  => 'defer',
		'in_footer' => true,
	);
	if ( $needs_full ) {
		wp_enqueue_script(
			'pilgrimz-tour-map-component',
			'https://mapcomponent.pilgrimz.dev/pilgrimz-tour-map.min.js',
			array(),
			PILGRIMZ_TOUR_MAP_VERSION,
			$script_args
		);
	}
	if ( $needs_preview ) {
		wp_enqueue_script(
			'pilgrimz-tour-map-preview-component',
			'https://mapcomponent.pilgrimz.dev/pilgrimz-tour-map-preview.min.js',
			array(),
			PILGRIMZ_TOUR_MAP_VERSION,
			$script_args
		);
	}
}
add_action( 'wp_enqueue_scripts', 'pilgrimz_tour_map_enqueue_scripts' );

/**
 * Get the WordPress locale and convert it to the language code format.
 * Supports: en, es, fr, etc.
 *
 * @return string Language code (e.g., 'en', 'es', 'fr')
 */
function pilgrimz_tour_map_get_language() {
	$locale = get_locale();
	// Extract the language code (first two characters).
	$language = substr( $locale, 0, 2 );
	return $language;
}

/**
 * Shortcode callback function.
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output for the tour map.
 */
function pilgrimz_tour_map_shortcode( $atts ) {
	// Parse shortcode attributes.
	$atts = pilgrimz_tour_map_parse_atts( $atts );

	// Validate required attributes.
	if ( empty( $atts['tour-id'] ) ) {
		return '<p style="color: red;">' . esc_html__( 'Error: tour-id attribute is required for the Pilgrimz Tour Map shortcode.', 'pilgrimz-tour-map' ) . '</p>';
	}

	// Get hub setting from options if not specified in shortcode.
	if ( empty( $atts['hub'] ) ) {
		$hub_option = get_option( 'pilgrimz_tour_map_hub', '' );
		if ( ! empty( $hub_option ) ) {
			$atts['hub'] = $hub_option;
		}
	}

	// Build style attribute from width and height.
	$style_parts = array();
	if ( ! empty( $atts['width'] ) ) {
		$style_parts[] = 'width: ' . esc_attr( $atts['width'] );
	}
	if ( ! empty( $atts['height'] ) ) {
		$style_parts[] = 'height: ' . esc_attr( $atts['height'] );
	}
	$style_attr = ! empty( $style_parts ) ? ' style="' . implode( '; ', $style_parts ) . '"' : '';

	// Build the web component HTML.
	$tag     = pilgrimz_tour_map_is_preview( $atts['preview'] ) ? 'pilgrimz-tour-map-preview' : 'pilgrimz-tour-map';
	$output  = '<' . $tag;
	$output .= ' tour-id="' . esc_attr( $atts['tour-id'] ) . '"';
	$output .= ' language="' . esc_attr( $atts['language'] ) . '"';

	// Only add hub attribute if it's not empty.
	if ( ! empty( $atts['hub'] ) ) {
		$output .= ' hub="' . esc_attr( $atts['hub'] ) . '"';
	}

	if ( ! empty( $atts['map-type'] ) ) {
		$output .= ' map-type="' . esc_attr( $atts['map-type'] ) . '"';
	}

	// Add style attribute if width or height specified.
	$output .= $style_attr;

	$output .= '></' . $tag . '>';

	return $output;
}
add_shortcode( 'pilgrimz-tour-map', 'pilgrimz_tour_map_shortcode' );

/**
 * Register admin menu and settings page.
 */
function pilgrimz_tour_map_admin_menu() {
	add_options_page(
		__( 'Pilgrimz Tour Map Settings', 'pilgrimz-tour-map' ),
		__( 'Pilgrimz Tour Map', 'pilgrimz-tour-map' ),
		'manage_options',
		'pilgrimz-tour-map',
		'pilgrimz_tour_map_settings_page'
	);
}
add_action( 'admin_menu', 'pilgrimz_tour_map_admin_menu' );

/**
 * Register plugin settings.
 */
function pilgrimz_tour_map_register_settings() {
	register_setting(
		'pilgrimz_tour_map_settings',
		'pilgrimz_tour_map_hub',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		)
	);

	register_setting(
		'pilgrimz_tour_map_settings',
		'pilgrimz_tour_map_preview',
		array(
			'type'              => 'boolean',
			'sanitize_callback' => 'rest_sanitize_boolean',
			'default'           => false,
		)
	);

	add_settings_section(
		'pilgrimz_tour_map_main_section',
		__( 'Default Settings', 'pilgrimz-tour-map' ),
		'pilgrimz_tour_map_section_callback',
		'pilgrimz-tour-map'
	);

	add_settings_field(
		'pilgrimz_tour_map_hub',
		__( 'Hub Code', 'pilgrimz-tour-map' ),
		'pilgrimz_tour_map_hub_field_callback',
		'pilgrimz-tour-map',
		'pilgrimz_tour_map_main_section'
	);

	add_settings_field(
		'pilgrimz_tour_map_preview',
		__( 'Lightweight Preview', 'pilgrimz-tour-map' ),
		'pilgrimz_tour_map_preview_field_callback',
		'pilgrimz-tour-map',
		'pilgrimz_tour_map_main_section'
	);
}
add_action( 'admin_init', 'pilgrimz_tour_map_register_settings' );

/**
 * Settings section callback.
 */
function pilgrimz_tour_map_section_callback() {
	echo '<p>' . esc_html__( 'Configure the defaults for your tour maps. The Pilgrimz team provides the hub code for a personalized look and feel; leave it blank for the default appearance. The lightweight preview loads the full map only on click.', 'pilgrimz-tour-map' ) . '</p>';
}

/**
 * Hub field callback.
 */
function pilgrimz_tour_map_hub_field_callback() {
	$hub = get_option( 'pilgrimz_tour_map_hub', '' );
	echo '<input type="text" id="pilgrimz_tour_map_hub" name="pilgrimz_tour_map_hub" value="' . esc_attr( $hub ) . '" class="regular-text" />';
	echo '<p class="description">' . esc_html__( 'Enter your custom hub code provided by the Pilgrimz team, or leave blank for the default look and feel.', 'pilgrimz-tour-map' ) . '</p>';
}

/**
 * Preview field callback.
 */
function pilgrimz_tour_map_preview_field_callback() {
	$preview = get_option( 'pilgrimz_tour_map_preview', false );
	echo '<label><input type="checkbox" id="pilgrimz_tour_map_preview" name="pilgrimz_tour_map_preview" value="1"' . checked( (bool) $preview, true, false ) . ' /> ' . esc_html__( 'Load the map on click (lightweight preview)', 'pilgrimz-tour-map' ) . '</label>';
	echo '<p class="description">' . esc_html__( 'Shows a static image and loads the full map only when a visitor clicks it. Use preview="false" in a shortcode to override.', 'pilgrimz-tour-map' ) . '</p>';
}

/**
 * Settings page HTML.
 */
function pilgrimz_tour_map_settings_page() {
	// Check user capabilities.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Display success message if settings were saved.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Settings page check only.
	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error(
			'pilgrimz_tour_map_messages',
			'pilgrimz_tour_map_message',
			__( 'Settings Saved', 'pilgrimz-tour-map' ),
			'updated'
		);
	}

	// Display errors/messages.
	settings_errors( 'pilgrimz_tour_map_messages' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<div class="card" style="max-width: 800px; margin-top: 20px;">
			<h2><?php esc_html_e( 'How to Use the Shortcode', 'pilgrimz-tour-map' ); ?></h2>
			<p><?php esc_html_e( 'To add a Pilgrimz tour map to your posts or pages, use the following shortcode:', 'pilgrimz-tour-map' ); ?></p>
			<p><code>[pilgrimz-tour-map tour-id="123"]</code></p>

			<h3><?php esc_html_e( 'Shortcode Parameters', 'pilgrimz-tour-map' ); ?></h3>
			<ul>
				<li><strong>tour-id</strong> (required): <?php esc_html_e( 'The ID of the tour you want to display.', 'pilgrimz-tour-map' ); ?></li>
				<li><strong>language</strong> (optional): <?php esc_html_e( 'The language code (en, es, fr, etc.). Defaults to your WordPress site language.', 'pilgrimz-tour-map' ); ?></li>
				<li><strong>width</strong> (optional): <?php esc_html_e( 'Map width (e.g., "100%", "800px"). The map will adapt to its container size.', 'pilgrimz-tour-map' ); ?></li>
				<li><strong>height</strong> (optional): <?php esc_html_e( 'Map height (e.g., "600px", "80vh"). The map has a minimum height of 300px when no height is given.', 'pilgrimz-tour-map' ); ?></li>
				<li><strong>map-type</strong> (optional): <?php esc_html_e( 'Base map style: "standard", "satellite" or "light".', 'pilgrimz-tour-map' ); ?></li>
				<li><strong>preview</strong> (optional): <?php esc_html_e( 'Set to "true" to show a static image and load the full map only on click. Overrides the site-wide setting.', 'pilgrimz-tour-map' ); ?></li>
			</ul>
			<p><em><?php esc_html_e( 'Note: The map automatically adapts to the size of its container and is fully responsive.', 'pilgrimz-tour-map' ); ?></em></p>

			<h3><?php esc_html_e( 'Examples', 'pilgrimz-tour-map' ); ?></h3>
			<p><code>[pilgrimz-tour-map tour-id="123"]</code> - <?php esc_html_e( 'Basic usage with auto-detected language', 'pilgrimz-tour-map' ); ?></p>
			<p><code>[pilgrimz-tour-map tour-id="123" language="es"]</code> - <?php esc_html_e( 'Spanish language tour', 'pilgrimz-tour-map' ); ?></p>
			<p><code>[pilgrimz-tour-map tour-id="123" width="100%" height="500px"]</code> - <?php esc_html_e( 'Custom map dimensions', 'pilgrimz-tour-map' ); ?></p>
			<p><code>[pilgrimz-tour-map tour-id="123" preview="true"]</code> - <?php esc_html_e( 'Lightweight preview, the map loads on click', 'pilgrimz-tour-map' ); ?></p>
		</div>

		<form action="options.php" method="post" style="margin-top: 30px;">
			<?php
			settings_fields( 'pilgrimz_tour_map_settings' );
			do_settings_sections( 'pilgrimz-tour-map' );
			submit_button( __( 'Save Settings', 'pilgrimz-tour-map' ) );
			?>
		</form>
	</div>
	<?php
}

/**
 * Load plugin textdomain for translations.
 */
function pilgrimz_tour_map_load_textdomain() {
	load_plugin_textdomain(
		'pilgrimz-tour-map',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'plugins_loaded', 'pilgrimz_tour_map_load_textdomain' );
