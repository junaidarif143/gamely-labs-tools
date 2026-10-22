<?php
/**
 * Page content created on activation. Kept here so FAQ markup and FAQ schema come from the same data.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gl_tools_faq_html( $faq ) {
	$html = '<h2>Frequently asked questions</h2>' . "\n" . '<div class="gl-faq">' . "\n";
	foreach ( $faq as $q => $a ) {
		$html .= '<details><summary>' . esc_html( $q ) . '</summary><p>' . esc_html( $a ) . '</p></details>' . "\n";
	}
	return $html . '</div>' . "\n";
}

function gl_tools_page_definitions() {
	$defs = array();

	/* ---------------------------------------------------------------
	 * Sprinkler run-time calculator
	 * ------------------------------------------------------------- */
	$sprinkler_faq = array(
		'How long should I run my sprinklers to get 1 inch of water?' => 'Divide 1 inch by your sprinkler\'s precipitation rate and multiply by 60. Fixed spray heads typically apply about 1.5 to 2 inches per hour, so 1 inch takes roughly 30 to 40 minutes. Rotors typically apply 0.5 to 1 inch per hour, so 1 inch takes roughly 60 to 120 minutes. A catch-cup test gives you the exact rate for your own system.',
		'Is it better to water every day or a few times a week?'      => 'For established lawns, a few deep waterings per week usually beat short daily ones. Deep, less frequent watering moistens soil further down and encourages deeper roots, which makes the lawn more drought tolerant. New seed and fresh sod are the exception: they need light, frequent watering until they root.',
		'What is cycle and soak?'                                     => 'Cycle and soak splits one long watering into two or more shorter runs with a 30 to 60 minute break between them. The break lets water sink in before the next run, so less of it runs off. It matters most on clay soil, slopes and fast spray heads.',
		'What time of day should I water my lawn?'                    => 'Early morning, roughly between 4 and 10 a.m., is generally best. Wind is usually calmer and less water evaporates, and the grass dries during the day, which lowers the risk of fungal disease compared with watering in the evening.',
		'How do I do a catch-cup test?'                               => 'Place 6 to 8 straight-sided containers such as tuna cans or rain gauges around one zone, run that zone for 15 minutes, measure the water depth in each container and average them. Multiply the average depth in inches by 4 to get the zone\'s precipitation rate in inches per hour.',
	);

	$sprinkler_content = <<<'HTML'
<p class="gl-lede">Find out exactly how many minutes to run each sprinkler zone so your lawn gets the water it needs, without wasting any. Set your weekly target, subtract the rain, tell the calculator how fast each zone applies water, and get a ready-to-use watering schedule you can print.</p>

[gl_sprinkler]

<h2>How to use this calculator</h2>
<ol>
<li><strong>Pick your grass type and season.</strong> The calculator suggests a weekly water target. You can type your own number instead.</li>
<li><strong>Enter recent or expected rainfall</strong> for the week, in inches. Rain counts toward the target, so the calculator only schedules the difference.</li>
<li><strong>Choose how many days a week you water.</strong> Two or three deep waterings suit most established lawns.</li>
<li><strong>Choose your soil type.</strong> This decides whether a long run needs to be split into shorter cycles to avoid runoff.</li>
<li><strong>Add each zone.</strong> Pick the sprinkler type, or enter a measured rate from a catch-cup test. Add the zone's area if you want to see how many gallons you use.</li>
</ol>

<h2>The formula behind it</h2>
<p>Every number in the schedule comes from three simple steps:</p>
<ul>
<li><strong>Water needed per watering day</strong> = (weekly target − rainfall) ÷ watering days per week</li>
<li><strong>Minutes per watering day</strong> = water needed per day ÷ precipitation rate (inches per hour) × 60</li>
<li><strong>Gallons</strong> = area in square feet × inches applied × 0.623</li>
</ul>
<p>The last figure comes from the fact that one inch of water spread over one square foot is about 0.623 gallons, which works out to roughly 623 gallons per 1,000 square feet.</p>

<h2>Worked example</h2>
<p>Say your cool-season lawn needs about 1 inch of water this week, the weather forecast expects 0.25 inch of rain, and you water twice a week on loam soil.</p>
<ul>
<li>Water needed per watering day: (1 − 0.25) ÷ 2 = <strong>0.375 inch</strong></li>
<li>A zone of fixed spray heads applying 1.6 inches per hour: 0.375 ÷ 1.6 × 60 ≈ <strong>14 minutes</strong>, split into 2 cycles of 7 minutes</li>
<li>A zone of rotors applying 0.6 inch per hour: 0.375 ÷ 0.6 × 60 ≈ <strong>38 minutes</strong>, split into 2 cycles of 19 minutes</li>
<li>If the rotor zone covers 1,500 square feet, each watering day uses about 1,500 × 0.375 × 0.623 ≈ <strong>350 gallons</strong></li>
</ul>
<p>Notice how different the two zones are. Spray heads and rotors apply water at very different speeds, which is why a single run time for every zone almost always over-waters some areas and under-waters others.</p>

<h2>Typical sprinkler precipitation rates</h2>
<table class="gl-table">
<thead><tr><th>Sprinkler type</th><th>Typical rate</th><th>Notes</th></tr></thead>
<tbody>
<tr><td>Fixed spray heads</td><td>1.5 – 2.0 in/hr</td><td>Fast; most prone to runoff on clay and slopes</td></tr>
<tr><td>Gear-driven rotors</td><td>0.5 – 1.0 in/hr</td><td>Slower; need much longer run times</td></tr>
<tr><td>Rotary (multi-stream) nozzles</td><td>0.4 – 0.6 in/hr</td><td>Slow, even coverage; good for clay</td></tr>
</tbody>
</table>
<p>These ranges are only a starting point. Pressure, nozzle size, head spacing and wear all change the real rate, so a catch-cup test is worth the 15 minutes it takes.</p>

<h2>How to measure your real rate with a catch-cup test</h2>
<ol>
<li>Set 6 to 8 straight-sided containers around one zone: some near the heads, some in between.</li>
<li>Run that zone for exactly 15 minutes.</li>
<li>Measure the depth in each container with a ruler and average the readings.</li>
<li>Multiply the average by 4. That is the zone's rate in inches per hour. Enter it in the calculator as a measured rate.</li>
</ol>
<p>If the depths vary a lot between containers, your coverage is uneven. Check for blocked nozzles, tilted heads or low pressure before adjusting run times.</p>

<h2>Why the calculator splits long runs into cycles</h2>
<p>Soil can only absorb water so fast. Clay soaks it up slowly, sand quickly. When a sprinkler applies water faster than the soil can take it in, the extra runs off onto the sidewalk or pools in low spots. The calculator limits how much water each cycle applies based on your soil type, then splits the day's watering into enough cycles to stay under that limit. Leave 30 to 60 minutes between cycles so the water can sink in. Most irrigation controllers let you set several start times for this.</p>
<p>These per-cycle limits are rules of thumb. If you still see runoff, add another cycle; if the soil stays dry an inch or two down after watering, lengthen the runs.</p>

<h2>Tips for a healthier lawn with less water</h2>
<ul>
<li><strong>Check the soil, not just the clock.</strong> Push a screwdriver into the lawn after watering. It should slide in easily to a depth of about 6 inches.</li>
<li><strong>Adjust with the seasons.</strong> Lawns need far less water in spring and fall than in midsummer. Re-run the calculator whenever the weather changes.</li>
<li><strong>Raise your mowing height.</strong> Taller grass shades the soil and holds moisture longer.</li>
<li><strong>Follow local rules.</strong> Many towns limit watering days or hours, especially in summer. Set your watering days to match.</li>
</ul>
HTML;

	$defs['sprinkler'] = array(
		'title'   => 'Sprinkler Run Time Calculator',
		'slug'    => 'sprinkler-run-time-calculator',
		'excerpt' => 'Work out how many minutes to run each sprinkler zone, with rain, soil type and cycle-and-soak built in.',
		'content' => $sprinkler_content . "\n" . gl_tools_faq_html( $sprinkler_faq ),
		'faq'     => $sprinkler_faq,
		'order'   => 1,
	);

	/* ---------------------------------------------------------------
	 * Square foot garden planner
	 * ------------------------------------------------------------- */
	$sfg_faq = array(
		'How many plants go in one square foot?'                => 'It depends on the plant\'s mature size. Large plants such as tomatoes, peppers and broccoli get 1 per square. Medium plants such as lettuce, chard and basil get 4. Smaller plants such as bush beans, spinach and beets get 9. Very small root crops such as carrots and radishes get 16.',
		'How deep should a square foot garden bed be?'          => 'Six inches of good soil mix is the standard depth for square foot gardening and suits most vegetables. Deep-rooted crops such as long carrots and potatoes do better with 10 to 12 inches.',
		'What soil do you use for square foot gardening?'       => 'The classic mix is one third blended compost, one third peat moss (or coconut coir) and one third coarse vermiculite, measured by volume. Using compost from several sources gives a wider range of nutrients.',
		'How wide should a square foot garden bed be?'          => 'Keep beds no wider than 4 feet if you can reach from both sides, or about 3 feet if the bed is against a wall or fence. That way you can reach every square without stepping on the soil.',
		'How much soil do I need for a 4x8 raised bed?'         => 'A 4 by 8 foot bed filled 6 inches deep needs 16 cubic feet of soil, which is about 0.59 cubic yards. At 12 inches deep it needs 32 cubic feet, or about 1.19 cubic yards.',
	);

	$sfg_content = <<<'HTML'
<p class="gl-lede">Plan your raised beds one square foot at a time. Draw your beds, click squares to plant them, and the planner tells you how many plants or seeds each square holds, what to buy, and exactly how much soil mix you need to fill the beds.</p>

[gl_sfg_planner]

<h2>How to use the planner</h2>
<ol>
<li><strong>Add your beds.</strong> Enter each bed's length and width in feet. A 4 × 4 or 4 × 8 bed is a good place to start.</li>
<li><strong>Pick a plant</strong> from the list. Each plant shows how many fit in one square.</li>
<li><strong>Click squares</strong> to plant them. Click a planted square again with the same plant selected to clear it.</li>
<li><strong>Set the soil depth</strong> to see how much mix you need, split into compost, peat moss or coir, and vermiculite.</li>
<li><strong>Print your plan</strong> to take to the garden center. Your plan is also saved in this browser so you can come back to it.</li>
</ol>

