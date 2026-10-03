# AI log — Les 1

## Used AI
Codex.

## Goal
Create a minimal local WordPress theme based on my existing portfolio,
yevhent.com.

## Tested
- Docker containers are running.
- WordPress opens at http://localhost.
- My custom theme is visible and activated in WordPress.
- The homepage opens without an error.

## Expected result
WordPress should load my theme and show its index.php content.

## Actual result
The homepage loaded successfully.

## My own changes
- Used my real name, stack, and projects.
- Reviewed the suggested theme structure.
- Will adapt the design and code myself during later lessons.

## Issues
No issues so far.

# AI log — Les 2: van AI naar eigen code

## Used AI
Codex (theme code from les 1) and Claude (explanations, VS Code setup, test support).

## Review of the AI result

| File | I understand it | Check / replace first |
|---|---|---|
| style.css | ✅ theme header, CSS variables, mobile-first media queries | Check: the button hover colour `#ff9447` is hard-coded instead of a CSS variable. |
| functions.php | ✅ enqueue style, theme support, project array | Replace later: projects are a hard-coded PHP array → Custom Post Type, so I can edit them in wp-admin. Projects have no links or images yet. |
| header.php | ✅ wp_head(), wp_body_open(), navigation with anchors | Check: logo text and menu links are hard-coded → later `bloginfo( 'name' )` and `wp_nav_menu()`. |
| footer.php | ✅ wp_footer(), copyright year with wp_date() | OK for now. Only my name is hard-coded. |
| front-page.php | ✅ Hero, About, Work loop with esc_html(), Contact | Contact links are replaced with my real email, GitHub and LinkedIn. Still to do: rewrite the About text (written by AI) in my own words. |
| index.php | ✅ fallback template with The Loop, h1/h2 via is_singular() | Check: no pagination on archive pages (`the_posts_pagination()` is missing). |

## Theme header
style.css has a full header: Theme Name, Description, Version, Author, Text Domain
(plus Theme URI, Author URI, Requires, License).

## Screenshot
Replaced the old screenshot (photo of the live yevhent.com with browser tabs)
with a 1200×900 screenshot of the local theme itself — the size WordPress recommends.

## My own change
- What: every project card in "Selected Work" now shows a number (01, 02, 03) in orange
  above the title.
- File: style.css — `counter-reset` on `.project-grid`, `counter-increment` and a
  `::before` pseudo-element on `.project-card`. Theme version bumped to 0.1.1.
- How it works: CSS counters count the cards automatically, so no PHP or HTML changes are
  needed and a 4th project gets "04" by itself. `decimal-leading-zero` adds the 0.
- Why: the AI cards all looked the same. The numbers make the order clear, repeat the
  orange accent and match the numbered style of my real site, yevhent.com.
  The version bump changes `style.css?ver=` so the browser loads the new CSS instead of the cached one.

## Tested

### 1. Home page (front-page.php)
- Expected: http://localhost shows my theme with Hero, About, Selected Work (3 cards) and Contact.
- Actual: page returns 200, style.css?ver=0.1.0 is loaded, 3 project cards are rendered.
- Error: no.

### 2. Other pages (index.php)
- Expected: a normal post opens through index.php with its title as <h1>.
- Actual: http://localhost/2026/09/10/hello-world/ shows "Hello world!" as <h1> inside my theme.
- Error: no.

### 3. PHP syntax
- Expected: no syntax errors.
- Actual: `php -l` inside the WordPress container: no errors in all 5 PHP files.

### 4. My own change (numbered project cards)
- Expected: the three cards show 01, 02 and 03 in orange above the title.
- Actual: first the numbers were not visible — the browser still used the old cached CSS.
  After a reload, style.css?ver=0.1.1 loaded and all three cards show 01, 02, 03.
- Error: cached CSS. Fixed by raising the theme Version, which changes the ?ver= in the CSS URL.

