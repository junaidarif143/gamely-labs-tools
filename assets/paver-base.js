/* Gamely Labs: Paver Base Calculator (base gravel, bedding sand, excavation) */
(function () {
	'use strict';
	var root = document.getElementById('gl-paver-base');
	if (!root || !window.GLC) return;
	var G = window.GLC;

	// Unilock: 4–6 in of compacted base for walkways and patios, 8–12 in for driveways;
	// extend the base past the paver edge by the base depth; compact in lifts of 3–4 in.
	var USES = {
		patio: { label: 'Walkway or patio', depths: [4, 5, 6], def: 6 },
		drive: { label: 'Driveway', depths: [8, 10, 12], def: 8 }
	};
	var LIFT = 4;
	var BASE_TPY = 1.4; // road base, Select Sand & Gravel estimate

	var st = G.store('gl_paver_base_v1', {
		areas: [{ name: 'Patio', shape: 'rect', l: 16, w: 12, dia: '', inner: 1, sqft: '', qty: 1 }],
		use: 'patio', base: 6, sand: 1, paver: 2.375, extra: 10, bag: 0.5
	}, function (s) { return Array.isArray(s.areas) && s.areas.length; });
	var S = st.state;
	var el = function (id) { return document.getElementById(id); };

	function baseOpts() {
		return G.opts(USES[S.use].depths.map(function (d) { return [d, d + ' inches']; }), S.base);
	}

	root.innerHTML =
		'<div class="gl-step"><h3><span class="gl-step-num">1</span>Your project</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glpb-use">Type of project</label><select id="glpb-use">' + G.opts(Object.keys(USES).map(function (k) { return [k, USES[k].label]; }), S.use) + '</select></div>' +
			'<div class="gl-field"><label for="glpb-base">Compacted base depth</label><select id="glpb-base">' + baseOpts() + '</select><span class="gl-hint" id="glpb-base-hint"></span></div>' +
			'<div class="gl-field"><label for="glpb-sand">Bedding sand (inches)</label><input id="glpb-sand" type="number" min="0.5" max="2" step="0.25" inputmode="decimal" value="' + G.esc(S.sand) + '"><span class="gl-hint">Usually about 1 in</span></div>' +
			'<div class="gl-field"><label for="glpb-paver">Paver thickness (inches)</label><input id="glpb-paver" type="number" min="1" max="6" step="0.125" inputmode="decimal" value="' + G.esc(S.paver) + '"><span class="gl-hint">60 mm pavers ≈ 2⅜ in</span></div>' +
		'</div></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">2</span>Paved area</h3><div id="glpb-areas"></div><p class="gl-note">Enter the finished paver size. The calculator widens the base on every side automatically.</p></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">3</span>Buying</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glpb-extra">Extra base for compaction</label><select id="glpb-extra">' + G.opts([[5, '5%'], [10, '10%']], S.extra) + '</select></div>' +
			'<div class="gl-field"><label for="glpb-bag">Bag size (base and sand)</label><select id="glpb-bag">' + G.opts([[0.5, '0.5 cu ft'], [0.75, '0.75 cu ft']], S.bag) + '</select></div>' +
		'</div></div>' +
		'<div class="gl-results gl-print-area" id="glpb-results" aria-live="polite"></div>';

	var areasUI = G.areas(el('glpb-areas'), function () { return S.areas; }, { prefix: 'glpba-', shapes: ['rect', 'circle', 'sqft'], newName: 'Area', onChange: update });

	el('glpb-use').addEventListener('change', function () {
		S.use = this.value; S.base = USES[S.use].def;
		el('glpb-base').innerHTML = baseOpts();
		update();
	});
	el('glpb-base').addEventListener('change', function () { S.base = +this.value; update(); });
	el('glpb-extra').addEventListener('change', function () { S.extra = +this.value; update(); });
	el('glpb-bag').addEventListener('change', function () { S.bag = +this.value; update(); });
	[['glpb-sand', 'sand'], ['glpb-paver', 'paver']].forEach(function (p) {
		el(p[0]).addEventListener('input', function () { S[p[1]] = this.value; update(); });
	});

	// Base footprint: the paved area widened by the base depth on every side.
	function baseFootprint(a, ext) {
		var q = Math.max(1, Math.round(G.num(a.qty) || 1));
		if (a.shape === 'rect') return ((G.num(a.l) || 0) + 2 * ext) * ((G.num(a.w) || 0) + 2 * ext) * q;
		if (a.shape === 'circle') return Math.PI * Math.pow((G.num(a.dia) || 0) / 2 + ext, 2) * q;
		return null; // unknown shape: no perimeter to widen
	}

	function update() {
		st.save();
		el('glpb-base-hint').textContent = S.use === 'patio' ? 'Unilock guideline: 4–6 in' : 'Unilock guideline: 8–12 in';
		var box = el('glpb-results');
		var paved = G.totalSqft(S.areas);
		var sand = G.num(S.sand), paver = G.num(S.paver);
		if (!(paved > 0)) { box.innerHTML = '<p class="gl-warn">Enter the size of the paved area.</p>'; return; }
		if (!(sand > 0) || !(paver > 0)) { box.innerHTML = '<p class="gl-warn">Enter the bedding sand depth and paver thickness.</p>'; return; }

		var ext = S.base / 12; // feet added on each side
		var footprint = 0, approx = false;
		S.areas.forEach(function (a) {
			var f = baseFootprint(a, ext);
			if (f === null) { footprint += G.areaSqft(a).total; approx = true; }
			else footprint += f;
		});

		var baseCuft = footprint * S.base / 12 * (1 + S.extra / 100);
		var baseYd = baseCuft / 27, baseTons = baseYd * BASE_TPY;
		var sandCuft = paved * sand / 12, sandYd = sandCuft / 27;
		var dig = S.base + sand + paver;
		var digCuft = footprint * dig / 12, digYd = digCuft / 27;
		var lifts = Math.max(1, G.ceil(S.base / LIFT));

		box.innerHTML =
			'<h3>Materials and digging</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + G.fmt(baseTons, 1) + ' t</b><span>base gravel (' + G.fmt(baseYd, 2) + ' yd³)</span></div>' +
				'<div class="gl-stat"><b>' + G.fmt(sandYd, 2) + ' yd³</b><span>bedding sand (' + G.fmt(sandCuft, 1) + ' ft³)</span></div>' +
				'<div class="gl-stat"><b>' + G.fmt(dig, 2) + ' in</b><span>dig depth below finished surface</span></div>' +
			'</div>' +
			'<div class="gl-table-wrap"><table><thead><tr><th>Item</th><th>Amount</th><th>In bags of ' + S.bag + ' ft³</th></tr></thead><tbody>' +
				'<tr><td><strong>Base gravel</strong><br><small>' + S.base + ' in compacted, +' + S.extra + '%</small></td><td>' + G.fmt(baseCuft, 1) + ' ft³ · ' + G.fmt(baseYd, 2) + ' yd³ · ' + G.fmt(baseTons, 2) + ' t</td><td>' + G.ceil(baseCuft / S.bag) + '</td></tr>' +
				'<tr><td><strong>Bedding sand</strong><br><small>' + G.fmt(sand) + ' in under the pavers only</small></td><td>' + G.fmt(sandCuft, 1) + ' ft³ · ' + G.fmt(sandYd, 2) + ' yd³</td><td>' + G.ceil(sandCuft / S.bag) + '</td></tr>' +
				'<tr><td><strong>Soil to dig out</strong><br><small>' + G.fmt(dig, 2) + ' in over the base footprint</small></td><td>' + G.fmt(digCuft, 1) + ' ft³ · ' + G.fmt(digYd, 2) + ' yd³</td><td>–</td></tr>' +
				'<tr><td><strong>Base footprint</strong><br><small>paved area + ' + S.base + ' in each side</small></td><td>' + G.fmt(footprint, 0) + ' sq ft (paved: ' + G.fmt(paved, 0) + ' sq ft)</td><td>–</td></tr>' +
			'</tbody></table></div>' +
			'<p class="gl-note">Compact the base in <strong>' + lifts + ' ' + G.plural(lifts, 'layer', 'layers') + '</strong> of no more than ' + LIFT + ' inches each. Slope the surface 1–2% away from buildings (about ⅛ to ¼ inch per foot). Base weight uses ' + BASE_TPY + ' tons per cubic yard; check your supplier’s figure.</p>' +
			(approx ? '<p class="gl-warn">For areas entered as square feet, the base can’t be widened automatically. Add the extra width to your measurements or enter the shape as a rectangle.</p>' : '') +
			(baseYd > 2 ? '<p class="gl-note">At this size, bulk delivery by the ton or yard is usually far cheaper than bags.</p>' : '') +
			'<div class="gl-actions gl-noprint"><button type="button" class="gl-btn" id="glpb-print">Print</button><button type="button" class="gl-btn gl-ghost" id="glpb-reset">Start over</button></div>';

		el('glpb-print').addEventListener('click', G.printArea);
		el('glpb-reset').addEventListener('click', function () {
			S = st.reset();
			el('glpb-use').value = S.use; el('glpb-base').innerHTML = baseOpts(); el('glpb-sand').value = S.sand; el('glpb-paver').value = S.paver;
			el('glpb-extra').value = String(S.extra); el('glpb-bag').value = String(S.bag);
			areasUI.render(); update();
		});
	}

	update();
})();
