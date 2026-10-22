<?php
/**
 * Plugin Name:       Gamely Labs Tools
 * Description:       Lawn, garden and yard calculators for Gamely Labs. Each tool is a shortcode; on activation or upgrade the plugin creates the tool pages and the homepage.
 * Version:           1.5.1
 * Update URI:        false
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Gamely Labs
 * License:           GPL-2.0-or-later
 * Text Domain:       gamely-tools
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GL_TOOLS_VERSION', '1.5.1' );
define( 'GL_TOOLS_AUTHOR', 'Junaid Arif' );
define( 'GL_TOOLS_OWNER', 'AJ Tech Studio' );
define( 'GL_TOOLS_EMAIL', 'info@ajtechstudio.com' );
define( 'GL_TOOLS_FILE', __FILE__ );
define( 'GL_TOOLS_URL', plugin_dir_url( __FILE__ ) );
define( 'GL_TOOLS_DIR', plugin_dir_path( __FILE__ ) );

require_once GL_TOOLS_DIR . 'includes/pages.php';
require_once GL_TOOLS_DIR . 'includes/posts.php';

/**
 * Public catalog of tools, used by the Gamely theme for menus, cards and related tools.
 * icon = a name the theme knows; tint/ink = the tool's soft background and icon colour.
 */
function gl_tools_catalog() {
	return array(
		'sprinkler'  => array( 'title' => 'Sprinkler Run Time Calculator', 'short' => 'Sprinkler Run Time', 'slug' => 'sprinkler-run-time-calculator', 'cat' => 'lawn', 'icon' => 'drop', 'tint' => '#DCE8F0', 'ink' => '#2E5E7E', 'blurb' => 'Minutes per zone, cycle-and-soak and gallons used, from your weekly target and rainfall.', 'cta' => 'Open calculator' ),
		'fertilizer' => array( 'title' => 'Lawn Fertilizer Calculator', 'short' => 'Lawn Fertilizer', 'slug' => 'lawn-fertilizer-calculator', 'cat' => 'lawn', 'icon' => 'sack', 'tint' => '#E5EDD6', 'ink' => '#4E6E30', 'blurb' => 'Pounds and bags for any N-P-K on the shelf, plus a yearly feeding plan for your grass.', 'cta' => 'Open calculator' ),
		'lime'       => array( 'title' => 'Lawn Lime Calculator', 'short' => 'Lawn Lime', 'slug' => 'lawn-lime-calculator', 'cat' => 'lawn', 'icon' => 'flask', 'tint' => '#F1E4D3', 'ink' => '#8A5E3B', 'blurb' => 'Turn your soil test result into bags and a safe, split application schedule.', 'cta' => 'Open calculator' ),
		'sfg'        => array( 'title' => 'Square Foot Garden Planner', 'short' => 'Square Foot Garden Planner', 'slug' => 'square-foot-garden-planner', 'cat' => 'garden', 'icon' => 'grid', 'tint' => '#E5EDD6', 'ink' => '#4E6E30', 'blurb' => 'Click to plant your beds and get plant counts, a shopping list and the soil mix.', 'cta' => 'Open planner' ),
		'raised_bed' => array( 'title' => 'Raised Bed Soil Calculator', 'short' => 'Raised Bed Soil', 'slug' => 'raised-bed-soil-calculator', 'cat' => 'garden', 'icon' => 'bed', 'tint' => '#F1E4D3', 'ink' => '#8A5E3B', 'blurb' => 'Soil for several beds at once, any recipe, with bagged vs bulk cost compared.', 'cta' => 'Open calculator' ),
		// Newer tools carry their own icon paths ('svg'), so the theme never needs updating for a new tool.
		'sod'        => array( 'title' => 'Sod Calculator', 'short' => 'Sod', 'slug' => 'sod-calculator', 'cat' => 'lawn', 'icon' => 'sod', 'tint' => '#E5EDD6', 'ink' => '#4E6E30', 'blurb' => 'Pallets and pieces for slabs or rolls, with extra for cuts and an exact partial-pallet option.', 'cta' => 'Open calculator', 'svg' => '<path d="M3 15c3-1 6-1 9 0s6 1 9 0"/><path d="M3 19c3-1 6-1 9 0s6 1 9 0"/><path d="M6 13V9M9 12V7M12 13V8M15 12V6M18 13V9"/>' ),
		'grass_seed' => array( 'title' => 'Grass Seed Calculator', 'short' => 'Grass Seed', 'slug' => 'grass-seed-calculator', 'cat' => 'lawn', 'icon' => 'seed', 'tint' => '#F6ECCB', 'ink' => '#7A5A12', 'blurb' => 'Pounds of seed by grass type for a new lawn or overseeding, with bags and the best time to sow.', 'cta' => 'Open calculator', 'svg' => '<path d="M12 21c0-6 0-9-4-13"/><path d="M12 21c0-5 1-8 5-11"/><ellipse cx="7" cy="6" rx="2.2" ry="3.2" transform="rotate(-30 7 6)"/><ellipse cx="18" cy="8" rx="2.2" ry="3.2" transform="rotate(35 18 8)"/>' ),
		'compost'    => array( 'title' => 'Compost Calculator', 'short' => 'Compost', 'slug' => 'compost-calculator', 'cat' => 'garden', 'icon' => 'compost', 'tint' => '#EADFCF', 'ink' => '#6B4A2F', 'blurb' => 'Cubic feet, yards and bags for new beds, top-ups and lawn topdressing, at extension-recommended depths.', 'cta' => 'Open calculator', 'svg' => '<path d="M4 9h16l-1.5 11h-13z"/><path d="M8 9V6a4 4 0 0 1 8 0v3"/><path d="M9 14l2 2 4-4"/>' ),
		'mulch'      => array( 'title' => 'Mulch Calculator', 'short' => 'Mulch', 'slug' => 'mulch-calculator', 'cat' => 'garden', 'icon' => 'mulch', 'tint' => '#F1E4D3', 'ink' => '#8A5E3B', 'blurb' => 'Bags or yards for every bed and tree ring at once, with top-up depth and bagged vs bulk cost.', 'cta' => 'Open calculator', 'svg' => '<path d="M3 19h18"/><path d="M5 19c1-4 3-6 7-6s6 2 7 6"/><path d="M9 13l1-2M13 13l1-3M16 14l1.5-1.5"/>' ),
	);
}