## Issues and fixes
1. **Everything red in VS Code.** Intelephense did not know WordPress functions
   (get_header, have_posts…) because WordPress core runs in Docker, not in my project folder.
   Fix: added `wordpress` to the Intelephense stubs setting. The code itself was fine.
2. **.env was committed to Git.** The ignore file was named `gitignore` (without the dot),
   so Git ignored nothing. Fix: renamed it to `.gitignore` and removed `.env` from Git
   with `git rm --cached .env` (the local file stays).

# AI log — Les 3: custom theme structureren

## Used AI
Claude (explanations of the template hierarchy and enqueueing, VS Code troubleshooting,
review of my files). I wrote the PHP myself.

## 1. Enqueueing styles and scripts

`functions.php` already loaded `style.css` with `wp_enqueue_style()` from les 2.
This lesson I added `wp_enqueue_script()` for a new `assets/js/main.js`.

Why enqueueing instead of a hard-coded `<link>` / `<script>` in header.php:
WordPress collects every request from the theme, plugins and core into one registry and
prints them at `wp_head()` / `wp_footer()`. That gives deduplication (two plugins asking
for jQuery get one copy), dependency order (3rd parameter), and the `?ver=` cache busting
I already ran into in les 2.

Two parameters I had to think about:
- `get_stylesheet_uri()` points to the **active** theme, `get_template_directory_uri()`
  to the **parent** theme. With a child theme those are different folders.
- The last parameter of `wp_enqueue_script()` is `true`, which moves the `<script>` to the
  footer. A script in `<head>` blocks HTML parsing; in the footer the page renders first.

## 2. add_theme_support( 'post-thumbnails' )

Added inside `yevhen_setup()`, which runs on the `after_setup_theme` hook — that is required,
it does not work at file top level.

Effect:
1. A **Featured image** panel appears in the editor sidebar. I saw this immediately when I
   created my first page: before this line the box simply does not exist, it is not hidden.
2. `the_post_thumbnail()`, `has_post_thumbnail()` and `get_the_post_thumbnail_url()` become
   usable in my templates.

It takes an optional second argument (an array of post types) to limit it. With no second
argument it applies to posts and pages. Enabling it does not resize images that were already
uploaded — WordPress generates the registered sizes at upload time.

## 3. Templates and the template hierarchy

Created `page.php`. I copied `index.php` and removed the `is_singular()` branch: `index.php`
serves both a single post and a list of posts, but `page.php` only ever serves one page, so
only the `<h1>` version of `the_title()` is needed. The Loop itself stays — `the_post()` is
what sets up the global post object, so without it `the_title()` and `the_content()` have
nothing to read.

Both templates got a temporary test heading so I could see which file WordPress chose.

Which template is used:

| Request | Chain WordPress walks | Winner in my theme |
|---|---|---|
| Homepage (static page) | `front-page.php` → `page-{slug}.php` → `page-{id}.php` → `page.php` → `singular.php` → `index.php` | `front-page.php` |
| Normal page (Over mij) | `page-over-mij.php` → `page-{id}.php` → `page.php` → `singular.php` → `index.php` | `page.php` |

The important part: `front-page.php` always wins for the homepage, whether Settings → Reading
is set to "latest posts" or to a static page. Both my pages are Pages in the database and both
are `is_page()` — the hierarchy decides, not the content type.

In WordPress I made a `Home` page and an `Over mij` page, and set Settings → Reading to
"A static page" with Home as homepage and Posts page empty.

## Tested

### 1. Homepage uses front-page.php
- Expected: `http://localhost` shows "TEST: front-page.php".
- Actual: HTTP 200, "TEST: front-page.php" is in the HTML.
- Error: no.

### 2. Normal page uses page.php
- Expected: `http://localhost/over-mij/` shows "TEST: page.php" and my own page text.
- Actual: HTTP 200, "TEST: page.php" is rendered and `the_content()` outputs the text I typed
  in the editor.
- Error: no.

### 3. Fallback in the hierarchy
- Expected: if `front-page.php` does not exist, the homepage falls through to the next file
  in the chain, `page.php`.
