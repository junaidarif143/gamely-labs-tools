<?php
/**
 * Guides (blog posts) created by the plugin. 'tools' lists the catalog keys each guide relates to;
 * the Gamely theme uses it for "Tools in this guide" and for "Related guides" on tool pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gl_tools_post_definitions() {
	$p = array();

	/* 1 --------------------------------------------------------------- */
	$p['when_fertilize'] = array(
		'title'   => 'When to Fertilize Your Lawn: A Simple Schedule by Grass Type',
		'slug'    => 'when-to-fertilize-lawn',
		'excerpt' => 'The best months to feed cool-season and warm-season lawns, how much nitrogen to use each time, and the mistakes that waste fertilizer.',
		'tools'   => array( 'fertilizer', 'lime' ),
		'content' => <<<'HTML'
<p class="gl-lede">Timing matters more than the brand on the bag. Fertilize when your grass is actively growing and can use the nitrogen, and you'll get a thicker lawn with less product. Feed it at the wrong time and much of it is wasted, or worse, feeds weeds and disease. Here is a simple schedule for each type of grass.</p>

<h2>First, know which grass you have</h2>
<p>Lawn grasses fall into two groups, and they grow at opposite times of year.</p>
<ul>
<li><strong>Cool-season grasses</strong> such as tall fescue, Kentucky bluegrass, perennial ryegrass and fine fescues grow most in spring and fall and slow down in summer heat. They dominate lawns in the northern half of the US and the transition zone.</li>
<li><strong>Warm-season grasses</strong> such as bermudagrass, zoysiagrass, St. Augustinegrass and centipedegrass grow most in summer and go dormant (brown) after frost. They dominate the South.</li>
</ul>
<p>The rule is simple: <strong>feed the grass while it is growing, not while it is resting.</strong></p>

<h2>Cool-season lawns: focus on fall</h2>
<p>For fescue, bluegrass and ryegrass, early fall is the most valuable feeding of the year. The grass is recovering from summer stress and storing energy for winter and next spring.</p>
<ul>
<li><strong>Main feeding: late summer to early fall.</strong> University of Illinois Extension advises that if you only fertilize once a year, do it in late summer or early fall, roughly mid-August to mid-September in central Illinois (earlier in the north, later in the south).</li>
<li><strong>Second feeding: late spring.</strong> UConn's soil lab suggests timing applications around May and early September.</li>
<li><strong>How much per year:</strong> UConn suggests about 2 pounds of nitrogen per 1,000 square feet a year if you leave clippings on the lawn, or about 3 pounds if you bag them, because clippings return nitrogen to the soil.</li>
</ul>
<p>Avoid heavy feeding in midsummer. Cool-season grass is stressed by heat then, and lush growth is more prone to disease.</p>

<h2>Warm-season lawns: feed through summer</h2>
<p>For bermudagrass, zoysia, St. Augustine and centipede, fertilize from late spring, once the lawn has fully greened up, through summer while it is growing strongly.</p>
<ul>
<li><strong>Start after green-up,</strong> not at the first warm day. Feeding a lawn that is still half dormant mostly feeds early weeds.</li>
<li><strong>How much per year</strong> depends on the grass. Virginia Cooperative Extension notes that centipedegrass and mature zoysiagrass do best on about 1 to 2 pounds of nitrogen per 1,000 square feet a year, while bermudagrass and St. Augustinegrass can use more, up to about 3 pounds or more in high-maintenance programs.</li>
<li><strong>Stop in late summer.</strong> Fertilizing warm-season grass in fall or winter, when it is slowing down or dormant, wastes fertilizer and can make the grass more vulnerable to cold damage.</li>
</ul>

<h2>How much to apply each time</h2>
<p>Lawn fertilizer rates are given in pounds of <em>nitrogen</em> per 1,000 square feet, not pounds of product. Extension guides commonly cap quick-release nitrogen at about 1 pound per 1,000 square feet per application; Virginia Tech recommends no more than 0.9 pound of mostly water-soluble nitrogen at once. Slow-release products can be applied at somewhat higher rates according to the label.</p>
<p>To turn that into pounds of product, divide the nitrogen rate by the first number on the bag (as a decimal). One pound of nitrogen from a 24-0-6 fertilizer is 1 ÷ 0.24 ≈ 4.2 pounds of product per 1,000 square feet. Our <a href="/lawn-fertilizer-calculator/">lawn fertilizer calculator</a> does this for any bag and lawn size, and builds a yearly plan for your grass type.</p>

<h2>Sample schedules</h2>
<table class="gl-table">
<thead><tr><th>Grass</th><th>When to feed</th><th>Typical yearly nitrogen</th></tr></thead>
<tbody>
<tr><td>Cool-season, clippings left</td><td>Late spring + early fall</td><td>About 2 lb per 1,000 sq ft</td></tr>
<tr><td>Cool-season, clippings bagged</td><td>Late spring + early fall, plus early summer if needed</td><td>About 3 lb</td></tr>
<tr><td>Bermuda, St. Augustine</td><td>Late spring through late summer, every 6–8 weeks</td><td>About 2–3+ lb</td></tr>
<tr><td>Zoysia, centipede</td><td>Late spring and midsummer</td><td>About 1–2 lb</td></tr>
</tbody>
</table>
<p>These are general starting points. Your state's cooperative extension service publishes schedules tuned to your climate.</p>

<h2>Common fertilizing mistakes</h2>
<ul>
<li><strong>Skipping the soil test.</strong> A test tells you whether you need phosphorus or potassium at all, and whether low pH is the real problem. If your soil is too acidic, fertilizer won't fix a thin lawn; lime will. See our guide to <a href="/how-to-read-soil-test/">reading a soil test</a> and the <a href="/lawn-lime-calculator/">lawn lime calculator</a>.</li>
<li><strong>Feeding a dormant or drought-stressed lawn.</strong> Grass that isn't growing can't use the nitrogen.</li>
<li><strong>Applying before heavy rain.</strong> Rain can wash granules into storm drains. A light watering afterward is fine if the label calls for it.</li>
<li><strong>Leaving granules on pavement.</strong> Sweep them back onto the lawn.</li>
<li><strong>Uneven spreading.</strong> Apply half the product in one direction and half at right angles to avoid stripes.</li>
</ul>

<h2>Sources</h2>
<ul>
<li><a href="https://extension.illinois.edu/news-releases/five-fall-tips-cool-season-lawn-care" rel="noopener" target="_blank">University of Illinois Extension: Five fall tips for cool-season lawn care</a></li>
<li><a href="https://soiltesting-cahnr.media.uconn.edu/wp-content/uploads/sites/3514/2022/06/Lawns.pdf" rel="noopener" target="_blank">UConn Soil Nutrient Analysis Laboratory: Lawns</a></li>
<li><a href="https://www.pubs.ext.vt.edu/452/452-718/452-718.html" rel="noopener" target="_blank">Virginia Cooperative Extension: Soil test note for lawns</a></li>
</ul>
HTML,
	);

	/* 2 --------------------------------------------------------------- */
	$p['water_seed'] = array(
		'title'   => 'How Long and How Often to Water New Grass Seed',
		'slug'    => 'how-to-water-new-grass-seed',
		'excerpt' => 'A week-by-week watering plan for newly seeded lawns, from germination to the first mowings, and how to turn it into sprinkler run times.',
		'tools'   => array( 'grass_seed', 'sprinkler' ),
		'content' => <<<'HTML'
<p class="gl-lede">New grass seed needs a completely different watering routine from an established lawn. Seeds and young seedlings have no deep roots, so the top inch of soil must stay moist until they're up and growing. Then you switch, gradually, to deep and infrequent watering. Here's how to manage each stage.</p>

<h2>Stage 1: Seeding to germination (about the first 2–3 weeks)</h2>
<p><strong>Goal: keep the soil surface moist, never soggy.</strong> A seed that starts to sprout and then dries out dies.</p>
<ul>
<li><strong>Water lightly, about twice a day.</strong> Iowa State University Extension recommends light waterings about twice daily, and up to four light waterings a day in hot, windy weather.</li>
<li><strong>Apply just enough to wet the surface.</strong> You're refreshing the top inch, not soaking deep. Stop before water puddles or runs off, which washes seed into low spots.</li>
<li><strong>Expect germination in about 2–3 weeks</strong> for most cool-season grasses. Kentucky bluegrass is slower than ryegrass or fescue, so don't give up on a bluegrass seeding too early.</li>
</ul>
<p>A light layer of straw mulch, thin enough that you can still see about half the soil, helps hold moisture and reduces how often you need to water.</p>

<h2>Stage 2: Seedlings 1–2 inches tall</h2>
<p><strong>Goal: start training roots to grow down.</strong></p>
<ul>
<li><strong>Water less often but more deeply.</strong> Once the seedlings reach 1 to 2 inches, Iowa State advises gradually reducing how often you water while applying more each time.</li>
<li>A practical approach is to drop from twice a day to once a day, then to every other day, lengthening each watering as you go.</li>
</ul>

<h2>Stage 3: After the first two or three mowings</h2>
<p><strong>Goal: switch to a normal lawn routine.</strong></p>
<ul>
<li>Once the new grass has been mowed two or three times, one thorough watering a week is usually enough, according to Iowa State.</li>
<li>Established lawns generally need about an inch of water a week, including rain. Water deeply and infrequently rather than a little every day.</li>
</ul>

<h2>Turning this into sprinkler run times</h2>
<p>"Water lightly" means different minutes for different sprinklers. Fixed spray heads apply water roughly three times faster than rotors, so the same run time can flood one zone and barely dampen another.</p>
<ol>
<li><strong>Measure each zone's rate</strong> with a quick catch-cup test: set a few straight-sided containers in the zone, run it for 15 minutes, average the depth and multiply by 4 to get inches per hour.</li>
<li><strong>For Stage 1,</strong> you only need a few minutes per cycle, often 3 to 8 minutes for spray heads, just enough to darken the soil surface. Check with your finger rather than trusting the clock.</li>
<li><strong>For Stage 3,</strong> use our <a href="/sprinkler-run-time-calculator/">sprinkler run time calculator</a> to set minutes per zone for about an inch a week, split into cycles so the water soaks in.</li>
</ol>

<h2>Common mistakes with new seed</h2>
<ul>
<li><strong>Letting the surface dry out for even one hot afternoon</strong> right after germination. This is the most common cause of patchy results.</li>
<li><strong>Watering too heavily.</strong> Puddling and runoff wash seed away and starve seedlings of air.</li>
<li><strong>Staying on the frequent schedule too long.</strong> Keeping the surface wet for months grows shallow roots and invites disease.</li>
<li><strong>Seeding at the wrong time.</strong> Cool-season grasses establish best from late summer to early fall; warm-season grasses in late spring to early summer. Seed sown in midsummer heat is very hard to keep alive.</li>
<li><strong>Sowing too much seed.</strong> Overcrowded seedlings compete and thin out. Our <a href="/grass-seed-calculator/">grass seed calculator</a> gives the right amount for your grass type and lawn size.</li>
</ul>

<h2>Sources</h2>
<ul>
<li><a href="https://yardandgarden.extension.iastate.edu/faq/how-should-i-water-newly-seeded-lawn" rel="noopener" target="_blank">Iowa State University Extension: How should I water a newly seeded lawn?</a></li>
<li><a href="https://yardandgarden.extension.iastate.edu/how-to/late-summer-and-fall-lawn-care" rel="noopener" target="_blank">Iowa State University Extension: Late summer and fall lawn care</a></li>
</ul>
HTML,
	);

	/* 3 --------------------------------------------------------------- */
	$p['fall_checklist'] = array(
		'title'   => 'Fall Lawn Care Checklist: What to Do and When',
		'slug'    => 'fall-lawn-care-checklist',
		'excerpt' => 'A month-by-month fall checklist for cool-season lawns: seeding, aerating, feeding, weeds, leaves and the last mowing, plus notes for warm-season grass.',
		'tools'   => array( 'grass_seed', 'fertilizer', 'lime', 'compost' ),
		'content' => <<<'HTML'
<p class="gl-lede">For cool-season lawns, fall is the most important season of the year. Warm soil, cool air and regular rain make it the best time to repair thin spots, feed the roots and knock back perennial weeds. Work through this checklist in order and your lawn will come out of winter thicker and greener.</p>

<p>Dates below are typical for the Midwest and similar climates; shift them a few weeks earlier in the North and later in the South. Warm-season lawns have different needs, covered at the end.</p>

<h2>Late August to mid-September</h2>
<h3>☐ Seed bare spots and overseed thin areas</h3>
<p>Iowa State University Extension puts the window for seeding cool-season lawns at about mid-August to mid-September. That gives new grass time to establish before hard frost. See our step-by-step guide to <a href="/how-to-overseed-lawn/">overseeding a thin lawn</a> and use the <a href="/grass-seed-calculator/">grass seed calculator</a> to buy the right amount.</p>

<h3>☐ Core aerate compacted lawns</h3>
<p>Core aeration pulls small plugs of soil out of the lawn so air, water and roots can move down. Iowa State suggests early September, aiming for 20 to 40 holes per square foot, when the soil is moist. The University of Illinois notes that aerators can't cut hard, dry soil, so aerate a few days after rain. Leave the cores on the lawn to break down. Aerating right before overseeding gives seed great soil contact, and it's the perfect moment to topdress with a thin layer of compost (about ¼ inch); our <a href="/compost-calculator/">compost calculator</a> works it out.</p>

<h2>Mid-September</h2>
<h3>☐ Fertilize</h3>
<p>If you fertilize only once a year, this is the time. Iowa State recommends feeding in mid-September with no more than 1 pound of actual nitrogen per 1,000 square feet. Use our <a href="/lawn-fertilizer-calculator/">lawn fertilizer calculator</a> to convert that into pounds of product for your bag and lawn.</p>

<h3>☐ Test your soil and lime if needed</h3>
<p>Fall is a convenient time to take a soil test. If it calls for lime, you can apply it whenever the ground isn't frozen, and freeze-thaw cycles over winter help work it in. Turn the recommendation into bags with the <a href="/lawn-lime-calculator/">lawn lime calculator</a>.</p>

<h2>Late September to early November</h2>
<h3>☐ Control perennial broadleaf weeds</h3>
<p>Dandelions, clover and creeping Charlie are moving sugars down to their roots in fall, so a systemic weed killer applied now travels to the roots too. Iowa State suggests late September to early November. Always follow the label, and don't use weed killers on newly seeded areas until the new grass has been mowed several times; check the product label for the waiting period.</p>

<h3>☐ Keep mowing, and don't scalp</h3>
<p>Keep mowing as long as the grass grows, which in Iowa is often into late October or early November. Never remove more than one-third of the blade at once. Keeping a reasonable height (around 2½ to 3 inches for most cool-season lawns late in the year) lets the grass keep making food for its roots.</p>

<h3>☐ Deal with leaves</h3>
<p>A thick mat of leaves smothers grass. Thin layers of dry leaves can be shredded with a mulching mower so the pieces fall between grass blades and feed the soil. Heavy layers should be raked or mowed more than once. Leaves raked into planting beds make good winter cover.</p>

<h3>☐ Keep watering in dry spells</h3>
<p>Fall can be dry. Lawns need about an inch of water a week, especially new seed and sod. Our <a href="/sprinkler-run-time-calculator/">sprinkler run time calculator</a> turns that into minutes per zone.</p>

<h2>Before the ground freezes</h2>
<ul>
<li>☐ Do a last mowing, then clean and store the mower.</li>
<li>☐ Drain and store hoses, and shut off and winterize irrigation systems.</li>
<li>☐ Clean, empty and store the spreader.</li>
</ul>

<h2>If you have a warm-season lawn</h2>
<p>Bermuda, zoysia, St. Augustine and centipede are slowing down in fall, not gearing up. Skip fall fertilizing (feed in late spring and summer instead), do major seeding and sodding in late spring to early summer, and keep mowing at your usual height until the grass goes dormant. Fall is still a good time to test soil and control winter weeds.</p>

<h2>Sources</h2>
<ul>
<li><a href="https://yardandgarden.extension.iastate.edu/how-to/late-summer-and-fall-lawn-care" rel="noopener" target="_blank">Iowa State University Extension: Late summer and fall lawn care</a></li>
<li><a href="https://extension.illinois.edu/news-releases/five-fall-tips-cool-season-lawn-care" rel="noopener" target="_blank">University of Illinois Extension: Five fall tips for cool-season lawn care</a></li>
</ul>
HTML,
	);

	/* 4 --------------------------------------------------------------- */
	$p['soil_test'] = array(
		'title'   => 'How to Read a Soil Test Report for Your Lawn and Garden',
		'slug'    => 'how-to-read-soil-test',
		'excerpt' => 'What pH, buffer pH, phosphorus, potassium, organic matter and CEC mean on a soil test, the ranges to aim for, and how to act on the results.',
		'tools'   => array( 'lime', 'fertilizer', 'compost' ),
		'content' => <<<'HTML'
<p class="gl-lede">A soil test is the cheapest way to stop guessing about lime and fertilizer. But the report can look like a page of chemistry. Here's what each number means, what ranges to aim for, and how to turn the recommendations into a shopping list.</p>

<h2>Getting a good sample</h2>
<p>A test is only as good as the sample. Most state university extension services run inexpensive soil tests for homeowners and send instructions with the kit. Follow your lab's directions for depth, but the general approach is the same everywhere:</p>
<ul>
<li>Take small cores from many spots across the area (often 10 to 15), avoiding unusual places like old burn piles or right next to the driveway.</li>
<li>Mix them in a clean bucket and send a portion of the mix.</li>
<li>Sample the lawn, the vegetable garden and the flower beds separately; they're managed differently.</li>
</ul>

<h2>Soil pH</h2>
<p>pH measures how acidic or alkaline the soil is, on a scale where 7 is neutral, lower is acidic and higher is alkaline. It matters because it controls how available nutrients are to plants.</p>
<ul>
<li><strong>Lawns and vegetable gardens:</strong> University of Maryland Extension gives 5.5 to 7.0 as the target for both.</li>
<li><strong>Most plants</strong> do well between about 6.0 and 7.5, according to Oklahoma State University Extension, while acid-loving plants such as blueberries and azaleas prefer 4.5 to 5.5.</li>
<li>Phosphorus becomes less available below pH 6.0 and above 7.3, so pH problems can look like nutrient shortages.</li>
</ul>
<p>If your pH is too low, the report will usually recommend lime. If it's too high, it may recommend sulfur, or simply choosing plants suited to alkaline soil.</p>

<h2>Buffer pH (or lime requirement)</h2>
<p>This number confuses many people. Your soil pH tells the lab <em>whether</em> you need lime; the buffer pH tells it <em>how much</em>. Two soils with the same pH can need very different amounts of lime, because clay and organic matter resist change more than sand. You don't need to interpret the buffer value yourself; the lab turns it into a lime recommendation. Our <a href="/lawn-lime-calculator/">lawn lime calculator</a> converts that recommendation into bags and splits large amounts into safe applications.</p>

<h2>Phosphorus (P) and potassium (K)</h2>
<p>Labs report these as a number plus a rating (low, medium, optimum, high) or as a percent sufficiency. The scales differ between labs, so always read the ratings on your own report rather than comparing raw numbers. For example, Oklahoma State's scale treats a phosphorus index of 65 and a potassium index of 300 as 100% sufficient, meaning no more is needed.</p>
<ul>
<li><strong>Low or medium:</strong> follow the recommendation, usually given in pounds of phosphate (P₂O₅) or potash (K₂O) per 1,000 square feet.</li>
<li><strong>Optimum or high:</strong> you don't need more. Choose a fertilizer with a zero middle or last number (such as 24-0-6). Extra phosphorus can wash into lakes and streams, and several states restrict it on lawns.</li>
</ul>

<h2>Nitrogen</h2>
<p>Many labs don't test nitrogen for lawns and gardens because it changes quickly in soil. Instead, the report gives a standard recommendation based on what you're growing. Our <a href="/lawn-fertilizer-calculator/">lawn fertilizer calculator</a> turns a nitrogen rate into pounds of any product.</p>

<h2>Organic matter</h2>
<p>Organic matter is reported as a percentage by weight, so the number looks small even in good soil. University of Maryland Extension considers 2% or more healthy, notes that well-established vegetable gardens often test above 12%, and suggests about an inch of compost a year to keep garden soil productive. Our <a href="/compost-calculator/">compost calculator</a> works out how much to buy.</p>

<h2>CEC (cation exchange capacity)</h2>
<p>CEC measures how well the soil holds onto nutrients like potassium and calcium. Clay and organic matter raise it; sand lowers it. University of Maryland suggests adding organic matter if CEC is below 10. Low-CEC soils also benefit from smaller, more frequent fertilizer applications.</p>

<h2>Turning the report into action</h2>
<ol>
<li><strong>Fix pH first</strong> if the report calls for lime or sulfur. Nutrients won't work well until pH is in range.</li>
<li><strong>Add only the nutrients that test low.</strong> Pick a fertilizer whose analysis matches.</li>
<li><strong>Build organic matter</strong> with compost if it's low.</li>
<li><strong>Retest every two or three years,</strong> or sooner if you're correcting a big problem.</li>
</ol>

<h2>Sources</h2>
<ul>
<li><a href="https://extension.umd.edu/resource/understanding-your-soil-test-report" rel="noopener" target="_blank">University of Maryland Extension: Understanding your soil test report</a></li>
<li><a href="https://extension.okstate.edu/fact-sheets/understanding-your-lawn-and-garden-soil-test.html" rel="noopener" target="_blank">Oklahoma State University Extension: Understanding your lawn and garden soil test</a></li>
</ul>
HTML,
	);

	/* 5 --------------------------------------------------------------- */
	$p['overseed'] = array(
		'title'   => 'How to Overseed a Thin Lawn, Step by Step',
		'slug'    => 'how-to-overseed-lawn',
		'excerpt' => 'When to overseed, how to prepare the lawn for good seed-to-soil contact, how much seed to use, and how to care for the new grass.',
		'tools'   => array( 'grass_seed', 'sprinkler', 'compost' ),
		'content' => <<<'HTML'
<p class="gl-lede">Overseeding means sowing new grass seed into an existing lawn to thicken it up, without tearing everything out. Done at the right time with good seed-to-soil contact, it can transform a thin lawn in a single season. Here's how to do it.</p>

<h2>Step 1: Figure out why the lawn is thin</h2>
<p>New seed won't fix the underlying problem. The University of Wisconsin points out that compacted, heavy clay or poorly drained soil should be corrected first; overseeding alone won't overcome chronic conditions. Common culprits include:</p>
<ul>
<li><strong>Too much shade</strong> for the grass you have. Choose a shade-tolerant seed blend, such as fine fescues.</li>
<li><strong>Compacted soil</strong> from foot traffic. Core aeration helps.</li>
<li><strong>Low pH or poor fertility.</strong> A soil test will tell you. See <a href="/how-to-read-soil-test/">how to read a soil test</a>.</li>
<li><strong>Mowing too short,</strong> which weakens grass and lets weeds in.</li>
</ul>

<h2>Step 2: Pick the right time</h2>
<ul>
<li><strong>Cool-season lawns</strong> (fescue, bluegrass, ryegrass): late summer to early fall is best. Iowa State University Extension suggests mid-August to mid-September; the University of Wisconsin recommends finishing by early September so new grass establishes before hard frost. Spring is a second choice.</li>
<li><strong>Warm-season lawns</strong> (bermuda, zoysia): late spring to early summer, once soil is warm.</li>
<li><strong>Skip weed preventers.</strong> Pre-emergent crabgrass products stop grass seed from germinating too. Don't use one in spring if you plan to seed then, and check the label for how long to wait.</li>
</ul>

<h2>Step 3: Mow short and clear debris</h2>
<p>Iowa State suggests mowing to about 1½ to 2 inches before overseeding, so seed can reach the soil and seedlings get light. Rake out or bag the clippings and any loose thatch.</p>

<h2>Step 4: Open up the soil</h2>
<p>Seed that sits on top of thatch or hard ground dries out and fails. You need seed-to-soil contact. Choose one method:</p>
<ul>
<li><strong>Core aeration:</strong> pull plugs across the lawn, ideally with several passes. Seed falls into the holes and around the crumbled cores.</li>
<li><strong>Slit seeding:</strong> a slit seeder (rentable) cuts shallow grooves and drops seed right into them. The University of Wisconsin notes these work extremely well when soil conditions are good.</li>
<li><strong>Power raking or dethatching:</strong> scratches the surface and removes debris.</li>
</ul>
<p>For bare patches, loosen the soil with a rake first. A thin topdressing of compost or quality topsoil over the seed helps hold moisture; our <a href="/compost-calculator/">compost calculator</a> can size it.</p>

<h2>Step 5: Spread the right amount of seed</h2>
<p>A common rule is about half the new-lawn rate for overseeding, though your seed bag's overseeding rate takes priority. Split the seed into two batches and spread one in one direction and the other crosswise for even coverage. Our <a href="/grass-seed-calculator/">grass seed calculator</a> gives pounds and bags for your grass type and lawn size.</p>

<h2>Step 6: Water lightly and often</h2>
<p>Keep the top inch of soil moist until the new grass is up, which usually means light watering once or twice a day; Iowa State notes that overseeded grass should germinate in about 2 to 3 weeks. Then water less often and more deeply. Our guide to <a href="/how-to-water-new-grass-seed/">watering new grass seed</a> has the full schedule.</p>

<h2>Step 7: Care for the new grass</h2>
<ul>
<li><strong>Mow</strong> when the new grass reaches your normal mowing height, with a sharp blade, removing no more than a third of the leaf.</li>
<li><strong>Feed</strong> about 4 to 6 weeks after germination, or as directed for your fertilizer program.</li>
<li><strong>Hold off on weed killers</strong> until the new grass has been mowed several times; follow the product label.</li>
<li><strong>Limit traffic</strong> on the area for a few weeks.</li>
</ul>

<h2>Sources</h2>
<ul>
<li><a href="https://hort.extension.wisc.edu/aerating-overseeding-lawns/" rel="noopener" target="_blank">University of Wisconsin–Madison Extension: Aerating and overseeding lawns</a></li>
<li><a href="https://yardandgarden.extension.iastate.edu/how-to/late-summer-and-fall-lawn-care" rel="noopener" target="_blank">Iowa State University Extension: Late summer and fall lawn care</a></li>
</ul>
HTML,
	);

	/* 6 --------------------------------------------------------------- */
	$p['lay_sod'] = array(
		'title'   => 'How to Lay Sod So It Takes: Prep, Installation and Watering',
		'slug'    => 'how-to-lay-sod',
		'excerpt' => 'Prepare the soil, lay sod in a brick pattern, and water it through the first critical weeks until the roots knit in.',
		'tools'   => array( 'sod', 'compost', 'sprinkler' ),
		'content' => <<<'HTML'
<p class="gl-lede">Sod gives you an instant lawn, but whether it thrives depends on the soil under it and how well you water in the first two weeks. This guide follows the University of Nebraska–Lincoln's recommendations for establishing a lawn from sod.</p>

<h2>Before delivery: measure and order</h2>
<p>Measure every area to be sodded and add about 5% for cuts on simple shapes, or up to 10% around curves, beds and trees. Sod is sold by the pallet, and pallet sizes vary by supplier, so our <a href="/sod-calculator/">sod calculator</a> turns your measurements into pallets and pieces for slabs or rolls. Schedule delivery for the day you'll lay it; sod stacked on a pallet heats up and dries out fast.</p>

<h2>Step 1: Prepare the soil</h2>
<p>Most sod failures start here. The University of Nebraska–Lincoln recommends:</p>
<ul>
<li><strong>Clear the area</strong> of old grass, debris and rocks.</li>
<li><strong>Grade for drainage</strong> so water flows away from buildings, filling low spots. Aim for at least 4 inches of topsoil.</li>
<li><strong>Test the soil</strong> and correct pH or nutrient problems. See <a href="/how-to-read-soil-test/">how to read a soil test</a>.</li>
<li><strong>Till 4 to 6 inches deep.</strong> On clay soil, work in about an inch of compost, then another inch in a different direction. Don't add sand to heavy clay. Our <a href="/compost-calculator/">compost calculator</a> works out the amount.</li>
<li><strong>Let the soil settle</strong> or firm it lightly, then rake it smooth and level. Avoid working soil when it's wet.</li>
</ul>
<p>Finish the grade so the soil sits about the thickness of the sod below sidewalks and driveways, so the new lawn ends up flush.</p>

<h2>Step 2: Lay the sod</h2>
<ol>
<li><strong>Lightly moisten the soil</strong> just before you start.</li>
<li><strong>Start along a straight edge,</strong> like a driveway or sidewalk.</li>
<li><strong>Stagger the joints like bricks</strong> so the ends of pieces don't line up row to row.</li>
<li><strong>Butt the edges tightly</strong> without overlapping or stretching the pieces; stretched sod shrinks back and leaves gaps.</li>
<li><strong>Cut pieces</strong> with a sharp knife around curves and beds.</li>
<li><strong>On slopes,</strong> start at the bottom and lay the pieces across the slope. The University of Nebraska–Lincoln notes that slopes of 10% or more may need pegs or staples to hold sod in place.</li>
<li><strong>Roll lightly</strong> to press the sod into contact with the soil, then water thoroughly right away.</li>
</ol>

<h2>Step 3: Water through the first weeks</h2>
<ul>
<li><strong>Days 1–14: water daily.</strong> The University of Nebraska–Lincoln recommends daily watering for the first two weeks, with an extra light watering in mid-to-late afternoon during hot weather. The goal is to keep both the sod and the soil just under it moist.</li>
<li><strong>Check rooting after 7–10 days.</strong> Gently tug on a corner. When the sod resists lifting, which usually happens within 10–14 days in good weather, start watering less often but more deeply.</li>
<li><strong>Keep an eye on it for months.</strong> Sod can take a long time to root fully, so watch for drought stress during dry spells.</li>
</ul>
<p>Edges and corners dry out first; give them extra attention. Once the sod has rooted, use our <a href="/sprinkler-run-time-calculator/">sprinkler run time calculator</a> to set a deep, weekly watering schedule.</p>

<h2>Step 4: First mowing and beyond</h2>
<ul>
<li><strong>Mow</strong> as soon as there's new top growth, at about 3 to 3½ inches (3½ inches or higher for tall fescue), removing no more than a third of the blade.</li>
<li><strong>Use a light walk-behind mower</strong> for the first few mowings to avoid rutting soft sod.</li>
<li><strong>Keep traffic off</strong> until the lawn is established.</li>
<li><strong>Wait to aerate</strong> until the sod is firmly rooted, usually one to two months after installation.</li>
</ul>

<h2>Sources</h2>
<ul>
<li><a href="https://turf.unl.edu/sites/unl.edu.ianr.agronomy-horticulture.turf/files/media/file/Establishing-lawn-fromsod2012m.pdf" rel="noopener" target="_blank">University of Nebraska–Lincoln Turfgrass Science: Establishing lawns from sod</a></li>
<li><a href="https://sodsolutions.com/sod-university/square-feet-per-pallet/" rel="noopener" target="_blank">Sod Solutions: How many square feet are in a pallet of sod?</a></li>
</ul>
HTML,
	);

	/* 7 --------------------------------------------------------------- */
	$p['bed_mixes'] = array(
		'title'   => 'Raised Bed Soil Mixes Compared: Which Should You Use?',
		'slug'    => 'raised-bed-soil-mixes',
		'excerpt' => 'Topsoil and compost, the square foot gardening mix, or bagged raised bed soil: how they compare on cost, drainage and upkeep.',
		'tools'   => array( 'raised_bed', 'sfg', 'compost' ),
		'content' => <<<'HTML'
<p class="gl-lede">Filling a raised bed is the biggest one-time cost of the project, and the mix you choose shapes how well it grows for years. Here's how the three most common options compare, and how to work out how much you need.</p>

<h2>Option 1: Topsoil and compost, 50/50</h2>
<p><strong>What it is:</strong> equal parts screened topsoil and compost, mixed together.</p>
<ul>
<li><strong>Pros:</strong> the cheapest option for large or deep beds, especially bought in bulk by the cubic yard. Holds nutrients and water well and settles into a stable, soil-like bed.</li>
<li><strong>Cons:</strong> quality varies a lot between suppliers. Ask to see the topsoil; it should be crumbly, not sticky clay, and free of rocks and roots. Heavy and hard work to move.</li>
<li><strong>Best for:</strong> several beds, deep beds, and gardeners on a budget.</li>
</ul>

<h2>Option 2: Square foot gardening mix</h2>
<p><strong>What it is:</strong> one-third compost, one-third peat moss or coconut coir and one-third coarse vermiculite, by volume. This is the mix popularized by Mel Bartholomew's square foot gardening method.</p>
<ul>
<li><strong>Pros:</strong> very light and fluffy, drains well yet holds moisture, and has almost no weed seeds. Easy to plant and weed.</li>
<li><strong>Cons:</strong> usually the most expensive, because peat or coir and vermiculite are sold in bags. Peat and coir are sold compressed and expand when loosened, so check the bag's loose volume.</li>
<li><strong>Best for:</strong> shallow beds (around 6 inches) and small, intensively planted gardens. Our <a href="/square-foot-garden-planner/">square foot garden planner</a> calculates the mix as you lay out your beds.</li>
</ul>

<h2>Option 3: Bagged raised bed mix</h2>
<p><strong>What it is:</strong> a ready-made product sold as "raised bed soil" or "garden soil for raised beds."</p>
<ul>
<li><strong>Pros:</strong> convenient: just open and pour. Consistent, and easy to carry in a car.</li>
<li><strong>Cons:</strong> costs add up quickly for large beds, and products vary widely, so read the label.</li>
<li><strong>Best for:</strong> one or two small beds, or topping up.</li>
</ul>

<h2>What to avoid</h2>
<ul>
<li><strong>Pure compost.</strong> It shrinks a lot as it breaks down, and very rich compost alone can hold too much water and supply more nutrients than plants need.</li>
<li><strong>Plain native clay</strong> dug from the yard. It packs down and drains poorly in a bed.</li>
<li><strong>Bagged "topsoil" of unknown quality</strong> as the only ingredient. Some is mostly fine silt or sand.</li>
</ul>

<h2>How deep should the soil be?</h2>
<p>About 6 inches of good mix grows most vegetables, and it's the standard depth for square foot gardening. Deep-rooted crops such as long carrots, parsnips and potatoes do better with 10 to 12 inches. In very tall beds, many gardeners fill the bottom with untreated logs and branches to save soil, since most vegetable roots stay in the top foot.</p>

<h2>How much will you need?</h2>
<p>Multiply length × width (in feet) × depth (in inches) ÷ 12 for cubic feet. A 4 × 8 foot bed filled 6 inches deep needs 16 cubic feet; at 12 inches, 32 cubic feet (about 1.2 cubic yards). Plan on roughly 10% extra because new soil settles. Our <a href="/raised-bed-soil-calculator/">raised bed soil calculator</a> handles several beds, round beds and filler, and compares bagged against bulk cost.</p>

<h2>Keeping the bed productive</h2>
<p>Beds sink a little every year as organic matter breaks down. Top them up each spring with compost; University of Maryland Extension suggests that about an inch of compost a year helps keep vegetable and flower garden soil productive. Our <a href="/compost-calculator/">compost calculator</a> sizes the top-up for you.</p>

<h2>Sources</h2>
<ul>
<li><a href="https://extension.umd.edu/resource/understanding-your-soil-test-report" rel="noopener" target="_blank">University of Maryland Extension: Understanding your soil test report</a></li>
<li><a href="https://extension.oregonstate.edu/catalog/em-9308-how-use-compost-gardens-landscapes" rel="noopener" target="_blank">Oregon State University Extension: How to use compost in gardens and landscapes</a></li>
</ul>
HTML,
	);

	/* 8 --------------------------------------------------------------- */
	$p['mulch_mistakes'] = array(
		'title'   => '6 Mulching Mistakes That Hurt Trees and Shrubs',
		'slug'    => 'mulching-mistakes',
		'excerpt' => 'Mulch volcanoes, layers that are too deep, mulch against stems and more: what goes wrong with mulch and how to do it right.',
		'tools'   => array( 'mulch', 'compost' ),
		'content' => <<<'HTML'
<p class="gl-lede">Mulch is one of the best things you can do for a landscape: it holds moisture, evens out soil temperature, smothers weeds and slowly feeds the soil. But a few common habits turn it from helpful to harmful. Here are six mistakes to avoid.</p>

<h2>1. Piling mulch into a "volcano"</h2>
<p>Mulch heaped up against a tree trunk is one of the most common sights in landscaping, and one of the most damaging. University of Illinois Extension and UConn Extension both warn against it: mulch against the trunk keeps the bark constantly moist and dark, which invites rot, insects and disease, and can encourage roots to grow up into the mulch and circle the trunk.</p>
<p><strong>Do this instead:</strong> spread mulch in a wide, flat ring like a doughnut, and leave a bare circle a few inches across around the trunk itself.</p>

<h2>2. Making the layer too deep</h2>
<p>More is not better. The University of Illinois Extension recommends 2 to 3 inches. Thicker layers can keep water and air from reaching roots, and fine mulches can mat down into a crust that sheds water.</p>
<p><strong>Do this instead:</strong> keep it at 2 to 3 inches. Our <a href="/mulch-calculator/">mulch calculator</a> works out exactly how much to buy for that depth.</p>

<h2>3. Adding more every year without checking</h2>
<p>Many landscapes get a fresh load every spring whether they need it or not, and the layers build up year after year. University of Illinois Extension notes that annual top-dressing can push mulch far deeper than intended.</p>
<p><strong>Do this instead:</strong> poke into the mulch with a trowel and measure. If it's still 2 inches or more, just rake it to freshen the surface. If it has thinned, add only the difference; the mulch calculator has a field for existing depth so it only counts the top-up.</p>

<h2>4. Piling mulch against shrub stems and plant crowns</h2>
<p>The same problem as volcanoes applies to shrubs, perennials and young plants. Mulch packed against stems keeps them wet and can lead to rot.</p>
<p><strong>Do this instead:</strong> pull mulch back an inch or two from stems and plant crowns.</p>

<h2>5. Making the ring too small</h2>
<p>A tiny circle of mulch right around a trunk does little for the tree. Tree roots spread far beyond the trunk, and grass competes with them for water and nutrients.</p>
<p><strong>Do this instead:</strong> make tree rings as wide as is practical, several feet across at least, and wider for larger trees. The mulch calculator's "ring around a tree" shape handles the math, including the bare circle at the trunk.</p>

<h2>6. Digging woody mulch into the soil</h2>
<p>Wood chips and bark work well on the surface. Mixed into the soil, fresh woody material can tie up nitrogen as it decomposes, starving the plants around it.</p>
<p><strong>Do this instead:</strong> leave woody mulch on top. To improve soil, mix in finished compost rather than mulch; our <a href="/compost-calculator/">compost calculator</a> gives recommended depths for new and existing beds.</p>

<h2>The right way, in short</h2>
<ul>
<li>2 to 3 inches deep, measured after it settles.</li>
<li>Wide, flat rings around trees, with a bare circle at the trunk.</li>
<li>An inch or two of clearance around stems and crowns.</li>
<li>Check depth before adding more each year.</li>
<li>Woody mulch on top of the soil, compost into it.</li>
</ul>

<h2>Sources</h2>
<ul>
<li><a href="https://extension.illinois.edu/news-releases/too-much-good-thing-mulch-volcanoes-slowly-kill-trees" rel="noopener" target="_blank">University of Illinois Extension: Too much of a good thing, mulch volcanoes slowly kill trees</a></li>
<li><a href="https://extension.uconn.edu/2013/10/09/mulch-volcanoes-a-growing-problem" rel="noopener" target="_blank">UConn Extension: Mulch volcanoes, a growing problem</a></li>
</ul>
HTML,
	);

	return $p;
}

