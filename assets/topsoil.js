/* Gamely Labs: Topsoil Calculator (leveling low spots, new lawns, filling areas) */
(function () {
	'use strict';
	var root = document.getElementById('gl-topsoil');
	if (!root || !window.GLC) return;
	var G = window.GLC;

	// Clemson Extension: topdress established lawns with no more than ½ inch at a time.
	var MAX_TOPDRESS = 0.5;
	var JOBS = {
		level: { label: 'Level low spots in a lawn', depth: 1, hint: 'Average depth of the dips' },
		newlawn: { label: 'Topsoil layer for a new lawn', depth: 4, hint: 'At least 4 in under new seed or sod' },
		fill: { label: 'Fill or raise an area', depth: 6, hint: 'Depth to add' }
	};

	var st = G.store('gl_topsoil_v1', {
		areas: [{ name: 'Low spot', shape: 'rect', l: 10, w: 6, dia: '', inner: 1, sqft: '', qty: 1 }],
		job: 'level', depth: 1.5, bag: 0.75, priceBag: '', priceYard: ''
	}, function (s) { return Array.isArray(s.areas) && s.areas.length; });
	var S = st.state;
	var el = function (id) { return document.getElementById(id); };

	root.innerHTML =
		'<div class="gl-step"><h3><span class="gl-step-num">1</span>What’s the job?</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glts-job">Project</label><select id="glts-job">' + G.opts(Object.keys(JOBS).map(function (k) { return [k, JOBS[k].label]; }), S.job) + '</select></div>' +
			'<div class="gl-field"><label for="glts-depth">Depth (inches)</label><input id="glts-depth" type="number" min="0.25" max="36" step="0.25" inputmode="decimal" value="' + G.esc(S.depth) + '"><span class="gl-hint" id="glts-depth-hint"></span></div>' +
		'</div></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">2</span>Areas</h3><div id="glts-areas"></div></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">3</span>Buying</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glts-bag">Bag size</label><select id="glts-bag">' + G.opts([[0.75, '0.75 cu ft'], [1, '1 cu ft'], [1.5, '1.5 cu ft'], [2, '2 cu ft']], S.bag) + '</select></div>' +
			'<div class="gl-field"><label for="glts-pbag">Price per bag ($, optional)</label><input id="glts-pbag" type="number" min="0" step="0.01" inputmode="decimal" value="' + G.esc(S.priceBag) + '"></div>' +
			'<div class="gl-field"><label for="glts-pyard">Bulk price per cu yd ($, optional)</label><input id="glts-pyard" type="number" min="0" step="1" inputmode="decimal" value="' + G.esc(S.priceYard) + '"></div>' +
		'</div></div>' +
		'<div class="gl-results gl-print-area" id="glts-results" aria-live="polite"></div>';

	var areasUI = G.areas(el('glts-areas'), function () { return S.areas; }, { prefix: 'gltsa-', shapes: ['rect', 'circle', 'sqft'], newName: 'Area', onChange: update });

	el('glts-job').addEventListener('change', function () {
		S.job = this.value;
		S.depth = JOBS[S.job].depth;
		el('glts-depth').value = S.depth;
		update();
	});
	el('glts-bag').addEventListener('change', function () { S.bag = +this.value; update(); });
	[['glts-depth', 'depth'], ['glts-pbag', 'priceBag'], ['glts-pyard', 'priceYard']].forEach(function (p) {
		el(p[0]).addEventListener('input', function () { S[p[1]] = this.value; update(); });
	});

	function update() {
		st.save();
		el('glts-depth-hint').textContent = JOBS[S.job].hint;
		var box = el('glts-results');
		var sqft = G.totalSqft(S.areas), depth = G.num(S.depth);
		if (!(sqft > 0)) { box.innerHTML = '<p class="gl-warn">Enter the size of the area.</p>'; return; }
		if (!(depth > 0)) { box.innerHTML = '<p class="gl-warn">Enter a depth in inches.</p>'; return; }

		var cuft = sqft * depth / 12, yd = cuft / 27, ydOrder = G.ceil(yd * 2) / 2;
		var bags = G.ceil(cuft / S.bag);
		var pBag = G.num(S.priceBag), pYard = G.num(S.priceYard);

		var plan = '';
		if (S.job === 'level') {
			var passes = Math.max(1, G.ceil(depth / MAX_TOPDRESS));
			var each = cuft / passes;
			plan = '<h3 style="margin-top:6px">Spread it in ' + passes + ' ' + G.plural(passes, 'pass', 'passes') + '</h3>' +
				'<p>Add no more than ½ inch at a time so the grass can grow up through it. That’s about <strong>' + G.fmt(each, 1) + ' cu ft</strong> (' + G.ceil(each / S.bag) + ' ' + G.plural(G.ceil(each / S.bag), 'bag', 'bags') + ') per pass. Wait until the grass has grown through each layer, usually a few weeks in the growing season, before adding the next.</p>' +
				(depth > 2 ? '<p class="gl-warn">For dips deeper than about 2 inches, many people instead lift the sod, fill underneath with soil, firm it, and lay the sod back down. It’s faster than many thin passes.</p>' : '');
		} else if (S.job === 'newlawn' && depth < 4) {
			plan = '<p class="gl-warn">The University of Nebraska–Lincoln recommends at least 4 inches of topsoil under a new lawn.</p>';
		}

		var cost = '';
		if (isFinite(pBag) || isFinite(pYard)) {
			var cb = bags * pBag, cy = ydOrder * pYard;
			var cheaper = isFinite(pBag) && isFinite(pYard) ? (cb <= cy ? 'bags' : 'bulk') : '';
			cost = '<div class="gl-stats">' +
				(isFinite(pBag) ? '<div class="gl-stat"><b>' + G.money(cb) + '</b><span>bagged' + (cheaper === 'bags' ? ' · cheaper' : '') + '</span></div>' : '') +
				(isFinite(pYard) ? '<div class="gl-stat"><b>' + G.money(cy) + '</b><span>bulk (' + G.fmt(ydOrder, 1) + ' yd), before delivery' + (cheaper === 'bulk' ? ' · cheaper' : '') + '</span></div>' : '') +
			'</div>';
		}

		box.innerHTML =
			'<h3>Topsoil you need</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + G.fmt(cuft, 1) + ' ft³</b><span>over ' + G.fmt(sqft, 0) + ' sq ft at ' + G.fmt(depth) + ' in</span></div>' +
				'<div class="gl-stat"><b>' + G.fmt(yd, 2) + ' yd³</b><span>for bulk (order ' + G.fmt(ydOrder, 1) + ')</span></div>' +
				'<div class="gl-stat"><b>' + bags + '</b><span>' + G.plural(bags, 'bag', 'bags') + ' of ' + S.bag + ' cu ft</span></div>' +
			'</div>' +
			cost + plan +
			'<p class="gl-note">Choose soil that matches what you already have: sand only on sandy soil, otherwise a screened topsoil. Fresh soil settles, so add a little extra when filling deep areas.</p>' +
			'<div class="gl-actions gl-noprint"><button type="button" class="gl-btn" id="glts-print">Print</button><button type="button" class="gl-btn gl-ghost" id="glts-reset">Start over</button></div>';

		el('glts-print').addEventListener('click', G.printArea);
		el('glts-reset').addEventListener('click', function () {
			S = st.reset();
			el('glts-job').value = S.job; el('glts-depth').value = S.depth; el('glts-bag').value = String(S.bag);
			el('glts-pbag').value = ''; el('glts-pyard').value = '';
			areasUI.render(); update();
		});
	}

	update();
})();
