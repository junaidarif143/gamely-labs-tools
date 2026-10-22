/* Gamely Labs: Lawn Lime Calculator */
(function () {
	'use strict';

	var root = document.getElementById('gl-lime');
	if (!root) return;

	var STORE_KEY = 'gl_lime_v1';
	var EPS = 1e-9;
	var MAX_PER_APP = 50; // lb per 1,000 sq ft per application on established turf (Virginia Cooperative Extension)
	var SQFT_PER_ACRE = 43560;

	var UNITS = {
		lb1000: { label: 'lb per 1,000 sq ft', toPer1000: function (v) { return v; } },
		lbacre: { label: 'lb per acre', toPer1000: function (v) { return v * 1000 / SQFT_PER_ACRE; } },
		tonacre: { label: 'tons per acre', toPer1000: function (v) { return v * 2000 * 1000 / SQFT_PER_ACRE; } }
	};

	var defaults = {
		rec: '', unit: 'lb1000',
		area: '',
		labCce: 100, prodCce: 100,
		bag: 40, price: '',
		lawn: 'established'
	};
	var state = load();

	function load() {
		try {
			var raw = window.localStorage.getItem(STORE_KEY);
			if (raw) return Object.assign({}, defaults, JSON.parse(raw) || {});
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
			'<h3><span class="gl-step-num">1</span>Your soil test recommendation</h3>' +
			'<div class="gl-grid">' +
				'<div class="gl-field"><label for="gll-rec">Lime recommended</label><input id="gll-rec" type="number" min="0" step="any" inputmode="decimal" placeholder="from your report" value="' + esc(state.rec) + '"></div>' +
				'<div class="gl-field"><label for="gll-unit">Unit on the report</label><select id="gll-unit">' +
					opts(Object.keys(UNITS).map(function (k) { return [k, UNITS[k].label]; }), state.unit) + '</select></div>' +
				'<div class="gl-field"><label for="gll-labcce">Report assumes CCE of (%)</label><input id="gll-labcce" type="number" min="1" max="200" step="1" inputmode="decimal" value="' + esc(state.labCce) + '"><span class="gl-hint">Usually 100; check your report</span></div>' +
			'</div>' +
		'</div>' +
		'<div class="gl-step">' +
			'<h3><span class="gl-step-num">2</span>Your lawn and product</h3>' +
			'<div class="gl-grid">' +
				'<div class="gl-field"><label for="gll-area">Lawn area (sq ft)</label><input id="gll-area" type="number" min="0" step="10" inputmode="numeric" placeholder="e.g. 5000" value="' + esc(state.area) + '"></div>' +
				'<div class="gl-field"><label for="gll-lawn">Lawn</label><select id="gll-lawn">' +
					opts([['established', 'Established lawn'], ['new', 'New lawn (before seeding or sod)']], state.lawn) + '</select></div>' +
				'<div class="gl-field"><label for="gll-cce">Product CCE (%)</label><input id="gll-cce" type="number" min="1" max="200" step="1" inputmode="decimal" value="' + esc(state.prodCce) + '"><span class="gl-hint">“Calcium carbonate equivalent” on the bag</span></div>' +
				'<div class="gl-field"><label for="gll-bag">Bag weight (lb)</label><input id="gll-bag" type="number" min="1" step="1" inputmode="decimal" value="' + esc(state.bag) + '"></div>' +
				'<div class="gl-field"><label for="gll-price">Price per bag ($, optional)</label><input id="gll-price" type="number" min="0" step="0.01" inputmode="decimal" value="' + esc(state.price) + '"></div>' +
			'</div>' +
		'</div>' +
		'<div class="gl-results gl-print-area" id="gll-results" aria-live="polite"></div>';

	[['gll-rec', 'rec'], ['gll-labcce', 'labCce'], ['gll-area', 'area'], ['gll-cce', 'prodCce'], ['gll-bag', 'bag'], ['gll-price', 'price']].forEach(function (pair) {
		el(pair[0]).addEventListener('input', function () { state[pair[1]] = this.value; update(); });
	});
	[['gll-unit', 'unit'], ['gll-lawn', 'lawn']].forEach(function (pair) {
		el(pair[0]).addEventListener('change', function () { state[pair[1]] = this.value; update(); });
	});

	function update() {
		save();
		var box = el('gll-results');
		var rec = num(state.rec), area = num(state.area), labCce = num(state.labCce), cce = num(state.prodCce), bag = num(state.bag), price = num(state.price);

		if (!isFinite(rec)) { box.innerHTML = '<p class="gl-warn">Enter the lime recommendation from your soil test report.</p>'; return; }
		if (rec === 0) { box.innerHTML = '<p><strong>No lime needed.</strong> Your soil test doesn’t call for lime. Retest in 2 to 3 years.</p>'; return; }
		if (!(area > 0)) { box.innerHTML = '<p class="gl-warn">Enter your lawn area in square feet.</p>'; return; }
		if (!(labCce > 0) || !(cce > 0)) { box.innerHTML = '<p class="gl-warn">Enter the CCE values (use 100 if unsure).</p>'; return; }

		var recPer1000 = UNITS[state.unit].toPer1000(rec);
		var prodPer1000 = recPer1000 * labCce / cce;
		var totalLb = prodPer1000 * area / 1000;
		var bags = bag > 0 ? Math.ceil(totalLb / bag - EPS) : NaN;

		var apps = state.lawn === 'established' ? Math.max(1, Math.ceil(prodPer1000 / MAX_PER_APP - EPS)) : 1;
		var appPer1000 = prodPer1000 / apps, appLb = totalLb / apps;

		var plan = '';
		for (var i = 0; i < apps; i++) {
			var when = i === 0 ? 'Now' : (apps > 1 ? 'About ' + (i * 3) + ' months later' : '');
			plan += '<tr><td><strong>Application ' + (i + 1) + '</strong><br><small>' + when + '</small></td>' +
				'<td>' + fmt(appPer1000, 1) + ' lb</td><td>' + fmt(appLb, 0) + ' lb' + (bag > 0 ? '<br><small>≈ ' + fmt(appLb / bag, 1) + ' bags</small>' : '') + '</td></tr>';
		}

		var warn = '';
		if (cce < 50 || cce > 115) warn += '<p class="gl-warn">A CCE of ' + fmt(cce) + '% is unusual for lawn lime; most products list roughly 80–110%. Double-check the label.</p>';
		if (state.lawn === 'new' && prodPer1000 > 150) warn += '<p class="gl-warn">That is a large amount. Confirm the unit on your soil report before buying.</p>';
		if (state.unit !== 'lb1000') {
			warn += '<p class="gl-note">' + fmt(rec) + ' ' + UNITS[state.unit].label + ' = ' + fmt(recPer1000, 1) + ' lb per 1,000 sq ft.</p>';
		}

		box.innerHTML =
			'<h3>Lime to buy</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + fmt(prodPer1000, 1) + ' lb</b><span>product per 1,000 sq ft</span></div>' +
				'<div class="gl-stat"><b>' + fmt(totalLb, 0) + ' lb</b><span>for ' + fmt(area, 0) + ' sq ft</span></div>' +
				'<div class="gl-stat"><b>' + (isFinite(bags) ? bags + ' bags' : '–') + '</b><span>of ' + (bag > 0 ? fmt(bag) + ' lb' : '–') + '</span></div>' +
				(isFinite(price) && isFinite(bags) ? '<div class="gl-stat"><b>' + money(bags * price) + '</b><span>total cost</span></div>' : '') +
			'</div>' +
			'<h3 style="margin-top:6px">Application schedule</h3>' +
			(state.lawn === 'established'
				? (apps > 1
					? '<p>That is more than ' + MAX_PER_APP + ' lb per 1,000 sq ft, the most an established lawn should get at once, so split it into <strong>' + apps + ' applications</strong> spaced 1 to 6 months apart.</p>'
					: '<p>This is within the ' + MAX_PER_APP + ' lb per 1,000 sq ft limit for one application, so you can apply it all at once.</p>')
				: '<p>For a new lawn, spread the full amount and work it into the top few inches of soil before seeding or laying sod.</p>') +
			'<div class="gl-table-wrap"><table><thead><tr><th>When</th><th>Per 1,000 sq ft</th><th>Whole lawn</th></tr></thead><tbody>' + plan + '</tbody></table></div>' +
			warn +
			'<div class="gl-actions gl-noprint">' +
				'<button type="button" class="gl-btn" id="gll-print">Print</button>' +
				'<button type="button" class="gl-btn gl-ghost" id="gll-reset">Start over</button>' +
			'</div>';

		el('gll-print').addEventListener('click', function () {
			document.body.classList.add('gl-printing'); window.print();
			setTimeout(function () { document.body.classList.remove('gl-printing'); }, 500);
		});
		el('gll-reset').addEventListener('click', function () {
			state = JSON.parse(JSON.stringify(defaults));
			el('gll-rec').value = ''; el('gll-area').value = ''; el('gll-price').value = '';
			el('gll-labcce').value = state.labCce; el('gll-cce').value = state.prodCce; el('gll-bag').value = state.bag;
			el('gll-unit').value = state.unit; el('gll-lawn').value = state.lawn;
			update();
		});
	}

	update();
})();
