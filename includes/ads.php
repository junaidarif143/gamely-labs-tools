<?php
/**
 * Google AdSense: site verification tag, the AdSense script (Auto ads) and /ads.txt.
 *
 * The theme's Customizer field "AdSense code for <head>" still works; if it already contains
 * this publisher ID, nothing is printed twice.
 */

defined( 'ABSPATH' ) || exit;

define( 'GL_TOOLS_ADSENSE_PUB', 'pub-3391763778564662' );

/**
 * Which ad network runs the site: 'adsense' (default) or 'ezoic'.
 * Switch with the option gl_ad_provider; no code change needed to go back.
 */
function gl_tools_ad_provider() {
	return 'ezoic' === get_option( 'gl_ad_provider', 'adsense' ) ? 'ezoic' : 'adsense';
}

/** Ezoic ads.txt manager (Ezoic's shared ID 19390 + our domain). */
define( 'GL_TOOLS_EZOIC_ADSTXT', 'https://srv.adstxtmanager.com/19390/gamelylabs.com' );

/** Ezoic privacy (consent) scripts and header script, first thing in <head>. */
add_action( 'wp_head', 'gl_tools_ezoic_head', 0 );
function gl_tools_ezoic_head() {
	if ( 'ezoic' !== gl_tools_ad_provider() || is_admin() || is_preview() || is_customize_preview() ) {
		return;
	}
	// phpcs:disable WordPress.WP.EnqueuedResources
	echo '<script data-cfasync="false" src="https://cmp.gatekeeperconsent.com/min.js"></script>' . "\n";
	echo '<script data-cfasync="false" src="https://the.gatekeeperconsent.com/cmp.min.js"></script>' . "\n";
	echo '<script async src="//www.ezojs.com/ezoic/sa.min.js"></script>' . "\n";
	echo '<script>window.ezstandalone = window.ezstandalone || {}; ezstandalone.cmd = ezstandalone.cmd || [];</script>' . "\n";
	echo '<script src="//ezoicanalytics.com/analytics.js"></script>' . "\n";
	// phpcs:enable
}

/** ads.txt, served at the site root. */
add_action( 'init', 'gl_tools_ads_txt', 0 );
function gl_tools_ads_txt() {
	$path = wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	if ( '/ads.txt' !== $path ) {
		return;
	}
	if ( 'ezoic' === gl_tools_ad_provider() ) {
		wp_redirect( GL_TOOLS_EZOIC_ADSTXT, 301 ); // phpcs:ignore WordPress.Security.SafeRedirect
		exit;
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
	if ( 'ezoic' === gl_tools_ad_provider() ) {
		return; // Ezoic serves Google demand itself; AdSense Auto ads would clash.
	}
	$theme_code = (string) get_theme_mod( 'gamely_ads_head', '' );
	if ( false === strpos( $theme_code, GL_TOOLS_ADSENSE_PUB ) ) {
		echo '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . esc_attr( $client ) . '" crossorigin="anonymous"></script>' . "\n"; // phpcs:ignore WordPress.WP.EnqueuedResources
	}
}
