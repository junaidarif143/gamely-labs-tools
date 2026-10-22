/* Gamely Labs: Compost Calculator */
(function () {
	'use strict';
	var root = document.getElementById('gl-compost');
	if (!root || !window.GLC) return;
	var G = window.GLC;

	// Depths from Oregon State University Extension, "How to use compost in gardens and landscapes" (EM 9308).
	var USES = {
		newbed: { label: 'New garden bed (mix into soil)', depth: 3, range: '3–4 in' },
		topbed: { label: 'Established bed (yearly top-up)', depth: 1, range: '¼–1 in' },
		newlawn: { label: 'New lawn (mix in before seeding or sod)', depth: 2, range: '1–2 in' },
		toplawn: { label: 'Topdress an existing lawn', depth: 0.25, range: '¼–½ in' },
		custom: { label: 'Custom depth', depth: null, range: '' }
	};

	var st = G.store('gl_compost_v1', {
		areas: [{ name: 'Vegetable garden', shape: 'rect', l: 20, w: 10, dia: '', inner: 1, sqft: '', qty: 1 }],
		use: 'newbed', custom: 2, bag: 1, priceBag: '', priceYard: ''
	}, function (s) { return Array.isArray(s.areas) && s.areas.length; });
	var S = st.state;
	var el = function (id) { return document.getElementById(id); };

	root.innerHTML =
		'<div class="gl-step"><h3><span class="gl-step-num">1</span>What are you using compost for?</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glc-use">Project</label><select id="glc-use">' + G.opts(Object.keys(USES).map(function (k) { return [k, USES[k].label]; }), S.use) + '</select><span class="gl-hint" id="glc-range"></span></div>' +
			'<div class="gl-field" id="glc-custom-wrap"><label for="glc-custom">Depth (inches)</label><input id="glc-custom" type="number" min="0.1" max="12" step="0.25" inputmode="decimal" value="' + G.esc(S.custom) + '"></div>' +
		'</div></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">2</span>Area to cover</h3><div id="glc-areas"></div></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">3</span>Buying</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glc-bag">Bag size</label><select id="glc-bag">' + G.opts([[0.75, '0.75 cu ft'], [1, '1 cu ft'], [1.5, '1.5 cu ft'], [2, '2 cu ft']], S.bag) + '</select></div>' +
			'<div class="gl-field"><label for="glc-pbag">Price per bag ($, optional)</label><input id="glc-pbag" type="number" min="0" step="0.01" inputmode="decimal" value="' + G.esc(S.priceBag) + '"></div>' +
			'<div class="gl-field"><label for="glc-pyard">Bulk price per cu yd ($, optional)</label><input id="glc-pyard" type="number" min="0" step="1" inputmode="decimal" value="' + G.esc(S.priceYard) + '"></div>' +
		'</div></div>' +
		'<div class="gl-results gl-print-area" id="glc-results" aria-live="polite"></div>';

	var areasUI = G.areas(el('glc-areas'), function () { return S.areas; }, { prefix: 'glca-', shapes: ['rect', 'circle', 'sqft'], newName: 'Area', onChange: update });

	el('glc-use').addEventListener('change', function () { S.use = this.value; update(); });
	el('glc-bag').addEventListener('change', function () { S.bag = +this.value; update(); });
	[['glc-custom', 'custom'], ['glc-pbag', 'priceBag'], ['glc-pyard', 'priceYard']].forEach(function (p) {
		el(p[0]).addEventListener('input', function () { S[p[1]] = this.value; update(); });
	});

	function update() {
		st.save();
		var u = USES[S.use];
		el('glc-custom-wrap').style.display = S.use === 'custom' ? '' : 'none';
		el('glc-range').textContent = u.range ? 'Recommended: ' + u.range + ' (using ' + u.depth + ' in)' : 'Enter your own depth';

		var box = el('glc-results');
		var depth = S.use === 'custom' ? G.num(S.custom) : u.depth;
		var sqft = G.totalSqft(S.areas);
		if (!(sqft > 0)) { box.innerHTML = '<p class="gl-warn">Enter the size of the area you want to cover.</p>'; return; }
		if (!(depth > 0)) { box.innerHTML = '<p class="gl-warn">Enter a depth in inches.</p>'; return; }

		var cuft = sqft * depth / 12, yd = cuft / 27, ydOrder = G.ceil(yd * 2) / 2;
		var bags = G.ceil(cuft / S.bag);
		var pBag = G.num(S.priceBag), pYard = G.num(S.priceYard);
		var per1000 = depth * 1000 / 12 / 27;

		var cost = '';
		if (isFinite(pBag) || isFinite(pYard)) {
			var cb = bags * pBag, cy = ydOrder * pYard;
			var cheaper = isFinite(pBag) && isFinite(pYard) ? (cb <= cy ? 'bags' : 'bulk') : '';
			cost = '<div class="gl-stats" style="margin-top:14px">' +
				(isFinite(pBag) ? '<div class="gl-stat"><b>' + G.money(cb) + '</b><span>bagged' + (cheaper === 'bags' ? ' · cheaper' : '') + '</span></div>' : '') +
				(isFinite(pYard) ? '<div class="gl-stat"><b>' + G.money(cy) + '</b><span>bulk (' + G.fmt(ydOrder, 1) + ' yd), before delivery' + (cheaper === 'bulk' ? ' · cheaper' : '') + '</span></div>' : '') +
			'</div>';
		}
		var tip = {
			newbed: 'Spread it evenly, then dig or till it into the top 6–8 inches of soil.',
			topbed: 'Spread it on the surface around plants; worms and watering will work it in.',
			newlawn: 'Work it into the top 4–6 inches of soil, then rake level before seeding or laying sod.',
			toplawn: 'Rake it into the grass so the blades still show. It works best right after aerating.',
			custom: ''
		}[S.use];

		box.innerHTML =
			'<h3>Compost you need</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + G.fmt(cuft, 1) + ' ft³</b><span>over ' + G.fmt(sqft, 0) + ' sq ft at ' + G.fmt(depth) + ' in</span></div>' +
				'<div class="gl-stat"><b>' + G.fmt(yd, 2) + ' yd³</b><span>for bulk (order ' + G.fmt(ydOrder, 1) + ')</span></div>' +
				'<div class="gl-stat"><b>' + bags + '</b><span>' + G.plural(bags, 'bag', 'bags') + ' of ' + S.bag + ' cu ft</span></div>' +
			'</div>' +
			cost +
			'<p class="gl-note">That rate is ' + G.fmt(per1000, 2) + ' cubic yards per 1,000 sq ft. ' + G.esc(tip) + '</p>' +
			(cuft > 54 ? '<p class="gl-note">Over 2 cubic yards, bulk compost from a landscape supplier is usually far cheaper than bags.</p>' : '') +
			'<div class="gl-actions gl-noprint"><button type="button" class="gl-btn" id="glc-print">Print</button><button type="button" class="gl-btn gl-ghost" id="glc-reset">Start over</button></div>';

		el('glc-print').addEventListener('click', G.printArea);
		el('glc-reset').addEventListener('click', function () {
			S = st.reset();
			el('glc-use').value = S.use; el('glc-bag').value = String(S.bag); el('glc-custom').value = S.custom;
			el('glc-pbag').value = ''; el('glc-pyard').value = '';
			areasUI.render(); update();
		});
	}

	update();
})();
