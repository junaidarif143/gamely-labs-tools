<?php
/**
 * Installable web app: manifest, service worker (offline calculators), offline page, install button.
 *
 * Served URLs (handled before WordPress routing, so no rewrite rules are needed):
 *   /manifest.webmanifest  the web app manifest
 *   /sw.js                 the service worker (must live at the site root to control every page)
 *   /gl-offline.html       shown when a page is opened offline that was never cached
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'gl_tools_pwa_serve', 0 );
function gl_tools_pwa_serve() {
	$path = wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$home = rtrim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	switch ( $path ) {
		case $home . '/manifest.webmanifest':
			gl_tools_pwa_headers( 'application/manifest+json' );
			echo wp_json_encode( gl_tools_pwa_manifest(), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
			exit;
		case $home . '/sw.js':
			gl_tools_pwa_headers( 'application/javascript' );
			header( 'Service-Worker-Allowed: /' );
			echo gl_tools_pwa_sw(); // phpcs:ignore WordPress.Security.EscapeOutput
			exit;
		case $home . '/gl-offline.html':
			gl_tools_pwa_headers( 'text/html' );
			header( 'X-Robots-Tag: noindex' );
			echo gl_tools_pwa_offline_html(); // phpcs:ignore WordPress.Security.EscapeOutput
			exit;
	}
}

function gl_tools_pwa_headers( $type ) {
	status_header( 200 );
	header( 'Content-Type: ' . $type . '; charset=utf-8' );
	header( 'Cache-Control: no-cache, max-age=0, must-revalidate' );
	header( 'X-Content-Type-Options: nosniff' );
}

function gl_tools_pwa_icon( $file ) {
	return GL_TOOLS_URL . 'assets/app/' . $file . '?v=' . GL_TOOLS_VERSION;
}

function gl_tools_pwa_manifest() {
	$shortcuts = array();
	foreach ( array( 'sprinkler', 'mulch', 'gravel', 'raised_bed' ) as $key ) {
		$t = gl_tools_catalog()[ $key ];
		$shortcuts[] = array(
			'name'       => $t['title'],
			'short_name' => $t['short'],
			'url'        => '/' . $t['slug'] . '/?source=app',
			'icons'      => array( array( 'src' => gl_tools_pwa_icon( 'icon-192.png' ), 'sizes' => '192x192', 'type' => 'image/png' ) ),
		);
	}
	return array(
		'id'               => '/',
		'name'             => 'Gamely Labs: Lawn & Garden Calculators',
		'short_name'       => 'Gamely Labs',
		'description'      => 'Free lawn, garden and yard calculators: sprinkler run times, fertilizer, lime, mulch, soil, sod, seed, gravel and more. Works offline.',
		'start_url'        => '/?source=app',
		'scope'            => '/',
		'display'          => 'standalone',
		'orientation'      => 'portrait',
		'background_color' => '#F6F1E7',
		'theme_color'      => '#2F4A2C',
		'lang'             => 'en-US',
		'dir'              => 'ltr',
		'categories'       => array( 'lifestyle', 'utilities', 'productivity' ),
		'icons'            => array(
			array( 'src' => gl_tools_pwa_icon( 'icon-192.png' ), 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any' ),
			array( 'src' => gl_tools_pwa_icon( 'icon-512.png' ), 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any' ),
			array( 'src' => gl_tools_pwa_icon( 'maskable-192.png' ), 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'maskable' ),
			array( 'src' => gl_tools_pwa_icon( 'maskable-512.png' ), 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable' ),
		),
		'shortcuts'        => $shortcuts,
	);
}

/** Pages stored for offline use when the app is installed or first visited. */
function gl_tools_pwa_precache_urls() {
	$urls = array( '/', '/gl-offline.html' );
	foreach ( gl_tools_catalog() as $t ) {
		$urls[] = '/' . $t['slug'] . '/';
	}
	$guides = (int) get_option( 'page_for_posts' );
	if ( $guides ) {
		$urls[] = wp_make_link_relative( get_permalink( $guides ) );
	}
	return $urls;
}

