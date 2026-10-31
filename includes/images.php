<?php
/**
 * Illustrations: original diagrams bundled in assets/img/ (one per tool page and guide).
 *
 * On first run (and whenever the image set changes) each file is added to the Media Library,
 * attached to its page or guide, set as the featured image if none is set, and shown in the
 * content as a figure with alt text and a caption.
 */

defined( 'ABSPATH' ) || exit;

/** slug => array( title, alt, caption ), from assets/img/meta.json. */
function gl_tools_image_meta() {
	static $meta = null;
	if ( null === $meta ) {
		$file = GL_TOOLS_DIR . 'assets/img/meta.json';
		$meta = file_exists( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : array();
		$meta = is_array( $meta ) ? $meta : array();
	}
	return $meta;
}

/** Changes when the plugin version or the image set (meta.json) changes; file changes are caught per image. */
function gl_tools_image_set_version() {
	$file = GL_TOOLS_DIR . 'assets/img/meta.json';
	return GL_TOOLS_VERSION . ':' . ( file_exists( $file ) ? md5_file( $file ) : '' );
}

add_action( 'init', 'gl_tools_maybe_import_images', 30 );
function gl_tools_maybe_import_images() {
	$ver = gl_tools_image_set_version();
	if ( get_option( 'gl_tools_img_ver' ) === $ver || get_transient( 'gl_tools_img_lock' ) ) {
		return;
	}
	set_transient( 'gl_tools_img_lock', 1, 5 * MINUTE_IN_SECONDS );
	if ( gl_tools_import_images() ) {
		update_option( 'gl_tools_img_ver', $ver );
	}
	delete_transient( 'gl_tools_img_lock' );
}

/** Find the page or guide an image belongs to. */
function gl_tools_image_target( $slug ) {
	$p = get_page_by_path( $slug, OBJECT, 'page' );
	if ( ! $p ) {
		$p = get_page_by_path( $slug, OBJECT, 'post' );
	}
	return $p;
}

/**
 * Import or refresh every bundled image. Returns false if anything failed (it is retried later).
 */
function gl_tools_import_images() {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';

	$ids    = (array) get_option( 'gl_tools_img_ids', array() );
	$hashes = (array) get_option( 'gl_tools_img_hashes', array() );
	$ok     = true;

	foreach ( gl_tools_image_meta() as $slug => $m ) {
		$src = GL_TOOLS_DIR . 'assets/img/' . $slug . '.webp';
		if ( ! file_exists( $src ) ) {
			continue;
		}
		$target = gl_tools_image_target( $slug );
		if ( ! $target ) {
			continue; // e.g. a guide that is not created yet; tried again when the set changes
		}
		$hash  = md5_file( $src );
		$id    = isset( $ids[ $slug ] ) ? (int) $ids[ $slug ] : 0;
		$valid = $id && get_post( $id ) && 'attachment' === get_post_type( $id );

		if ( ! $valid || ( $hashes[ $slug ] ?? '' ) !== $hash ) {
			$upload = wp_upload_bits( $slug . '.webp', null, (string) file_get_contents( $src ) );
			if ( ! empty( $upload['error'] ) ) {
				$ok = false;
				continue;
			}
			$att = array(
				'post_mime_type' => 'image/webp',
				'post_title'     => $m['title'],
				'post_excerpt'   => $m['caption'],
				'post_content'   => '',
				'post_status'    => 'inherit',
				'post_author'    => (int) $target->post_author,
			);
			if ( $valid ) {
				// Replace the file behind the existing attachment so its ID (and featured image) stay.
				$old = get_attached_file( $id );
				update_attached_file( $id, $upload['file'] );
				wp_update_post( array_merge( $att, array( 'ID' => $id ) ) );
				if ( $old && $old !== $upload['file'] && file_exists( $old ) ) {
					wp_delete_file( $old );
				}
			} else {
				$id = wp_insert_attachment( $att, $upload['file'], $target->ID, true );
				if ( is_wp_error( $id ) ) {
					$ok = false;
					continue;
				}
			}
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
			$ids[ $slug ]    = $id;
			$hashes[ $slug ] = $hash;
		} else {
			wp_update_post( array( 'ID' => $id, 'post_title' => $m['title'], 'post_excerpt' => $m['caption'] ) );
		}
		update_post_meta( $id, '_wp_attachment_image_alt', $m['alt'] );

		$thumb = (int) get_post_thumbnail_id( $target->ID );
		if ( ! $thumb || ! get_post( $thumb ) ) {
			set_post_thumbnail( $target->ID, $id );
		}
	}

	update_option( 'gl_tools_img_ids', $ids, false );
	update_option( 'gl_tools_img_hashes', $hashes, false );
	return $ok;
}

/** The illustration attachment for a page or guide, if any. */
function gl_tools_image_for( $post ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return 0;
	}
	$ids = (array) get_option( 'gl_tools_img_ids', array() );
	return isset( $ids[ $post->post_name ] ) ? (int) $ids[ $post->post_name ] : 0;
}

/**
 * Show the illustration in the article: on tool pages after "How to use" (before the
 * second section), on guides before the first section.
 */
add_filter( 'the_content', 'gl_tools_insert_figure', 20 );
function gl_tools_insert_figure( $content ) {
	if ( ! is_singular( array( 'page', 'post' ) ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$post = get_post();
	$id   = gl_tools_image_for( $post );
	$meta = gl_tools_image_meta();
	if ( ! $id || ! isset( $meta[ $post->post_name ] ) || false !== strpos( $content, 'g-figure' ) ) {
		return $content;
	}
	$m   = $meta[ $post->post_name ];
	$img = wp_get_attachment_image(
		$id,
		'large',
		false,
		array(
			'alt'     => $m['alt'],
			'loading' => 'lazy',
			'sizes'   => '(max-width: 900px) 100vw, 760px',
		)
	);
	if ( ! $img ) {
		return $content;
	}
	$figure = '<figure class="g-figure">' . $img . '<figcaption>' . esc_html( $m['caption'] ) . '</figcaption></figure>';

	$nth = 'page' === $post->post_type ? 2 : 1;
	if ( preg_match_all( '#<h2[ >]#', $content, $mm, PREG_OFFSET_CAPTURE ) && isset( $mm[0][ $nth - 1 ] ) ) {
		$pos = $mm[0][ $nth - 1 ][1];
		return substr( $content, 0, $pos ) . $figure . "\n" . substr( $content, $pos );
	}
	return $content . $figure;
}
