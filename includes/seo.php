<?php
/**
 * Keyword clusters: one search intent per page (see SEO-CLUSTERS.md).
 * Each tool page gets an SEO title built around its cluster's main question,
 * and a list of cluster neighbours used for "Related tools".
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * key => seo title (without the site name) and related tool keys, closest first.
 */
function gl_tools_seo_map() {
	return array(
		'sprinkler'  => array( 'title' => 'Sprinkler Run Time Calculator: How Long to Water Your Lawn', 'related' => array( 'grass_seed', 'sod', 'fertilizer' ) ),
		'fertilizer' => array( 'title' => 'Lawn Fertilizer Calculator: How Much per 1,000 sq ft', 'related' => array( 'lime', 'grass_seed', 'sprinkler' ) ),
		'lime'       => array( 'title' => 'Lawn Lime Calculator: How Much Lime per 1,000 sq ft', 'related' => array( 'fertilizer', 'compost', 'grass_seed' ) ),
		'sfg'        => array( 'title' => 'Square Foot Garden Planner: Plants per Square & Layout', 'related' => array( 'raised_bed', 'compost', 'mulch' ) ),
		'raised_bed' => array( 'title' => 'Raised Bed Soil Calculator: How Much Soil Do I Need?', 'related' => array( 'sfg', 'compost', 'topsoil' ) ),
		'sod'        => array( 'title' => 'Sod Calculator: How Many Pallets of Sod Do I Need?', 'related' => array( 'topsoil', 'sprinkler', 'grass_seed' ) ),
		'grass_seed' => array( 'title' => 'Grass Seed Calculator: Pounds per 1,000 sq ft & per Acre', 'related' => array( 'sprinkler', 'topsoil', 'fertilizer' ) ),
		'compost'    => array( 'title' => 'Compost Calculator: How Much Compost Do I Need?', 'related' => array( 'raised_bed', 'topsoil', 'mulch' ) ),
		'mulch'      => array( 'title' => 'Mulch Calculator: How Much Mulch Do I Need? (Bags & Yards)', 'related' => array( 'compost', 'raised_bed', 'gravel' ) ),
		'gravel'     => array( 'title' => 'Gravel Calculator: How Much Gravel Do I Need? (Tons & Yards)', 'related' => array( 'paver_base', 'topsoil', 'mulch' ) ),
		'topsoil'    => array( 'title' => 'Topsoil Calculator: How Much Topsoil Do I Need?', 'related' => array( 'sod', 'grass_seed', 'compost' ) ),
		'paver_base' => array( 'title' => 'Paver Base Calculator: Gravel, Sand & Dig Depth', 'related' => array( 'gravel', 'topsoil', 'mulch' ) ),
	);
}

/**
 * SEO titles for guides whose on-page headline is not the query they should own.
 * slug => title. Keeps a guide from competing with its calculator for "how much" searches.
 */
function gl_tools_guide_seo_titles() {
	return array(
		'raised-bed-soil-mixes' => 'Best Soil Mix for Raised Beds: 3 Options Compared',
	);
}

/** Related tools for a tool key, in cluster order (used by the Gamely theme). */
function gl_tools_related( $key, $limit = 3 ) {
	$map     = gl_tools_seo_map();
	$catalog = gl_tools_catalog();
	$out     = array();
	if ( isset( $map[ $key ] ) ) {
		foreach ( $map[ $key ]['related'] as $k ) {
			if ( isset( $catalog[ $k ] ) ) {
				$out[ $k ] = $catalog[ $k ];
			}
		}
	}
	return array_slice( $out, 0, $limit, true );
}