<h2>What is square foot gardening?</h2>
<p>Square foot gardening is a method popularized by Mel Bartholomew. Instead of long rows with wide paths, you build a small raised bed, divide its surface into a grid of one-foot squares, and plant each square with a set number of plants based on their size. Because nothing is planted in paths, you grow more food in less space, use less water, and have far fewer weeds to pull.</p>

<h2>Plants per square: the spacing chart</h2>
<table class="gl-table">
<thead><tr><th>Per square</th><th>Typical plants</th></tr></thead>
<tbody>
<tr><td><strong>1</strong></td><td>Tomato (staked), pepper, eggplant, broccoli, cabbage, cauliflower, kale</td></tr>
<tr><td><strong>2</strong></td><td>Cucumber (on a trellis)</td></tr>
<tr><td><strong>4</strong></td><td>Leaf lettuce, Swiss chard, parsley, basil, marigold, strawberry, corn, potato</td></tr>
<tr><td><strong>8</strong></td><td>Pole beans and peas (on a trellis)</td></tr>
<tr><td><strong>9</strong></td><td>Bush beans, spinach, beets, bulb onions, garlic, turnips, cilantro</td></tr>
<tr><td><strong>16</strong></td><td>Carrots, radishes, green onions</td></tr>
<tr><td><strong>1 per 4 squares</strong></td><td>Zucchini and summer squash (they sprawl)</td></tr>
</tbody>
</table>
<p>To space plants inside a square, divide it into an even grid: 2 × 2 for 4 plants, 3 × 3 for 9, and 4 × 4 for 16. Tall plants and trellised crops belong on the north side of the bed so they don't shade everything else.</p>

<h2>How much soil mix you need</h2>
<p>The planner works out soil volume with this formula:</p>
<ul>
<li><strong>Cubic feet</strong> = length (ft) × width (ft) × depth (in) ÷ 12</li>
<li><strong>Cubic yards</strong> = cubic feet ÷ 27</li>
</ul>
<p><strong>Example:</strong> a 4 × 8 foot bed filled 6 inches deep needs 4 × 8 × 6 ÷ 12 = <strong>16 cubic feet</strong>, about 0.59 cubic yards. For the classic one-third mix, that is about 5.3 cubic feet each of compost, peat moss or coir, and vermiculite.</p>
<p>Peat moss and coir are often sold compressed and expand when you loosen them, so check the bag for its loose volume before buying. Bagged products are usually sold in 1, 1.5 or 2 cubic foot bags. For large beds, buying compost in bulk by the cubic yard is usually cheaper.</p>

<h2>Tips for a productive square foot garden</h2>
<ul>
<li><strong>Put beds in full sun.</strong> Most vegetables need 6 to 8 hours of direct sun a day.</li>
<li><strong>Replant as you harvest.</strong> When a square is done, add a trowel of compost and plant something new. That is how a small bed stays productive all season.</li>
<li><strong>Mix plant families.</strong> Spreading crops across the bed instead of planting big blocks of one thing helps limit pests and disease.</li>
<li><strong>Water at the base.</strong> A watering can or drip line keeps leaves dry and puts water where roots can use it.</li>
</ul>
HTML;

	$defs['sfg'] = array(
		'title'   => 'Square Foot Garden Planner',
		'slug'    => 'square-foot-garden-planner',
		'excerpt' => 'Plan raised beds square by square, with plant counts, a shopping list and the exact soil mix you need.',
		'content' => $sfg_content . "\n" . gl_tools_faq_html( $sfg_faq ),
		'faq'     => $sfg_faq,
		'order'   => 2,
	);

	/* ---------------------------------------------------------------
	 * Homepage (tools hub)
	 * ------------------------------------------------------------- */
	$home_content = <<<'HTML'
<div class="gl-hero">
<p class="gl-eyebrow">Free lawn, garden &amp; yard calculators</p>
<h2 class="gl-hero-title">Plan the project, skip the guesswork.</h2>
<p>Gamely Labs builds practical calculators for homeowners: how long to water, how many plants fit, how much soil, mulch or seed to buy. Every tool shows its math and comes with a plain-English guide.</p>
</div>

<h2>Lawn tools</h2>
<div class="gl-cards">
<a class="gl-card" href="/sprinkler-run-time-calculator/">
<span class="gl-card-tag">Lawn</span>
<strong>Sprinkler Run Time Calculator</strong>
<span>Minutes per zone, cycle-and-soak and gallons used, from your weekly target and rainfall.</span>
</a>
<a class="gl-card" href="/lawn-fertilizer-calculator/">
<span class="gl-card-tag">Lawn</span>
<strong>Lawn Fertilizer Calculator</strong>
<span>Pounds of product and bags for any N-P-K, plus a yearly feeding plan for your grass type.</span>
</a>
<a class="gl-card" href="/lawn-lime-calculator/">
<span class="gl-card-tag">Lawn</span>
<strong>Lawn Lime Calculator</strong>
<span>Turn your soil test's lime recommendation into bags and a safe application schedule.</span>
</a>
</div>

<h2>Garden tools</h2>
<div class="gl-cards">
<a class="gl-card" href="/square-foot-garden-planner/">
<span class="gl-card-tag">Garden</span>
<strong>Square Foot Garden Planner</strong>
<span>Click to plant your beds, get plant counts, a shopping list and the soil mix you need.</span>
</a>
<a class="gl-card" href="/raised-bed-soil-calculator/">
<span class="gl-card-tag">Garden</span>
<strong>Raised Bed Soil Calculator</strong>
<span>Soil for several beds at once, any mix recipe, with bagged vs bulk cost compared.</span>
</a>
</div>

<h2>Coming soon</h2>
<ul class="gl-soon">
<li>Compost calculator</li>
<li>Sod calculator</li>
<li>Grass seed calculator by grass type</li>
<li>Mulch calculator for multiple beds</li>
</ul>

<h2>How we build our tools</h2>
<p>Each calculator uses the standard formulas from horticulture and irrigation practice, and every page explains the method with a worked example so you can check the numbers yourself. Where a figure depends on your own yard, like how fast your sprinklers apply water, we show you how to measure it instead of guessing.</p>
HTML;

	/* ---------------------------------------------------------------
	 * Raised bed soil calculator
	 * ------------------------------------------------------------- */
	$rb_faq = array(
		'How much soil do I need for a 4x8 raised bed?'               => 'A 4 by 8 foot bed needs 16 cubic feet of soil at 6 inches deep, 24 cubic feet at 9 inches, and 32 cubic feet (about 1.2 cubic yards) at 12 inches. Plan on roughly 10% extra because new soil settles.',
		'How many bags of soil fill a raised bed?'                    => 'Divide the bed\'s volume in cubic feet by the bag size. A 4 by 8 by 1 foot bed holds 32 cubic feet, which is 16 bags of 2 cubic feet or about 22 bags of 1.5 cubic feet, before any allowance for settling.',
		'Is it cheaper to buy raised bed soil in bags or in bulk?'     => 'For more than about one cubic yard (27 cubic feet), bulk soil delivered by the yard is usually cheaper per cubic foot than bags, though delivery fees can change the math for small orders. Enter both prices in the calculator to compare them for your project.',
		'What can I use to fill the bottom of a deep raised bed?'     => 'Many gardeners fill the bottom of beds deeper than about 12 inches with untreated logs, branches or coarse wood, then top them with soil. Most vegetables root mainly in the top 12 inches, so this saves soil. Enter the filler depth in the calculator and it subtracts that volume.',
		'What is the best soil mix for raised beds?'                  => 'A simple, reliable mix is equal parts screened topsoil and compost. Square foot gardeners often use one third compost, one third peat moss or coir, and one third vermiculite. Bagged raised-bed mixes are convenient for small beds.',
	);

	$rb_content = <<<'HTML'
<p class="gl-lede">Work out exactly how much soil you need to fill one raised bed or a whole garden of them. Add rectangular or round beds, pick a soil recipe, and get cubic feet, cubic yards, a bag count for each ingredient and a side-by-side cost of buying bagged versus bulk.</p>

[gl_raised_bed]

<h2>How to use this calculator</h2>
<ol>
<li><strong>Add each bed.</strong> Choose rectangle or round, enter the size in feet and the soil depth in inches. If you have several identical beds, set the quantity instead of adding them one by one.</li>
<li><strong>Add filler, if any.</strong> For deep beds you plan to fill partly with logs or branches, enter the filler depth and the calculator subtracts it.</li>
<li><strong>Choose an allowance for settling.</strong> Fresh soil sinks as it settles and gets watered, so plan for some extra.</li>
<li><strong>Pick your soil recipe</strong> and the bag size you plan to buy.</li>
<li><strong>Optional: enter prices</strong> for a bag and for a cubic yard delivered in bulk, to see which is cheaper.</li>
</ol>

<h2>The formulas</h2>
<ul>
<li><strong>Rectangular bed:</strong> cubic feet = length (ft) × width (ft) × depth (in) ÷ 12</li>
<li><strong>Round bed:</strong> cubic feet = 3.1416 × (diameter ÷ 2)² × depth (in) ÷ 12</li>
<li><strong>Cubic yards</strong> = cubic feet ÷ 27</li>
<li><strong>Bags</strong> = cubic feet of an ingredient ÷ bag size, rounded up</li>
</ul>

<h2>Worked example</h2>
<p>Two 4 × 8 foot beds, each 12 inches deep, filled with a 50/50 mix of topsoil and compost, with a 10% allowance for settling:</p>
<ul>
<li>Each bed: 4 × 8 × 12 ÷ 12 = 32 cubic feet, so two beds hold <strong>64 cubic feet</strong></li>
<li>With 10% extra: 64 × 1.1 = <strong>70.4 cubic feet</strong>, or about <strong>2.6 cubic yards</strong></li>
<li>Half topsoil, half compost: 35.2 cubic feet of each, which is <strong>18 bags of 2 cubic feet</strong> of each, or about 1.3 cubic yards of each in bulk</li>
</ul>

<h2>Common raised bed soil recipes</h2>
<table class="gl-table">
<thead><tr><th>Recipe</th><th>What goes in</th><th>Good for</th></tr></thead>
<tbody>
<tr><td>Simple 50/50</td><td>Screened topsoil and compost in equal parts</td><td>Large beds on a budget; easy to buy in bulk</td></tr>
<tr><td>Square foot mix</td><td>⅓ compost, ⅓ peat moss or coir, ⅓ coarse vermiculite</td><td>Light, fluffy beds that hold moisture well</td></tr>
<tr><td>Bagged raised bed mix</td><td>A ready-made product</td><td>Small beds and container-style beds</td></tr>
</tbody>
</table>
<p>Whatever you choose, avoid filling beds with plain native clay or with compost alone. Pure compost shrinks a lot as it breaks down, and heavy clay drains poorly in a bed.</p>