- Actual: I renamed `front-page.php` to `front-page.php.off` and reloaded `http://localhost`.
  The homepage then showed "TEST: page.php". After renaming the file back, the homepage used
  `front-page.php` again.
- Error: no. This is the clearest proof that the hierarchy is a chain and not a single lookup.

### 4. Enqueued assets
- Expected: both pages load `style.css` and `main.js` with a `?ver=` from the theme header.
- Actual: both pages load `style.css?ver=0.1.1` and `main.js?ver=0.1.1`.
- Error: no.

### 5. PHP syntax
- Expected: no syntax errors.
- Actual: `php -l` on all six PHP files: no errors.

## Something I noticed

The text I typed into the `Home` page does not appear on the homepage. That is not a bug:
`front-page.php` never calls `the_content()`, it renders my hardcoded hero, about, work and
contact sections. `page.php` does call `the_content()`, so on Over mij my text does show.
Same content field in the database, two templates, one uses it and one ignores it.

## Issues and fixes

1. **All WordPress functions underlined yellow in VS Code.** Same symptom as les 1, but a
   different cause, so the les 1 fix did not help. In les 1 the squiggles were red and I fixed
   them by adding `wordpress` to `intelephense.stubs`. This time the warnings came from a
   **second** PHP language server: besides Intelephense I also have DEVSense PHP Tools
   installed. Both analyse every `.php` file, and PHP Tools does not read `intelephense.stubs`.
   It resolves symbols from files in the workspace, and WordPress core is in Docker, not in my
   project folder — so it reported every WordPress function as unknown, at warning severity.
   Fix: added `.vscode/settings.json` with `"php.problems.exclude": true`, which turns off
   PHP Tools' diagnostics and leaves Intelephense as the only analyser. 15 warnings → 0.
   Lesson: the same symptom can have a different cause, and one fix is analyser-specific.
2. **Indentation.** `page.php` was copied from `index.php`, which uses tabs, but my new test
   heading is indented with spaces. Cosmetic only, still open.

## Still to do
- Remove the two temporary `TEST:` headings once the lesson is checked.
- Rewrite the Over mij text in my own words (it is still partly AI-drafted), same open point
  as the About text from les 2.

# AI log — Les 4: header, footer en loops

## Used AI
Claude (explanation of the Loop and featured images, review of my files, checking the
rendered HTML). I wrote the PHP myself.

## What already existed

`header.php` with `wp_head()`, `body_class()` and a navigation, and `footer.php` with
`wp_footer()`, were already finished in les 1 and 2, and all three templates already used
`get_header()` and `get_footer()`. So this lesson was mainly about the Loop and about making
the content actually come from WordPress instead of from hardcoded HTML.

## 1. The Loop

```php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        // the "current post" exists here
    endwhile;
endif;
```

`the_post()` is the part that matters. WordPress has already run the main query before my
template loads. `the_post()` takes the next result and puts it in a global variable, and every
tag starting with `the_` or `get_the_` reads from that global. That is why `the_title()` works
without any arguments — it is not magic, it reads what `the_post()` set up.

## 2. Featured image

`the_post_thumbnail( 'large' )` is just another tag reading the same global. It prints a
complete `<img>` with `srcset`, so the browser can pick a smaller file on a phone. The output
I checked:

```
class="attachment-large size-large wp-post-image" width="1024" height="768" srcset=...
```

The `size-large` class confirms WordPress served the 1024px version it generated at upload,
not the original file.

Two things I had to handle:
- **Guard with `has_post_thumbnail()`.** Without an image `the_post_thumbnail()` prints
  nothing, but my wrapper `<div class="page-thumbnail">` would still be printed — empty markup
  and broken spacing.
- **CSS.** WordPress bakes `width` and `height` attributes into the tag at the real pixel size,
  so without `max-width: 100%; height: auto;` a large image breaks out of the container on a
  phone.

