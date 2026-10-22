/* Gamely Labs: Lawn Fertilizer Calculator */
(function () {
	'use strict';

	var root = document.getElementById('gl-fertilizer');
	if (!root) return;

	var STORE_KEY = 'gl_fertilizer_v1';
	var EPS = 1e-9;

	// Yearly nitrogen (lb per 1,000 sq ft) from university extension guidance (UConn, Virginia Tech).
	var GRASS = {
		cool: { label: 'Cool-season (fescue, bluegrass, ryegrass)', when: 'late spring and early fall' },
		bermuda: { label: 'Bermuda or St. Augustine', low: 2, high: 3.5, when: 'late spring through summer, while it is actively growing' },
		zoysia: { label: 'Zoysia or centipede', low: 1, high: 2, when: 'late spring through summer, while it is actively growing' }
	};

	var defaults = {
		areas: [{ name: 'Front lawn', sqft: '' }, { name: 'Back lawn', sqft: '' }],
		n: 24, p: 0, k: 6,
		bag: 25,
		price: '',
		rate: '1',
		customRate: '',
		release: 'quick',
		grass: 'cool',
		clippings: 'left'
	};
	var state = load();

	function load() {
		try {
			var raw = window.localStorage.getItem(STORE_KEY);
			if (raw) {
				var s = JSON.parse(raw);
				if (s && Array.isArray(s.areas) && s.areas.length) return Object.assign({}, defaults, s);
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
			'<h3><span class="gl-step-num">1</span>Your lawn</h3>' +
			'<div id="glf-areas"></div>' +
			'<button type="button" class="gl-btn gl-ghost gl-small" id="glf-add">+ Add an area</button>' +
		'</div>' +
		'<div class="gl-step">' +
			'<h3><span class="gl-step-num">2</span>Your fertilizer</h3>' +
			'<div class="gl-grid">' +
				'<div class="gl-field"><label for="glf-n">N (nitrogen %)</label><input id="glf-n" type="number" min="0" max="46" step="1" inputmode="decimal" value="' + esc(state.n) + '"><span class="gl-hint">First number on the bag</span></div>' +
				'<div class="gl-field"><label for="glf-p">P (phosphate %)</label><input id="glf-p" type="number" min="0" max="60" step="1" inputmode="decimal" value="' + esc(state.p) + '"></div>' +
				'<div class="gl-field"><label for="glf-k">K (potash %)</label><input id="glf-k" type="number" min="0" max="60" step="1" inputmode="decimal" value="' + esc(state.k) + '"></div>' +
				'<div class="gl-field"><label for="glf-bag">Bag weight (lb)</label><input id="glf-bag" type="number" min="1" step="1" inputmode="decimal" value="' + esc(state.bag) + '"></div>' +
				'<div class="gl-field"><label for="glf-price">Price per bag ($, optional)</label><input id="glf-price" type="number" min="0" step="0.01" inputmode="decimal" placeholder="e.g. 34.99" value="' + esc(state.price) + '"></div>' +
				'<div class="gl-field"><label for="glf-release">Nitrogen type</label><select id="glf-release">' +
					opts([['quick', 'Mostly quick-release'], ['slow', 'Slow-release']], state.release) + '</select><span class="gl-hint">Check the label for “slow release” %</span></div>' +
			'</div>' +
		'</div>' +
		'<div class="gl-step">' +
			'<h3><span class="gl-step-num">3</span>How much to apply</h3>' +
			'<div class="gl-grid">' +
				'<div class="gl-field"><label for="glf-rate">Nitrogen per application</label><select id="glf-rate">' +
					opts([['0.5', '0.5 lb N per 1,000 sq ft (light)'], ['0.75', '0.75 lb N per 1,000 sq ft'], ['1', '1 lb N per 1,000 sq ft (standard)'], ['custom', 'Custom rate']], state.rate) + '</select></div>' +
				'<div class="gl-field" id="glf-custom-wrap"><label for="glf-custom">Custom lb N per 1,000 sq ft</label><input id="glf-custom" type="number" min="0.1" max="3" step="0.05" inputmode="decimal" value="' + esc(state.customRate) + '"></div>' +
				'<div class="gl-field"><label for="glf-grass">Grass type</label><select id="glf-grass">' +
					opts(Object.keys(GRASS).map(function (k) { return [k, GRASS[k].label]; }), state.grass) + '</select></div>' +
				'<div class="gl-field" id="glf-clip-wrap"><label for="glf-clip">Grass clippings</label><select id="glf-clip">' +
					opts([['left', 'Left on the lawn'], ['removed', 'Bagged and removed']], state.clippings) + '</select></div>' +
			'</div>' +
		'</div>' +
		'<div class="gl-results gl-print-area" id="glf-results" aria-live="polite"></div>';

	function renderAreas() {
		el('glf-areas').innerHTML = state.areas.map(function (a, i) {
			return '<div class="gl-item" data-i="' + i + '" style="padding:10px 14px">' +
				'<div class="gl-grid" style="align-items:end">' +
					'<div class="gl-field"><label for="glf-an-' + i + '">Area name</label><input id="glf-an-' + i + '" type="text" data-f="name" maxlength="40" value="' + esc(a.name) + '" style="width:100%;min-height:40px;padding:8px 10px;font:inherit;border:1px solid #b9c7bb;border-radius:7px"></div>' +
					'<div class="gl-field"><label for="glf-as-' + i + '">Square feet</label><input id="glf-as-' + i + '" type="number" min="0" step="10" inputmode="numeric" data-f="sqft" placeholder="length × width" value="' + esc(a.sqft) + '"></div>' +
				'</div>' +
				(state.areas.length > 1 ? '<button type="button" class="gl-link-btn" data-remove="' + i + '">Remove</button>' : '') +
			'</div>';
		}).join('');
	}
	el('glf-areas').addEventListener('input', function (e) {
		var item = e.target.closest('.gl-item'), f = e.target.getAttribute('data-f');
		if (!item || !f) return;
		state.areas[+item.getAttribute('data-i')][f] = e.target.value;
		update();
	});
	el('glf-areas').addEventListener('click', function (e) {
		var r = e.target.getAttribute('data-remove');
		if (r === null) return;
		state.areas.splice(+r, 1); renderAreas(); update();
	});
	el('glf-add').addEventListener('click', function () {
		if (state.areas.length >= 12) return;
		state.areas.push({ name: 'Area ' + (state.areas.length + 1), sqft: '' });
		renderAreas(); update();
	});

	[['glf-n', 'n'], ['glf-p', 'p'], ['glf-k', 'k'], ['glf-bag', 'bag'], ['glf-price', 'price'], ['glf-custom', 'customRate']].forEach(function (pair) {
		el(pair[0]).addEventListener('input', function () { state[pair[1]] = this.value; update(); });
	});
	[['glf-release', 'release'], ['glf-rate', 'rate'], ['glf-grass', 'grass'], ['glf-clip', 'clippings']].forEach(function (pair) {
		el(pair[0]).addEventListener('change', function () { state[pair[1]] = this.value; update(); });
	});

	function update() {
		save();
		el('glf-custom-wrap').style.display = state.rate === 'custom' ? '' : 'none';
		el('glf-clip-wrap').style.display = state.grass === 'cool' ? '' : 'none';

		var box = el('glf-results');
		var area = state.areas.reduce(function (s, a) { return s + (num(a.sqft) > 0 ? num(a.sqft) : 0); }, 0);
		var n = num(state.n), p = num(state.p) || 0, k = num(state.k) || 0, bag = num(state.bag), price = num(state.price);
		var rate = state.rate === 'custom' ? num(state.customRate) : num(state.rate);

		if (!(area > 0)) { box.innerHTML = '<p class="gl-warn">Enter the square footage of your lawn to see how much to apply.</p>'; return; }
		if (!(n > 0)) { box.innerHTML = '<p class="gl-warn">This product has no nitrogen (the first number is 0), so a nitrogen-based rate can’t be calculated. Follow the bag’s directions or your soil test instead.</p>'; return; }
		if (!(rate > 0)) { box.innerHTML = '<p class="gl-warn">Enter a nitrogen rate.</p>'; return; }

		var per1000 = rate / (n / 100);
		var totalLb = per1000 * area / 1000;
		var bags = bag > 0 ? Math.ceil(totalLb / bag - EPS) : NaN;
		var leftover = bag > 0 ? bags * bag - totalLb : NaN;

		// Yearly plan
		var g = GRASS[state.grass], yearLow, yearHigh;
		if (state.grass === 'cool') { yearLow = yearHigh = state.clippings === 'left' ? 2 : 3; }
		else { yearLow = g.low; yearHigh = g.high; }
		var appsLow = Math.max(1, Math.round(yearLow / rate)), appsHigh = Math.max(1, Math.round(yearHigh / rate));
		var appsText = appsLow === appsHigh ? appsLow : appsLow + '–' + appsHigh;
		var yearProduct = per1000 * yearHigh * area / 1000 / rate;

		var warn = '';
		if (state.release === 'quick' && rate > 1 + EPS) {
			warn += '<p class="gl-warn">That is more than 1 lb of quick-release nitrogen per 1,000 sq ft in one go. Extension guides recommend staying at or below about 1 lb (Virginia Tech says 0.9) to avoid burning the lawn and wasting nitrogen.</p>';
		}
		if (p > 0) {
			warn += '<p class="gl-note">This product contains phosphate. Some states restrict phosphorus on established lawns unless a soil test shows a need.</p>';
		}

		box.innerHTML =
			'<h3>How much to spread</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + fmt(per1000, 2) + ' lb</b><span>of product per 1,000 sq ft</span></div>' +
				'<div class="gl-stat"><b>' + fmt(totalLb, 1) + ' lb</b><span>for ' + fmt(area, 0) + ' sq ft</span></div>' +
				'<div class="gl-stat"><b>' + (isFinite(bags) ? bags + ' bag' + (bags === 1 ? '' : 's') : '–') + '</b><span>' + (isFinite(leftover) ? fmt(leftover, 1) + ' lb left over' : 'enter bag weight') + '</span></div>' +
				(isFinite(price) && isFinite(bags) ? '<div class="gl-stat"><b>' + money(bags * price) + '</b><span>this application</span></div>' : '') +
			'</div>' +
			'<div class="gl-table-wrap"><table><thead><tr><th>Nutrient delivered</th><th>Per 1,000 sq ft</th><th>Whole lawn</th></tr></thead><tbody>' +
				'<tr><td>Nitrogen (N)</td><td>' + fmt(rate, 2) + ' lb</td><td>' + fmt(rate * area / 1000, 2) + ' lb</td></tr>' +
				'<tr><td>Phosphate (P₂O₅)</td><td>' + fmt(per1000 * p / 100, 2) + ' lb</td><td>' + fmt(totalLb * p / 100, 2) + ' lb</td></tr>' +
				'<tr><td>Potash (K₂O)</td><td>' + fmt(per1000 * k / 100, 2) + ' lb</td><td>' + fmt(totalLb * k / 100, 2) + ' lb</td></tr>' +
			'</tbody></table></div>' +
			warn +
			'<h3 style="margin-top:18px">Your yearly plan</h3>' +
			'<p>' + esc(g.label.split(' (')[0]) + ' lawns typically need about <strong>' + (yearLow === yearHigh ? fmt(yearLow) : fmt(yearLow) + '–' + fmt(yearHigh)) + ' lb of nitrogen per 1,000 sq ft a year</strong>. At ' + fmt(rate, 2) + ' lb per feeding, that is <strong>' + appsText + ' feeding' + (appsHigh === 1 ? '' : 's') + '</strong>, spread across ' + esc(g.when) + '. ' +
				'A full year at the upper figure uses about <strong>' + fmt(yearProduct, 0) + ' lb</strong> of this product' + (bag > 0 ? ' (' + Math.ceil(yearProduct / bag - EPS) + ' bags)' : '') + '.</p>' +
			'<p class="gl-note">Spread in two passes at half the rate, the second at right angles to the first. Your bag’s spreader settings chart tells you which setting delivers the rate above.</p>' +
			'<div class="gl-actions gl-noprint">' +
				'<button type="button" class="gl-btn" id="glf-print">Print</button>' +
				'<button type="button" class="gl-btn gl-ghost" id="glf-reset">Start over</button>' +
			'</div>';

		el('glf-print').addEventListener('click', function () {
			document.body.classList.add('gl-printing'); window.print();
			setTimeout(function () { document.body.classList.remove('gl-printing'); }, 500);
		});
		el('glf-reset').addEventListener('click', function () {
			state = JSON.parse(JSON.stringify(defaults));
			['n', 'p', 'k', 'bag'].forEach(function (f) { el('glf-' + f).value = state[f]; });
			el('glf-price').value = ''; el('glf-custom').value = '';
			el('glf-release').value = state.release; el('glf-rate').value = state.rate;
			el('glf-grass').value = state.grass; el('glf-clip').value = state.clippings;
			renderAreas(); update();
		});
	}

	renderAreas();
	update();
})();