/**
 * Tools announced as coming soon (shown by the theme).
 */
function gl_tools_coming_soon() {
	return array( 'Gravel & stone', 'Topsoil for leveling', 'Paver base', 'Garden planting calendar' );
}

/**
 * Registry of tools: shortcode => script handle + file.
 */
function gl_tools_registry() {
	return array(
		'gl_sprinkler'   => array( 'script' => 'gl-sprinkler', 'file' => 'assets/sprinkler.js' ),
		'gl_sfg_planner' => array( 'script' => 'gl-sfg', 'file' => 'assets/sfg.js' ),
		'gl_raised_bed'  => array( 'script' => 'gl-raised-bed', 'file' => 'assets/raised-bed.js' ),
		'gl_fertilizer'  => array( 'script' => 'gl-fertilizer', 'file' => 'assets/fertilizer.js' ),
		'gl_lime'        => array( 'script' => 'gl-lime', 'file' => 'assets/lime.js' ),
		'gl_mulch'       => array( 'script' => 'gl-mulch', 'file' => 'assets/mulch.js', 'deps' => array( 'gl-common' ) ),
		'gl_compost'     => array( 'script' => 'gl-compost', 'file' => 'assets/compost.js', 'deps' => array( 'gl-common' ) ),
		'gl_sod'         => array( 'script' => 'gl-sod', 'file' => 'assets/sod.js', 'deps' => array( 'gl-common' ) ),
		'gl_grass_seed'  => array( 'script' => 'gl-grass-seed', 'file' => 'assets/grass-seed.js', 'deps' => array( 'gl-common' ) ),
	);
}

add_action( 'wp_enqueue_scripts', 'gl_tools_register_assets' );
function gl_tools_register_assets() {
	wp_register_style( 'gl-tools', GL_TOOLS_URL . 'assets/gl-tools.css', array(), GL_TOOLS_VERSION );
	wp_register_script( 'gl-common', GL_TOOLS_URL . 'assets/gl-common.js', array(), GL_TOOLS_VERSION, true );
	foreach ( gl_tools_registry() as $tool ) {
		wp_register_script( $tool['script'], GL_TOOLS_URL . $tool['file'], isset( $tool['deps'] ) ? $tool['deps'] : array(), GL_TOOLS_VERSION, true );
	}
	// The homepage and tool pages use the shared styles for cards and layout.
	if ( is_front_page() || is_page() ) {
		wp_enqueue_style( 'gl-tools' );
	}
}