<h2>Bags versus bulk</h2>
<p>One cubic yard is 27 cubic feet, the same as 13.5 bags of 2 cubic feet or 18 bags of 1.5 cubic feet. Garden centers and landscape suppliers sell soil and compost by the yard, and for bigger projects the price per cubic foot is usually lower than bags. Small loads may carry a delivery fee, so compare the totals, not just the unit price. The calculator shows both side by side once you enter your local prices.</p>

<h2>Tips for filling raised beds</h2>
<ul>
<li><strong>Fill in layers and water as you go.</strong> Wetting each few inches helps the soil settle before you plant.</li>
<li><strong>Top up every year.</strong> Beds usually sink a little each season. An inch or two of compost each spring keeps them full and feeds the soil.</li>
<li><strong>Use untreated wood for filler.</strong> Avoid pressure-treated or painted lumber in a vegetable bed.</li>
</ul>
HTML;

	$defs['raised_bed'] = array(
		'title'   => 'Raised Bed Soil Calculator',
		'slug'    => 'raised-bed-soil-calculator',
		'excerpt' => 'Soil volume, bag counts and bagged vs bulk cost for one or many raised beds.',
		'content' => $rb_content . "\n" . gl_tools_faq_html( $rb_faq ),
		'faq'     => $rb_faq,
		'order'   => 3,
	);

	/* ---------------------------------------------------------------
	 * Lawn fertilizer calculator
	 * ------------------------------------------------------------- */
	$fert_faq = array(
		'How do I calculate how much fertilizer to put on my lawn?'      => 'Divide the pounds of nitrogen you want per 1,000 square feet by the nitrogen percentage on the bag (written as a decimal). For example, 1 pound of nitrogen from a 32-0-4 fertilizer is 1 ÷ 0.32 = 3.1 pounds of product per 1,000 square feet. Multiply by your lawn area in thousands of square feet for the total.',
		'What do the three numbers on a fertilizer bag mean?'            => 'They are the percentages by weight of nitrogen (N), phosphate (P2O5) and potash (K2O). A 50-pound bag of 24-0-6 contains 12 pounds of nitrogen, no phosphate and 3 pounds of potash.',
		'How much nitrogen should I apply at one time?'                 => 'Extension guides commonly cap quick-release (water-soluble) nitrogen at about 1 pound per 1,000 square feet per application, and Virginia Tech recommends no more than 0.9 pound. Slow-release products can be applied at somewhat higher rates following the label.',
		'How many times a year should I fertilize my lawn?'             => 'It depends on your grass. UConn Extension suggests about 2 pounds of nitrogen per 1,000 square feet a year for cool-season lawns when clippings are left on the lawn, often split between late spring and early fall. Warm-season grasses are fed while they are actively growing in late spring and summer; centipede and zoysia need less than Bermuda or St. Augustine.',
		'When is the best time to fertilize a cool-season lawn?'        => 'Late spring and early fall are the main windows for fescue, bluegrass and ryegrass. Fall feeding is especially valuable because it helps the grass recover from summer and store energy for spring.',
	);

	$fert_content = <<<'HTML'
<p class="gl-lede">Find out exactly how much fertilizer to spread for any product on the shelf. Enter the three numbers on the bag and your lawn size, choose how much nitrogen you want to apply, and get pounds of product, bags to buy and a yearly feeding plan for your type of grass.</p>

[gl_fertilizer]

<h2>How to use this calculator</h2>
<ol>
<li><strong>Measure your lawn.</strong> Add each area of lawn separately (front, back, side) or enter one total. Length × width works for rectangles; for odd shapes, break the lawn into rough rectangles.</li>
<li><strong>Enter the fertilizer analysis</strong>, the three numbers on the bag such as 24-0-6, plus the bag weight.</li>
<li><strong>Choose a nitrogen rate.</strong> 0.5 to 1 pound of nitrogen per 1,000 square feet per application is the usual range.</li>
<li><strong>Pick quick- or slow-release.</strong> The calculator warns you if a quick-release rate is higher than extension guides recommend.</li>
<li><strong>Choose your grass type</strong> to see roughly how many feedings a year it needs.</li>
</ol>

<h2>The formula</h2>
<ul>
<li><strong>Product per 1,000 sq ft</strong> = nitrogen rate ÷ (nitrogen % ÷ 100)</li>
<li><strong>Product for your lawn</strong> = product per 1,000 sq ft × lawn area ÷ 1,000</li>
<li><strong>Bags</strong> = product for your lawn ÷ bag weight, rounded up</li>
</ul>
<p>Lawn fertilizer recommendations are given in pounds of <em>nitrogen</em>, not pounds of product, because nitrogen is the nutrient lawns use most. That is why the same lawn needs very different amounts of a 10-10-10 and a 32-0-4.</p>

<h2>Worked example</h2>
<p>You want to apply 1 pound of nitrogen per 1,000 square feet to a 6,000 square foot lawn using a 24-0-6 fertilizer sold in 25-pound bags.</p>
<ul>
<li>Product per 1,000 sq ft: 1 ÷ 0.24 = <strong>4.17 pounds</strong></li>
<li>Product for the lawn: 4.17 × 6,000 ÷ 1,000 = <strong>25 pounds</strong>, exactly <strong>1 bag</strong></li>
<li>That also delivers 4.17 × 0.06 = 0.25 pound of potash per 1,000 square feet</li>
</ul>
<p>Iowa State University Extension uses the same method: its example of 1 pound of nitrogen from a 32-0-4 fertilizer works out to 3.125 pounds of product per 1,000 square feet.</p>

<h2>How much nitrogen a year?</h2>
<table class="gl-table">
<thead><tr><th>Grass</th><th>Typical yearly nitrogen (lb per 1,000 sq ft)</th><th>When to feed</th></tr></thead>
<tbody>
<tr><td>Cool-season (fescue, bluegrass, ryegrass)</td><td>About 2 with clippings left on the lawn; about 3 if clippings are removed</td><td>Late spring and early fall</td></tr>
<tr><td>Bermuda, St. Augustine</td><td>About 2 to 3.5, depending on how much growth you want</td><td>Late spring through summer</td></tr>
<tr><td>Zoysia, centipede</td><td>About 1 to 2</td><td>Late spring through summer</td></tr>
</tbody>
</table>
<p>These are general figures from university extension guides. A soil test and your own state extension service will give the most accurate advice for your region.</p>

<h2>Applying fertilizer evenly</h2>
<ul>
<li><strong>Use a spreader and make two passes</strong> at half the rate, the second at right angles to the first. This avoids stripes.</li>
<li><strong>Sweep granules off hard surfaces</strong> such as driveways and sidewalks back onto the lawn, so they don't wash into storm drains.</li>
<li><strong>Water lightly afterward</strong> if the label says to, and don't fertilize right before heavy rain.</li>
<li><strong>Skip dormant lawns.</strong> Feeding grass that isn't growing, such as warm-season grass in winter or any lawn in a drought, wastes fertilizer.</li>
<li><strong>Check local rules on phosphorus.</strong> Several states restrict phosphorus on established lawns unless a soil test shows a need.</li>
</ul>

<h2>Sources</h2>
<ul>
<li><a href="https://yardandgarden.extension.iastate.edu/how-to/how-calculate-fertilizer-rates-lawn" rel="noopener" target="_blank">Iowa State University Extension: How to calculate fertilizer rates for the lawn</a></li>
<li><a href="https://soiltesting-cahnr.media.uconn.edu/wp-content/uploads/sites/3514/2022/06/Lawns.pdf" rel="noopener" target="_blank">UConn Soil Nutrient Analysis Laboratory: Lawns</a></li>
<li><a href="https://www.pubs.ext.vt.edu/452/452-718/452-718.html" rel="noopener" target="_blank">Virginia Cooperative Extension: Soil test note for lawns</a></li>
</ul>
HTML;

	$defs['fertilizer'] = array(
		'title'   => 'Lawn Fertilizer Calculator',
		'slug'    => 'lawn-fertilizer-calculator',
		'excerpt' => 'Pounds of product and bags for any N-P-K fertilizer, with a yearly plan by grass type.',
		'content' => $fert_content . "\n" . gl_tools_faq_html( $fert_faq ),
		'faq'     => $fert_faq,
		'order'   => 4,
	);

	/* ---------------------------------------------------------------
	 * Lawn lime calculator
	 * ------------------------------------------------------------- */
	$lime_faq = array(
		'How much lime can I put on my lawn at once?'                     => 'For an established lawn, Virginia Cooperative Extension recommends no more than 50 pounds of lime per 1,000 square feet in a single application. If your soil test calls for more, split it into several applications spaced 1 to 6 months apart.',
		'How do I convert tons per acre to pounds per 1,000 square feet?' => 'Multiply tons per acre by 45.9. An acre is 43,560 square feet, so 1 ton (2,000 pounds) per acre equals about 45.9 pounds per 1,000 square feet. To convert pounds per acre, divide by 43.56.',
		'Is pelletized lime better than ground lime?'                     => 'Both work the same way once they dissolve. Pelletized lime is ground limestone pressed into pellets, so it spreads more evenly with less dust through a lawn spreader. Compare products by the neutralizing value (calcium carbonate equivalent) on the label, not by form.',
		'Should I use dolomitic or calcitic lime?'                        => 'Dolomitic lime contains magnesium as well as calcium, so it is the better choice when your soil test shows low magnesium. If magnesium is adequate, calcitic lime is fine.',
		'How long does lime take to work?'                                => 'Lime reacts slowly. It can take several months to a year or more to fully change soil pH, which is why recommendations are spread over time and why it helps to retest the soil every two to three years.',
	);

	$lime_content = <<<'HTML'
<p class="gl-lede">Turn the lime recommendation on your soil test into the number of bags to buy and a safe application schedule. The calculator converts any units your lab uses, adjusts for the strength of the product you buy, and splits large amounts into applications your lawn can handle.</p>

[gl_lime]

<h2>Start with a soil test</h2>
<p>Lime raises soil pH, but how much a lawn needs depends on both its current pH and how strongly the soil resists change, which varies a lot between sandy and clay soils. Only a lab test measures that, so this calculator works from your lab's recommendation rather than guessing. Most state university extension services offer inexpensive soil tests for homeowners; the report tells you whether to lime and how much.</p>
<p>If your report recommends <em>sulfur</em> instead, your soil is too alkaline and needs its pH lowered. Lime would make that worse.</p>