/** Title tag: replace the page-title part on tool pages and mapped guides; the site name stays. */
add_filter( 'document_title_parts', 'gl_tools_document_title', 20 );
function gl_tools_document_title( $parts ) {
	if ( is_admin() || ! is_singular() ) {
		return $parts;
	}
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	if ( is_page() ) {
		$map = gl_tools_seo_map();
		foreach ( gl_tools_catalog() as $key => $tool ) {
			if ( $tool['slug'] === $slug && isset( $map[ $key ] ) ) {
				$parts['title'] = $map[ $key ]['title'];
				return $parts;
			}
		}
	} elseif ( is_singular( 'post' ) ) {
		$guides = gl_tools_guide_seo_titles();
		if ( isset( $guides[ $slug ] ) ) {
			$parts['title'] = $guides[ $slug ];
		}
	}
	return $parts;
}

/* ---------------------------------------------------------------
 * Author pages: keep them out of the sitemap and send visitors to About.
 * The default author archive is a thin duplicate and its URL shows the login name.
 * ------------------------------------------------------------- */
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}, 10, 2 );

add_action( 'template_redirect', function () {
	if ( is_author() ) {
		$about = get_page_by_path( 'about' );
		wp_safe_redirect( $about ? get_permalink( $about ) : home_url( '/' ), 301 );
		exit;
	}
} );

// Hide user records from the public REST API listing (they expose login slugs).
add_filter( 'rest_endpoints', function ( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
} );

/* ---------------------------------------------------------------
 * Structured data: Organization + WebSite on the homepage, WebApplication on calculators.
 * ------------------------------------------------------------- */
add_action( 'wp_head', 'gl_tools_site_schema', 4 );
function gl_tools_site_schema() {
	$org = array(
		'@type'  => 'Organization',
		'@id'    => home_url( '/#organization' ),
		'name'   => get_bloginfo( 'name' ),
		'url'    => home_url( '/' ),
		'email'  => GL_TOOLS_EMAIL,
		'parentOrganization' => array( '@type' => 'Organization', 'name' => GL_TOOLS_OWNER ),
		'founder' => array_values( array_map( function ( $p ) {
			return array( '@type' => 'Person', 'name' => $p['name'], 'jobTitle' => $p['role'], 'image' => $p['photo'] );
		}, gl_tools_team() ) ),
	);
	if ( is_front_page() ) {
		$data = array(
			'@context' => 'https://schema.org',
			'@graph'   => array(
				$org,
				array(
					'@type'     => 'WebSite',
					'@id'       => home_url( '/#website' ),
					'name'      => get_bloginfo( 'name' ),
					'url'       => home_url( '/' ),
					'publisher' => array( '@id' => home_url( '/#organization' ) ),
				),
			),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>' . "\n";
		return;
	}
	if ( ! is_page() ) {
		return;
	}
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	foreach ( gl_tools_catalog() as $key => $tool ) {
		if ( $tool['slug'] !== $slug ) {
			continue;
		}
		$data = array(
			'@context'            => 'https://schema.org',
			'@type'               => 'WebApplication',
			'name'                => $tool['title'],
			'url'                 => get_permalink( get_queried_object_id() ),
			'description'         => get_the_excerpt( get_queried_object_id() ),
			'applicationCategory' => 'UtilitiesApplication',
			'operatingSystem'     => 'Any (runs in the browser)',
			'browserRequirements' => 'Requires JavaScript',
			'isAccessibleForFree' => true,
			'offers'              => array( '@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD' ),
			'publisher'           => array( '@type' => 'Organization', 'name' => get_bloginfo( 'name' ), 'url' => home_url( '/' ) ),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>' . "\n";
		return;
	}
}

/* ---------------------------------------------------------------
 * Site time zone: US Pacific (the owners' working clock), set once.
 * ------------------------------------------------------------- */
add_action( 'init', function () {
	if ( get_option( 'gl_tools_tz_set' ) ) {
		return;
	}
	if ( '' === (string) get_option( 'timezone_string' ) && 0.0 === (float) get_option( 'gmt_offset' ) ) {
		update_option( 'timezone_string', 'America/Los_Angeles' );
	}
	update_option( 'gl_tools_tz_set', 1 );
}, 5 );
