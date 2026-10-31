# Gamely Labs: keyword clusters (one intent = one page)

Rule (from keyword/topical clustering): group every keyword that has the **same search intent**
into one cluster, and give each cluster **exactly one page**. That page should answer all the
variations, so it can rank for hundreds of keywords. Two pages chasing the same intent split
Google's signals (keyword cannibalization) and both rank worse.

Variations below are built from how people phrase these searches (calculator + "how much … do I need",
units, bed/lawn sizes, materials). They are not volume-checked yet: at the Search Console review
(around Nov 26 – Dec 3, 2026), move any query that shows up on the wrong page into its owner's cluster.

## Clusters and the page that owns each

| Cluster (intent) | Owner page | Variations the page must answer |
|---|---|---|
| How long to water / sprinkler minutes | /sprinkler-run-time-calculator/ | sprinkler run time calculator, how long to water lawn, how long to run sprinklers for 1 inch, minutes per zone, rotor vs spray run time, cycle and soak |
| How much fertilizer to apply | /lawn-fertilizer-calculator/ | lawn fertilizer calculator, how much fertilizer per 1,000 sq ft, how much 10-10-10 per 1,000 sq ft, how many sq ft does a bag cover, N-P-K meaning, nitrogen per application |
| How much lime to apply | /lawn-lime-calculator/ | lawn lime calculator, how much lime per 1,000 sq ft, how many bags of lime, tons per acre to lb per 1,000, pelletized vs ground lime |
| Plan a square foot garden | /square-foot-garden-planner/ | square foot garden planner, plants per square foot, square foot gardening spacing chart, 4x8 / 4x4 layout, square foot garden soil mix |
| How much soil for raised beds | /raised-bed-soil-calculator/ | raised bed soil calculator, how much soil for a raised bed, 4x8 / 4x4 / 3x6 raised bed soil, how many bags fill a raised bed, cubic yards for raised beds, bags vs bulk |
| How much sod | /sod-calculator/ | sod calculator, how much sod do I need, pallets of sod for 1,000 sq ft, sq ft per pallet, pieces of sod, sod slab size |
| How much grass seed | /grass-seed-calculator/ | grass seed calculator, grass seed per 1,000 sq ft, grass seed per acre, overseeding rate, fescue / bluegrass / bermuda seeding rate |
| How much compost | /compost-calculator/ | compost calculator, how much compost do I need, compost per 1,000 sq ft, compost for new lawn, lawn topdressing with compost, yard of compost coverage |
| How much mulch | /mulch-calculator/ | mulch calculator, how much mulch do I need, bags of mulch per yard, how much a yard / bag of mulch covers, mulch depth |
| How much gravel | /gravel-calculator/ | gravel calculator, how much gravel do I need, gravel tons / yards, crushed stone / pea gravel calculator, driveway gravel, area a ton covers, yard of gravel weight |
| How much topsoil / level a lawn | /topsoil-calculator/ | topsoil calculator, how much topsoil do I need, topsoil to level lawn, yard of topsoil coverage, topsoil for new lawn, sand or topsoil |
| Paver base quantity | /paver-base-calculator/ | paver base calculator, how much paver base do I need, base for a 12x12 patio, paver base depth, bedding sand, dig depth |

Guides own the **informational / how-to** intent and link to their calculator; calculators own the
**"how much / calculate"** intent:

| Guide | Owns | Must not compete for |
|---|---|---|
| When to fertilize your lawn | when to fertilize, fertilizer schedule | how much fertilizer (calculator) |
| How to water new grass seed | watering new seed schedule | sprinkler minutes (calculator) |
| Fall lawn care checklist | fall lawn tasks | — |
| How to read a soil test | soil test meaning | how much lime (calculator) |
| How to overseed a lawn | overseeding steps | how much seed to overseed (calculator) |
| How to lay sod | laying sod steps | how much sod (calculator) |
| Raised bed soil mixes compared | **best soil for raised beds** | how much soil (calculator) |
| Mulching mistakes | mulching mistakes | how much mulch (calculator) |
| How much water does a lawn need | how much water a lawn needs per week, signs a lawn needs water, deep vs frequent watering | sprinkler minutes per zone (calculator) |
| Spring lawn care checklist | spring lawn tasks, crabgrass preventer timing | — |
| How to build a gravel path | gravel path steps | how much gravel (calculator) |
| How to level a bumpy lawn | leveling steps, lifting sod | how much topsoil (calculator) |

## Cannibalization found and fixed (v1.7.0)

1. **"How much soil do I need for a 4x8 raised bed?"** was a FAQ (and FAQ schema) on both the
   Square Foot Garden Planner and the Raised Bed Soil Calculator. Now only the Raised Bed Soil
   Calculator answers it; the planner's FAQ became a planting question ("How many plants fit in a
   4x8 square foot garden?") and links to the soil calculator.
2. **"What is the best soil mix for raised beds?"** was a FAQ on the Raised Bed Soil Calculator while
   the "Raised Bed Soil Mixes Compared" guide targets the same intent. The FAQ was replaced by a
   "how much" question (4x4 bed), the calculator links to the guide, and the guide's SEO title is
   now "Best Soil Mix for Raised Beds: 3 Options Compared".
3. **Lawn topdressing / leveling** overlapped between Compost and Topsoil. Split: compost owns
   "topdress to improve the soil", topsoil owns "level low spots"; each page now points to the other.
4. **Gravel vs paver base** was already split (gravel page links paver base for pavers); paver base
   now links back for loose gravel.

## What changed on the pages

- SEO `<title>` per tool, built as "Tool name: main question (units)".
- Meta description (page excerpt) rewritten around the cluster's main question and variations.
- 1–2 new FAQs per tool answering the top variations (coverage per ton/yard/bag, common bed
  sizes, per acre, driveway, 12x12 patio, 1,000 sq ft). FAQ schema picks them up automatically.
- "Related tools" sidebar uses each tool's own cluster neighbours instead of the first 3 in its category.

## Fixed Oct 31, 2026 (v1.9.0)

5. **"How much water does a lawn need per week?"** was a Sprinkler calculator FAQ and the target of the new guide
   "How Much Water Does Your Lawn Really Need?". The guide now owns that informational question; the calculator keeps
   "how long to run sprinklers" and its FAQ became "What weekly water target should I enter?", pointing to the guide.