<h2>How to use this calculator</h2>
<ol>
<li><strong>Enter the recommendation from your soil test</strong> and choose its unit: pounds per 1,000 square feet, pounds per acre, or tons per acre.</li>
<li><strong>Enter your lawn area.</strong></li>
<li><strong>Enter the product's neutralizing value</strong> (calcium carbonate equivalent, or CCE) from the bag. If your lab's recommendation assumes a different value than 100%, enter that too; your report usually says.</li>
<li><strong>Choose established lawn or new lawn.</strong> Established lawns get the amount split into applications of up to 50 pounds per 1,000 square feet.</li>
</ol>

<h2>The formulas</h2>
<ul>
<li><strong>Tons per acre to lb per 1,000 sq ft:</strong> multiply by 45.9</li>
<li><strong>Lb per acre to lb per 1,000 sq ft:</strong> divide by 43.56</li>
<li><strong>Adjusting for the product:</strong> product needed = recommendation × (CCE the lab assumed ÷ CCE of your product)</li>
<li><strong>Applications for an established lawn</strong> = product per 1,000 sq ft ÷ 50, rounded up</li>
</ul>

<h2>Worked example</h2>
<p>Your soil test recommends 2 tons of lime per acre, based on 100% CCE. Your lawn is 5,000 square feet and you buy pelletized lime with a CCE of 90%, in 40-pound bags.</p>
<ul>
<li>2 tons per acre × 45.9 = <strong>91.8 lb per 1,000 sq ft</strong></li>
<li>Adjusted for the product: 91.8 × 100 ÷ 90 = <strong>102 lb per 1,000 sq ft</strong></li>
<li>For the lawn: 102 × 5 = <strong>510 pounds</strong>, or <strong>13 bags</strong> of 40 pounds</li>
<li>Because 102 is more than 50, an established lawn gets it in <strong>3 applications of 34 lb per 1,000 sq ft</strong> (170 pounds each), spaced 1 to 6 months apart</li>
</ul>

<h2>Applying lime</h2>
<ul>
<li><strong>Use a spreader</strong> and make two passes at half the rate in different directions for even coverage.</li>
<li><strong>Fall and early spring</strong> are convenient times; lime can go down whenever the ground isn't frozen or waterlogged.</li>
<li><strong>For a new lawn,</strong> work lime into the top few inches of soil before seeding or laying sod, where it acts fastest.</li>
<li><strong>Retest every 2 to 3 years.</strong> Lime works slowly, and a fresh test tells you when to add more.</li>
</ul>

<h2>Sources</h2>
<ul>
<li><a href="https://www.pubs.ext.vt.edu/452/452-718/452-718.html" rel="noopener" target="_blank">Virginia Cooperative Extension: Soil test note for lawns</a></li>
<li><a href="https://soiltesting-cahnr.media.uconn.edu/wp-content/uploads/sites/3514/2022/06/Lawns.pdf" rel="noopener" target="_blank">UConn Soil Nutrient Analysis Laboratory: Lawns</a></li>
</ul>
HTML;

	$defs['lime'] = array(
		'title'   => 'Lawn Lime Calculator',
		'slug'    => 'lawn-lime-calculator',
		'excerpt' => 'Convert a soil test lime recommendation into bags and a safe application schedule.',
		'content' => $lime_content . "\n" . gl_tools_faq_html( $lime_faq ),
		'faq'     => $lime_faq,
		'order'   => 5,
	);

	/* ---------------------------------------------------------------
	 * Mulch calculator
	 * ------------------------------------------------------------- */
	$mulch_faq = array(
		'How much mulch do I need?'                          => 'Multiply the area in square feet by the depth in inches and divide by 12 to get cubic feet. Divide by 27 for cubic yards. For example, 100 square feet at 3 inches deep needs 25 cubic feet, about 0.93 cubic yards or 13 bags of 2 cubic feet.',
		'How deep should mulch be?'                          => 'University extension guides, such as the University of Illinois Extension, recommend 2 to 3 inches for landscape beds and around trees. Deeper mulch can keep water and air from reaching roots.',
		'How many bags of mulch are in a yard?'              => 'One cubic yard is 27 cubic feet, so it equals 13.5 bags of 2 cubic feet, 18 bags of 1.5 cubic feet, or 9 bags of 3 cubic feet.',
		'Should mulch touch the tree trunk?'                 => 'No. Piling mulch against a trunk, often called a mulch volcano, keeps the bark wet and can lead to rot and other damage. Keep a bare circle a few inches wide around the trunk and spread the mulch outward in a wide, flat ring.',
		'Do I need to remove old mulch before adding new?'   => 'Usually not. Check how deep the old layer is first. If it is already 2 to 3 inches, just rake it to freshen it. If it has broken down to an inch or so, add only enough to bring it back to 2 to 3 inches. Enter the existing depth in the calculator to get the top-up amount.',
	);
	$mulch_content = <<<'HTML'
<p class="gl-lede">Work out how much mulch to buy for every bed and tree ring in your yard at once. Add each area, choose your depth, and get the number of bags or cubic yards, with a side-by-side cost of bagged versus bulk mulch.</p>

[gl_mulch]

<h2>How to use this calculator</h2>
<ol>
<li><strong>Add each bed or tree.</strong> Choose rectangle, circle or ring around a tree, or type the square feet if you already know them. For irregular beds, break them into rough rectangles.</li>
<li><strong>For trees, enter the ring size and a bare circle at the trunk.</strong> The calculator leaves that circle out, because mulch should never touch the trunk.</li>
<li><strong>Pick a depth.</strong> Two to three inches is the usual recommendation.</li>
<li><strong>If you're topping up,</strong> enter how much mulch is already there. The calculator only adds the difference.</li>
<li><strong>Optional: enter prices</strong> per bag and per cubic yard to compare bagged and bulk.</li>
</ol>

<h2>The formula</h2>
<ul>
<li><strong>Cubic feet</strong> = area (sq ft) × depth (in) ÷ 12</li>
<li><strong>Cubic yards</strong> = cubic feet ÷ 27</li>
<li><strong>Ring around a tree</strong> = 3.1416 × (outer radius² − bare-center radius²)</li>
<li><strong>Bags</strong> = cubic feet ÷ bag size, rounded up</li>
</ul>

<h2>Worked example</h2>
<p>A 20 × 4 foot front bed and a 6-foot mulch ring around a maple, with a 1-foot bare circle at the trunk, mulched 3 inches deep:</p>
<ul>
<li>Front bed: 20 × 4 = <strong>80 sq ft</strong></li>
<li>Tree ring: 3.1416 × (3² − 0.5²) ≈ <strong>27.5 sq ft</strong></li>
<li>Total: 107.5 sq ft × 3 ÷ 12 ≈ <strong>26.9 cubic feet</strong>, just under <strong>1 cubic yard</strong></li>
<li>In 2-cubic-foot bags: 26.9 ÷ 2 = 13.4, so <strong>14 bags</strong></li>
</ul>

<h2>How deep should mulch be?</h2>
<p>A layer of 2 to 3 inches is enough to hold moisture, keep soil temperatures even and smother most weed seedlings. The University of Illinois Extension notes that fresh mulch looks deeper than it is and settles, and that adding a new layer every year without checking can build it up too deep. More than about 3 inches can starve roots of air and water.</p>

<h2>Avoid the mulch volcano</h2>
<p>Mulch heaped up against a tree trunk looks tidy but keeps the bark constantly damp and dark, which invites rot, insects and disease, and can encourage roots to circle the trunk. Instead, spread mulch in a wide, flat ring, as wide as practical, and leave a bare circle a few inches across around the trunk itself.</p>

<h2>Bags or bulk?</h2>
<p>One cubic yard is 27 cubic feet, the same as 13.5 bags of 2 cubic feet. For a yard or more, bulk mulch from a landscape supplier usually costs much less per cubic foot than bags, but check delivery fees on small loads. Bulk is normally sold in half- or whole-yard steps, so the calculator rounds bulk orders up to the next half yard.</p>

<h2>Sources</h2>
<ul>
<li><a href="https://extension.illinois.edu/news-releases/too-much-good-thing-mulch-volcanoes-slowly-kill-trees" rel="noopener" target="_blank">University of Illinois Extension: Too much of a good thing, mulch volcanoes slowly kill trees</a></li>
<li><a href="https://extension.uconn.edu/2013/10/09/mulch-volcanoes-a-growing-problem" rel="noopener" target="_blank">UConn Extension: Mulch volcanoes, a growing problem</a></li>
</ul>
HTML;
	$defs['mulch'] = array(
		'title'   => 'Mulch Calculator',
		'slug'    => 'mulch-calculator',
		'excerpt' => 'How many bags or cubic yards of mulch for all your beds and tree rings, with bagged vs bulk cost.',
		'content' => $mulch_content . "\n" . gl_tools_faq_html( $mulch_faq ),
		'faq'     => $mulch_faq,
		'order'   => 6,
	);

	/* ---------------------------------------------------------------
	 * Compost calculator
	 * ------------------------------------------------------------- */
	$compost_faq = array(
		'How much compost should I add to my garden?'          => 'Oregon State University Extension suggests about 3 to 4 inches mixed into the soil when starting new garden or landscape beds, and about ¼ to 1 inch a year as a top-up for established vegetable beds.',
		'How much compost do I need per 1,000 square feet?'    => 'One inch of compost over 1,000 square feet is about 83 cubic feet, or roughly 3.1 cubic yards. Multiply that by the depth in inches you plan to apply.',
		'How much compost for a new lawn?'                     => 'Oregon State University Extension suggests mixing 1 to 2 inches of compost into the soil before seeding or laying sod, which is about 3.1 to 6.2 cubic yards per 1,000 square feet.',
		'Can I topdress my lawn with compost?'                 => 'Yes. A thin layer of about ¼ to ½ inch, raked into the grass so the blades still show, improves the soil over time. It works especially well right after core aeration.',
		'How many bags of compost are in a cubic yard?'        => 'A cubic yard is 27 cubic feet: 27 bags of 1 cubic foot, 18 bags of 1.5 cubic feet, or 36 bags of 0.75 cubic feet.',
	);
	$compost_content = <<<'HTML'
<p class="gl-lede">Find out how much compost to buy for a new bed, a yearly top-up or your lawn. Pick the project and the calculator uses the depth university extension guides recommend, then gives cubic feet, cubic yards and bags.</p>

[gl_compost]

<h2>How to use this calculator</h2>
<ol>
<li><strong>Choose your project.</strong> Each one uses a recommended depth; choose "Custom depth" to set your own.</li>
<li><strong>Add the area</strong> you want to cover, as one or more rectangles, circles or a known square footage.</li>
<li><strong>Pick a bag size</strong> and, optionally, enter bag and bulk prices to compare costs.</li>
</ol>

