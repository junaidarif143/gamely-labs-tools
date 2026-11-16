<?php
/**
 * Google AdSense: site verification tag, the AdSense script (Auto ads) and /ads.txt.
 *
 * The theme's Customizer field "AdSense code for <head>" still works; if it already contains
 * this publisher ID, nothing is printed twice.
 */

defined( 'ABSPATH' ) || exit;

define( 'GL_TOOLS_ADSENSE_PUB', 'pub-3391763778564662' );

/** ads.txt, served at the site root. */
add_action( 'init', 'gl_tools_ads_txt', 0 );
function gl_tools_ads_txt() {
	$path = wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	if ( '/ads.txt' !== $path ) {
		return;
	}
	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'Cache-Control: public, max-age=3600' );
	echo 'google.com, ' . GL_TOOLS_ADSENSE_PUB . ", DIRECT, f08c47fec0942fa0\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	exit;
}

/** Verification meta tag and the AdSense script in <head>. */
add_action( 'wp_head', 'gl_tools_adsense_head', 1 );
function gl_tools_adsense_head() {
	if ( is_admin() || is_preview() || is_customize_preview() ) {
		return;
	}
	$client = 'ca-' . GL_TOOLS_ADSENSE_PUB;
	echo '<meta name="google-adsense-account" content="' . esc_attr( $client ) . '">' . "\n";
	$theme_code = (string) get_theme_mod( 'gamely_ads_head', '' );
	if ( false === strpos( $theme_code, GL_TOOLS_ADSENSE_PUB ) ) {
		echo '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . esc_attr( $client ) . '" crossorigin="anonymous"></script>' . "\n"; // phpcs:ignore WordPress.WP.EnqueuedResources
	}
}
