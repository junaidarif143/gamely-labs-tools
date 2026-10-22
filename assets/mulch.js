/* Gamely Labs: Mulch Calculator (multiple beds and tree rings) */
(function () {
	'use strict';
	var root = document.getElementById('gl-mulch');
	if (!root || !window.GLC) return;
	var G = window.GLC;

	var st = G.store('gl_mulch_v1', {
		areas: [
			{ name: 'Front bed', shape: 'rect', l: 20, w: 4, dia: '', inner: 1, sqft: '', qty: 1 },
			{ name: 'Maple tree', shape: 'ring', l: '', w: '', dia: 6, inner: 1, sqft: '', qty: 1 }
		],
		depth: 3, existing: 0, bag: 2, priceBag: '', priceYard: ''
	}, function (s) { return Array.isArray(s.areas) && s.areas.length; });
	var S = st.state;
	var el = function (id) { return document.getElementById(id); };

	root.innerHTML =
		'<div class="gl-step"><h3><span class="gl-step-num">1</span>Beds and tree rings to mulch</h3><div id="glm-areas"></div></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">2</span>Depth and buying</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glm-depth">Mulch depth</label><select id="glm-depth">' + G.opts([[2, '2 inches'], [3, '3 inches (typical)'], [4, '4 inches']], S.depth) + '</select><span class="gl-hint">Extension guides recommend 2–3 in</span></div>' +
			'<div class="gl-field"><label for="glm-existing">Mulch already there (in)</label><input id="glm-existing" type="number" min="0" max="6" step="0.5" inputmode="decimal" value="' + G.esc(S.existing) + '"><span class="gl-hint">Topping up? We only add the difference</span></div>' +
			'<div class="gl-field"><label for="glm-bag">Bag size</label><select id="glm-bag">' + G.opts([[1.5, '1.5 cu ft'], [2, '2 cu ft'], [3, '3 cu ft']], S.bag) + '</select></div>' +
			'<div class="gl-field"><label for="glm-pbag">Price per bag ($, optional)</label><input id="glm-pbag" type="number" min="0" step="0.01" inputmode="decimal" placeholder="e.g. 4.50" value="' + G.esc(S.priceBag) + '"></div>' +
			'<div class="gl-field"><label for="glm-pyard">Bulk price per cu yd ($, optional)</label><input id="glm-pyard" type="number" min="0" step="1" inputmode="decimal" placeholder="e.g. 40" value="' + G.esc(S.priceYard) + '"></div>' +
		'</div></div>' +
		'<div class="gl-results gl-print-area" id="glm-results" aria-live="polite"></div>';

	var areasUI = G.areas(el('glm-areas'), function () { return S.areas; }, { prefix: 'glma-', shapes: ['rect', 'circle', 'ring', 'sqft'], newName: 'Bed', addLabel: 'a bed or tree', onChange: update });

	el('glm-depth').addEventListener('change', function () { S.depth = +this.value; update(); });
	el('glm-bag').addEventListener('change', function () { S.bag = +this.value; update(); });
	[['glm-existing', 'existing'], ['glm-pbag', 'priceBag'], ['glm-pyard', 'priceYard']].forEach(function (p) {
		el(p[0]).addEventListener('input', function () { S[p[1]] = this.value; update(); });
	});

	function update() {
		st.save();
		var box = el('glm-results');
		var sqft = G.totalSqft(S.areas);
		var depth = Math.max(0, S.depth - (G.num(S.existing) || 0));
		if (!(sqft > 0)) { box.innerHTML = '<p class="gl-warn">Enter the size of at least one bed or tree ring.</p>'; return; }
		if (depth === 0) {
			box.innerHTML = '<h3>Mulch you need</h3><p><strong>None.</strong> Your existing ' + G.fmt(G.num(S.existing)) + ' in already meets the ' + S.depth + ' in target. Fluff it up with a rake instead of adding more.</p>';
			return;
		}
		var cuft = sqft * depth / 12, yd = cuft / 27, ydOrder = G.ceil(yd * 2) / 2;
		var bags = G.ceil(cuft / S.bag);
		var pBag = G.num(S.priceBag), pYard = G.num(S.priceYard);

		var rows = S.areas.map(function (a) {
			var s = G.areaSqft(a);
			var v = s.total * depth / 12;
			return '<tr><td>' + G.esc(a.name || 'Area') + (s.qty > 1 ? ' × ' + s.qty : '') + '</td><td>' + G.fmt(s.total, 1) + ' sq ft</td><td>' + G.fmt(v, 1) + ' cu ft</td><td>' + G.ceil(v / S.bag) + ' ' + G.plural(G.ceil(v / S.bag), 'bag', 'bags') + '</td></tr>';
		}).join('');

		var cost = '';
		if (isFinite(pBag) || isFinite(pYard)) {
			var cb = bags * pBag, cy = ydOrder * pYard;
			var cheaper = isFinite(pBag) && isFinite(pYard) ? (cb <= cy ? 'bags' : 'bulk') : '';
			cost = '<div class="gl-stats" style="margin-top:14px">' +
				(isFinite(pBag) ? '<div class="gl-stat"><b>' + G.money(cb) + '</b><span>bagged' + (cheaper === 'bags' ? ' · cheaper' : '') + '</span></div>' : '') +
				(isFinite(pYard) ? '<div class="gl-stat"><b>' + G.money(cy) + '</b><span>bulk (' + G.fmt(ydOrder, 1) + ' yd), before delivery' + (cheaper === 'bulk' ? ' · cheaper' : '') + '</span></div>' : '') +
			'</div>';
		}
		var warn = S.depth > 3 ? '<p class="gl-warn">More than 3 inches can keep water and air from reaching roots. Extension guides recommend 2–3 inches.</p>' : '';

		box.innerHTML =
			'<h3>Mulch you need</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + bags + '</b><span>' + G.plural(bags, 'bag', 'bags') + ' of ' + S.bag + ' cu ft</span></div>' +
				'<div class="gl-stat"><b>' + G.fmt(yd, 2) + ' yd³</b><span>for bulk (order ' + G.fmt(ydOrder, 1) + ')</span></div>' +
				'<div class="gl-stat"><b>' + G.fmt(cuft, 1) + ' ft³</b><span>over ' + G.fmt(sqft, 0) + ' sq ft at ' + G.fmt(depth) + ' in</span></div>' +
			'</div>' +
			'<div class="gl-table-wrap"><table><thead><tr><th>Area</th><th>Size</th><th>Mulch</th><th>Bags</th></tr></thead><tbody>' + rows + '</tbody></table></div>' +
			cost + warn +
			'<p class="gl-note">Keep mulch a few inches away from tree trunks and plant stems. Per-area bag counts are rounded up, so they can add up to a little more than the total.</p>' +
			'<div class="gl-actions gl-noprint"><button type="button" class="gl-btn" id="glm-print">Print shopping list</button><button type="button" class="gl-btn gl-ghost" id="glm-reset">Start over</button></div>';

		el('glm-print').addEventListener('click', G.printArea);
		el('glm-reset').addEventListener('click', function () {
			S = st.reset();
			el('glm-depth').value = String(S.depth); el('glm-bag').value = String(S.bag);
			el('glm-existing').value = S.existing; el('glm-pbag').value = ''; el('glm-pyard').value = '';
			areasUI.render(); update();
		});
	}

	update();
})();