<h2>Recommended compost depths</h2>
<table class="gl-table">
<thead><tr><th>Project</th><th>Depth</th><th>Cubic yards per 1,000 sq ft</th></tr></thead>
<tbody>
<tr><td>New garden or landscape bed (mixed in)</td><td>3–4 in</td><td>9.3–12.3</td></tr>
<tr><td>Established vegetable bed (yearly top-up)</td><td>¼–1 in</td><td>0.8–3.1</td></tr>
<tr><td>New lawn (mixed in before seeding or sod)</td><td>1–2 in</td><td>3.1–6.2</td></tr>
<tr><td>Existing lawn (topdressing)</td><td>¼–½ in</td><td>0.8–1.5</td></tr>
</tbody>
</table>
<p>These depths come from Oregon State University Extension's guide to using compost. The calculator uses 3 inches for new beds, 1 inch for top-ups, 2 inches for new lawns and ¼ inch for topdressing, all of which you can change.</p>

<h2>The formula</h2>
<ul>
<li><strong>Cubic feet</strong> = area (sq ft) × depth (in) ÷ 12</li>
<li><strong>Cubic yards</strong> = cubic feet ÷ 27</li>
<li><strong>Bags</strong> = cubic feet ÷ bag size, rounded up</li>
</ul>

<h2>Worked examples</h2>
<p><strong>New vegetable garden.</strong> A 20 × 10 foot plot with 3 inches of compost mixed in: 200 × 3 ÷ 12 = <strong>50 cubic feet</strong>, about <strong>1.85 cubic yards</strong>. That is 50 bags of 1 cubic foot, so this is a job where bulk compost usually saves money.</p>
<p><strong>Lawn topdressing.</strong> A 4,000 square foot lawn with ¼ inch of compost: 4,000 × 0.25 ÷ 12 ≈ <strong>83 cubic feet</strong>, about <strong>3.1 cubic yards</strong>.</p>

<h2>Tips for using compost</h2>
<ul>
<li><strong>Mix it in for new beds.</strong> Spread the compost, then dig or till it into the top 6 to 8 inches of soil so roots grow into improved soil, not just a layer on top.</li>
<li><strong>Use mature compost.</strong> Finished compost is dark, crumbly and smells earthy. Raw or hot compost can tie up nitrogen and harm young plants.</li>
<li><strong>Don't overdo it every year.</strong> Once beds are established, a thin yearly layer is plenty. Very heavy yearly applications can build up more nutrients, such as phosphorus, than plants need.</li>
</ul>

<h2>Sources</h2>
<ul>
<li><a href="https://extension.oregonstate.edu/catalog/em-9308-how-use-compost-gardens-landscapes" rel="noopener" target="_blank">Oregon State University Extension: How to use compost in gardens and landscapes (EM 9308)</a></li>
</ul>
HTML;
	$defs['compost'] = array(
		'title'   => 'Compost Calculator',
		'slug'    => 'compost-calculator',
		'excerpt' => 'How much compost for new beds, yearly top-ups and lawns, in cubic feet, cubic yards and bags.',
		'content' => $compost_content . "\n" . gl_tools_faq_html( $compost_faq ),
		'faq'     => $compost_faq,
		'order'   => 7,
	);

	/* ---------------------------------------------------------------
	 * Sod calculator
	 * ------------------------------------------------------------- */
	$sod_faq = array(
		'How many square feet are in a pallet of sod?'   => 'Most pallets hold between 400 and 700 square feet. Slab pallets are commonly sold as 400, 450 or 500 square feet, and pallets of large rolls as 500 to 700. Always check your supplier, since sizes vary by farm and grass.',
		'How big is a piece of sod?'                     => 'Slabs, common in the South, are usually 16 by 24 inches, about 2.67 square feet. Mini rolls, common in the North, are often 18 by 40 inches, or 5 square feet. Large rolls are about 10 square feet.',
		'How much extra sod should I order?'             => 'Order about 5% extra for simple rectangular lawns and up to 10% for curved edges, beds and trees, to cover cuts and measuring errors.',
		'How many pieces of sod do I need?'              => 'Divide the area in square feet, plus your waste allowance, by the size of one piece. For 1,000 square feet plus 5% using 16 by 24 inch slabs: 1,050 ÷ 2.67 ≈ 394 slabs.',
		'How soon should I lay sod after delivery?'      => 'The same day if possible. Sod stacked on a pallet heats up and dries out quickly, especially in warm weather, so have the soil prepared before it arrives.',
	);
	$sod_content = <<<'HTML'
<p class="gl-lede">Measure your lawn areas and find out exactly how many pallets and pieces of sod to order. The calculator handles slabs and rolls, adds extra for cuts, and shows a partial-pallet option if your supplier sells sod by the piece.</p>

[gl_sod]

<h2>How to use this calculator</h2>
<ol>
<li><strong>Add each area to sod.</strong> Split odd shapes into rectangles and circles, or enter square feet directly.</li>
<li><strong>Choose how your sod is sold:</strong> slabs, mini rolls or large rolls, and the square feet per pallet your supplier lists.</li>
<li><strong>Pick an allowance for waste.</strong> Use 5% for simple shapes and 10% when there are curves, beds or trees to cut around.</li>
<li><strong>Optional: enter the price per pallet</strong> to see the total.</li>
</ol>

<h2>The formula</h2>
<ul>
<li><strong>Sod needed</strong> = total area × (1 + waste % ÷ 100)</li>
<li><strong>Pieces</strong> = sod needed ÷ size of one piece, rounded up</li>
<li><strong>Pallets</strong> = sod needed ÷ square feet per pallet, rounded up</li>
</ul>

<h2>Worked example</h2>
<p>A 40 × 30 foot back yard, sodded with 16 × 24 inch slabs sold on 450-square-foot pallets, with 5% extra:</p>
<ul>
<li>Area: 40 × 30 = <strong>1,200 sq ft</strong>, plus 5% = <strong>1,260 sq ft</strong></li>
<li>Slabs: 1,260 ÷ 2.67 ≈ <strong>473 slabs</strong></li>
<li>Pallets: 1,260 ÷ 450 = 2.8, so <strong>3 pallets</strong>, leaving about 90 sq ft spare</li>
<li>Or, if partial pallets are available: <strong>2 pallets plus 135 slabs</strong></li>
</ul>

<h2>Common sod sizes</h2>
<table class="gl-table">
<thead><tr><th>Format</th><th>Typical piece size</th><th>Common pallet sizes</th></tr></thead>
<tbody>
<tr><td>Slabs (mostly southern US)</td><td>16 × 24 in, about 2.67 sq ft</td><td>400, 450 or 500 sq ft</td></tr>
<tr><td>Mini rolls (mostly northern US)</td><td>18 × 40 in, 5 sq ft</td><td>400, 450 or 500 sq ft</td></tr>
<tr><td>Large rolls</td><td>About 10 sq ft</td><td>500, 600 or 700 sq ft</td></tr>
</tbody>
</table>

<h2>Tips for laying sod</h2>
<ul>
<li><strong>Prepare the soil first.</strong> Remove old grass and debris, loosen the top few inches, work in compost if the soil is poor, and rake it level and firm.</li>
<li><strong>Stagger the joints</strong> like bricks, and butt the edges tightly without overlapping or stretching the pieces.</li>
<li><strong>Water right away and often.</strong> Keep the sod and the soil under it moist until the roots knit in, then water more deeply and less often.</li>
<li><strong>Stay off it</strong> as much as you can for the first couple of weeks.</li>
</ul>

<h2>Sources</h2>
<ul>
<li><a href="https://sodsolutions.com/sod-university/square-feet-per-pallet/" rel="noopener" target="_blank">Sod Solutions: How many square feet are in a pallet of sod?</a></li>
</ul>
HTML;
	$defs['sod'] = array(
		'title'   => 'Sod Calculator',
		'slug'    => 'sod-calculator',
		'excerpt' => 'How many pallets and pieces of sod to order, with extra for cuts and a partial-pallet option.',
		'content' => $sod_content . "\n" . gl_tools_faq_html( $sod_faq ),
		'faq'     => $sod_faq,
		'order'   => 8,
	);

	/* ---------------------------------------------------------------
	 * Grass seed calculator
	 * ------------------------------------------------------------- */
	$seed_faq = array(
		'How much grass seed do I need per 1,000 square feet?' => 'It depends on the grass. University of Tennessee Extension lists 5 to 8 pounds for tall fescue, 1½ to 2 pounds for Kentucky bluegrass, 4 to 6 pounds for perennial ryegrass and ½ to 1 pound for hulled bermudagrass per 1,000 square feet for a new lawn.',
		'How much seed do I need to overseed?'                 => 'A common rule of thumb is about half the new-lawn rate when overseeding a thin lawn. If your seed bag gives an overseeding rate, use that instead.',
		'Why do grass seeding rates vary so much?'             => 'Seed size varies enormously between grasses. A pound of Kentucky bluegrass contains far more seeds than a pound of tall fescue, so you need fewer pounds of bluegrass to get the same number of seeds per square foot.',
		'When is the best time to plant grass seed?'           => 'Sow cool-season grasses such as fescue, bluegrass and ryegrass in late summer to early fall, when soil is warm and air is cooling. Sow warm-season grasses such as bermudagrass and zoysiagrass in late spring to early summer, once the soil has warmed.',
		'What is the difference between hulled and unhulled bermudagrass seed?' => 'Hulled seed has had its outer husk removed, so it germinates faster and you need less of it. Unhulled seed is typically sown at about double the hulled rate.',
	);
	$seed_content = <<<'HTML'
<p class="gl-lede">Find out how many pounds of grass seed to buy for your lawn. Pick your grass type, choose a new lawn or overseeding, add your lawn areas, and get the amount, bags to buy and the best time to sow.</p>

[gl_grass_seed]

<h2>How to use this calculator</h2>
<ol>
<li><strong>Pick your grass type.</strong> For a blend or mix, choose the last option and enter the rate printed on the bag.</li>
<li><strong>Choose new lawn or overseeding.</strong> Overseeding a thin lawn uses about half the new-lawn rate.</li>
<li><strong>Add your lawn areas</strong> as rectangles, circles or known square feet.</li>
<li><strong>Enter your bag weight</strong> and, optionally, the price.</li>
</ol>