/**
 * Shortcode renderer: an empty mount point the tool's script fills in.
 * The noscript text keeps the page useful (and crawlable) without JavaScript.
 */
function gl_tools_render( $tag ) {
	$reg = gl_tools_registry();
	if ( ! isset( $reg[ $tag ] ) ) {
		return '';
	}
	wp_enqueue_style( 'gl-tools' );
	wp_enqueue_script( $reg[ $tag ]['script'] );
	$id = esc_attr( str_replace( '_', '-', $tag ) );
	return '<div class="gl-tool" id="' . $id . '" data-gl-tool="' . esc_attr( $tag ) . '">'
		. '<noscript><p class="gl-note">This calculator needs JavaScript. Please enable it in your browser to use the tool. The guide below explains the method so you can also work it out by hand.</p></noscript>'
		. '</div>';
}

add_action( 'init', 'gl_tools_register_shortcodes' );
function gl_tools_register_shortcodes() {
	foreach ( array_keys( gl_tools_registry() ) as $tag ) {
		add_shortcode( $tag, function () use ( $tag ) {
			return gl_tools_render( $tag );
		} );
	}
}

/**
 * Content hashes of pages exactly as earlier plugin versions created them.
 * A page whose content still matches one of these has not been edited, so an upgrade may refresh it.
 */
function gl_tools_legacy_hashes() {
	return array(
		'home' => array( '7e6c05d12a54a95820cd6e8493521b43' ), // v1.0.0
	);
}

function gl_tools_wrap( $content ) {
	// One HTML block keeps the markup exactly as written (no auto-paragraphs); shortcodes still run.
	return "<!-- wp:html -->\n" . $content . "\n<!-- /wp:html -->";
}

/**
 * Create missing pages and refresh unedited ones. Safe to run repeatedly.
 */
function gl_tools_sync_pages() {
	$page_ids = get_option( 'gl_tools_pages', array() );
	$hashes   = get_option( 'gl_tools_hashes', array() );
	$legacy   = gl_tools_legacy_hashes();

	foreach ( gl_tools_page_definitions() as $key => $def ) {
		$content  = gl_tools_wrap( $def['content'] );
		$existing = isset( $page_ids[ $key ] ) ? get_post( $page_ids[ $key ] ) : null;
		if ( ! $existing || 'trash' === $existing->post_status ) {
			$existing = get_page_by_path( $def['slug'] );
		}

		// WordPress ships an unpublished "Privacy Policy" draft with template text; replace it with ours.
		if ( $existing && 'privacy' === $key && 'draft' === $existing->post_status && false !== strpos( $existing->post_content, 'Suggested text:' ) ) {
			wp_update_post( array( 'ID' => $existing->ID, 'post_content' => $content, 'post_title' => $def['title'], 'post_excerpt' => $def['excerpt'], 'post_status' => 'publish', 'comment_status' => 'closed' ) );
			$page_ids[ $key ] = $existing->ID;
			$hashes[ $key ]   = md5( get_post( $existing->ID )->post_content );
			continue;
		}

		if ( $existing ) {
			$page_ids[ $key ] = $existing->ID;
			$current_hash     = md5( $existing->post_content );
			$known            = array_merge( isset( $legacy[ $key ] ) ? $legacy[ $key ] : array(), isset( $hashes[ $key ] ) ? array( $hashes[ $key ] ) : array() );
			$new_hash         = md5( $content );
			// Only overwrite content we wrote ourselves and nobody has edited since.
			if ( $current_hash !== $new_hash && in_array( $current_hash, $known, true ) ) {
				wp_update_post( array( 'ID' => $existing->ID, 'post_content' => $content ) );
				$hashes[ $key ] = md5( get_post( $existing->ID )->post_content );
			}
			continue;
		}

		$id = wp_insert_post(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_title'     => $def['title'],
				'post_name'      => $def['slug'],
				'post_content'   => $content,
				'post_excerpt'   => $def['excerpt'],
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'menu_order'     => isset( $def['order'] ) ? $def['order'] : 0,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			$page_ids[ $key ] = $id;
			$hashes[ $key ]   = md5( get_post( $id )->post_content );
		}
	}
	update_option( 'gl_tools_pages', $page_ids );
	update_option( 'gl_tools_hashes', $hashes );

	// Point WordPress's own privacy-page setting at our policy.
	if ( ! empty( $page_ids['privacy'] ) && 'publish' === get_post_status( $page_ids['privacy'] ) ) {
		update_option( 'wp_page_for_privacy_policy', (int) $page_ids['privacy'] );
	}
	// Author name for tool-page bylines in the Gamely theme, unless one was already set.
	gl_tools_set_author();
	return $page_ids;
}