The featured image only works because of `add_theme_support( 'post-thumbnails' )` from les 3.

## 3. Dynamic content on the homepage

`front-page.php` had no Loop at all, it only printed hardcoded HTML, so the text I typed into
the Home page in WordPress was ignored. I replaced the hardcoded About paragraph with a Loop
and `the_content()`.

This works because on a static front page the main query **is** that Home page, so
`have_posts()` is true and `the_post()` sets up the Home page as the current post.

Gotcha: I removed the `<p>` wrapper instead of putting `the_content()` inside it. The block
editor already outputs `<p>` around each paragraph, so `<p><p>text</p></p>` would have been
invalid HTML. I checked the rendered source: the output is a single
`<p class="wp-block-paragraph">`, no nesting.

This also let me finally replace the AI-written About text with my own, which was still an
open point from les 2.

## 4. Footer

Added contact information (email, GitHub, LinkedIn) next to the copyright line, and moved the
whole Contact section out of `front-page.php` into `footer.php`. Now contact details appear on
every page instead of only the homepage, and there is no duplicate `id="contact-title"`.

`wp_footer()` stays the last thing before `</body>`. That is the hook where WordPress and
plugins inject footer scripts, including my own `main.js`, which I enqueued with
`$in_footer = true`. In the rendered homepage the `<script>` tag for `main.js` appears after
the footer markup, which confirms it.

## Tested

### 1. Featured image on a page
- Expected: `http://localhost/over-mij/` shows the featured image between the title and the text.
- Actual: HTTP 200, `<div class="page-thumbnail">` is rendered with an `<img>` at 1024x768,
  class `attachment-large size-large wp-post-image`, and `srcset` present.
- Error: no.

### 2. Alt text
- Expected: the image has a meaningful `alt`.
- Actual: first the `alt` was **empty**. `the_post_thumbnail()` does not invent alt text, it
  reads the Alternative Text field of the attachment in the Media Library, which I had left
  blank. After filling it in: `alt="Me with my girlfriend"`.
- Error: yes, fixed. Important because my header already has a skip-link and `aria-label`,
  so an empty alt would undercut the rest.

### 3. Dynamic About section
- Expected: the About text on the homepage comes from the Home page editor, not from the theme.
- Actual: the text I typed in WordPress appears on the homepage as
  `<p class="wp-block-paragraph">`. No nested `<p>` tags in the source.
- Error: no.

### 4. Footer on every page
- Expected: contact details and copyright on both the homepage and Over mij, and `main.js`
  loaded after the footer.
- Actual: both correct. `main.js?ver=0.1.2` appears after the `</footer>` markup.
- Error: no.

### 5. Navigation anchors
- Expected: `#about`, `#work` and `#contact` in the nav all point at an existing element.
- Actual: all four ids (`main`, `about`, `work`, `contact`) exist in the rendered homepage.
- Error: see below, `#contact` was broken first.

### 6. PHP syntax
- Expected: no syntax errors.
- Actual: `php -l` on all six PHP files: no errors.

## Issues and fixes

1. **Broken `#contact` link.** Moving the Contact section from `front-page.php` to
   `footer.php` deleted the element that had `id="contact"`, so the Contact link in my
   navigation scrolled nowhere. I only noticed by checking the rendered HTML for the id, not
   by looking at the page. Fix: put `id="contact"` on the `<footer>` element. Now the anchor
   works from every page instead of only the homepage. Lesson: moving a block of HTML can
   break something that lives in a completely different file.
2. **Cached CSS again.** Same trap as les 2: my new `.page-thumbnail img` rule did not apply
   until I raised the theme Version from 0.1.1 to 0.1.2, which changes `style.css?ver=`.
   I expected this one this time.
3. **Code style.** In a few new lines I wrote `if (have_posts())` and `esc_html(wp_date('Y'))`
   without spaces inside the parentheses, while the rest of the theme uses the WordPress
   style `if ( have_posts() )`. Cosmetic, still open.