<h2>Seeding rates by grass type</h2>
<table class="gl-table">
<thead><tr><th>Grass</th><th>New lawn (lb per 1,000 sq ft)</th></tr></thead>
<tbody>
<tr><td>Tall fescue</td><td>5 – 8</td></tr>
<tr><td>Kentucky bluegrass</td><td>1½ – 2</td></tr>
<tr><td>Perennial ryegrass</td><td>4 – 6</td></tr>
<tr><td>Fine fescues</td><td>3 – 5</td></tr>
<tr><td>Bermudagrass, hulled</td><td>½ – 1</td></tr>
<tr><td>Bermudagrass, unhulled</td><td>1 – 2</td></tr>
<tr><td>Zoysiagrass</td><td>½ – 3</td></tr>
<tr><td>Centipedegrass</td><td>½ – 3</td></tr>
</tbody>
</table>
<p>Rates are from University of Tennessee Extension's guide to seeding turfgrass. The calculator uses the middle of each range as its recommendation and shows the full range too.</p>

<h2>The formula</h2>
<ul>
<li><strong>Seed needed (lb)</strong> = seeding rate (lb per 1,000 sq ft) × lawn area (sq ft) ÷ 1,000</li>
<li><strong>Overseeding</strong> uses about half the new-lawn rate</li>
<li><strong>Bags</strong> = seed needed ÷ bag weight, rounded up</li>
</ul>

<h2>Worked example</h2>
<p>A new tall fescue lawn made up of a 50 × 40 foot front lawn and a 60 × 50 foot back lawn:</p>
<ul>
<li>Area: 2,000 + 3,000 = <strong>5,000 sq ft</strong></li>
<li>Rate: the middle of 5–8 is <strong>6.5 lb per 1,000 sq ft</strong></li>
<li>Seed: 6.5 × 5,000 ÷ 1,000 = <strong>32.5 lb</strong> (full range 25–40 lb), or <strong>2 bags</strong> of 25 lb</li>
<li>Overseeding the same lawn instead: about <strong>16 lb</strong>, so 1 bag</li>
</ul>

<h2>Tips for a good stand of grass</h2>
<ul>
<li><strong>Sow at the right time.</strong> Late summer to early fall for cool-season grasses; late spring to early summer for warm-season grasses.</li>
<li><strong>Get seed in contact with soil.</strong> Rake the soil loose before seeding, then rake lightly or roll afterward so the seed is pressed in.</li>
<li><strong>Split the seed in two</strong> and spread half in one direction and half crosswise for even coverage.</li>
<li><strong>Keep the surface moist</strong> with light, frequent watering until the grass is up, then water more deeply and less often.</li>
<li><strong>More seed isn't better.</strong> Sowing far above the recommended rate crowds seedlings, which then compete and thin out.</li>
</ul>

<h2>Sources</h2>
<ul>
<li><a href="https://uthort.tennessee.edu/wp-content/uploads/sites/228/2023/11/W160-B.pdf" rel="noopener" target="_blank">University of Tennessee Extension: Turfgrass establishment, seeding (W160-B)</a></li>
</ul>
HTML;
	$defs['grass_seed'] = array(
		'title'   => 'Grass Seed Calculator',
		'slug'    => 'grass-seed-calculator',
		'excerpt' => 'Pounds of grass seed by grass type for a new lawn or overseeding, with bags and when to sow.',
		'content' => $seed_content . "\n" . gl_tools_faq_html( $seed_faq ),
		'faq'     => $seed_faq,
		'order'   => 9,
	);

	/* ---------------------------------------------------------------
	 * Gravel calculator
	 * ------------------------------------------------------------- */
	$gravel_faq = array(
		'How much does a cubic yard of gravel weigh?'          => 'Clean crushed stone typically weighs about 2,700 to 2,800 pounds per cubic yard, roughly 1.35 to 1.4 tons, according to aggregate supplier Select Sand & Gravel. Crusher run or road base, which contains stone dust, weighs more, up to about 1.5 tons. Weight varies with the type of rock and its moisture.',
		'How do I convert cubic yards of gravel to tons?'      => 'Multiply cubic yards by the stone’s weight in tons per cubic yard. For crushed stone at about 1.4 tons per yard, 3 cubic yards is about 4.2 tons. Ask your supplier for their own figure if you are buying by weight.',
		'How many bags of gravel are in a cubic yard?'         => 'A cubic yard is 27 cubic feet, so it equals 54 bags of 0.5 cubic feet or 36 bags of 0.75 cubic feet. For more than a yard or so, bulk delivery is usually much cheaper than bags.',
		'How deep should gravel be for a walkway?'             => 'Two to three inches of surface gravel is common for a walkway or patio, over a firm, well-drained base. Driveways need much more: several inches of base stone under the surface layer.',
		'Should I order extra gravel?'                         => 'For compacted stone such as a driveway base, order about 5 to 10% extra to allow for compaction and minor variations. Loose decorative stone needs little or no extra.',
	);
	$gravel_content = <<<'HTML'
<p class="gl-lede">Work out how much gravel or crushed stone you need in cubic yards, tons and bags. Add your paths, patios or driveway, choose the stone and depth, and compare buying by the ton, by the yard or in bags.</p>

[gl_gravel]

<h2>How to use this calculator</h2>
<ol>
<li><strong>Add each area</strong> as a rectangle, a circle or a known number of square feet.</li>
<li><strong>Choose the type of stone.</strong> Each has a typical weight; pick "My supplier’s figure" to enter the exact tons per cubic yard from your supplier.</li>
<li><strong>Choose the depth</strong> and whether to add extra for compaction.</li>
<li><strong>Optional: enter prices</strong> per ton, per yard or per bag to see which is cheapest.</li>
</ol>

<h2>The formulas</h2>
<ul>
<li><strong>Cubic feet</strong> = area (sq ft) × depth (in) ÷ 12, plus any compaction allowance</li>
<li><strong>Cubic yards</strong> = cubic feet ÷ 27</li>
<li><strong>Tons</strong> = cubic yards × tons per cubic yard</li>
<li><strong>Bags</strong> = cubic feet ÷ bag size, rounded up</li>
</ul>

<h2>Worked example</h2>
<p>A garden path 30 feet long and 3 feet wide, covered with 3 inches of crushed stone, plus 5% for compaction:</p>
<ul>
<li>Area: 30 × 3 = <strong>90 sq ft</strong></li>
<li>Volume: 90 × 3 ÷ 12 = 22.5 cu ft, plus 5% = <strong>23.6 cubic feet</strong>, about <strong>0.88 cubic yards</strong></li>
<li>Weight at 1.4 tons per yard: 0.88 × 1.4 ≈ <strong>1.2 tons</strong></li>
<li>In 0.5-cubic-foot bags: 23.6 ÷ 0.5 = 47.3, so <strong>48 bags</strong>. At this size, a 1-ton or 1-yard bulk delivery is usually cheaper.</li>
</ul>