/**
 * Create the "Guides" category, the Guides page (as the posts page), and the guide posts.
 * Same rules as pages: create what is missing, refresh only content nobody has edited.
 */
function gl_tools_sync_posts() {
	$ids    = get_option( 'gl_tools_posts', array() );
	$hashes = get_option( 'gl_tools_post_hashes', array() );

	$cat = get_category_by_slug( 'guides' );
	if ( $cat ) {
		$cat_id = (int) $cat->term_id;
	} else {
		$term   = wp_insert_term( 'Guides', 'category', array( 'slug' => 'guides' ) );
		$cat_id = is_wp_error( $term ) ? (int) get_option( 'default_category' ) : (int) $term['term_id'];
	}

	// Guides page used as the posts index.
	$guides_page = get_page_by_path( 'guides' );
	if ( ! $guides_page ) {
		$gid = wp_insert_post( array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_title'     => 'Lawn & Garden Guides',
			'post_name'      => 'guides',
			'post_content'   => '',
			'post_excerpt'   => 'Practical, sourced guides for lawn, garden and yard projects, each paired with a calculator.',
			'comment_status' => 'closed',
			'menu_order'     => 19,
		) );
	} else {
		$gid = $guides_page->ID;
	}
	if ( $gid && ! get_option( 'page_for_posts' ) ) {
		update_option( 'page_for_posts', (int) $gid );
	}

	$n = 0;
	foreach ( gl_tools_post_definitions() as $key => $def ) {
		$n++;
		$content  = gl_tools_wrap( $def['content'] );
		$existing = isset( $ids[ $key ] ) ? get_post( $ids[ $key ] ) : null;
		if ( ! $existing || 'trash' === $existing->post_status ) {
			$existing = get_page_by_path( $def['slug'], OBJECT, 'post' );
		}
		if ( $existing ) {
			$ids[ $key ] = $existing->ID;
			$cur         = md5( $existing->post_content );
			if ( isset( $hashes[ $key ] ) && $cur === $hashes[ $key ] && $cur !== md5( $content ) ) {
				wp_update_post( array( 'ID' => $existing->ID, 'post_content' => $content ) );
				$hashes[ $key ] = md5( get_post( $existing->ID )->post_content );
			}
			update_post_meta( $existing->ID, '_gl_tools', implode( ',', $def['tools'] ) );
			continue;
		}
		$id = wp_insert_post( array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'post_title'     => $def['title'],
			'post_name'      => $def['slug'],
			'post_content'   => $content,
			'post_excerpt'   => $def['excerpt'],
			// Published now, a minute apart, so the list order follows this file's order (newest last).
			'post_date'      => gmdate( 'Y-m-d H:i:s', current_time( 'timestamp' ) - ( 60 * ( count( gl_tools_post_definitions() ) - $n + 1 ) ) ),
			'post_category'  => array( $cat_id ),
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			$ids[ $key ]    = $id;
			$hashes[ $key ] = md5( get_post( $id )->post_content );
			update_post_meta( $id, '_gl_tools', implode( ',', $def['tools'] ) );
		}
	}
	update_option( 'gl_tools_posts', $ids );
	update_option( 'gl_tools_post_hashes', $hashes );
}

/** Guides that mention a given tool key, newest first. */
function gl_tools_guides_for( $tool_key, $limit = 3 ) {
	return get_posts( array(
		'post_type'      => 'post',
		'posts_per_page' => $limit,
		'meta_query'     => array(
			array( 'key' => '_gl_tools', 'value' => $tool_key, 'compare' => 'LIKE' ),
		),
	) );
}
