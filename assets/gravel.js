/* Gamely Labs: Gravel Calculator (cubic yards, tons and bags) */
(function () {
	'use strict';
	var root = document.getElementById('gl-gravel');
	if (!root || !window.GLC) return;
	var G = window.GLC;

	// Typical weights in tons per cubic yard. Crushed stone and road base: Select Sand & Gravel.
	// Weight varies by quarry, rock type and moisture, so the figure is editable.
	var STONES = {
		crushed: { label: 'Crushed stone (#57, #67, ¾ in clean)', tpy: 1.4 },
		roadbase: { label: 'Crusher run / road base (with fines)', tpy: 1.5 },
		pea: { label: 'Pea gravel or river rock', tpy: 1.4 },
		lava: { label: 'Lava rock', tpy: 0.8 },
		custom: { label: 'My supplier’s figure', tpy: null }
	};
	var DEPTHS = [[2, '2 in: thin decorative layer'], [3, '3 in: walkway or patio surface'], [4, '4 in: driveway top layer'], [6, '6 in: driveway base layer'], ['custom', 'Custom depth']];

	var st = G.store('gl_gravel_v1', {
		areas: [{ name: 'Garden path', shape: 'rect', l: 30, w: 3, dia: '', inner: 1, sqft: '', qty: 1 }],
		depth: 3, customDepth: '', stone: 'crushed', customTpy: '', extra: 5, bag: 0.5, priceTon: '', priceYard: '', priceBag: ''
	}, function (s) { return Array.isArray(s.areas) && s.areas.length; });
	var S = st.state;
	var el = function (id) { return document.getElementById(id); };

	root.innerHTML =
		'<div class="gl-step"><h3><span class="gl-step-num">1</span>Areas to cover</h3><div id="glgr-areas"></div></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">2</span>Stone and depth</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glgr-stone">Type of stone</label><select id="glgr-stone">' + G.opts(Object.keys(STONES).map(function (k) { return [k, STONES[k].label]; }), S.stone) + '</select><span class="gl-hint" id="glgr-tpy-hint"></span></div>' +
			'<div class="gl-field" id="glgr-tpy-wrap"><label for="glgr-tpy">Tons per cubic yard</label><input id="glgr-tpy" type="number" min="0.3" max="3" step="0.05" inputmode="decimal" value="' + G.esc(S.customTpy) + '" placeholder="e.g. 1.45"></div>' +
			'<div class="gl-field"><label for="glgr-depth">Depth</label><select id="glgr-depth">' + G.opts(DEPTHS, S.depth) + '</select></div>' +
			'<div class="gl-field" id="glgr-cdepth-wrap"><label for="glgr-cdepth">Depth (inches)</label><input id="glgr-cdepth" type="number" min="0.5" max="24" step="0.5" inputmode="decimal" value="' + G.esc(S.customDepth) + '"></div>' +
			'<div class="gl-field"><label for="glgr-extra">Extra for compaction</label><select id="glgr-extra">' + G.opts([[0, 'None (loose decorative stone)'], [5, '5%'], [10, '10% (compacted base)']], S.extra) + '</select></div>' +
		'</div></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">3</span>Prices (optional)</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glgr-pton">Per ton ($)</label><input id="glgr-pton" type="number" min="0" step="1" inputmode="decimal" value="' + G.esc(S.priceTon) + '"></div>' +
			'<div class="gl-field"><label for="glgr-pyard">Per cubic yard ($)</label><input id="glgr-pyard" type="number" min="0" step="1" inputmode="decimal" value="' + G.esc(S.priceYard) + '"></div>' +
			'<div class="gl-field"><label for="glgr-bag">Bag size</label><select id="glgr-bag">' + G.opts([[0.5, '0.5 cu ft'], [0.75, '0.75 cu ft'], [1, '1 cu ft']], S.bag) + '</select></div>' +
			'<div class="gl-field"><label for="glgr-pbag">Per bag ($)</label><input id="glgr-pbag" type="number" min="0" step="0.01" inputmode="decimal" value="' + G.esc(S.priceBag) + '"></div>' +
		'</div></div>' +
		'<div class="gl-results gl-print-area" id="glgr-results" aria-live="polite"></div>';

	var areasUI = G.areas(el('glgr-areas'), function () { return S.areas; }, { prefix: 'glgra-', shapes: ['rect', 'circle', 'sqft'], newName: 'Area', onChange: update });

	el('glgr-stone').addEventListener('change', function () { S.stone = this.value; update(); });
	el('glgr-depth').addEventListener('change', function () { S.depth = this.value === 'custom' ? 'custom' : +this.value; update(); });
	el('glgr-extra').addEventListener('change', function () { S.extra = +this.value; update(); });
	el('glgr-bag').addEventListener('change', function () { S.bag = +this.value; update(); });
	[['glgr-tpy', 'customTpy'], ['glgr-cdepth', 'customDepth'], ['glgr-pton', 'priceTon'], ['glgr-pyard', 'priceYard'], ['glgr-pbag', 'priceBag']].forEach(function (p) {
		el(p[0]).addEventListener('input', function () { S[p[1]] = this.value; update(); });
	});

	function update() {
		st.save();
		var custom = S.stone === 'custom';
		el('glgr-tpy-wrap').style.display = custom ? '' : 'none';
		el('glgr-cdepth-wrap').style.display = S.depth === 'custom' ? '' : 'none';
		el('glgr-tpy-hint').textContent = custom ? 'Ask your supplier for their figure' : 'Typical: ' + STONES[S.stone].tpy + ' tons per cubic yard';

		var box = el('glgr-results');
		var sqft = G.totalSqft(S.areas);
		var depth = S.depth === 'custom' ? G.num(S.customDepth) : S.depth;
		var tpy = custom ? G.num(S.customTpy) : STONES[S.stone].tpy;
		if (!(sqft > 0)) { box.innerHTML = '<p class="gl-warn">Enter the size of the area you want to cover.</p>'; return; }
		if (!(depth > 0)) { box.innerHTML = '<p class="gl-warn">Enter a depth in inches.</p>'; return; }
		if (!(tpy > 0)) { box.innerHTML = '<p class="gl-warn">Enter your supplier’s weight in tons per cubic yard.</p>'; return; }

		var cuft = sqft * depth / 12 * (1 + S.extra / 100);
		var yd = cuft / 27, tons = yd * tpy;
		var bags = G.ceil(cuft / S.bag);
		var pTon = G.num(S.priceTon), pYard = G.num(S.priceYard), pBag = G.num(S.priceBag);
		var costs = [];
		if (isFinite(pTon)) costs.push(['by the ton (' + G.fmt(G.ceil(tons * 2) / 2, 1) + ' t)', G.ceil(tons * 2) / 2 * pTon]);
		if (isFinite(pYard)) costs.push(['by the yard (' + G.fmt(G.ceil(yd * 2) / 2, 1) + ' yd)', G.ceil(yd * 2) / 2 * pYard]);
		if (isFinite(pBag)) costs.push(['in bags (' + bags + ')', bags * pBag]);
		var best = costs.length > 1 ? costs.reduce(function (a, b) { return b[1] < a[1] ? b : a; }) : null;

		box.innerHTML =
			'<h3>Gravel you need</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + G.fmt(tons, 2) + ' t</b><span>tons (at ' + G.fmt(tpy) + ' t per yd³)</span></div>' +
				'<div class="gl-stat"><b>' + G.fmt(yd, 2) + ' yd³</b><span>cubic yards</span></div>' +
				'<div class="gl-stat"><b>' + G.fmt(bags, 0) + '</b><span>' + G.plural(bags, 'bag', 'bags') + ' of ' + S.bag + ' cu ft</span></div>' +
			'</div>' +
			(costs.length ? '<div class="gl-stats">' + costs.map(function (c) {
				return '<div class="gl-stat"><b>' + G.money(c[1]) + '</b><span>' + G.esc(c[0]) + (best && best === c ? ' · cheapest' : '') + '</span></div>';
			}).join('') + '</div>' : '') +
			'<div class="gl-table-wrap"><table><tbody>' +
				'<tr><td>Area</td><td>' + G.fmt(sqft, 0) + ' sq ft at ' + G.fmt(depth) + ' in deep</td></tr>' +
				'<tr><td>Volume</td><td>' + G.fmt(cuft, 1) + ' cu ft' + (S.extra ? ' (includes ' + S.extra + '% for compaction)' : '') + '</td></tr>' +
			'</tbody></table></div>' +
			'<p class="gl-note">Suppliers usually sell bulk stone by the ton or the yard in half-unit steps, so costs are rounded up to the next half ton or yard. Stone weight varies by quarry and moisture; ask your supplier for their tons-per-yard figure if you’re buying by weight.</p>' +
			'<div class="gl-actions gl-noprint"><button type="button" class="gl-btn" id="glgr-print">Print</button><button type="button" class="gl-btn gl-ghost" id="glgr-reset">Start over</button></div>';

		el('glgr-print').addEventListener('click', G.printArea);
		el('glgr-reset').addEventListener('click', function () {
			S = st.reset();
			el('glgr-stone').value = S.stone; el('glgr-depth').value = String(S.depth); el('glgr-extra').value = String(S.extra); el('glgr-bag').value = String(S.bag);
			['glgr-tpy', 'glgr-cdepth', 'glgr-pton', 'glgr-pyard', 'glgr-pbag'].forEach(function (id) { el(id).value = ''; });
			areasUI.render(); update();
		});
	}

	update();
})();
