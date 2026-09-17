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