## Still to do
- Remove the two temporary `TEST:` headings once les 3 and 4 are checked.
- Make the navigation dynamic with `wp_nav_menu()` and the logo with `bloginfo( 'name' )` —
  still open from les 2.
- Move the Tech stack list and the projects array out of the theme files so I can edit them
  in wp-admin.

# AI log — Les 5: Sass, npm en Webpack

## Used AI
Claude (explanation of the build chain and the `!default` mechanism, review of my files,
checking the compiled CSS and the rendered HTML). I wrote the Sass and the PHP myself.
`package.json` and `webpack.config.js` come from the examples provided with the lesson;
I adjusted the fields.

## What this lesson changed

Until now my CSS lived in `style.css` and the browser read it directly. Now my source lives
in `src/scss/` and `src/js/`, Webpack compiles it to `dist/`, and only `dist/` is enqueued.
`style.css` still exists but holds nothing except the theme header — WordPress identifies a
theme by that comment block, so deleting it would make the theme disappear from wp-admin.

```
themes/my-portfolio/
├── package.json          dependencies + scripts
├── webpack.config.js     build configuration
├── style.css             theme header only
├── src/                  what I edit
│   ├── js/main.js
│   └── scss/main.scss, _variables.scss, _theme.scss
└── dist/                 generated, never edited by hand
```

## 1. npm

`npm install` read `package.json` and installed 211 packages into `node_modules/`
(0 vulnerabilities). Bootstrap 5.3.8.

- `dependencies` = code that ends up in the browser (bootstrap, @popperjs/core).
- `devDependencies` = only needed to build (webpack, sass, the loaders).
- `package-lock.json` pins the exact resolved versions, so the build is reproducible on
  another machine. It belongs in git; `node_modules/` (77 MB) does not.

Two things that caught me out:
- The `name` field in the example was `"Voorbeeld site"`, which is not a valid npm package
  name — no spaces and no capitals are allowed. Changed it to `yevhen-portfolio`.
- The lesson text mentions `npm run build`, but the scripts in the example `package.json` are
  `dev`, `watch` and `prod`. I used `npm run prod`.

## 2. Webpack

Webpack takes an entry file, follows every import and writes a bundle. Loaders transform
files on the way through: `sass-loader` compiles Sass to CSS, `css-loader` resolves `@import`
and `url()`, and `MiniCssExtractPlugin` writes the result to a real `.css` file instead of
injecting it with JavaScript.

One thing in the given config confused me until I understood it: `main.scss` is used as an
*entry point*, but Webpack entries are JavaScript by definition, so this produces a pointless
empty `main.js` next to the CSS. That is exactly why `webpack-remove-empty-scripts` is in the
plugin list.

The config builds two sets at once: unminified (`main.css`, `main.js`) and minified
(`main.min.css`, `main.min.js`). Output: 237 KB raw CSS, 227 KB minified, 86 bytes of JS.

## 3. Bootstrap via Sass, and the !default rule

This is the part that actually matters. Bootstrap declares all its variables like this:

```scss
$primary: #0d6efd !default;
```

`!default` means "use this value **only if** the variable does not already have one". So the
order of imports decides everything:

```scss
@import "variables";                  // my values first
@import "bootstrap/scss/bootstrap";   // bootstrap builds from them
@import "theme";                      // my own rules last
```

Set before Bootstrap → Bootstrap skips its own value and builds every button, link and badge
from my colour. Set after → too late, Bootstrap has already computed everything from blue.

My overrides in `_variables.scss` (no `!default`, so mine win):

| Variable | Value | Covers |
|---|---|---|
| `$body-bg` | `#0b1020` | colour |
| `$body-color` | `#ededed` | colour |
| `$primary` | `#ff7a1a` | colour |
| `$font-family-base` | my system font stack | typography |
| `$border-radius` | `12px` | — |
| `$spacer` | `1rem` | spacing |

Verified in the compiled output:

