# Luxora / Nera Competitions Standard — Claude Code Guide

## Project Overview

WordPress theme for a luxury competition/giveaway platform built on WooCommerce + Lottery for WooCommerce. Two homepage strategies exist: **Luxora Homepage** (fixed, premium, light-themed) and **Nera Homepage** (flexible, ACF-driven).

- **Theme path**: `wp-content/themes/nera-competitions-standard/`
- **Build tool**: Vite 6 + TailwindCSS v4
- **JS stack**: AlpineJS (interactivity) + Vue 3 (isolated SPAs)
- **Content**: ACF Pro (all field groups registered via PHP, not DB)

---

## Critical Rules

### 1. TailwindCSS — Inline Utility Classes First

**Always prefer TailwindCSS utility classes inline in PHP/HTML templates.** Only reach for custom CSS in `src/partials/` when:
- A style is impossible or impractical with utilities (complex animations, third-party overrides)
- A pattern repeats 5+ times and warrants a `@layer components` class

Never write one-off CSS for layout, spacing, color, or typography that Tailwind can handle inline.

```php
// CORRECT — utilities inline
<div class="flex items-center gap-4 px-6 py-3 bg-white rounded-xl shadow-card">

// WRONG — unnecessary custom class
<div class="my-custom-box">
```

Design tokens (colors, fonts, spacing, shadows) are defined in `src/partials/_theme.css` via `@theme {}`. Use them as Tailwind utilities: `text-luxora-forest`, `bg-luxora-mint`, `font-playfair`, `shadow-card`, etc.

### 2. All Section Content via ACF Pro

**Every user-facing content value in a section must be editable via ACF Pro.** No hardcoded strings for headings, body text, labels, URLs, or images in template files — these must pull from `get_field()` or `the_field()`.

```php
// CORRECT
<h2 class="text-3xl font-playfair"><?php the_field('section_heading'); ?></h2>

// WRONG — hardcoded
<h2 class="text-3xl font-playfair">Why Choose Luxora?</h2>
```

ACF field groups are registered in `inc/acf/`. When adding a new section:
1. Add fields to the relevant ACF file (or create a new one in `inc/acf/`)
2. Register the file in `functions.php` via `require_once`
3. All fields use `acf_add_local_field_group()` (code-based, not DB-stored)
4. Provide sensible default/placeholder values in the ACF field definition

---

## Directory Structure

```
nera-competitions-standard/
├── src/
│   ├── main.css                  # TailwindCSS entry (@import + partials)
│   ├── main.js                   # JS entry (imports theme modules)
│   └── partials/
│       ├── _theme.css            # @theme tokens (colors, fonts, shadows, spacing)
│       ├── _base.css             # Base element resets
│       ├── _animations.css       # Custom keyframes
│       ├── _utilities.css        # Custom utility classes
│       ├── _components.css       # @layer components (reusable UI patterns)
│       ├── _features.css         # Page/feature-specific styles
│       ├── _cart-checkout.css    # WooCommerce cart/checkout
│       └── _overrides.css        # WordPress core overrides
├── assets/css/
│   └── luxora-homepage.css       # Luxora light theme global overrides
├── inc/
│   ├── acf/                      # ACF field group definitions (13 files)
│   ├── api/                      # REST API endpoints (instant-wins, winners, archive)
│   └── *.php                     # Customizer, menu-walkers, shortcodes, etc.
├── page-templates/               # WordPress page templates
├── template-parts/
│   ├── homepage/                 # 19 Nera homepage section partials
│   ├── luxora-homepage/          # 7 Luxora homepage section partials
│   ├── single-product/
│   ├── product-listing/
│   ├── components/               # Shared UI components
│   └── ...
├── frontend/                     # Vue 3 SPA components
├── functions.php                 # Theme setup, enqueue, AJAX handlers
├── vite.config.js
├── package.json
└── .env.local                    # NERA_DEV_MODE=true for Vite HMR
```

---

## Homepage Templates

### Luxora Homepage (`page-templates/luxora-homepage-template.php`)
Fixed 7-section layout. Sections loaded via `get_template_part()` from `template-parts/luxora-homepage/`. All content editable via `group_luxora_homepage` ACF field group (`inc/acf/acf-luxora-homepage.php`). Loads `assets/css/luxora-homepage.css` for light-theme overrides.

**Sections (in order)**:
1. `hero` — eyebrow, tagline, body, dual CTAs, trust stats
2. `marquee-banner` — rotating announcement strip
3. `competitions` — featured competitions grid
4. `why` — benefit highlights
5. `brand-statement` — trust/promise message
6. `winners` — winner testimonials/showcases
7. `how-it-works` — step guide
8. `free-entry-banner` — CTA strip

### Nera Homepage (`page-templates/homepage-template.php`)
Flexible section ordering via ACF `homepage_sections` repeater. 14 available section slugs: `hero`, `credibility`, `account_prompt`, `stats`, `welcome`, `featured_competitions`, `promo_banner`, `testimonials`, `winners`, `quick_guide`, `about`, `categories`, `faq`, `brand_statement`. Falls back to hardcoded default order if field is empty.

---

## ACF Field Groups

All groups registered via `acf_add_local_field_group()` in `inc/acf/`:

