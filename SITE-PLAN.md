# Gamely Labs: site plan

Started: **Thursday, October 22, 2026**

## Status on day one (Oct 22, 2026)

- 9 calculators live: Sprinkler Run Time, Lawn Fertilizer, Lawn Lime, Sod, Grass Seed, Square Foot Garden Planner, Raised Bed Soil, Compost, Mulch
- 8 guides live (Guides section)
- About, Contact, Privacy Policy, Terms published (owner: AJ Tech Studio, author: Junaid Arif, contact: info@ajtechstudio.com)
- Google Search Console verified (domain property), sitemap submitted: `https://gamelylabs.com/wp-sitemap.xml`
- Deployment: GitHub (`junaidarif143/gamely-labs-tools`, `junaidarif143/gamely-labs-theme`) pulled by Hostinger Git deploy into
  `wp-content/plugins/gamely-labs-tools` and `wp-content/themes/gamely-labs-theme`

## Plan

| When | What |
|---|---|
| Week of Oct 22 | ~~Build Gravel, Topsoil (leveling) and Paver Base calculators~~ Done Oct 22 |
| Oct 22 – Nov 9 | Publish about 2 guides a week. Behind schedule until Oct 31; catch-up guides scheduled: Oct 31, Nov 3, Nov 6, Nov 9 |
| Around Nov 9–12, 2026 | Apply for Google AdSense (site about 2.5–3 weeks old, indexed, with ~12 tools and 12+ guides) |
| Around Nov 26 – Dec 3, 2026 | First Search Console review: pick new guide topics from real search queries |
| Ongoing | ~2 guides a week; new tools whenever useful |

## Log

- 2026-10-22: Plan started. Tools 1–9 and guides 1–8 live.
- 2026-10-22: Gravel, Topsoil and Paver Base calculators live (12 tools). New "Yard tools" section in menu, homepage and footer.
- 2026-10-28: Keyword/topical clustering applied (v1.7.0): one intent per page, SEO titles, cluster meta descriptions, variant FAQs, two cannibalization fixes. See SEO-CLUSTERS.md.
- 2026-10-31: Review (site 9 days old, too early for Search Console trends). Hostinger server cache turned on. Social share tags (Open Graph/Twitter) + share image added (theme 1.3.0). 4 guides added with scheduled publishing (plugin 1.8.0): How much water a lawn needs (Oct 31), Spring lawn care checklist (Nov 3), Gravel path (Nov 6), Leveling a bumpy lawn (Nov 9).
- 2026-10-31: SEO audit (score 6.5/10; report in Claude Docs). v1.9.0: author box + About team section (Junaid Arif, Muhammad Ali, photos), Organization/WebSite/WebApplication schema, author pages out of sitemap and redirected to About, REST user list hidden, time zone set to America/Los_Angeles, water-guide overlap fixed. Bug fixed: the two v1.0 pages (Sprinkler, Square Foot Garden) never received updates, including the Oct 28 cluster fixes; they now refresh when unedited.
- 2026-10-31: v1.10.0 + theme 1.5.0: 24 original illustrations (one per tool and guide; diagrams of the method on each page, facts matched to page text). Bundled in assets/img/, imported to the Media Library automatically, set as featured images (share images, Article schema image, guide-card thumbnails) and shown in-article with alt text and captions. Search Console checked (28 days to Oct 28): 53 impressions, 0 clicks, avg position 75.7, 23 queries; index report last updated Oct 22 (1 indexed, 29 crawled-not-indexed: normal for a new site).
- 2026-10-31: Search Console: sitemap resubmitted. URL Inspection shows all 12 tool pages are on Google. Re-indexing requested for 11 (all but Sprinkler; daily quota hit). Follow-up scheduled Nov 1 for Sprinkler + guides.
