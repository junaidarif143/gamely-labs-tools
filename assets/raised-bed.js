/* Gamely Labs: Raised Bed Soil Calculator */
(function () {
	'use strict';

	var root = document.getElementById('gl-raised-bed');
	if (!root) return;

	var STORE_KEY = 'gl_raised_bed_v1';
	var EPS = 1e-9;

	var RECIPES = {
		r5050: { label: 'Simple 50/50 (topsoil + compost)', parts: [['Screened topsoil', 50], ['Compost', 50]] },
		sfg: { label: 'Square foot mix (⅓ each)', parts: [['Compost', 100 / 3], ['Peat moss or coir', 100 / 3], ['Coarse vermiculite', 100 / 3]] },
		bagged: { label: 'Bagged raised bed mix only', parts: [['Raised bed mix', 100]] },
		custom: { label: 'Custom percentages', parts: null }
	};

	var defaults = {
		beds: [{ name: 'Bed 1', shape: 'rect', l: 8, w: 4, dia: 4, depth: 12, qty: 1, filler: 0 }],
		settle: 10,
		recipe: 'r5050',
		custom: [['Topsoil', 40], ['Compost', 40], ['Other', 20]],
		bag: 2,
		priceBag: '',
		priceYard: ''
	};
	var state = load();

	function load() {
		try {
			var raw = window.localStorage.getItem(STORE_KEY);
			if (raw) {
				var s = JSON.parse(raw);
				if (s && Array.isArray(s.beds) && s.beds.length) return Object.assign({}, defaults, s);
			}
		} catch (e) { /* storage unavailable */ }
		return JSON.parse(JSON.stringify(defaults));
	}
	function save() {
		try { window.localStorage.setItem(STORE_KEY, JSON.stringify(state)); } catch (e) { /* ignore */ }
	}
	function num(v) { var n = parseFloat(v); return isFinite(n) ? n : NaN; }
	function fmt(n, dp) {
		if (!isFinite(n)) return '–';
		return n.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: dp == null ? 2 : dp });
	}
	function money(n) {
		return isFinite(n) ? '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '–';
	}
	function esc(s) {
		return String(s).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}
	function opts(pairs, sel) {
		return pairs.map(function (p) {
			return '<option value="' + p[0] + '"' + (String(p[0]) === String(sel) ? ' selected' : '') + '>' + esc(p[1]) + '</option>';
		}).join('');
	}
	var el = function (id) { return document.getElementById(id); };

	root.innerHTML =
		'<div class="gl-step">' +
			'<h3><span class="gl-step-num">1</span>Your beds</h3>' +
			'<div id="glr-beds"></div>' +
			'<button type="button" class="gl-btn gl-ghost gl-small" id="glr-add">+ Add a bed</button>' +
		'</div>' +
		'<div class="gl-step">' +
			'<h3><span class="gl-step-num">2</span>Soil recipe and buying</h3>' +
			'<div class="gl-grid">' +
				'<div class="gl-field"><label for="glr-recipe">Soil recipe</label><select id="glr-recipe">' +
					opts(Object.keys(RECIPES).map(function (k) { return [k, RECIPES[k].label]; }), state.recipe) + '</select></div>' +
				'<div class="gl-field"><label for="glr-settle">Extra for settling</label><select id="glr-settle">' +
					opts([[0, 'None'], [10, '10% (typical)'], [15, '15%'], [20, '20%']], state.settle) + '</select></div>' +
				'<div class="gl-field"><label for="glr-bag">Bag size</label><select id="glr-bag">' +
					opts([[1, '1 cu ft'], [1.5, '1.5 cu ft'], [2, '2 cu ft'], [3, '3 cu ft']], state.bag) + '</select></div>' +
			'</div>' +
			'<div id="glr-custom"></div>' +
			'<div class="gl-grid" style="margin-top:12px">' +
				'<div class="gl-field"><label for="glr-pbag">Price per bag ($, optional)</label>' +
					'<input id="glr-pbag" type="number" min="0" step="0.01" inputmode="decimal" placeholder="e.g. 9.98" value="' + esc(state.priceBag) + '"></div>' +
				'<div class="gl-field"><label for="glr-pyard">Bulk price per cu yd ($, optional)</label>' +
					'<input id="glr-pyard" type="number" min="0" step="1" inputmode="decimal" placeholder="e.g. 55" value="' + esc(state.priceYard) + '">' +
					'<span class="gl-hint">Average across your ingredients</span></div>' +
			'</div>' +
		'</div>' +
		'<div class="gl-results gl-print-area" id="glr-results" aria-live="polite"></div>';

	function renderBeds() {
		el('glr-beds').innerHTML = state.beds.map(function (b, i) {
			var rect = b.shape === 'rect';
			return '<div class="gl-item" data-i="' + i + '">' +
				'<div class="gl-item-head"><input type="text" aria-label="Bed name" data-f="name" maxlength="40" value="' + esc(b.name) + '">' +
					(state.beds.length > 1 ? '<button type="button" class="gl-link-btn" data-remove="' + i + '">Remove</button>' : '') + '</div>' +
				'<div class="gl-grid">' +
					'<div class="gl-field"><label for="glr-shape-' + i + '">Shape</label><select id="glr-shape-' + i + '" data-f="shape">' +
						opts([['rect', 'Rectangle'], ['round', 'Round']], b.shape) + '</select></div>' +
					(rect
						? '<div class="gl-field"><label for="glr-l-' + i + '">Length (ft)</label><input id="glr-l-' + i + '" type="number" min="0" step="0.5" inputmode="decimal" data-f="l" value="' + esc(b.l) + '"></div>' +
						  '<div class="gl-field"><label for="glr-w-' + i + '">Width (ft)</label><input id="glr-w-' + i + '" type="number" min="0" step="0.5" inputmode="decimal" data-f="w" value="' + esc(b.w) + '"></div>'
						: '<div class="gl-field"><label for="glr-dia-' + i + '">Diameter (ft)</label><input id="glr-dia-' + i + '" type="number" min="0" step="0.5" inputmode="decimal" data-f="dia" value="' + esc(b.dia) + '"></div>') +
					'<div class="gl-field"><label for="glr-depth-' + i + '">Soil depth (in)</label><input id="glr-depth-' + i + '" type="number" min="0" step="1" inputmode="numeric" data-f="depth" value="' + esc(b.depth) + '"></div>' +
					'<div class="gl-field"><label for="glr-filler-' + i + '">Filler at bottom (in)</label><input id="glr-filler-' + i + '" type="number" min="0" step="1" inputmode="numeric" data-f="filler" value="' + esc(b.filler) + '">' +
						'<span class="gl-hint">Logs or branches; 0 if none</span></div>' +
					'<div class="gl-field"><label for="glr-qty-' + i + '">How many like this</label><input id="glr-qty-' + i + '" type="number" min="1" max="50" step="1" inputmode="numeric" data-f="qty" value="' + esc(b.qty) + '"></div>' +
				'</div></div>';
		}).join('');
	}

	function renderCustom() {
		var box = el('glr-custom');
		if (state.recipe !== 'custom') { box.innerHTML = ''; return; }
		box.innerHTML = '<div class="gl-grid" style="margin-top:12px">' + state.custom.map(function (c, i) {
			return '<div class="gl-field"><label for="glr-cn-' + i + '">Ingredient ' + (i + 1) + '</label>' +
				'<input id="glr-cn-' + i + '" type="text" data-c="' + i + '" data-k="0" maxlength="30" value="' + esc(c[0]) + '" style="width:100%;min-height:40px;padding:8px 10px;font:inherit;border:1px solid #b9c7bb;border-radius:7px;margin-bottom:6px">' +
				'<input type="number" min="0" max="100" step="1" inputmode="numeric" aria-label="Percent of ingredient ' + (i + 1) + '" data-c="' + i + '" data-k="1" value="' + esc(c[1]) + '">' +
				'<span class="gl-hint">% of the mix</span></div>';
		}).join('') + '</div>';
	}

	el('glr-beds').addEventListener('input', function (e) {
		var item = e.target.closest('.gl-item'), f = e.target.getAttribute('data-f');
		if (!item || !f) return;
		var b = state.beds[+item.getAttribute('data-i')];
		b[f] = e.target.value;
		if (f === 'shape') { renderBeds(); }
		update();
	});
	el('glr-beds').addEventListener('change', function (e) {
		if (e.target.getAttribute('data-f') !== 'shape') return;
		var b = state.beds[+e.target.closest('.gl-item').getAttribute('data-i')];
		if (b.shape !== e.target.value) { b.shape = e.target.value; renderBeds(); update(); }
	});
	el('glr-beds').addEventListener('click', function (e) {
		var r = e.target.getAttribute('data-remove');
		if (r === null) return;
		state.beds.splice(+r, 1); renderBeds(); update();
	});
	el('glr-add').addEventListener('click', function () {
		if (state.beds.length >= 20) return;
		state.beds.push({ name: 'Bed ' + (state.beds.length + 1), shape: 'rect', l: 4, w: 4, dia: 4, depth: 12, qty: 1, filler: 0 });
		renderBeds(); update();
	});
	el('glr-custom').addEventListener('input', function (e) {
		var i = e.target.getAttribute('data-c');
		if (i === null) return;
		state.custom[+i][+e.target.getAttribute('data-k')] = e.target.value;
		update();
	});
	el('glr-recipe').addEventListener('change', function () { state.recipe = this.value; renderCustom(); update(); });
	el('glr-settle').addEventListener('change', function () { state.settle = +this.value; update(); });
	el('glr-bag').addEventListener('change', function () { state.bag = +this.value; update(); });
	el('glr-pbag').addEventListener('input', function () { state.priceBag = this.value; update(); });
	el('glr-pyard').addEventListener('input', function () { state.priceYard = this.value; update(); });

	function bedVolume(b) {
		var depth = Math.max(0, (num(b.depth) || 0) - (num(b.filler) || 0));
		var area = b.shape === 'round' ? Math.PI * Math.pow((num(b.dia) || 0) / 2, 2) : (num(b.l) || 0) * (num(b.w) || 0);
		var qty = Math.max(1, Math.round(num(b.qty) || 1));
		return { each: area * depth / 12, qty: qty, total: area * depth / 12 * qty, area: area };
	}

	function update() {
		save();
		var beds = state.beds.map(function (b) { return { name: b.name || 'Bed', v: bedVolume(b), filler: num(b.filler) || 0, depth: num(b.depth) || 0 }; });
		var base = beds.reduce(function (s, b) { return s + b.v.total; }, 0);
		var total = base * (1 + state.settle / 100);
		var box = el('glr-results');

		if (!(base > 0)) {
			box.innerHTML = '<p class="gl-warn">Enter the size and depth of at least one bed.</p>';
			return;
		}

		var parts, warn = '';
		if (state.recipe === 'custom') {
			var sum = state.custom.reduce(function (s, c) { return s + (num(c[1]) || 0); }, 0);
			if (!(sum > 0)) { box.innerHTML = '<p class="gl-warn">Enter percentages for your custom mix.</p>'; return; }
			if (Math.abs(sum - 100) > 0.5) warn = '<p class="gl-warn">Your percentages add up to ' + fmt(sum, 1) + '%, so they were scaled to 100%.</p>';
			parts = state.custom.filter(function (c) { return num(c[1]) > 0; }).map(function (c) { return [c[0] || 'Ingredient', num(c[1]) * 100 / sum]; });
		} else {
			parts = RECIPES[state.recipe].parts;
		}

		var pBag = num(state.priceBag), pYard = num(state.priceYard);
		var bagTotal = 0, bulkTotal = 0, bagCount = 0;
		var rows = parts.map(function (p) {
			var cuft = total * p[1] / 100;
			var bags = Math.ceil(cuft / state.bag - EPS);
			var yd = cuft / 27;
			var ydOrder = Math.ceil(yd * 2 - EPS) / 2; // bulk is usually sold in half-yard steps
			bagCount += bags;
			bagTotal += bags * pBag;
			bulkTotal += ydOrder * pYard;
			return '<tr><td><strong>' + esc(p[0]) + '</strong><br><small>' + fmt(p[1], 1) + '%</small></td>' +
				'<td>' + fmt(cuft, 1) + ' cu ft</td><td>' + bags + (bags === 1 ? ' bag' : ' bags') + '</td><td>' + fmt(yd, 2) + ' cu yd<br><small>order ' + fmt(ydOrder, 1) + '</small></td></tr>';
		}).join('');

		var bedRows = beds.map(function (b) {
			var note = b.filler >= b.depth && b.depth > 0 ? ' <small>(filler fills the bed)</small>' : '';
			return '<tr><td>' + esc(b.name) + (b.v.qty > 1 ? ' × ' + b.v.qty : '') + '</td><td>' + fmt(b.v.total, 1) + ' cu ft' + note + '</td></tr>';
		}).join('');

		var cost = '';
		if (isFinite(pBag) || isFinite(pYard)) {
			var cheaper = isFinite(pBag) && isFinite(pYard) ? (bagTotal < bulkTotal ? 'bags' : 'bulk') : '';
			cost = '<div class="gl-stats" style="margin-top:14px">' +
				(isFinite(pBag) ? '<div class="gl-stat"><b>' + money(bagTotal) + '</b><span>bagged (' + bagCount + ' bags)' + (cheaper === 'bags' ? ' · cheaper' : '') + '</span></div>' : '') +
				(isFinite(pYard) ? '<div class="gl-stat"><b>' + money(bulkTotal) + '</b><span>bulk, before delivery' + (cheaper === 'bulk' ? ' · cheaper' : '') + '</span></div>' : '') +
				'</div>';
		}

		box.innerHTML =
			'<h3>Soil you need</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + fmt(total, 1) + ' cu ft</b><span>total' + (state.settle ? ', incl. ' + state.settle + '% extra' : '') + '</span></div>' +
				'<div class="gl-stat"><b>' + fmt(total / 27, 2) + ' cu yd</b><span>for bulk orders</span></div>' +
				'<div class="gl-stat"><b>' + bagCount + '</b><span>bags of ' + state.bag + ' cu ft in all</span></div>' +
			'</div>' +
			'<div class="gl-table-wrap"><table><thead><tr><th>Ingredient</th><th>Volume</th><th>Bagged</th><th>Bulk</th></tr></thead><tbody>' + rows + '</tbody></table></div>' +
			warn + cost +
			'<details style="margin-top:12px"><summary>Volume by bed (before the extra for settling)</summary><div class="gl-table-wrap"><table><tbody>' + bedRows + '</tbody></table></div></details>' +
			'<div class="gl-actions gl-noprint">' +
				'<button type="button" class="gl-btn" id="glr-print">Print shopping list</button>' +
				'<button type="button" class="gl-btn gl-ghost" id="glr-reset">Start over</button>' +
			'</div>';

		el('glr-print').addEventListener('click', function () {
			document.body.classList.add('gl-printing'); window.print();
			setTimeout(function () { document.body.classList.remove('gl-printing'); }, 500);
		});
		el('glr-reset').addEventListener('click', function () {
			state = JSON.parse(JSON.stringify(defaults));
			el('glr-recipe').value = state.recipe; el('glr-settle').value = String(state.settle);
			el('glr-bag').value = String(state.bag); el('glr-pbag').value = ''; el('glr-pyard').value = '';
			renderBeds(); renderCustom(); update();
		});
	}

	renderBeds();
	renderCustom();
	update();
})();
