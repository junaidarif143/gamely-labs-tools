/* Gamely Labs: shared helpers for calculators (window.GLC). */
(function () {
	'use strict';

	var EPS = 1e-9;

	function num(v) { var n = parseFloat(v); return isFinite(n) ? n : NaN; }
	function fmt(n, dp) {
		if (!isFinite(n)) return '–';
		return n.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: dp == null ? 2 : dp });
	}
	function money(n) {
		return isFinite(n) ? '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '–';
	}
	function esc(s) {
		return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}
	function opts(pairs, sel) {
		return pairs.map(function (p) {
			return '<option value="' + esc(p[0]) + '"' + (String(p[0]) === String(sel) ? ' selected' : '') + '>' + esc(p[1]) + '</option>';
		}).join('');
	}
	function ceil(n) { return Math.ceil(n - EPS); }
	function plural(n, one, many) { return n === 1 ? one : many; }

	function store(key, defaults, isValid) {
		var state;
		try {
			var raw = window.localStorage.getItem(key);
			if (raw) {
				var s = JSON.parse(raw);
				if (s && (!isValid || isValid(s))) state = Object.assign({}, JSON.parse(JSON.stringify(defaults)), s);
			}
		} catch (e) { /* storage unavailable */ }
		if (!state) state = JSON.parse(JSON.stringify(defaults));
		return {
			state: state,
			save: function () { try { window.localStorage.setItem(key, JSON.stringify(this.state)); } catch (e) { /* ignore */ } },
			reset: function () { this.state = JSON.parse(JSON.stringify(defaults)); return this.state; }
		};
	}

	function printArea() {
		document.body.classList.add('gl-printing');
		window.print();
		setTimeout(function () { document.body.classList.remove('gl-printing'); }, 500);
	}

	/* ---------- Areas list: rectangle, circle, ring around a tree, or known square feet ---------- */
	var SHAPES = {
		rect: 'Rectangle',
		circle: 'Circle',
		ring: 'Ring around a tree',
		sqft: 'I know the square feet'
	};

	function areaSqft(a) {
		var q = Math.max(1, Math.round(num(a.qty) || 1));
		var s;
		switch (a.shape) {
			case 'circle': s = Math.PI * Math.pow((num(a.dia) || 0) / 2, 2); break;
			case 'ring':
				var outer = (num(a.dia) || 0) / 2, inner = Math.min(outer, (num(a.inner) || 0) / 2);
				s = Math.PI * (outer * outer - inner * inner); break;
			case 'sqft': s = num(a.sqft) || 0; break;
			default: s = (num(a.l) || 0) * (num(a.w) || 0);
		}
		return { each: Math.max(0, s), qty: q, total: Math.max(0, s) * q };
	}

	/**
	 * Renders an editable list of areas into `box` and keeps `list` (an array in the tool's state) in sync.
	 * opts: { prefix, shapes: ['rect',...], newName: 'Area', onChange: fn, max }
	 */
	function areas(box, getList, o) {
		var shapes = o.shapes || Object.keys(SHAPES);
		var p = o.prefix;

		function field(id, label, f, val, extra) {
			return '<div class="gl-field"><label for="' + id + '">' + label + '</label><input id="' + id + '" type="number" min="0" step="any" inputmode="decimal" data-f="' + f + '" value="' + esc(val) + '">' + (extra || '') + '</div>';
		}
		function render() {
			var list = getList();
			box.innerHTML = list.map(function (a, i) {
				var f = '';
				if (a.shape === 'rect') f = field(p + 'l' + i, 'Length (ft)', 'l', a.l) + field(p + 'w' + i, 'Width (ft)', 'w', a.w);
				else if (a.shape === 'circle') f = field(p + 'd' + i, 'Diameter (ft)', 'dia', a.dia);
				else if (a.shape === 'ring') f = field(p + 'd' + i, 'Ring diameter (ft)', 'dia', a.dia, '<span class="gl-hint">Edge to edge, across the tree</span>') + field(p + 'n' + i, 'Bare circle at trunk (ft)', 'inner', a.inner, '<span class="gl-hint">Keep mulch off the trunk</span>');
				else f = field(p + 's' + i, 'Square feet', 'sqft', a.sqft);
				return '<div class="gl-item" data-i="' + i + '">' +
					'<div class="gl-item-head"><input type="text" aria-label="Area name" data-f="name" maxlength="40" value="' + esc(a.name) + '">' +
						(list.length > 1 ? '<button type="button" class="gl-link-btn" data-remove="' + i + '">Remove</button>' : '') + '</div>' +
					'<div class="gl-grid">' +
						'<div class="gl-field"><label for="' + p + 'shape' + i + '">Shape</label><select id="' + p + 'shape' + i + '" data-f="shape">' +
							opts(shapes.map(function (k) { return [k, SHAPES[k]]; }), a.shape) + '</select></div>' +
						f +
						'<div class="gl-field"><label for="' + p + 'q' + i + '">How many like this</label><input id="' + p + 'q' + i + '" type="number" min="1" max="99" step="1" inputmode="numeric" data-f="qty" value="' + esc(a.qty || 1) + '"></div>' +
					'</div></div>';
			}).join('') +
				'<button type="button" class="gl-btn gl-ghost gl-small" data-add="1">+ Add ' + (o.addLabel || 'an area') + '</button>';
		}

		box.addEventListener('input', function (e) {
			var item = e.target.closest('.gl-item'), f = e.target.getAttribute('data-f');
			if (!item || !f || f === 'shape') return;
			getList()[+item.getAttribute('data-i')][f] = e.target.value;
			o.onChange();
		});
		box.addEventListener('change', function (e) {
			if (e.target.getAttribute('data-f') !== 'shape') return;
			var i = +e.target.closest('.gl-item').getAttribute('data-i');
			getList()[i].shape = e.target.value;
			render();
			var sel = document.getElementById(p + 'shape' + i);
			if (sel) sel.focus();
			o.onChange();
		});
		box.addEventListener('click', function (e) {
			var list = getList();
			if (e.target.getAttribute('data-add')) {
				if (list.length >= (o.max || 20)) return;
				list.push({ name: (o.newName || 'Area') + ' ' + (list.length + 1), shape: shapes[0], l: '', w: '', dia: '', inner: 1, sqft: '', qty: 1 });
				render(); o.onChange();
				var names = box.querySelectorAll('input[data-f="name"]');
				if (names.length) names[names.length - 1].select();
				return;
			}
			var r = e.target.getAttribute('data-remove');
			if (r !== null) { list.splice(+r, 1); render(); o.onChange(); }
		});
		render();
		return { render: render };
	}

	function totalSqft(list) {
		return list.reduce(function (s, a) { return s + areaSqft(a).total; }, 0);
	}

	window.GLC = {
		EPS: EPS, num: num, fmt: fmt, money: money, esc: esc, opts: opts, ceil: ceil, plural: plural,
		store: store, printArea: printArea, areas: areas, areaSqft: areaSqft, totalSqft: totalSqft
	};
})();