| File | Group Key | Page/Context |
|---|---|---|
| `acf-luxora-homepage.php` | `group_luxora_homepage` | Luxora Homepage template |
| `acf-brand-statement.php` | `group_brand_statement` | Brand Statement section |
| `acf-how-it-works.php` | `group_how_it_works_page` | How It Works page |
| `acf-single-product.php` | `group_single_product_competition` | Single product/competition |
| `acf-winners.php` | `group_winners_page` | Winners page |
| `acf-archive-winners.php` | `group_archive_winners_page` | Archive Winners page |
| `acf-my-purpose.php` | `group_my_purpose_page` | My Purpose page |
| `acf-contact.php` | `group_contact_page` | Contact page |
| `acf-product-listing.php` | `group_product_listing` | Product Listing template |
| `acf-footer.php` | `group_neracompetitions_footer` | Footer |
| `acf-header.php` | `group_nera_header` | Header |
| `acf-postal-entry.php` | `group_neracompetitions_postal_entry` | Postal entry |
| `acf-woocommerce.php` | `group_neracompetitions_woocommerce` | WooCommerce settings |

---

## Design Tokens (TailwindCSS v4 `@theme`)

Defined in `src/partials/_theme.css`. Key tokens:

**Colors**:
- `luxora-forest` (#3d4a3a), `luxora-sage` (#6b8c6b), `luxora-mint` (#c8e6c0), `luxora-ink` (#1e2a1e)
- `primary` (#1313ec), `secondary` (#f4f7ff)

**Typography**:
- `font-playfair` — Playfair Display (headings)
- `font-dm-sans` — DM Sans (body)
- `font-dancing` — Dancing Script (decorative/draws branding)

**Shadows**: `shadow-xs`, `shadow-sm`, `shadow-md`, `shadow-lg`, `shadow-xl`, `shadow-2xl`, `shadow-card`

**Transitions**: `transition-fast` (150ms), `transition-base` (300ms), `transition-slow` (500ms)

---

## JavaScript Patterns

**AlpineJS** — use for UI state, toggles, modals, dropdowns. Components registered in `assets/js/alpine-*.js` before Alpine initializes.

**Vue 3** — isolated SPAs only (instant wins modal, winners listings). Entry points in `frontend/`. Bundled separately by Vite.

**AJAX** — all AJAX actions use `admin-ajax.php` with nonce verification via `neraSettings.nonce`. Handlers in `functions.php`.

**Modules** (imported in `src/main.js`):
- `animations.js`, `homepage.js`, `ticket-selector.js`, `scroll-to-top.js`
- `product-listing.js`, `cart.js`, `cart-sound.js`, `single-product.js`

---

## Build & Dev

```bash
npm run dev     # Vite dev server with HMR at localhost:5173
npm run build   # Production build → dist/ with manifest
npm run format  # Prettier (PHP + JS)
npm run deploy  # Build + rsync to production
```

Set `NERA_DEV_MODE=true` in `.env.local` to enable Vite HMR. Production uses `dist/manifest.json` for asset fingerprinting.

### 3. ACF Rich Text Fields — Always Wrap with `prose`

Any ACF field of type **Wysiwyg / Rich Text** must be wrapped with the TailwindCSS Typography plugin classes so WordPress-generated HTML (headings, lists, links, blockquotes) is styled correctly.

```php
// CORRECT — prose wrapper on rich text output
<div class="prose prose-lg max-w-none">
    <?php the_field('section_body'); ?>
</div>

// For the Luxora light theme, use a custom prose variant if needed
<div class="prose prose-luxora max-w-none">
    <?php the_field('section_body'); ?>
</div>
```

**Rules**:
- All `the_field()` / `get_field()` calls for wysiwyg fields must have a `prose` parent
- Add `max-w-none` to let it fill the container (default prose caps width at 65ch)
- Use `prose-sm`, `prose-lg`, `prose-xl` to match surrounding type scale
- Never manually style `<p>`, `<ul>`, `<h2>` etc. inside a wysiwyg output — let prose handle it

The `@tailwindcss/typography` plugin is already installed (`@tailwindcss/typography@0.5.19` in `package.json`).

---

### 4. Seed ACF Field Data via WP-CLI — No Placeholder Defaults

**Never rely on placeholder/default values in ACF field definitions to populate content.** When creating a new section with ACF fields, use WP-CLI to inject real data directly into the database.

```bash
# Seed a field on a specific post (e.g., post ID 1 = homepage)
wp post meta update 1 section_heading "Why Choose Luxora?"
wp post meta update 1 section_subheading "Transparent. Trusted. Life-changing."

# Seed an ACF repeater row (JSON-encoded)
wp post meta update 1 benefit_items '{"0":{"icon":"star","title":"Verified Winners","description":"Every draw is independently verified."}}'

# Seed an ACF image field (use attachment ID)
wp post meta update 1 hero_image 42
```

Run WP-CLI from the project root (where `wp-config.php` lives):
```bash
cd /Users/minhle/Local\ Sites/luxora/app/public
wp post meta update <post_id> <field_key> "<value>"
```

This ensures the section renders with real content immediately after creation, without requiring a manual admin visit.

---

## Adding a New Section — Checklist

1. **ACF fields** — add fields to the relevant `inc/acf/acf-*.php` file (or create a new one and `require_once` it in `functions.php`)
2. **Template part** — create `template-parts/{context}/section-name.php`, pull all content via `get_field()`
3. **Include in template** — add `get_template_part()` call in the page template
4. **Style with Tailwind inline first** — only add to `src/partials/` if genuinely needed
5. **Test in browser** — run `npm run dev`, verify ACF fields render correctly

---

## Key Plugins

- **Advanced Custom Fields Pro** — content field management
- **Lottery for WooCommerce** — core competition logic
- **WooCommerce** — products, cart, checkout
- **FluentForm** — contact/entry forms

---

## Notes

- WordPress block editor is supported but most pages use full-width page templates that bypass the editor entirely.
- SiteGround hosting: cache-aware AJAX for guest cart sessions.
- `theme.json` configures block editor design tokens (keep in sync with `_theme.css` manually if tokens change).
- Font loading: Google Fonts enqueued in `functions.php`; Luxora homepage also loads Jost via `<link>` in the template head.
