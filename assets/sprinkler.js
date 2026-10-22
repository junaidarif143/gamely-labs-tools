/* Gamely Labs: Sprinkler Run Time Calculator */
(function () {
	'use strict';

	var root = document.getElementById('gl-sprinkler');
	if (!root) return;

	var STORE_KEY = 'gl_sprinkler_v1';

	// Weekly targets (inches) used as editable starting points.
	var TARGETS = {
		cool: { peak: 1.25, shoulder: 0.75 },
		warm: { peak: 1.0, shoulder: 0.5 }
	};
	// Typical precipitation rates (in/hr) when the user has not measured.
	var TYPES = {
		spray: { label: 'Fixed spray heads', rate: 1.6 },
		rotor: { label: 'Gear-driven rotors', rate: 0.6 },
		rotary: { label: 'Rotary (multi-stream) nozzles', rate: 0.45 },
		measured: { label: 'My measured rate (catch-cup test)', rate: null }
	};
	// Rule-of-thumb maximum depth to apply per cycle before runoff (inches).
	var SOILS = {
		clay: { label: 'Clay (sticky, drains slowly)', perCycle: 0.15 },
		loam: { label: 'Loam (crumbly, average)', perCycle: 0.3 },
		sand: { label: 'Sandy (gritty, drains fast)', perCycle: 0.5 }
	};
	var DAY_SETS = {
		1: ['Saturday'],
		2: ['Monday', 'Thursday'],
		3: ['Monday', 'Wednesday', 'Friday'],
		4: ['Monday', 'Wednesday', 'Friday', 'Sunday'],
		5: ['Monday', 'Tuesday', 'Thursday', 'Friday', 'Saturday'],
		6: ['Every day except Sunday'],
		7: ['Every day']
	};

	var defaults = {
		grass: 'cool',
		season: 'peak',
		target: 1.25,
		targetCustom: false,
		rain: 0,
		days: 2,
		soil: 'loam',
		zones: [
			{ name: 'Front lawn', type: 'spray', rate: '', area: '' },
			{ name: 'Back lawn', type: 'rotor', rate: '', area: '' }
		]
	};

	var state = load();

	function load() {
		try {
			var raw = window.localStorage.getItem(STORE_KEY);
			if (raw) {
				var s = JSON.parse(raw);
				if (s && Array.isArray(s.zones) && s.zones.length) return Object.assign({}, defaults, s);
			}
		} catch (e) { /* storage unavailable: use defaults */ }
		return JSON.parse(JSON.stringify(defaults));
	}
	function save() {
		try { window.localStorage.setItem(STORE_KEY, JSON.stringify(state)); } catch (e) { /* ignore */ }
	}

	function num(v) {
		var n = parseFloat(v);
		return isFinite(n) ? n : NaN;
	}
	function fmt(n, dp) {
		if (!isFinite(n)) return '–';
		return n.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: dp == null ? 2 : dp });
	}
	function esc(s) {
		return String(s).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}
	function options(map, selected, labelKey) {
		return Object.keys(map).map(function (k) {
			var label = labelKey ? map[k][labelKey] : map[k];
			return '<option value="' + k + '"' + (k === selected ? ' selected' : '') + '>' + esc(label) + '</option>';
		}).join('');
	}

	/* ---------- Layout ---------- */
	root.innerHTML =
		'<div class="gl-step">' +
			'<h3><span class="gl-step-num">1</span>How much water does your lawn need this week?</h3>' +
			'<div class="gl-grid">' +
				'<div class="gl-field"><label for="gls-grass">Grass type</label>' +
					'<select id="gls-grass">' + options({ cool: 'Cool-season', warm: 'Warm-season' }, state.grass) + '</select>' +
					'<span class="gl-hint" id="gls-grass-hint"></span></div>' +
				'<div class="gl-field"><label for="gls-season">Season</label>' +
					'<select id="gls-season">' + options({ peak: 'Peak summer heat', shoulder: 'Spring or fall' }, state.season) + '</select></div>' +
				'<div class="gl-field"><label for="gls-target">Weekly target (inches)</label>' +
					'<input id="gls-target" type="number" min="0" max="4" step="0.05" inputmode="decimal" value="' + state.target + '">' +
					'<span class="gl-hint" id="gls-target-hint"></span></div>' +
				'<div class="gl-field"><label for="gls-rain">Rain this week (inches)</label>' +
					'<input id="gls-rain" type="number" min="0" max="10" step="0.05" inputmode="decimal" value="' + state.rain + '">' +
					'<span class="gl-hint">Recent rain or the forecast</span></div>' +
			'</div>' +
		'</div>' +
		'<div class="gl-step">' +
			'<h3><span class="gl-step-num">2</span>How and where do you water?</h3>' +
			'<div class="gl-grid">' +
				'<div class="gl-field"><label for="gls-days">Watering days per week</label>' +
					'<select id="gls-days">' + options({ 1: '1 day', 2: '2 days', 3: '3 days', 4: '4 days', 5: '5 days', 6: '6 days', 7: '7 days' }, String(state.days)) + '</select>' +
					'<span class="gl-hint">2–3 deep waterings suit most lawns</span></div>' +
				'<div class="gl-field"><label for="gls-soil">Soil type</label>' +
					'<select id="gls-soil">' + options(SOILS, state.soil, 'label') + '</select></div>' +
			'</div>' +
		'</div>' +
		'<div class="gl-step">' +
			'<h3><span class="gl-step-num">3</span>Your sprinkler zones</h3>' +
			'<div id="gls-zones"></div>' +
			'<button type="button" class="gl-btn gl-ghost gl-small" id="gls-add">+ Add a zone</button>' +
		'</div>' +
		'<div class="gl-results gl-print-area" id="gls-results" aria-live="polite"></div>';

	var el = function (id) { return document.getElementById(id); };

	/* ---------- Zones ---------- */
	function renderZones() {
		el('gls-zones').innerHTML = state.zones.map(function (z, i) {
			var measured = z.type === 'measured';
			return '<div class="gl-item" data-i="' + i + '">' +
				'<div class="gl-item-head">' +
					'<input type="text" aria-label="Zone name" data-f="name" value="' + esc(z.name) + '" maxlength="40">' +
					(state.zones.length > 1 ? '<button type="button" class="gl-link-btn" data-remove="' + i + '">Remove</button>' : '') +
				'</div>' +
				'<div class="gl-grid">' +
					'<div class="gl-field"><label for="glz-type-' + i + '">Sprinkler type</label>' +
						'<select id="glz-type-' + i + '" data-f="type">' + options(TYPES, z.type, 'label') + '</select>' +
						'<span class="gl-hint">' + (measured ? 'Enter your measured rate' : 'Typical rate: ' + TYPES[z.type].rate + ' in/hr') + '</span></div>' +
					(measured ?
						'<div class="gl-field"><label for="glz-rate-' + i + '">Measured rate (in/hr)</label>' +
							'<input id="glz-rate-' + i + '" type="number" min="0.05" max="5" step="0.05" inputmode="decimal" data-f="rate" value="' + esc(z.rate) + '" placeholder="e.g. 0.8">' +
							'<span class="gl-hint">15-minute cup test: average depth × 4</span></div>'
						: '') +
					'<div class="gl-field"><label for="glz-area-' + i + '">Area (sq ft, optional)</label>' +
						'<input id="glz-area-' + i + '" type="number" min="0" step="10" inputmode="numeric" data-f="area" value="' + esc(z.area) + '" placeholder="e.g. 1500">' +
						'<span class="gl-hint">For gallons used</span></div>' +
				'</div>' +
			'</div>';
		}).join('');
	}

	el('gls-zones').addEventListener('input', function (e) {
		var item = e.target.closest('.gl-item');
		var f = e.target.getAttribute('data-f');
		if (!item || !f) return;
		var z = state.zones[+item.getAttribute('data-i')];
		z[f] = e.target.value;
		if (f === 'type') {
			renderZones();
			var next = el('glz-type-' + item.getAttribute('data-i'));
			if (next) next.focus();
		}
		update();
	});
	el('gls-zones').addEventListener('change', function (e) {
		// Some browsers fire only "change" on selects.
		if (e.target.getAttribute('data-f') === 'type') {
			var item = e.target.closest('.gl-item');
			var z = state.zones[+item.getAttribute('data-i')];
			if (z.type !== e.target.value) {
				z.type = e.target.value;
				renderZones();
				update();
			}
		}
	});
	el('gls-zones').addEventListener('click', function (e) {
		var r = e.target.getAttribute('data-remove');
		if (r === null) return;
		state.zones.splice(+r, 1);
		renderZones();
		update();
	});
	el('gls-add').addEventListener('click', function () {
		if (state.zones.length >= 12) return;
		state.zones.push({ name: 'Zone ' + (state.zones.length + 1), type: 'spray', rate: '', area: '' });
		renderZones();
		update();
		var inputs = el('gls-zones').querySelectorAll('input[data-f="name"]');
		if (inputs.length) inputs[inputs.length - 1].select();
	});

	/* ---------- Settings ---------- */
	function presetTarget() {
		return TARGETS[state.grass][state.season];
	}
	function syncTargetHint() {
		el('gls-grass-hint').textContent = state.grass === 'cool'
			? 'Fescue, bluegrass, ryegrass'
			: 'Bermuda, zoysia, St. Augustine, centipede';
		el('gls-target-hint').textContent = state.targetCustom
			? 'Your own number (suggested: ' + presetTarget() + ' in)'
			: 'Suggested starting point';
	}
	['gls-grass', 'gls-season'].forEach(function (id) {
		el(id).addEventListener('change', function () {
			state.grass = el('gls-grass').value;
			state.season = el('gls-season').value;
			if (!state.targetCustom) {
				state.target = presetTarget();
				el('gls-target').value = state.target;
			}
			syncTargetHint();
			update();
		});
	});
	el('gls-target').addEventListener('input', function () {
		state.target = this.value;
		state.targetCustom = num(this.value) !== presetTarget();
		syncTargetHint();
		update();
	});
	el('gls-rain').addEventListener('input', function () { state.rain = this.value; update(); });
	el('gls-days').addEventListener('change', function () { state.days = +this.value; update(); });
	el('gls-soil').addEventListener('change', function () { state.soil = this.value; update(); });

	/* ---------- Calculation ---------- */
	function zoneRate(z) {
		return z.type === 'measured' ? num(z.rate) : TYPES[z.type].rate;
	}

	function calc() {
		var target = num(state.target);
		var rain = num(state.rain) || 0;
		var days = +state.days;
		var perCycleMax = SOILS[state.soil].perCycle;
		var need = Math.max(0, target - rain);
		var perDay = need / days;

		var zones = state.zones.map(function (z) {
			var rate = zoneRate(z);
			var out = { name: z.name || 'Zone', rate: rate, ok: isFinite(rate) && rate > 0 };
			if (!out.ok || !(perDay > 0)) return out;
			var minutes = perDay / rate * 60;
			var cycles = Math.max(1, Math.ceil(perDay / perCycleMax - 1e-9));
			var cycleMin = Math.max(1, Math.round(minutes / cycles));
			var area = num(z.area);
			out.minutes = minutes;
			out.cycles = cycles;
			out.cycleMin = cycleMin;
			out.dayMinutes = cycleMin * cycles;
			out.weekMinutes = out.dayMinutes * days;
			out.galDay = area > 0 ? area * perDay * 0.623 : NaN;
			out.galWeek = area > 0 ? out.galDay * days : NaN;
			return out;
		});
		return { target: target, rain: rain, need: need, days: days, perDay: perDay, zones: zones };
	}

	function update() {
		save();
		var box = el('gls-results');
		var r = calc();

		if (!(r.target >= 0)) {
			box.innerHTML = '<p class="gl-warn">Enter a weekly water target to see your schedule.</p>';
			return;
		}
		if (r.need === 0) {
			box.innerHTML = '<h3>Your watering schedule</h3>' +
				'<p><strong>No watering needed this week.</strong> The ' + fmt(r.rain) + ' in of rain covers your ' + fmt(r.target) + ' in target. Check again after the next dry spell.</p>';
			return;
		}

		var totalGalWeek = 0, anyGal = false;
		var rows = r.zones.map(function (z) {
			if (!z.ok) {
				return '<tr><td>' + esc(z.name) + '</td><td colspan="3"><em>Enter this zone\'s measured rate</em></td></tr>';
			}
			if (isFinite(z.galWeek)) { totalGalWeek += z.galWeek; anyGal = true; }
			var cycleText = z.cycles > 1
				? z.cycles + ' × ' + z.cycleMin + ' min<br><small>soak 30–60 min between</small>'
				: z.cycleMin + ' min';
			return '<tr><td><strong>' + esc(z.name) + '</strong><br><small>' + fmt(z.rate) + ' in/hr</small></td>' +
				'<td>' + cycleText + '</td>' +
				'<td>' + z.dayMinutes + ' min</td>' +
				'<td>' + (isFinite(z.galWeek) ? fmt(z.galWeek, 0) + ' gal' : '–') + '</td></tr>';
		}).join('');

		var warnings = '';
		if (r.perDay > 1) {
			warnings += '<p class="gl-warn">That is over 1 inch per watering day. Consider adding a watering day so the soil can absorb it.</p>';
		}
		if (r.days >= 5) {
			warnings += '<p class="gl-warn">Frequent, shallow watering keeps roots near the surface. For an established lawn, 2–3 deeper waterings a week are usually better. (New seed and sod are the exception.)</p>';
		}
		if (r.target > 2) {
			warnings += '<p class="gl-warn">Over 2 inches a week is more than most lawns need. Double-check your target.</p>';
		}

		box.innerHTML =
			'<h3>Your watering schedule</h3>' +
			'<div class="gl-stats">' +
				'<div class="gl-stat"><b>' + fmt(r.need) + ' in</b><span>to apply this week</span></div>' +
				'<div class="gl-stat"><b>' + fmt(r.perDay, 3) + ' in</b><span>per watering day</span></div>' +
				'<div class="gl-stat"><b class="gl-stat-text">' + esc(DAY_SETS[r.days].join(', ')) + '</b><span>suggested days</span></div>' +
				(anyGal ? '<div class="gl-stat"><b>' + fmt(totalGalWeek, 0) + ' gal</b><span>per week (zones with area)</span></div>' : '') +
			'</div>' +
			'<div class="gl-table-wrap"><table>' +
				'<thead><tr><th>Zone</th><th>Run per watering day</th><th>Total/day</th><th>Water/week</th></tr></thead>' +
				'<tbody>' + rows + '</tbody>' +
			'</table></div>' +
			warnings +
			'<p class="gl-note">Water early in the morning. Run times are rounded to whole minutes. Soil: ' + esc(SOILS[state.soil].label.split(' (')[0].toLowerCase()) + ', up to ' + SOILS[state.soil].perCycle + ' in per cycle.</p>' +
			'<div class="gl-actions gl-noprint">' +
				'<button type="button" class="gl-btn" id="gls-print">Print schedule</button>' +
				'<button type="button" class="gl-btn gl-ghost" id="gls-reset">Start over</button>' +
			'</div>';

		el('gls-print').addEventListener('click', printSchedule);
		el('gls-reset').addEventListener('click', reset);
	}

	function printSchedule() {
		document.body.classList.add('gl-printing');
		window.print();
		setTimeout(function () { document.body.classList.remove('gl-printing'); }, 500);
	}

	function reset() {
		state = JSON.parse(JSON.stringify(defaults));
		el('gls-grass').value = state.grass;
		el('gls-season').value = state.season;
		el('gls-target').value = state.target;
		el('gls-rain').value = state.rain;
		el('gls-days').value = String(state.days);
		el('gls-soil').value = state.soil;
		syncTargetHint();
		renderZones();
		update();
	}

	renderZones();
	syncTargetHint();
	update();
})();
