/* Gamely Labs: Square Foot Garden Planner */
(function () {
	'use strict';

	var root = document.getElementById('gl-sfg-planner');
	if (!root) return;

	var STORE_KEY = 'gl_sfg_v1';
	var MAX_SIDE = 12;
	var MAX_BEDS = 8;

	// per = plants (or seeds/sets) per 1-ft square. 0.25 = one plant per four squares.
	var PLANTS = [
		{ k: 'tomato', n: 'Tomato (staked)', per: 1, c: '#c0392b', g: 'Fruit' },
		{ k: 'pepper', n: 'Pepper', per: 1, c: '#d35400', g: 'Fruit' },
		{ k: 'eggplant', n: 'Eggplant', per: 1, c: '#6c3483', g: 'Fruit' },
		{ k: 'cucumber', n: 'Cucumber (trellis)', per: 2, c: '#1e8449', g: 'Fruit' },
		{ k: 'zucchini', n: 'Zucchini / summer squash', per: 0.25, c: '#b7950b', g: 'Fruit' },
		{ k: 'strawberry', n: 'Strawberry', per: 4, c: '#e74c3c', g: 'Fruit' },
		{ k: 'broccoli', n: 'Broccoli', per: 1, c: '#196f3d', g: 'Leaf & head' },
		{ k: 'cabbage', n: 'Cabbage', per: 1, c: '#52be80', g: 'Leaf & head' },
		{ k: 'cauliflower', n: 'Cauliflower', per: 1, c: '#a9a9a9', g: 'Leaf & head' },
		{ k: 'kale', n: 'Kale', per: 1, c: '#145a32', g: 'Leaf & head' },
		{ k: 'lettuce', n: 'Leaf lettuce', per: 4, c: '#82e0aa', g: 'Leaf & head' },
		{ k: 'chard', n: 'Swiss chard', per: 4, c: '#e91e63', g: 'Leaf & head' },
		{ k: 'spinach', n: 'Spinach', per: 9, c: '#229954', g: 'Leaf & head' },
		{ k: 'bushbean', n: 'Bush beans', per: 9, c: '#7dcea0', g: 'Beans & peas' },
		{ k: 'polebean', n: 'Pole beans (trellis)', per: 8, c: '#45b39d', g: 'Beans & peas' },
		{ k: 'peas', n: 'Peas (trellis)', per: 8, c: '#58d68d', g: 'Beans & peas' },
		{ k: 'corn', n: 'Corn', per: 4, c: '#f4d03f', g: 'Other' },
		{ k: 'potato', n: 'Potato', per: 4, c: '#a0522d', g: 'Roots' },
		{ k: 'carrot', n: 'Carrots', per: 16, c: '#e67e22', g: 'Roots' },
		{ k: 'radish', n: 'Radishes', per: 16, c: '#ec7063', g: 'Roots' },
		{ k: 'beet', n: 'Beets', per: 9, c: '#922b21', g: 'Roots' },
		{ k: 'turnip', n: 'Turnips', per: 9, c: '#bb8fce', g: 'Roots' },
		{ k: 'onion', n: 'Onions (bulb)', per: 9, c: '#d4ac0d', g: 'Roots' },
		{ k: 'garlic', n: 'Garlic', per: 9, c: '#d5d8dc', g: 'Roots' },
		{ k: 'greenonion', n: 'Green onions', per: 16, c: '#abebc6', g: 'Roots' },
		{ k: 'basil', n: 'Basil', per: 4, c: '#27ae60', g: 'Herbs & flowers' },
		{ k: 'parsley', n: 'Parsley', per: 4, c: '#2ecc71', g: 'Herbs & flowers' },
		{ k: 'cilantro', n: 'Cilantro', per: 9, c: '#48c9b0', g: 'Herbs & flowers' },
		{ k: 'marigold', n: 'Marigold', per: 4, c: '#f39c12', g: 'Herbs & flowers' }
	];
	var BY_KEY = {};
	PLANTS.forEach(function (p) { BY_KEY[p.k] = p; });

	var defaults = {
		beds: [{ name: 'Bed 1', l: 8, w: 4, cells: {} }],
		depth: 6,
		selected: 'tomato'
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
	function esc(s) {
		return String(s).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}
	function fmt(n, dp) {
		if (!isFinite(n)) return '–';
		return n.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: dp == null ? 2 : dp });
	}
	function clampSide(v) {
		var n = Math.round(parseFloat(v));
		if (!isFinite(n)) return 1;
		return Math.min(MAX_SIDE, Math.max(1, n));
	}
	function perLabel(p) {
		return p.per < 1 ? '1 per ' + Math.round(1 / p.per) + ' sq' : p.per + '/sq';
	}
	function textOn(hex) {
		var h = hex.replace('#', '');
		var r = parseInt(h.substr(0, 2), 16), g = parseInt(h.substr(2, 2), 16), b = parseInt(h.substr(4, 2), 16);
		return (r * 299 + g * 587 + b * 114) / 1000 > 150 ? '#1f2a22' : '#ffffff';
	}

	/* ---------- Layout ---------- */
	root.innerHTML =
		'<div class="gl-step gl-noprint">' +
			'<h3><span class="gl-step-num">1</span>Pick a plant, then click squares to plant it</h3>' +
			'<div class="gl-palette" id="glg-palette" role="toolbar" aria-label="Plants"></div>' +
			'<p class="gl-note">Tip: click a planted square again with the same plant selected to clear it, or use the eraser. Hold the mouse button and drag to fill several squares.</p>' +
		'</div>' +
		'<div class="gl-step gl-print-area">' +
			'<h3 class="gl-noprint"><span class="gl-step-num">2</span>Your beds</h3>' +
			'<div id="glg-beds"></div>' +
			'<button type="button" class="gl-btn gl-ghost gl-small gl-noprint" id="glg-add">+ Add a bed</button>' +
			'<div class="gl-results" id="glg-results" aria-live="polite"></div>' +
		'</div>';

	var el = function (id) { return document.getElementById(id); };

	function renderPalette() {
		var html = PLANTS.map(function (p) {
			return '<button type="button" class="gl-chip" data-plant="' + p.k + '" aria-pressed="' + (state.selected === p.k) + '">' +
				'<span class="gl-dot" style="background:' + p.c + '"></span>' + esc(p.n) + ' <small>' + perLabel(p) + '</small></button>';
		}).join('');
		html += '<button type="button" class="gl-chip gl-eraser" data-plant="erase" aria-pressed="' + (state.selected === 'erase') + '">✕ Eraser</button>';
		el('glg-palette').innerHTML = html;
	}
	el('glg-palette').addEventListener('click', function (e) {
		var b = e.target.closest('[data-plant]');
		if (!b) return;
		state.selected = b.getAttribute('data-plant');
		renderPalette();
		save();
	});

	function cellHtml(bed, bi, r, c) {
		var key = bed.cells[r + '-' + c];
		var p = key && BY_KEY[key];
		var label = 'Bed ' + (bi + 1) + ', row ' + (r + 1) + ', column ' + (c + 1) + (p ? ': ' + p.n : ': empty');
		if (!p) {
			return '<button type="button" class="gl-cell" data-b="' + bi + '" data-r="' + r + '" data-c="' + c + '" aria-label="' + esc(label) + '"></button>';
		}
		var count = p.per < 1 ? '¼' : p.per;
		return '<button type="button" class="gl-cell" data-b="' + bi + '" data-r="' + r + '" data-c="' + c + '" aria-label="' + esc(label) + '"' +
			' style="background:' + p.c + ';color:' + textOn(p.c) + '"><b>' + count + '</b><span>' + esc(p.n.split(' (')[0]) + '</span></button>';
	}

	function renderBeds() {
		el('glg-beds').innerHTML = state.beds.map(function (bed, bi) {
			var cells = '';
			for (var r = 0; r < bed.w; r++) {
				for (var c = 0; c < bed.l; c++) cells += cellHtml(bed, bi, r, c);
			}
			return '<div class="gl-item" data-bi="' + bi + '">' +
				'<div class="gl-item-head">' +
					'<input type="text" aria-label="Bed name" data-f="name" value="' + esc(bed.name) + '" maxlength="40">' +
					'<span class="gl-noprint">' +
						'<button type="button" class="gl-link-btn" data-clear="' + bi + '">Clear</button>' +
						(state.beds.length > 1 ? ' <button type="button" class="gl-link-btn" data-remove="' + bi + '">Remove</button>' : '') +
					'</span>' +
				'</div>' +
				'<div class="gl-grid gl-noprint" style="margin-bottom:12px">' +
					'<div class="gl-field"><label for="glg-l-' + bi + '">Length (ft)</label><input id="glg-l-' + bi + '" type="number" min="1" max="' + MAX_SIDE + '" step="1" inputmode="numeric" data-f="l" value="' + bed.l + '"></div>' +
					'<div class="gl-field"><label for="glg-w-' + bi + '">Width (ft)</label><input id="glg-w-' + bi + '" type="number" min="1" max="' + MAX_SIDE + '" step="1" inputmode="numeric" data-f="w" value="' + bed.w + '">' +
						(bed.w > 4 ? '<span class="gl-hint">Over 4 ft wide is hard to reach</span>' : '') + '</div>' +
				'</div>' +
				'<div class="gl-bed-grid" style="grid-template-columns:repeat(' + bed.l + ',auto)">' + cells + '</div>' +
			'</div>';
		}).join('');
	}

	/* ---------- Painting ---------- */
	var painting = null; // 'set' | 'clear' while the pointer is held

	// Updates one square in place. Does nothing if the square is already in the wanted state,
	// which also stops repeated pointerover events from re-rendering the same square.
	function applyCell(btn, mode) {
		var bi = +btn.getAttribute('data-b');
		var bed = state.beds[bi];
		var id = btn.getAttribute('data-r') + '-' + btn.getAttribute('data-c');
		var want = mode === 'clear' ? undefined : state.selected;
		if (bed.cells[id] === want) return;
		if (want === undefined) delete bed.cells[id];
		else bed.cells[id] = want;
		var tmp = document.createElement('div');
		tmp.innerHTML = cellHtml(bed, bi, +btn.getAttribute('data-r'), +btn.getAttribute('data-c'));
		var fresh = tmp.firstChild;
		btn.setAttribute('style', fresh.getAttribute('style') || '');
		btn.setAttribute('aria-label', fresh.getAttribute('aria-label'));
		btn.innerHTML = fresh.innerHTML;
	}
	function modeFor(btn) {
		if (state.selected === 'erase') return 'clear';
		var bed = state.beds[+btn.getAttribute('data-b')];
		var cur = bed.cells[btn.getAttribute('data-r') + '-' + btn.getAttribute('data-c')];
		return cur === state.selected ? 'clear' : 'set';
	}

	var beds = el('glg-beds');
	beds.addEventListener('pointerdown', function (e) {
		var btn = e.target.closest('.gl-cell');
		if (!btn || e.button !== 0 || e.pointerType !== 'mouse') return;
		e.preventDefault();
		painting = modeFor(btn);
		applyCell(btn, painting);
	});
	beds.addEventListener('pointerover', function (e) {
		if (!painting) return;
		var btn = e.target.closest('.gl-cell');
		if (btn) applyCell(btn, painting);
	});
	document.addEventListener('pointerup', function () {
		if (painting) { painting = null; renderResults(); save(); }
	});
	var lastPointer = 'mouse';
	document.addEventListener('pointerdown', function (e) { lastPointer = e.pointerType; }, true);
	document.addEventListener('keydown', function () { lastPointer = 'keyboard'; }, true);

	beds.addEventListener('click', function (e) {
		var btn = e.target.closest('.gl-cell');
		if (btn) {
			// Mouse clicks were already handled on pointerdown; this path covers touch, pen and keyboard.
			if (lastPointer === 'mouse' && e.detail > 0) return;
			applyCell(btn, modeFor(btn));
			renderResults();
			save();
			return;
		}
		var clr = e.target.getAttribute('data-clear');
		if (clr !== null) {
			state.beds[+clr].cells = {};
			renderBeds(); renderResults(); save();
			return;
		}
		var rm = e.target.getAttribute('data-remove');
		if (rm !== null) {
			state.beds.splice(+rm, 1);
			renderBeds(); renderResults(); save();
		}
	});

	beds.addEventListener('change', function (e) {
		var f = e.target.getAttribute('data-f');
		var item = e.target.closest('.gl-item');
		if (!f || !item) return;
		var bed = state.beds[+item.getAttribute('data-bi')];
		if (f === 'name') { bed.name = e.target.value; save(); renderResults(); return; }
		bed[f] = clampSide(e.target.value);
		// Drop plantings that fall outside the new size.
		Object.keys(bed.cells).forEach(function (id) {
			var rc = id.split('-');
			if (+rc[0] >= bed.w || +rc[1] >= bed.l) delete bed.cells[id];
		});
		renderBeds(); renderResults(); save();
	});

	el('glg-add').addEventListener('click', function () {
		if (state.beds.length >= MAX_BEDS) return;
		state.beds.push({ name: 'Bed ' + (state.beds.length + 1), l: 4, w: 4, cells: {} });
		renderBeds(); renderResults(); save();
	});

	/* ---------- Results ---------- */
	function renderResults() {
		var totals = {}; // plant key -> squares
		var area = 0, planted = 0;
		state.beds.forEach(function (bed) {
			area += bed.l * bed.w;
			Object.keys(bed.cells).forEach(function (id) {
				var k = bed.cells[id];
				if (!BY_KEY[k]) return;
				totals[k] = (totals[k] || 0) + 1;
				planted++;
			});
		});

		var depth = parseFloat(state.depth);
		var cuft = area * depth / 12;
		var cuyd = cuft / 27;
		var third = cuft / 3;

		var rows = PLANTS.filter(function (p) { return totals[p.k]; }).map(function (p) {
			var sq = totals[p.k];
			var count = p.per < 1 ? Math.ceil(sq * p.per) : sq * p.per;
			var unit = p.per < 1 ? (count === 1 ? 'plant' : 'plants') : '';
			var note = p.per < 1 && sq % Math.round(1 / p.per) !== 0
				? '<br><small>Give each plant ' + Math.round(1 / p.per) + ' squares</small>' : '';
			return '<tr><td><span class="gl-dot" style="display:inline-block;width:10px;height:10px;border-radius:50%;background:' + p.c + ';margin-right:6px"></span>' + esc(p.n) + '</td>' +
				'<td>' + sq + '</td><td>' + perLabel(p) + '</td><td><strong>' + count + '</strong> ' + unit + note + '</td></tr>';
		}).join('');

		el('glg-results').innerHTML =
			'<h3>Your plan</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + area + ' sq ft</b><span>in ' + state.beds.length + ' bed' + (state.beds.length > 1 ? 's' : '') + '</span></div>' +
				'<div class="gl-stat"><b>' + planted + '</b><span>squares planted</span></div>' +
				'<div class="gl-stat"><b>' + (area - planted) + '</b><span>squares still free</span></div>' +
			'</div>' +
			(rows
				? '<div class="gl-table-wrap"><table><thead><tr><th>Plant</th><th>Squares</th><th>Spacing</th><th>Plants / seeds</th></tr></thead><tbody>' + rows + '</tbody></table></div>'
				: '<p class="gl-note">No squares planted yet. Pick a plant above and click squares in your bed.</p>') +
			'<h3 style="margin-top:18px">Soil mix to fill your beds</h3>' +
			'<div class="gl-grid gl-noprint" style="margin-bottom:10px">' +
				'<div class="gl-field"><label for="glg-depth">Soil depth</label><select id="glg-depth">' +
					[6, 8, 10, 12].map(function (d) {
						return '<option value="' + d + '"' + (d === depth ? ' selected' : '') + '>' + d + ' inches' + (d === 6 ? ' (standard)' : '') + '</option>';
					}).join('') +
				'</select></div>' +
			'</div>' +
			'<div class="gl-table-wrap"><table><tbody>' +
				'<tr><td>Total soil mix</td><td><strong>' + fmt(cuft, 1) + ' cu ft</strong> (' + fmt(cuyd, 2) + ' cu yd)</td></tr>' +
				'<tr><td>Blended compost (⅓)</td><td>' + fmt(third, 1) + ' cu ft ≈ ' + Math.ceil(third / 2 - 1e-9) + ' bags of 2 cu ft</td></tr>' +
				'<tr><td>Peat moss or coco coir (⅓)</td><td>' + fmt(third, 1) + ' cu ft loose</td></tr>' +
				'<tr><td>Coarse vermiculite (⅓)</td><td>' + fmt(third, 1) + ' cu ft</td></tr>' +
			'</tbody></table></div>' +
			'<p class="gl-note">Peat moss and coir are often sold compressed; check the bag\'s loose (expanded) volume. Your plan is saved in this browser.</p>' +
			'<div class="gl-actions gl-noprint">' +
				'<button type="button" class="gl-btn" id="glg-print">Print plan</button>' +
				'<button type="button" class="gl-btn gl-ghost" id="glg-reset">Start over</button>' +
			'</div>';

		el('glg-depth').addEventListener('change', function () {
			state.depth = +this.value;
			renderResults(); save();
		});
		el('glg-print').addEventListener('click', function () {
			document.body.classList.add('gl-printing');
			window.print();
			setTimeout(function () { document.body.classList.remove('gl-printing'); }, 500);
		});
		el('glg-reset').addEventListener('click', function () {
			if (!window.confirm('Clear all beds and start a new plan?')) return;
			state = JSON.parse(JSON.stringify(defaults));
			renderPalette(); renderBeds(); renderResults(); save();
		});
	}

	renderPalette();
	renderBeds();
	renderResults();
})();
