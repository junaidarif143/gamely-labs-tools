/* Gamely Labs: Grass Seed Calculator by grass type */
(function () {
	'use strict';
	var root = document.getElementById('gl-grass-seed');
	if (!root || !window.GLC) return;
	var G = window.GLC;

	// New-lawn seeding rates, lb per 1,000 sq ft: University of Tennessee Extension W160-B.
	var GRASS = {
		tallfescue: { label: 'Tall fescue', lo: 5, hi: 8, season: 'cool' },
		kbg: { label: 'Kentucky bluegrass', lo: 1.5, hi: 2, season: 'cool' },
		ryegrass: { label: 'Perennial ryegrass', lo: 4, hi: 6, season: 'cool' },
		finefescue: { label: 'Fine fescue (creeping red, chewings, hard)', lo: 3, hi: 5, season: 'cool' },
		bermudahulled: { label: 'Bermudagrass, hulled', lo: 0.5, hi: 1, season: 'warm' },
		bermudaunhulled: { label: 'Bermudagrass, unhulled', lo: 1, hi: 2, season: 'warm' },
		zoysia: { label: 'Zoysiagrass', lo: 0.5, hi: 3, season: 'warm' },
		centipede: { label: 'Centipedegrass', lo: 0.5, hi: 3, season: 'warm' },
		custom: { label: 'Mix or other (bag rate)', lo: null, hi: null, season: '' }
	};

	var st = G.store('gl_grass_seed_v1', {
		areas: [{ name: 'Front lawn', shape: 'rect', l: 50, w: 40, dia: '', inner: 1, sqft: '', qty: 1 }, { name: 'Back lawn', shape: 'rect', l: 60, w: 50, dia: '', inner: 1, sqft: '', qty: 1 }],
		grass: 'tallfescue', job: 'new', customRate: '', bag: 25, price: ''
	}, function (s) { return Array.isArray(s.areas) && s.areas.length; });
	var S = st.state;
	var el = function (id) { return document.getElementById(id); };

	root.innerHTML =
		'<div class="gl-step"><h3><span class="gl-step-num">1</span>Your grass and project</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glg2-grass">Grass type</label><select id="glg2-grass">' + G.opts(Object.keys(GRASS).map(function (k) { return [k, GRASS[k].label]; }), S.grass) + '</select><span class="gl-hint" id="glg2-rate-hint"></span></div>' +
			'<div class="gl-field" id="glg2-custom-wrap"><label for="glg2-custom">Rate from the bag (lb per 1,000 sq ft)</label><input id="glg2-custom" type="number" min="0.1" max="15" step="0.1" inputmode="decimal" value="' + G.esc(S.customRate) + '"></div>' +
			'<div class="gl-field"><label for="glg2-job">Project</label><select id="glg2-job">' + G.opts([['new', 'New lawn or bare soil'], ['over', 'Overseeding a thin lawn']], S.job) + '</select></div>' +
		'</div></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">2</span>Lawn areas</h3><div id="glg2-areas"></div></div>' +
		'<div class="gl-step"><h3><span class="gl-step-num">3</span>Buying</h3><div class="gl-grid">' +
			'<div class="gl-field"><label for="glg2-bag">Bag weight (lb)</label><input id="glg2-bag" type="number" min="0.5" step="0.5" inputmode="decimal" value="' + G.esc(S.bag) + '"></div>' +
			'<div class="gl-field"><label for="glg2-price">Price per bag ($, optional)</label><input id="glg2-price" type="number" min="0" step="0.01" inputmode="decimal" value="' + G.esc(S.price) + '"></div>' +
		'</div></div>' +
		'<div class="gl-results gl-print-area" id="glg2-results" aria-live="polite"></div>';

	var areasUI = G.areas(el('glg2-areas'), function () { return S.areas; }, { prefix: 'glg2a-', shapes: ['rect', 'circle', 'sqft'], newName: 'Lawn', onChange: update });

	el('glg2-grass').addEventListener('change', function () { S.grass = this.value; update(); });
	el('glg2-job').addEventListener('change', function () { S.job = this.value; update(); });
	[['glg2-custom', 'customRate'], ['glg2-bag', 'bag'], ['glg2-price', 'price']].forEach(function (p) {
		el(p[0]).addEventListener('input', function () { S[p[1]] = this.value; update(); });
	});

	function update() {
		st.save();
		var g = GRASS[S.grass], custom = S.grass === 'custom';
		el('glg2-custom-wrap').style.display = custom ? '' : 'none';
		el('glg2-rate-hint').textContent = custom ? 'Mixes vary, so use the label’s rate' : 'New-lawn rate: ' + G.fmt(g.lo) + '–' + G.fmt(g.hi) + ' lb per 1,000 sq ft';

		var box = el('glg2-results');
		var sqft = G.totalSqft(S.areas);
		var over = S.job === 'over';
		var lo, hi, rate;
		if (custom) {
			rate = G.num(S.customRate);
			if (!(rate > 0)) { box.innerHTML = '<p class="gl-warn">Enter the seeding rate printed on your seed bag.</p>'; return; }
			lo = hi = rate;
			// A bag's rate is usually for the job described on it, so it is not halved for overseeding.
		} else {
			lo = g.lo; hi = g.hi; rate = (lo + hi) / 2;
			if (over) { lo /= 2; hi /= 2; rate /= 2; }
		}
		if (!(sqft > 0)) { box.innerHTML = '<p class="gl-warn">Enter the size of your lawn.</p>'; return; }

		var k = sqft / 1000;
		var lbs = rate * k, lbsLo = lo * k, lbsHi = hi * k;
		var bag = G.num(S.bag), price = G.num(S.price);
		var bags = bag > 0 ? G.ceil(lbs / bag) : NaN;

		var when = g.season === 'cool'
			? 'Cool-season grasses establish best when sown in late summer to early fall, with spring as the second choice.'
			: g.season === 'warm'
				? 'Warm-season grasses need warm soil: sow in late spring to early summer, once nights stay warm.'
				: '';

		box.innerHTML =
			'<h3>Seed you need</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + G.fmt(lbs, 1) + ' lb</b><span>' + (custom ? 'at your bag’s rate' : 'recommended (' + (over ? 'overseeding' : 'new lawn') + ')') + '</span></div>' +
				(lo !== hi ? '<div class="gl-stat"><b class="gl-stat-text">' + G.fmt(lbsLo, 1) + '–' + G.fmt(lbsHi, 1) + ' lb</b><span>full range for this grass</span></div>' : '') +
				'<div class="gl-stat"><b>' + (isFinite(bags) ? bags : '–') + '</b><span>' + (isFinite(bags) ? G.plural(bags, 'bag', 'bags') + ' of ' + G.fmt(bag) + ' lb' : 'enter bag weight') + '</span></div>' +
				(isFinite(price) && isFinite(bags) ? '<div class="gl-stat"><b>' + G.money(bags * price) + '</b><span>total cost</span></div>' : '') +
			'</div>' +
			'<div class="gl-table-wrap"><table><tbody>' +
				'<tr><td>Lawn area</td><td>' + G.fmt(sqft, 0) + ' sq ft</td></tr>' +
				'<tr><td>Seeding rate used</td><td>' + G.fmt(rate, 2) + ' lb per 1,000 sq ft' + (over && !custom ? ' <small>(half the new-lawn rate)</small>' : '') + '</td></tr>' +
			'</tbody></table></div>' +
			(when ? '<p class="gl-note">' + when + ' Keep the seedbed moist until the new grass is up.</p>' : '<p class="gl-note">Keep the seedbed moist until the new grass is up.</p>') +
			(g.season === 'warm' && (S.grass === 'zoysia' || S.grass === 'centipede') ? '<p class="gl-note">This grass has a wide rate range; seed producers’ labels usually give the best guide for their variety.</p>' : '') +
			'<div class="gl-actions gl-noprint"><button type="button" class="gl-btn" id="glg2-print">Print</button><button type="button" class="gl-btn gl-ghost" id="glg2-reset">Start over</button></div>';

		el('glg2-print').addEventListener('click', G.printArea);
		el('glg2-reset').addEventListener('click', function () {
			S = st.reset();
			el('glg2-grass').value = S.grass; el('glg2-job').value = S.job; el('glg2-custom').value = '';
			el('glg2-bag').value = S.bag; el('glg2-price').value = '';
			areasUI.render(); update();
		});
	}

	update();
})();
