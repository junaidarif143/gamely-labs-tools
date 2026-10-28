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