function gl_tools_pwa_sw() {
	$version  = GL_TOOLS_VERSION . '-' . ( defined( 'GAMELY_VERSION' ) ? GAMELY_VERSION : '0' );
	$precache = wp_json_encode( gl_tools_pwa_precache_urls(), JSON_UNESCAPED_SLASHES );
	return <<<JS
/* Gamely Labs service worker {$version}. Network first for pages, cached copies when offline. */
const V = 'gl-{$version}';
const PAGES = V + '-pages', ASSETS = V + '-assets';
const PRECACHE = {$precache};
const SKIP = /^\/(wp-admin|wp-login\.php|wp-json|wp-cron\.php|xmlrpc\.php)|[?&](preview|customize_changeset_uuid|s)=/;
const ASSET = /\.(css|js|woff2?|ttf|png|jpe?g|webp|svg|gif|ico)(\?|$)/i;

function pageKey(url) { const u = new URL(url); return u.origin + u.pathname; }

async function cacheAssetsFrom(html, base) {
  const c = await caches.open(ASSETS);
  const found = new Set();
  for (const m of html.matchAll(/(?:src|href)=["']([^"']+\.(?:css|js)(?:\?[^"']*)?)["']/g)) {
    try { const u = new URL(m[1].replace(/&#0?38;|&amp;/g, '&'), base); if (u.origin === self.location.origin || /fonts\.googleapis\.com$/.test(u.hostname)) found.add(u.href); } catch (e) {}
  }
  await Promise.all([...found].map(u => c.match(u).then(hit => hit || fetch(u, { credentials: 'omit', mode: u.startsWith(self.location.origin) ? 'same-origin' : 'no-cors' }).then(r => (r.ok || r.type === 'opaque') && c.put(u, r)).catch(() => {}))));
}

self.addEventListener('install', event => {
  event.waitUntil((async () => {
    const c = await caches.open(PAGES);
    await Promise.all(PRECACHE.map(async p => {
      try {
        const url = new URL(p, self.location.origin).href;
        const r = await fetch(url, { credentials: 'omit', cache: 'no-store' });
        if (!r.ok) return;
        await c.put(pageKey(url), r.clone());
        await cacheAssetsFrom(await r.text(), url);
      } catch (e) {}
    }));
    await self.skipWaiting();
  })());
});

self.addEventListener('activate', event => {
  event.waitUntil((async () => {
    for (const k of await caches.keys()) if (k.startsWith('gl-') && !k.startsWith(V)) await caches.delete(k);
    await self.clients.claim();
  })());
});

self.addEventListener('fetch', event => {
  const req = event.request;
  if (req.method !== 'GET') return;
  const url = new URL(req.url);
  const sameOrigin = url.origin === self.location.origin;
  const isFont = /^fonts\.(googleapis|gstatic)\.com$/.test(url.hostname);
  if (!sameOrigin && !isFont) return; // ads, analytics and other third parties go straight to the network
  if (sameOrigin && SKIP.test(url.pathname + url.search)) return;

  if (req.mode === 'navigate') {
    event.respondWith((async () => {
      try {
        const res = await fetch(req);
        if (res.ok && res.type === 'basic') {
          const copy = res.clone();
          copy.text().then(async t => {
            if (t.includes('id="wpadminbar"')) return; // never keep logged-in pages
            const c = await caches.open(PAGES);
            await c.put(pageKey(req.url), new Response(t, { headers: { 'Content-Type': 'text/html; charset=utf-8' } }));
            cacheAssetsFrom(t, req.url);
          }).catch(() => {});
        }
        return res;
      } catch (e) {
        const c = await caches.open(PAGES);
        return (await c.match(pageKey(req.url))) || (await c.match(pageKey(new URL('/gl-offline.html', self.location.origin).href))) || Response.error();
      }
    })());
    return;
  }

  if (isFont || (sameOrigin && ASSET.test(url.pathname + url.search))) {
    event.respondWith((async () => {
      const c = await caches.open(ASSETS);
      const hit = await c.match(req);
      const net = fetch(req).then(r => { if (r.ok || r.type === 'opaque') c.put(req, r.clone()); return r; }).catch(() => hit);
      return hit || net;
    })());
  }
});
JS;
}

function gl_tools_pwa_offline_html() {
	$items = '';
	foreach ( gl_tools_catalog() as $t ) {
		$items .= '<li><a href="/' . esc_attr( $t['slug'] ) . '/">' . esc_html( $t['short'] ) . '</a></li>';
	}
	return '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex"><meta name="theme-color" content="#2F4A2C"><title>Offline – Gamely Labs</title>'
		. '<style>:root{color-scheme:light}body{margin:0;background:#F6F1E7;color:#2A2620;font:16px/1.55 system-ui,-apple-system,"Segoe UI",sans-serif}main{max-width:560px;margin:0 auto;padding:40px 20px}h1{font-family:Georgia,serif;color:#2F4A2C;font-size:30px;margin:0 0 8px}p{color:#5A5347}ul{list-style:none;padding:0;margin:24px 0;display:grid;grid-template-columns:1fr 1fr;gap:10px}a{display:block;padding:12px 14px;background:#FFFDF7;border:1px solid #D3C6AC;border-radius:12px;color:#2F4A2C;font-weight:600;text-decoration:none}button{margin-top:8px;padding:12px 20px;border:0;border-radius:999px;background:#2F4A2C;color:#fff;font-weight:700;font-size:15px}</style></head>'
		. '<body><main><h1>You’re offline</h1><p>This page hasn’t been saved on this device yet. The calculators below work offline once you’ve opened them online.</p><ul>' . $items . '</ul><button type="button" onclick="location.reload()">Try again</button></main></body></html>';
}

/** Head tags: manifest, icons, app capable. */
add_action( 'wp_head', 'gl_tools_pwa_head', 2 );
function gl_tools_pwa_head() {
	echo '<link rel="manifest" href="' . esc_url( home_url( '/manifest.webmanifest' ) ) . '">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( gl_tools_pwa_icon( 'apple-touch-icon.png' ) ) . '">' . "\n";
	echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
	echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
	echo '<meta name="apple-mobile-web-app-title" content="Gamely Labs">' . "\n";
	echo '<meta name="apple-mobile-web-app-status-bar-style" content="default">' . "\n";
}

/** Register the service worker and offer an "Install app" button in the footer when the browser allows it. */
add_action( 'wp_footer', 'gl_tools_pwa_footer', 50 );
function gl_tools_pwa_footer() {
	// Logged-in admins get the worker too (so installs can be tested); it never caches pages showing the admin bar.
	$sw = esc_url( home_url( '/sw.js' ) );
	?>
<script>
(function () {
	if (!('serviceWorker' in navigator)) return;
	window.addEventListener('load', function () { navigator.serviceWorker.register('<?php echo $sw; // phpcs:ignore ?>', { scope: '/' }).catch(function () {}); });
	var deferred = null;
	function button() {
		var host = document.querySelector('.g-footer__legal');
		if (!host || document.getElementById('gl-install')) return null;
		var b = document.createElement('button');
		b.type = 'button'; b.id = 'gl-install'; b.className = 'gl-install';
		b.textContent = 'Install the app';
		b.style.cssText = 'margin-left:12px;padding:6px 14px;border:1px solid currentColor;border-radius:999px;background:transparent;color:inherit;font:inherit;font-weight:700;cursor:pointer';
		host.appendChild(b);
		return b;
	}
	window.addEventListener('beforeinstallprompt', function (e) {
		e.preventDefault(); deferred = e;
		var b = button(); if (!b) return;
		b.addEventListener('click', function () {
			if (!deferred) return;
			deferred.prompt();
			deferred.userChoice.finally(function () { deferred = null; b.remove(); });
		});
	});
	window.addEventListener('appinstalled', function () { var b = document.getElementById('gl-install'); if (b) b.remove(); });
})();
</script>
	<?php
}