```
--bs-primary:#ff7a1a; --bs-body-bg:#0b1020; --bs-border-radius:12px;
--bs-body-font-family:system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
```

(There is a second `--bs-body-bg:#212529` further down, that is Bootstrap's
`[data-bs-theme="dark"]` block, not a mistake.)

## 4. The opdracht: a different background

My old `_theme.scss` still had `body { background: var(--color-bg); ... }`, and because my
own partial is imported *after* Bootstrap, that rule won. `$body-bg` was set correctly but had
no visible effect at all.

Fix: removed `background`, `color` and `font-family` from that `body` rule and let Bootstrap's
variables supply them. Only then is `_variables.scss` really in control. After that, changing
`$body-bg` from `#0e0e10` to `#0b1020` and rebuilding visibly changed the site.

## Tested

### 1. Build runs
- Expected: `npm run prod` compiles without errors and fills `dist/`.
- Actual: build succeeded, `dist/css/main.css`, `dist/css/main.min.css`, `dist/js/main.js`
  and `dist/js/main.min.js` created.
- Error: no.

### 2. My variables reach the compiled CSS
- Expected: Bootstrap's generated custom properties use my values, not its defaults.
- Actual: `--bs-primary:#ff7a1a`, `--bs-body-bg:#0b1020`, `--bs-border-radius:12px` and my
  font stack are all present in `dist/css/main.min.css`.
- Error: no.

### 3. Only compiled files are enqueued
- Expected: the page loads `dist/css/main.min.css` and `dist/js/main.min.js`, and no longer
  the old `style.css` or `assets/js/main.js`.
- Actual: both are loaded with `?ver=0.2.1`, both return HTTP 200.
- Error: yes, first time. See below.

### 4. Background actually changed
- Expected: the site shows the new navy background instead of near-black.
- Actual: after rebuilding and bumping the version, the served CSS contains
  `--bs-body-bg:#0b1020` and the page shows it.
- Error: no.

### 5. Production build without a dev server
- Expected: the site works from the compiled files alone, with no webpack dev server running.
- Actual: homepage and Over mij both return HTTP 200 with the production build. There is no
  dev server anywhere in the setup — WordPress serves the static files from `dist/`.
- Error: no.

## Issues and fixes

1. **Broken stylesheet URL — the site had no CSS at all.** I wrote
   `get_stylesheet_uri() . '/dist/css/main.min.css'`. `get_stylesheet_uri()` returns the URL
   **of the style.css file itself**, filename included, not the folder. So the result was
   `.../my-portfolio/style.css/dist/css/main.min.css` → HTTP 404.
   Fix: `get_template_directory_uri()`, which returns the theme *folder* — exactly what I had
   already used correctly on the line below for the script.
   What makes this one worth remembering: PHP reported nothing, because string concatenation
   cannot fail, and the line looked fine. Only the rendered HTML showed the problem.
2. **`body` rule overriding my Sass variable.** Described above — the variable was right, my
   own CSS was simply louder. Fix: delete the competing declarations.
3. **Caching, again, but worse.** The output filename `main.min.css` never changes between
   builds, so without bumping the theme Version the browser keeps the old file forever. Third
   lesson in a row this has cost me time. Version is now 0.2.1.
4. **New workflow to get used to.** Editing `src/scss/` changes nothing until I run
   `npm run prod`. The browser never sees my Sass, only `dist/`. `npm run watch` recompiles
   automatically while working.

## Git
`node_modules/` stays out of the repo (77 MB, already in `.gitignore`). `dist/` **is**
committed (464 KB): the live server has no Node and runs no build, so the compiled files have
to travel with the theme.

## Still to do
- Remove the two temporary `TEST:` headings once les 3 and 4 are checked.
- `wp_nav_menu()` and `bloginfo( 'name' )` for the header — open since les 2.
- The whole of Bootstrap is compiled in, while I use almost none of its components. Importing
  only the parts I need would cut the 227 KB a lot.
- My own CSS and Bootstrap now partly do the same work. Worth cleaning up.