/**
 * Author name for tool-page bylines in the Gamely theme (any folder name), unless one is already set.
 */
function gl_tools_set_author() {
	if ( 0 === strpos( get_stylesheet(), 'gamely' ) && '' === (string) get_theme_mod( 'gamely_author', '' ) ) {
		set_theme_mod( 'gamely_author', GL_TOOLS_AUTHOR );
	}
}
add_action( 'after_switch_theme', 'gl_tools_set_author', 20 );

/**
 * Activation: set the site identity, create pages, set the homepage, remove sample content.
 */
register_activation_hook( __FILE__, 'gl_tools_activate' );
function gl_tools_activate() {
	if ( ! get_option( 'gl_tools_identity_set' ) ) {
		update_option( 'blogname', 'Gamely Labs' );
		update_option( 'blogdescription', 'Lawn, garden & yard calculators for homeowners' );
		update_option( 'permalink_structure', '/%postname%/' );
		update_option( 'gl_tools_identity_set', 1 );
	}

	$page_ids = gl_tools_sync_pages();
	gl_tools_sync_posts();

	// Use the tools hub as the homepage, unless a static front page is already chosen.
	if ( 'page' !== get_option( 'show_on_front' ) && ! empty( $page_ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', (int) $page_ids['home'] );
	}

	// Remove WordPress sample content, only if it is still unedited.
	if ( ! get_option( 'gl_tools_cleaned' ) ) {
		$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
		if ( $hello && false !== strpos( $hello->post_content, 'Welcome to WordPress' ) ) {
			wp_trash_post( $hello->ID );
		}
		$sample = get_page_by_path( 'sample-page' );
		if ( $sample && false !== strpos( $sample->post_content, 'This is an example page' ) ) {
			wp_trash_post( $sample->ID );
		}
		update_option( 'gl_tools_cleaned', 1 );
	}

	update_option( 'gl_tools_version', GL_TOOLS_VERSION );
	flush_rewrite_rules();
}

/**
 * Upgrades: replacing the plugin with a newer zip does not re-run activation,
 * so sync pages once whenever the stored version is older than this one.
 */
add_action( 'init', 'gl_tools_maybe_upgrade', 20 );
function gl_tools_maybe_upgrade() {
	if ( version_compare( (string) get_option( 'gl_tools_version', '1.0.0' ), GL_TOOLS_VERSION, '>=' ) ) {
		return;
	}
	update_option( 'gl_tools_version', GL_TOOLS_VERSION ); // set first so a failure never loops
	gl_tools_sync_pages();
	gl_tools_sync_posts();
	flush_rewrite_rules();
}

/**
 * FAQ structured data for tool pages that define FAQs (the same FAQs are shown on the page).
 */
add_action( 'wp_head', 'gl_tools_faq_schema' );
function gl_tools_faq_schema() {
	if ( ! is_page() ) {
		return;
	}
	$post = get_queried_object();
	if ( ! $post ) {
		return;
	}
	foreach ( gl_tools_page_definitions() as $def ) {
		if ( $def['slug'] === $post->post_name && ! empty( $def['faq'] ) ) {
			$items = array();
			foreach ( $def['faq'] as $q => $a ) {
				$items[] = array(
					'@type'          => 'Question',
					'name'           => $q,
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $a,
					),
				);
			}
			$data = array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $items,
			);
			echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>' . "\n";
			return;
		}
	}
}