<h2>Typical stone weights</h2>
<table class="gl-table">
<thead><tr><th>Material</th><th>Typical tons per cubic yard</th></tr></thead>
<tbody>
<tr><td>Clean crushed stone (#57, #67)</td><td>About 1.35 – 1.4</td></tr>
<tr><td>Crusher run / road base (with fines)</td><td>About 1.4 – 1.5</td></tr>
<tr><td>Lava rock</td><td>About 0.65 – 0.9</td></tr>
</tbody>
</table>
<p>Crushed stone and road base figures are from aggregate supplier Select Sand & Gravel; lava rock is far lighter because it is porous. For pea gravel and river rock, the calculator starts from the crushed-stone figure. Denser rock such as basalt weighs more than limestone, and wet stone weighs more than dry, so if you are buying by the ton, ask your supplier for their tons-per-yard figure.</p>

<h2>Choosing a depth</h2>
<ul>
<li><strong>Decorative top layer over fabric or an existing base:</strong> about 2 inches.</li>
<li><strong>Walkways and patio surfaces:</strong> 2 to 3 inches over firm, well-drained soil or a compacted base.</li>
<li><strong>Driveways:</strong> a deeper, compacted base of larger crushed stone or road base, topped with a few inches of surface stone. Build it in layers and compact each one.</li>
</ul>
<p>Laying pavers rather than loose gravel? Use our <a href="/paver-base-calculator/">paver base calculator</a>, which adds bedding sand and widens the base past the edges.</p>

<h2>Sources</h2>
<ul>
<li><a href="https://selectsg.com/how-much-does-a-yard-of-crushed-stone-weigh/" rel="noopener" target="_blank">Select Sand & Gravel: How much does a yard of crushed stone weigh?</a></li>
<li><a href="https://selectsg.com/how-much-does-a-yard-of-road-base-weigh/" rel="noopener" target="_blank">Select Sand & Gravel: How much does a yard of road base weigh?</a></li>
</ul>
HTML;
	$defs['gravel'] = array(
		'title'   => 'Gravel Calculator',
		'slug'    => 'gravel-calculator',
		'excerpt' => 'How much gravel or crushed stone you need in cubic yards, tons and bags, with cost by the ton, yard or bag.',
		'content' => $gravel_content . "\n" . gl_tools_faq_html( $gravel_faq ),
		'faq'     => $gravel_faq,
		'order'   => 10,
	);

	/* ---------------------------------------------------------------
	 * Topsoil calculator
	 * ------------------------------------------------------------- */
	$topsoil_faq = array(
		'How much topsoil do I need to level my lawn?'           => 'Measure each low area and its average depth, then multiply area by depth in inches and divide by 12 for cubic feet. A 10 by 6 foot dip that is 1½ inches deep needs 7.5 cubic feet, spread in three passes of no more than ½ inch each.',
		'How thick can I topdress a lawn at one time?'           => 'Clemson Cooperative Extension recommends no more than ½ inch of material at a time. Thicker layers can smother and stunt the grass. Let the grass grow up through each layer before adding more.',
		'Should I use sand or topsoil to level a lawn?'          => 'Match the material to your existing soil. Clemson Extension advises using sand only if your native soil is sandy; otherwise use a topsoil similar to what you have. Layers of very different materials can interfere with water movement.',
		'How deep should topsoil be for a new lawn?'             => 'The University of Nebraska–Lincoln recommends at least 4 inches of topsoil under a new lawn from sod, and Iowa State suggests 4 to 6 inches of good topsoil when seeding.',
		'How many bags of topsoil are in a cubic yard?'          => 'A cubic yard is 27 cubic feet: 36 bags of 0.75 cubic feet or 27 bags of 1 cubic foot. Above a cubic yard, bulk topsoil is usually far cheaper.',
	);
	$topsoil_content = <<<'HTML'
<p class="gl-lede">Find out how much topsoil you need to level low spots in your lawn, put down a base for a new lawn, or fill and raise an area. For lawn leveling, the calculator also splits the job into safe, thin passes so the grass survives.</p>

[gl_topsoil]

<h2>How to use this calculator</h2>
<ol>
<li><strong>Choose the job.</strong> Leveling low spots, a topsoil layer for a new lawn, or filling an area. Each sets a starting depth you can change.</li>
<li><strong>Add the areas.</strong> For lawn dips, measure each low spot and estimate its average depth with a straightedge and ruler.</li>
<li><strong>Pick a bag size</strong> and, optionally, enter bag and bulk prices.</li>
</ol>

<h2>The formulas</h2>
<ul>
<li><strong>Cubic feet</strong> = area (sq ft) × depth (in) ÷ 12</li>
<li><strong>Cubic yards</strong> = cubic feet ÷ 27</li>
<li><strong>Leveling passes</strong> = depth ÷ ½ inch, rounded up</li>
</ul>

<h2>Worked example: leveling a dip</h2>
<p>A low spot about 10 feet by 6 feet, averaging 1½ inches deep:</p>
<ul>
<li>Volume: 10 × 6 × 1.5 ÷ 12 = <strong>7.5 cubic feet</strong></li>
<li>Passes: 1.5 ÷ 0.5 = <strong>3 passes</strong> of about 2.5 cubic feet each</li>
<li>In 0.75-cubic-foot bags: <strong>10 bags</strong> in total, about 4 per pass</li>
</ul>

<h2>Leveling a lawn the right way</h2>
<p>Clemson Cooperative Extension recommends "light and frequent" topdressing, with no more than ½ inch applied at a time; heavier layers can stunt the grass or shade it out. Rake the soil so it sifts down between the grass blades, and let the grass grow up through it before the next pass. Topdress while the lawn is actively growing.</p>
<ul>
<li><strong>Match your soil.</strong> Use sand only if your native soil is sandy. On other soils, use a screened topsoil similar to what you have.</li>
<li><strong>Check what you buy.</strong> Topsoil and river sand can carry weed seeds, and nutrient levels vary, so buy from a reputable supplier and consider testing it.</li>
<li><strong>For deep dips,</strong> many people cut and lift the sod, fill underneath with soil, firm it, and lay the sod back down rather than making many thin passes.</li>
</ul>

<h2>Topsoil for a new lawn</h2>
<p>A new lawn needs enough good soil for deep roots. The University of Nebraska–Lincoln recommends at least 4 inches of topsoil before laying sod, and Iowa State University suggests 4 to 6 inches of good topsoil when seeding. See our guides to <a href="/how-to-lay-sod/">laying sod</a> and <a href="/how-to-overseed-lawn/">overseeding</a>.</p>

<h2>Sources</h2>
<ul>
<li><a href="https://hgic.clemson.edu/factsheet/topdressing-a-home-lawn/" rel="noopener" target="_blank">Clemson Cooperative Extension: Topdressing a home lawn</a></li>
<li><a href="https://turf.unl.edu/sites/unl.edu.ianr.agronomy-horticulture.turf/files/media/file/Establishing-lawn-fromsod2012m.pdf" rel="noopener" target="_blank">University of Nebraska–Lincoln: Establishing lawns from sod</a></li>
<li><a href="https://yardandgarden.extension.iastate.edu/how-to/late-summer-and-fall-lawn-care" rel="noopener" target="_blank">Iowa State University Extension: Late summer and fall lawn care</a></li>
</ul>
HTML;
	$defs['topsoil'] = array(
		'title'   => 'Topsoil Calculator',
		'slug'    => 'topsoil-calculator',
		'excerpt' => 'Topsoil for leveling lawn low spots in safe ½-inch passes, new lawns or filling areas, in cubic yards and bags.',
		'content' => $topsoil_content . "\n" . gl_tools_faq_html( $topsoil_faq ),
		'faq'     => $topsoil_faq,
		'order'   => 11,
	);

	/* ---------------------------------------------------------------
	 * Paver base calculator
	 * ------------------------------------------------------------- */
	$paver_faq = array(
		'How deep should the base be for pavers?'          => 'Unilock’s guideline is 4 to 6 inches of compacted base for pedestrian walkways and patios, and 8 to 12 inches for driveways. Poorly drained or clay soils and colder climates generally call for the deeper end.',
		'How much wider than the pavers should the base be?' => 'Unilock recommends extending the base beyond the paver edge by the same distance as the base depth. For a 6-inch base, extend it 6 inches past the edge on every side.',
		'How thick should bedding sand be under pavers?'   => 'About 1 inch of bedding sand is typical, screeded smooth over the compacted base. Don’t use the sand to fix low spots in the base; level the base itself.',
		'How deep do I need to dig for a paver patio?'     => 'Add the base depth, the bedding sand and the paver thickness. For 6 inches of base, 1 inch of sand and 2⅜-inch pavers, dig about 9⅜ inches below the finished surface.',
		'How do I compact a paver base?'                   => 'Spread and compact the base in layers. Unilock notes that typical plate compactors only compact about 3 to 4 inches at a time, so a 6-inch base needs at least two layers.',
	);
	$paver_content = <<<'HTML'
<p class="gl-lede">Plan the base for a paver patio, walkway or driveway. Enter the paved area and the calculator works out base gravel, bedding sand and how much soil to dig out, with the base widened past the edges the way manufacturers recommend.</p>

[gl_paver_base]

<h2>How to use this calculator</h2>
<ol>
<li><strong>Choose the project type</strong> and base depth. Patios and walkways need less base than driveways.</li>
<li><strong>Set the bedding sand depth and paver thickness.</strong> About 1 inch of sand and 2⅜-inch (60 mm) pavers are common.</li>
<li><strong>Add the paved area</strong> as rectangles or circles at its finished size. The calculator adds the extra base width itself.</li>
</ol>

<h2>Base depth guidelines</h2>
<table class="gl-table">
<thead><tr><th>Project</th><th>Compacted base</th></tr></thead>
<tbody>
<tr><td>Walkway or patio</td><td>4 – 6 in</td></tr>
<tr><td>Driveway</td><td>8 – 12 in</td></tr>
</tbody>
</table>
<p>These are Unilock’s guidelines. Choose the deeper end on clay or poorly drained soil, and in climates with hard freezes. Unilock also recommends extending the base past the paver edge by the base depth, so a 6-inch base runs 6 inches beyond the pavers on every side, and compacting it in layers of 3 to 4 inches.</p>

<h2>The formulas</h2>
<ul>
<li><strong>Base footprint</strong> = (length + 2 × base depth) × (width + 2 × base depth)</li>
<li><strong>Base gravel</strong> = base footprint × base depth ÷ 12, plus 5–10% for compaction</li>
<li><strong>Bedding sand</strong> = paved area × sand depth ÷ 12</li>
<li><strong>Dig depth</strong> = base depth + sand depth + paver thickness</li>
</ul>

<h2>Worked example</h2>
<p>A 16 × 12 foot patio with a 6-inch base, 1 inch of bedding sand and 2⅜-inch pavers:</p>
<ul>
<li>Base footprint: (16 + 1) × (12 + 1) = <strong>221 sq ft</strong></li>
<li>Base gravel: 221 × 6 ÷ 12 = 110.5 cu ft, plus 10% = <strong>121.6 cubic feet</strong>, about <strong>4.5 cubic yards</strong> or <strong>6.3 tons</strong> at 1.4 tons per yard</li>
<li>Bedding sand: 192 × 1 ÷ 12 = <strong>16 cubic feet</strong>, about 0.6 cubic yards</li>
<li>Dig depth: 6 + 1 + 2⅜ = <strong>9⅜ inches</strong>, removing about <strong>6.4 cubic yards</strong> of soil</li>
<li>Compaction: the 6-inch base goes down in <strong>2 layers</strong></li>
</ul>

<h2>Tips for a base that lasts</h2>
<ul>
<li><strong>Use a dense-graded base</strong> such as crushed stone with fines (often sold as road base or crusher run), which compacts firmly. See our <a href="/gravel-calculator/">gravel calculator</a> for stone weights.</li>
<li><strong>Slope for drainage.</strong> Unilock suggests 1 to 2%, which is about ½ inch of fall over 4 feet at 1%. Slope away from the house.</li>
<li><strong>Compact the soil first,</strong> then each layer of base, wetting it lightly if it is dry.</li>
<li><strong>Screed the sand, don’t compact it,</strong> before laying pavers, then compact the pavers and sweep in joint sand.</li>
</ul>

<h2>Sources</h2>
<ul>
<li><a href="https://unilock.com/diy/dos-and-donts-of-paver-installation-base-preparation-23/" rel="noopener" target="_blank">Unilock: Do’s and don’ts of paver installation, base preparation</a></li>
<li><a href="https://selectsg.com/how-much-does-a-yard-of-road-base-weigh/" rel="noopener" target="_blank">Select Sand & Gravel: How much does a yard of road base weigh?</a></li>
</ul>
HTML;
	$defs['paver_base'] = array(
		'title'   => 'Paver Base Calculator',
		'slug'    => 'paver-base-calculator',
		'excerpt' => 'Base gravel, bedding sand and digging depth for paver patios, walkways and driveways.',
		'content' => $paver_content . "\n" . gl_tools_faq_html( $paver_faq ),
		'faq'     => $paver_faq,
		'order'   => 12,
	);

	/* ---------------------------------------------------------------
	 * About
	 * ------------------------------------------------------------- */
	$about = <<<'HTML'
<p class="gl-lede">Gamely Labs makes free, practical calculators for lawn, garden and yard projects, so homeowners can buy the right amount, water the right way and skip the guesswork.</p>

<h2>Who runs Gamely Labs</h2>
<p>Gamely Labs is built and run by <strong>Junaid Arif</strong> and is owned by <strong>AJ Tech Studio</strong>. Junaid designs and writes the calculators and the guides that go with them.</p>
<p>We started Gamely Labs because most yard-project questions come down to simple math that is surprisingly hard to find in one place: how many minutes to run a sprinkler zone, how many bags of soil fill a raised bed, how much fertilizer a lawn really needs. The answers exist in university extension publications and product labels, but they are scattered, full of unit conversions, and rarely tailored to your yard. Our tools do that work for you.</p>

<h2>How we build our tools</h2>
<ul>
<li><strong>Standard, published methods.</strong> Each calculator uses the formulas found in horticulture and irrigation practice and in university extension guides. We don't invent rules of thumb and present them as facts.</li>
<li><strong>The math is always shown.</strong> Every tool page explains its formula and walks through a worked example, so you can check any number yourself.</li>
<li><strong>Sources are cited.</strong> Where a recommendation comes from a specific source, such as a nitrogen limit or a maximum lime rate, the guide names and links it.</li>
<li><strong>Your yard, not averages.</strong> When an answer depends on something only you can measure, such as how fast your sprinklers apply water, we show you how to measure it instead of guessing.</li>
<li><strong>Tested before publishing.</strong> We check every calculator against its worked examples before it goes live.</li>
</ul>

<h2>What our tools are, and aren't</h2>
<p>Our calculators give estimates for planning and shopping. They are not a substitute for a soil test, a product's label, local watering rules or advice from your state's cooperative extension service, which know your local conditions best. When a guide and a product label disagree, follow the label.</p>

<h2>Corrections and suggestions</h2>
<p>If you spot an error, or there's a calculator you wish existed, please tell us. We read every message and update pages when we find a mistake. You can reach us on our <a href="/contact/">contact page</a> or by email at <a href="mailto:info@ajtechstudio.com">info@ajtechstudio.com</a>.</p>
HTML;

	$defs['about'] = array(
		'title'   => 'About Gamely Labs',
		'slug'    => 'about',
		'excerpt' => 'Who runs Gamely Labs, how our lawn and garden calculators are built, and how to reach us.',
		'content' => $about,
		'order'   => 20,
	);

	/* ---------------------------------------------------------------
	 * Contact
	 * ------------------------------------------------------------- */
	$contact = <<<'HTML'
<p class="gl-lede">Questions, corrections or ideas for a new calculator? We'd love to hear from you.</p>

<h2>Email us</h2>
<p><a class="gl-btn" href="mailto:info@ajtechstudio.com?subject=Gamely%20Labs">info@ajtechstudio.com</a></p>
<p>We usually reply within two business days.</p>

<h2>What to include</h2>
<ul>
<li><strong>Reporting a problem with a calculator?</strong> Tell us which tool, the numbers you entered and what you expected to see. A screenshot helps.</li>
<li><strong>Found an error in a guide?</strong> Link the page and, if you can, the source that disagrees. We correct mistakes promptly.</li>
<li><strong>Suggesting a new tool?</strong> Describe the project you're planning and the question you couldn't answer.</li>
</ul>

<h2>Business and advertising enquiries</h2>
<p>Gamely Labs is owned by AJ Tech Studio. For partnerships or other business matters, email the same address with "Business" in the subject line.</p>

<p>Please note that we can't give individual professional advice about a specific property. For questions about your local soil, plants or regulations, your state's cooperative extension service is an excellent free resource.</p>
HTML;

	$defs['contact'] = array(
		'title'   => 'Contact',
		'slug'    => 'contact',
		'excerpt' => 'Contact Gamely Labs with questions, corrections or ideas for new lawn and garden calculators.',
		'content' => $contact,
		'order'   => 21,
	);

	/* ---------------------------------------------------------------
	 * Privacy policy
	 * ------------------------------------------------------------- */
	$privacy = <<<'HTML'
<p class="gl-lede">This policy explains what information Gamely Labs collects when you use gamelylabs.com, how it is used, and the choices you have.</p>
<p><strong>Effective date:</strong> October 22, 2026</p>

<h2>Who we are</h2>
<p>Gamely Labs (gamelylabs.com) is owned and operated by AJ Tech Studio. In this policy, "we", "us" and "our" refer to Gamely Labs and AJ Tech Studio. You can contact us at <a href="mailto:info@ajtechstudio.com">info@ajtechstudio.com</a>.</p>

<h2>Information you enter into our calculators</h2>
<p>The numbers you type into our calculators, such as lawn areas, bed sizes or product details, are processed in your own browser. They are not sent to our servers. To save you retyping, each calculator may store your most recent inputs in your browser's local storage on your device. You can clear them at any time with the calculator's "Start over" button or by clearing your browser's site data.</p>

<h2>Information collected automatically</h2>
<p>Like most websites, our hosting provider's servers automatically record basic technical information when you visit, such as your IP address, browser type, the pages requested and the date and time. These logs are used to keep the site secure and working properly and are kept only as long as needed for that purpose.</p>
<p>Our pages load fonts from Google Fonts, which means your browser connects to Google's servers and Google receives your IP address. See <a href="https://policies.google.com/privacy" rel="noopener" target="_blank">Google's privacy policy</a> for how Google handles this information.</p>

<h2>Information you send us</h2>
<p>If you email us, we receive your email address and whatever you choose to include in your message. We use it only to reply to you and to improve the site, and we don't sell it or add you to mailing lists.</p>

<h2>Cookies and advertising</h2>
<p>We use, or may use, Google AdSense to show advertising on this site. Advertising helps keep our calculators free.</p>
<ul>
<li>Third-party vendors, including Google, use cookies to serve ads based on a user's prior visits to this website or other websites.</li>
<li>Google's use of advertising cookies enables it and its partners to serve ads to you based on your visit to this site and/or other sites on the internet.</li>
<li>You may opt out of personalized advertising by visiting <a href="https://adssettings.google.com" rel="noopener" target="_blank">Google Ads Settings</a>. You can also opt out of some third-party vendors' use of cookies for personalized advertising at <a href="https://www.aboutads.info/choices/" rel="noopener" target="_blank">www.aboutads.info</a>.</li>
<li>You can learn more about how Google uses information from sites that use its services at <a href="https://policies.google.com/technologies/partner-sites" rel="noopener" target="_blank">How Google uses information from sites or apps that use our services</a>.</li>
</ul>
<p>Visitors in the European Economic Area, the United Kingdom and Switzerland will be asked for consent before personalized ads or advertising cookies are used, and can change their choice at any time.</p>
<p>Most browsers let you block or delete cookies through their settings. Blocking cookies won't stop our calculators from working.</p>

<h2>Analytics</h2>
<p>We do not currently use a separate analytics service. If we add one in the future, we will update this policy to describe it before it is used.</p>

<h2>How we share information</h2>
<p>We don't sell your personal information. Information is shared only with the service providers that run the site, namely our web host and, where ads are shown, Google and its advertising partners as described above, or when required by law.</p>

<h2>Your rights</h2>
<p>Depending on where you live, including under the GDPR in Europe and the CCPA in California, you may have the right to access, correct or delete personal information we hold about you, to object to or restrict certain processing, and to opt out of personalized advertising. To make a request, email <a href="mailto:info@ajtechstudio.com">info@ajtechstudio.com</a>. We will respond within the time required by applicable law.</p>

<h2>Children's privacy</h2>
<p>Gamely Labs is intended for adults planning home and garden projects. We do not knowingly collect personal information from children under 13. If you believe a child has sent us personal information, contact us and we will delete it.</p>

<h2>Links to other websites</h2>
<p>Our guides link to other websites, such as university extension publications. We aren't responsible for the privacy practices of those sites and encourage you to read their policies.</p>

<h2>Changes to this policy</h2>
<p>We may update this policy from time to time. When we do, we will change the effective date at the top of this page.</p>

<h2>Contact</h2>
<p>Questions about this policy? Email <a href="mailto:info@ajtechstudio.com">info@ajtechstudio.com</a>.</p>
HTML;

	$defs['privacy'] = array(
		'title'   => 'Privacy Policy',
		'slug'    => 'privacy-policy',
		'excerpt' => 'How Gamely Labs handles information, cookies and advertising, and the choices you have.',
		'content' => $privacy,
		'order'   => 22,
	);

	/* ---------------------------------------------------------------
	 * Terms
	 * ------------------------------------------------------------- */
	$terms = <<<'HTML'
<p class="gl-lede">These terms apply to your use of gamelylabs.com. By using the site, you agree to them.</p>
<p><strong>Effective date:</strong> October 22, 2026</p>

<h2>About the site</h2>
<p>Gamely Labs is owned and operated by AJ Tech Studio. The site provides free calculators and guides for lawn, garden and yard projects.</p>

<h2>Estimates, not professional advice</h2>
<p>Our calculators and guides provide general estimates for planning purposes. Results depend on the information you enter and on conditions we can't see, such as your soil, climate, equipment and the products you use. They are not professional, agronomic, legal or engineering advice.</p>
<ul>
<li>Always read and follow product labels. Where a label and our guide differ, follow the label.</li>
<li>Follow local laws and rules, including watering restrictions and limits on fertilizer or phosphorus use.</li>
<li>For decisions about your specific property, consult a soil test, your local cooperative extension service or a qualified professional.</li>
</ul>

<h2>No warranty</h2>
<p>We work hard to keep our tools accurate, but the site and its content are provided "as is", without warranties of any kind, express or implied, including accuracy, completeness or fitness for a particular purpose. We don't guarantee that the site will always be available or free of errors.</p>

<h2>Limitation of liability</h2>
<p>To the fullest extent permitted by law, Gamely Labs and AJ Tech Studio are not liable for any loss or damage arising from your use of the site or reliance on its content, including damage to lawns, plants, property or equipment, or the cost of materials.</p>

<h2>Acceptable use</h2>
<p>Please don't misuse the site, for example by attempting to disrupt it, access it in unauthorized ways, or copy its content or tools in bulk with automated means.</p>

<h2>Intellectual property</h2>
<p>The site's text, calculators, design and graphics belong to AJ Tech Studio unless otherwise noted. You're welcome to use the calculators for your own projects and to link to any page. Please don't republish our content without permission.</p>

<h2>Links and advertising</h2>
<p>The site links to third-party websites and may display advertising. We aren't responsible for the content, products or practices of third parties, and an ad on our site is not an endorsement.</p>

<h2>Changes</h2>
<p>We may update these terms from time to time. The effective date above shows when they last changed. Continuing to use the site after a change means you accept the updated terms.</p>

<h2>Contact</h2>
<p>Questions about these terms? Email <a href="mailto:info@ajtechstudio.com">info@ajtechstudio.com</a>. See also our <a href="/privacy-policy/">privacy policy</a>.</p>
HTML;

	$defs['terms'] = array(
		'title'   => 'Terms of Use',
		'slug'    => 'terms',
		'excerpt' => 'Terms for using the Gamely Labs website and its lawn and garden calculators.',
		'content' => $terms,
		'order'   => 23,
	);

	$defs['home'] = array(
		'title'   => 'Lawn, Garden & Yard Calculators',
		'slug'    => 'lawn-garden-yard-calculators',
		'excerpt' => 'Free calculators for lawn watering, garden planning, soil and more.',
		'content' => $home_content,
		'order'   => 0,
	);

	return $defs;
}
