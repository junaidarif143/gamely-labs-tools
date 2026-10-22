/* Gamely Labs: Sod Calculator (pieces and pallets) */
(function () {
	'use strict';
	var root = document.getElementById('gl-sod');
	if (!root || !window.GLC) return;
	var G = window.GLC;

	// Common sizes (Sod Solutions): slabs 16 x 24 in, mini rolls 18 x 40 in, large rolls about 10 sq ft.
	var FORMATS = {
		slab: { label: 'Slabs, 16 × 24 in (2.67 sq ft)', size: 16 * 24 / 144, unit: 'slabs', pallet: 450 },
		mini: { label: 'Mini rolls, 18 × 40 in (5 sq ft)', size: 5, unit: 'rolls', pallet: 500 },
		big: { label: 'Large rolls (10 sq ft)', size: 10, unit: 'rolls', pallet: 600 }
	};

	var st = G.store('gl_sod_v1', {
		areas: [{ name: 'Back yard', shape: 'rect', l: 40, w: 30, dia: '', inner: 1, sqft: '', qty: 1 }],
		format: 'slab', pallet: 450, waste: 5, pricePallet: ''
	}, function (s) { return Array.isArray(s.areas) && s.areas.length; });
	var S = st.state;
	var el = function (id) { return document.getElementById(id); };

	root.innerHTML =
		'<div class="gl-step"><h3><span class="gl-step-num">1</span>Areas to sod</h3><div id="gls2-areas"></div></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">2</span>How your sod is sold</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="gls2-format">Piece size</label><select id="gls2-format">' + G.opts(Object.keys(FORMATS).map(function (k) { return [k, FORMATS[k].label]; }), S.format) + '</select><span class="gl-hint">Check your supplier’s listing</span></div>' +
			'<div class="gl-field"><label for="gls2-pallet">Square feet per pallet</label><select id="gls2-pallet">' + G.opts([[400, '400 sq ft'], [450, '450 sq ft'], [500, '500 sq ft'], [600, '600 sq ft'], [700, '700 sq ft']], S.pallet) + '</select></div>' +
			'<div class="gl-field"><label for="gls2-waste">Extra for cuts and waste</label><select id="gls2-waste">' + G.opts([[0, 'None'], [5, '5% (simple shapes)'], [10, '10% (curves, beds, trees)']], S.waste) + '</select></div>' +
			'<div class="gl-field"><label for="gls2-price">Price per pallet ($, optional)</label><input id="gls2-price" type="number" min="0" step="1" inputmode="decimal" value="' + G.esc(S.pricePallet) + '"></div>' +
		'</div></div>' +
		'<div class="gl-results gl-print-area" id="gls2-results" aria-live="polite"></div>';

	var areasUI = G.areas(el('gls2-areas'), function () { return S.areas; }, { prefix: 'gls2a-', shapes: ['rect', 'circle', 'sqft'], newName: 'Area', onChange: update });

	el('gls2-format').addEventListener('change', function () {
		S.format = this.value;
		S.pallet = FORMATS[S.format].pallet;
		el('gls2-pallet').value = String(S.pallet);
		update();
	});
	el('gls2-pallet').addEventListener('change', function () { S.pallet = +this.value; update(); });
	el('gls2-waste').addEventListener('change', function () { S.waste = +this.value; update(); });
	el('gls2-price').addEventListener('input', function () { S.pricePallet = this.value; update(); });

	function update() {
		st.save();
		var box = el('gls2-results');
		var f = FORMATS[S.format];
		var sqft = G.totalSqft(S.areas);
		if (!(sqft > 0)) { box.innerHTML = '<p class="gl-warn">Enter the size of the area you want to sod.</p>'; return; }

		var need = sqft * (1 + S.waste / 100);
		var pieces = G.ceil(need / f.size);
		var pallets = G.ceil(need / S.pallet);
		var full = Math.floor(need / S.pallet + G.EPS);
		var rest = need - full * S.pallet;
		var restPieces = G.ceil(rest / f.size);
		var price = G.num(S.pricePallet);
		var leftover = pallets * S.pallet - need;

		var option2 = full >= 1 && restPieces > 0
			? '<p class="gl-note">If your supplier sells partial pallets: <strong>' + full + ' ' + G.plural(full, 'pallet', 'pallets') + '</strong> plus <strong>' + restPieces + ' ' + f.unit + '</strong> (' + G.fmt(restPieces * f.size, 0) + ' sq ft).</p>'
			: '';

		box.innerHTML =
			'<h3>Sod to order</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + pallets + '</b><span>' + G.plural(pallets, 'pallet', 'pallets') + ' of ' + S.pallet + ' sq ft</span></div>' +
				'<div class="gl-stat"><b>' + G.fmt(pieces, 0) + '</b><span>' + f.unit + ' in total</span></div>' +
				'<div class="gl-stat"><b>' + G.fmt(need, 0) + ' sq ft</b><span>' + G.fmt(sqft, 0) + ' sq ft + ' + S.waste + '% extra</span></div>' +
				(isFinite(price) ? '<div class="gl-stat"><b>' + G.money(pallets * price) + '</b><span>for ' + pallets + ' ' + G.plural(pallets, 'pallet', 'pallets') + '</span></div>' : '') +
			'</div>' +
			option2 +
			'<p class="gl-note">Ordering whole pallets leaves about ' + G.fmt(leftover, 0) + ' sq ft spare, handy for patching. Lay sod the day it arrives; it dries out quickly on the pallet.</p>' +
			'<div class="gl-actions gl-noprint"><button type="button" class="gl-btn" id="gls2-print">Print</button><button type="button" class="gl-btn gl-ghost" id="gls2-reset">Start over</button></div>';

		el('gls2-print').addEventListener('click', G.printArea);
		el('gls2-reset').addEventListener('click', function () {
			S = st.reset();
			el('gls2-format').value = S.format; el('gls2-pallet').value = String(S.pallet);
			el('gls2-waste').value = String(S.waste); el('gls2-price').value = '';
			areasUI.render(); update();
		});
	}

	update();
})();
