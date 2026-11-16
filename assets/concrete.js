/* Gamely Labs: Concrete Calculator (slabs and post holes: cubic yards and bags) */
(function () {
	'use strict';
	var root = document.getElementById('gl-concrete');
	if (!root || !window.GLC) return;
	var G = window.GLC;

	// Yield per bag of QUIKRETE Concrete Mix (No. 1101), from the product data sheet.
	var BAGS = [[80, 0.60], [60, 0.45], [40, 0.30]];
	var THICK = [[3.5, '3½ in: walkway or patio (2×4 forms)'], [4, '4 in: patio, walkway, shed pad'], [5, '5 in: hot tub pad, heavier use'], [6, '6 in: driveway or heavy loads'], ['custom', 'Custom thickness']];

	var st = G.store('gl_concrete_v1', {
		mode: 'slab',
		areas: [{ name: 'Patio', shape: 'rect', l: 10, w: 10, dia: '', inner: 1, sqft: '', qty: 1 }],
		thick: 4, customThick: '', extra: 10,
		posts: 10, postShape: 'square', postW: 3.5, holeDia: 10, holeDepth: 30, gravel: 6,
		priceBag: '', priceYard: ''
	}, function (s) { return Array.isArray(s.areas) && s.areas.length; });
	var S = st.state;
	var el = function (id) { return document.getElementById(id); };

	root.innerHTML =
		'<div class="gl-step"><h3><span class="gl-step-num">1</span>What are you pouring?</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glcc-mode">Project</label><select id="glcc-mode">' + G.opts([['slab', 'Slab, patio, walkway or pad'], ['posts', 'Fence or deck post holes']], S.mode) + '</select></div>' +
		'</div></div>' +
		'<div id="glcc-slab">' +
			'<div class="gl-step"><h3><span class="gl-step-num">2</span>Areas to pour</h3><div id="glcc-areas"></div></div>' +
			'<div class="gl-step"><h3><span class="gl-step-num">3</span>Thickness</h3><div class="gl-grid">' +
				'<div class="gl-field"><label for="glcc-thick">Slab thickness</label><select id="glcc-thick">' + G.opts(THICK, S.thick) + '</select></div>' +
				'<div class="gl-field" id="glcc-cthick-wrap"><label for="glcc-cthick">Thickness (inches)</label><input id="glcc-cthick" type="number" min="2" max="24" step="0.5" inputmode="decimal" value="' + G.esc(S.customThick) + '"></div>' +
				'<div class="gl-field"><label for="glcc-extra">Extra for spills and uneven base</label><select id="glcc-extra">' + G.opts([[0, 'None'], [5, '5%'], [10, '10% (recommended)']], S.extra) + '</select></div>' +
			'</div></div>' +
		'</div>' +
		'<div id="glcc-posts">' +
			'<div class="gl-step"><h3><span class="gl-step-num">2</span>Posts and holes</h3><div class="gl-grid">' +
				'<div class="gl-field"><label for="glcc-n">Number of posts</label><input id="glcc-n" type="number" min="1" max="500" step="1" inputmode="numeric" value="' + G.esc(S.posts) + '"></div>' +
				'<div class="gl-field"><label for="glcc-pshape">Post shape</label><select id="glcc-pshape">' + G.opts([['square', 'Square (e.g. 4×4, 6×6)'], ['round', 'Round']], S.postShape) + '</select></div>' +
				'<div class="gl-field"><label for="glcc-pw">Post width (in)</label><input id="glcc-pw" type="number" min="1" max="12" step="0.25" inputmode="decimal" value="' + G.esc(S.postW) + '"><span class="gl-hint">A 4×4 is 3½ in; a 6×6 is 5½ in</span></div>' +
				'<div class="gl-field"><label for="glcc-hd">Hole diameter (in)</label><input id="glcc-hd" type="number" min="4" max="36" step="0.5" inputmode="decimal" value="' + G.esc(S.holeDia) + '"><span class="gl-hint" id="glcc-hd-hint"></span></div>' +
				'<div class="gl-field"><label for="glcc-depth">Total hole depth (in)</label><input id="glcc-depth" type="number" min="6" max="96" step="1" inputmode="decimal" value="' + G.esc(S.holeDepth) + '"></div>' +
				'<div class="gl-field"><label for="glcc-gravel">Gravel in the bottom (in)</label><input id="glcc-gravel" type="number" min="0" max="12" step="1" inputmode="decimal" value="' + G.esc(S.gravel) + '"></div>' +
			'</div></div>' +
		'</div>' +
		'<div class="gl-step"><h3><span class="gl-step-num" id="glcc-pnum">4</span>Prices (optional)</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glcc-pbag">Per 80 lb bag ($)</label><input id="glcc-pbag" type="number" min="0" step="0.01" inputmode="decimal" value="' + G.esc(S.priceBag) + '"></div>' +
			'<div class="gl-field"><label for="glcc-pyard">Ready-mix per cubic yard ($)</label><input id="glcc-pyard" type="number" min="0" step="1" inputmode="decimal" value="' + G.esc(S.priceYard) + '"></div>' +
		'</div></div>' +
		'<div class="gl-results gl-print-area" id="glcc-results" aria-live="polite"></div>';

	var areasUI = G.areas(el('glcc-areas'), function () { return S.areas; }, { prefix: 'glcca-', shapes: ['rect', 'circle', 'sqft'], newName: 'Area', onChange: update });

	el('glcc-mode').addEventListener('change', function () { S.mode = this.value; update(); });
	el('glcc-thick').addEventListener('change', function () { S.thick = this.value === 'custom' ? 'custom' : +this.value; update(); });
	el('glcc-extra').addEventListener('change', function () { S.extra = +this.value; update(); });
	el('glcc-pshape').addEventListener('change', function () { S.postShape = this.value; update(); });
	[['glcc-cthick', 'customThick'], ['glcc-n', 'posts'], ['glcc-pw', 'postW'], ['glcc-hd', 'holeDia'], ['glcc-depth', 'holeDepth'], ['glcc-gravel', 'gravel'], ['glcc-pbag', 'priceBag'], ['glcc-pyard', 'priceYard']].forEach(function (p) {
		el(p[0]).addEventListener('input', function () { S[p[1]] = this.value; update(); });
	});

	function volumeSlab() {
		var sqft = G.totalSqft(S.areas);
		var t = S.thick === 'custom' ? G.num(S.customThick) : S.thick;
		if (!(sqft > 0)) return { err: 'Enter the size of the area you’re pouring.' };
		if (!(t > 0)) return { err: 'Enter a slab thickness in inches.' };
		var raw = sqft * t / 12;
		return { cuft: raw * (1 + S.extra / 100), rows: [['Area', G.fmt(sqft, 0) + ' sq ft at ' + G.fmt(t) + ' in thick'], ['Volume', G.fmt(raw, 1) + ' cu ft' + (S.extra ? ', plus ' + S.extra + '% extra' : '')]] };
	}

	function volumePosts() {
		var n = Math.round(G.num(S.posts)), w = G.num(S.postW), d = G.num(S.holeDia), depth = G.num(S.holeDepth), gr = G.num(S.gravel) || 0;
		if (!(n > 0)) return { err: 'Enter the number of posts.' };
		if (!(d > 0) || !(depth > 0)) return { err: 'Enter the hole diameter and depth.' };
		if (!(w > 0) || w >= d) return { err: 'Enter a post width smaller than the hole diameter.' };
		var fill = depth - gr;
		if (!(fill > 0)) return { err: 'The gravel layer can’t be as deep as the hole.' };
		var hole = Math.PI * Math.pow(d / 2, 2) * fill;               // cubic inches
		var post = S.postShape === 'round' ? Math.PI * Math.pow(w / 2, 2) * fill : w * w * fill;
		var each = (hole - post) / 1728;                              // cubic feet per hole
		return { cuft: each * n, perHole: each, n: n, rows: [['Each hole', G.fmt(d) + ' in wide, ' + G.fmt(depth) + ' in deep, ' + G.fmt(gr) + ' in gravel, concrete ' + G.fmt(fill) + ' in'], ['Concrete per hole', G.fmt(each, 2) + ' cu ft (post volume subtracted)']] };
	}

	function update() {
		st.save();
		var slab = S.mode === 'slab';
		el('glcc-slab').style.display = slab ? '' : 'none';
		el('glcc-posts').style.display = slab ? 'none' : '';
		el('glcc-pnum').textContent = slab ? '4' : '3';
		el('glcc-cthick-wrap').style.display = S.thick === 'custom' ? '' : 'none';
		var w = G.num(S.postW);
		el('glcc-hd-hint').textContent = w > 0 ? 'Rule of thumb: 3 × post width ≈ ' + G.fmt(w * 3) + ' in' : '';

		var box = el('glcc-results');
		var v = slab ? volumeSlab() : volumePosts();
		if (v.err) { box.innerHTML = '<p class="gl-warn">' + v.err + '</p>'; return; }

		var yd = v.cuft / 27;
		var stats = BAGS.map(function (b) {
			var n = G.ceil(v.cuft / b[1]);
			return '<div class="gl-stat"><b>' + G.fmt(n, 0) + '</b><span>' + G.plural(n, 'bag', 'bags') + ' of ' + b[0] + ' lb' + (slab ? '' : ' (≈' + G.fmt(v.perHole / b[1], 1) + ' per hole)') + '</span></div>';
		}).join('');
		var bags80 = G.ceil(v.cuft / 0.6);
		var pBag = G.num(S.priceBag), pYard = G.num(S.priceYard), costs = [];
		if (isFinite(pBag)) costs.push(['in 80 lb bags (' + bags80 + ')', bags80 * pBag]);
		if (isFinite(pYard)) costs.push(['ready-mix (' + G.fmt(G.ceil(yd * 4) / 4, 2) + ' yd, before any delivery or short-load fee)', G.ceil(yd * 4) / 4 * pYard]);

		box.innerHTML =
			'<h3>Concrete you need</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + G.fmt(yd, 2) + ' yd³</b><span>cubic yards</span></div>' +
				'<div class="gl-stat"><b>' + G.fmt(v.cuft, 1) + ' ft³</b><span>cubic feet</span></div>' +
			'</div>' +
			'<div class="gl-stats">' + stats + '</div>' +
			(costs.length ? '<div class="gl-stats">' + costs.map(function (c) { return '<div class="gl-stat"><b>' + G.money(c[1]) + '</b><span>' + G.esc(c[0]) + '</span></div>'; }).join('') + '</div>' : '') +
			'<div class="gl-table-wrap"><table><tbody>' + v.rows.map(function (r) { return '<tr><td>' + r[0] + '</td><td>' + r[1] + '</td></tr>'; }).join('') + '</tbody></table></div>' +
			'<p class="gl-note">Bag yields are QUIKRETE Concrete Mix figures: about 0.60 cu ft per 80 lb bag, 0.45 per 60 lb and 0.30 per 40 lb. Other brands are similar; check the bag. ' +
			(slab ? (yd >= 1 ? 'At about a cubic yard or more, compare a ready-mix delivery with mixing ' + bags80 + ' bags by hand.' : '') : 'Buy a bag or two extra: holes are rarely dug to the exact size, and a short hole is harder to fix than a spare bag is to return.') + '</p>' +
			'<div class="gl-actions gl-noprint"><button type="button" class="gl-btn" id="glcc-print">Print</button><button type="button" class="gl-btn gl-ghost" id="glcc-reset">Start over</button></div>';

		el('glcc-print').addEventListener('click', G.printArea);
		el('glcc-reset').addEventListener('click', function () {
			S = st.reset();
			el('glcc-mode').value = S.mode; el('glcc-thick').value = String(S.thick); el('glcc-extra').value = String(S.extra); el('glcc-pshape').value = S.postShape;
			el('glcc-cthick').value = ''; el('glcc-n').value = S.posts; el('glcc-pw').value = S.postW; el('glcc-hd').value = S.holeDia; el('glcc-depth').value = S.holeDepth; el('glcc-gravel').value = S.gravel;
			el('glcc-pbag').value = ''; el('glcc-pyard').value = '';
			areasUI.render(); update();
		});
	}

	update();
})();
