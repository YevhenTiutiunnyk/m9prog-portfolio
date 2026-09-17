```text
You are a WordPress theme developer. I am already a FullStack developer
and a third-year Software Development student at Mediacollege Amsterdam.
This is an educational assignment: I need to create a local initial version
of my existing portfolio website, yevhent.com, as a custom WordPress theme.

The goal of the website is to show companies where I am looking for an
internship who I am, my tech stack, and my real projects.

Create only a minimal theme foundation in PHP for WordPress. Do not copy
my existing website pixel by pixel and do not use React, Next.js, or Tailwind —
this must be a simple WordPress theme.

Use these real details:
- Name: Yevhen Tiutiunnyk
- Role: FullStack Developer
- Location: Amsterdam
- Status: Open to internships
- Tech stack: React, TypeScript, Node.js, Python, Laravel
- Projects:
  1. AI Helper SD — an AI tutor for Software Development students;
     React, Express, Groq LLM.
  2. Storefront Bot — a multilingual Telegram bot for a product catalogue
     and order processing; Python, aiogram, SQLAlchemy.
  3. Spoor / Spår — a marketing website for a 2D+3D puzzle platformer;
     HTML, CSS, JavaScript.

I need:
- a home page;
- an About section;
- a Selected Work section with three project cards;
- a Contact section;
- semantic HTML markup;
- mobile-first CSS;
- a dark, professional visual style with an orange accent.

First, show the minimal structure of the required files and explain what
each one does. Then suggest the code in small parts. Use only files that
are genuinely needed for an initial WordPress theme.
Explain every important decision in simple language.
```



I kept this to a classic PHP theme with no build step, no JavaScript and no plugins. It's just the files WordPress needs to show a one-page portfolio properly.

1. File structure
File	Required?	Why it's here
style.css	Yes	WordPress only sees a folder as a theme if this file has a comment header at the top.
index.php	Yes	The last fallback in WordPress's template hierarchy. If no other template matches, this one is used.
functions.php	Practically	It's the right place to load your CSS. You could hard-code a <link> tag instead, but WordPress expects styles to go through wp_enqueue_style().
header.php / footer.php	Practically	Both front-page.php and index.php need the same <head> and footer. Splitting them out means you write that code once.
front-page.php	For this site	WordPress always uses this file for the home page, whatever your Reading settings say.
Left out on purpose: page.php, single.php, template-parts/, theme.json, JS files and screenshot.png. None of them are needed for a first version. index.php already handles any other page.

2. style.css: theme header
Why: WordPress reads this comment to show your theme under Appearance → Themes. Theme Name is the only line that's strictly required. Version gets reused later to bust the browser cache.

We'll add the actual CSS below this header in step 8.

3. functions.php
Decisions:

Every function name starts with yevhen_. WordPress puts all theme and plugin functions in one global namespace, so a prefix avoids name clashes.
wp_enqueue_style instead of a <link> tag. WordPress can then handle load order and dependencies, and plugins can change or remove the stylesheet if they need to.
title-tag. You don't write <title> yourself. WordPress fills it from the site name and page title.
Now add the project data to the same file:

Why an array and not a Custom Post Type? For three fixed projects, an array is the simplest thing that works. The template just loops over it. Moving the projects into a project post type later (so you can edit them in wp-admin) is a natural next step for this assignment. Only the data source would change, not the card markup.

4. header.php
Decisions:

wp_head() and wp_body_open() are required. WordPress uses them to print your CSS, the <title>, the admin bar and plugin output. Without them, style.css won't load.
Links use home_url( '/#about' ), not just #about. On any other page (handled by index.php), the link still goes back to the home page section.
esc_url() cleans every URL before output. The rule in WordPress is to escape late, meaning right where you print.
Skip link. Keyboard users can jump past the navigation. It's a cheap accessibility win.
Hard-coded links instead of wp_nav_menu(). For a one-page site with three anchors, a registered menu adds setup without real benefit yet.
5. footer.php
Why: wp_footer() is the partner of wp_head(). Scripts and the admin bar need it. wp_date() uses the timezone set in WordPress, not the server's.

6. front-page.php, in three parts
Part A: Hero + About
Semantic decisions:

One <h1> (your name), then an <h2> per section and <h3> inside them. Screen readers and search engines read this outline.
<section> with aria-labelledby. Each section is named by its own heading, so assistive tech can list "About", "Selected Work" and "Contact" as regions.
The tech stack is a <ul>. It's a list, so mark it up as one.
aria-hidden on the dot. It's decorative, and the text next to it already says the status.
<main> opens here, not in header.php. Every template controls its own main content, which is easier to follow.
Part B: Selected Work
Decisions:

<article> per card. Each project makes sense on its own, which is exactly what <article> means.
esc_html() on all data. It's your own array today, but once it comes from the database it becomes user input. Build the habit now.
foreach … : / endforeach;. This alternative syntax is the WordPress convention in templates and is easier to read inside HTML than { }.
The same .tag-list class is reused from About. One component, two places.
Part C: Contact
⚠️ Replace the three placeholder links with your real email, GitHub and LinkedIn. I didn't want to guess them.

Why <address>: it's the HTML element for the author's contact information. Despite the name, it isn't only for postal addresses.
Why no contact form: a form needs server handling, spam protection and privacy consent. mailto: works right away. You can add a form later with a plugin.

7. index.php: the fallback
Why: this is the standard WordPress Loop. It shows any page, post or archive that doesn't have its own template. is_singular() picks the right heading level. A single page gets an <h1>, and a list of posts gets linked <h2>s.

8. CSS (put all of this in style.css below the header)
Part A: design tokens and base
Decisions:

CSS custom properties. All colours live in one place, so changing the accent is a one-line edit.
Contrast is checked. Orange #ff7a1a on the dark background, and muted grey #a1a1aa on it, both pass WCAG AA (roughly 7:1).
System font stack. It loads instantly, needs no Google Fonts request and looks native on every OS.
:focus-visible. Keyboard users see a clear orange outline, and mouse clicks don't trigger it.
Part B: header, hero, sections
Decisions:

No hamburger menu. Three short links fit on a phone screen, so there's no need for JavaScript.
clamp() on the title. The font scales smoothly from phone to desktop without extra media queries.
scroll-margin-top. Without it, the sticky header would cover the section heading after you click a nav link.
Part C: components
Why: orange is used sparingly: the button, the status dot, link colour and the card hover. When the accent is rare, it points the eye at what matters. .project-grid has one column by default. That's the mobile-first part.

Part D: larger screens
Mobile-first in simple terms: the base CSS is written for phones. The min-width queries only add layout as the screen grows: one card column, then two at 768px, then three at 1024px. You never have to undo desktop styles for mobile.
Why smooth scrolling is inside prefers-reduced-motion: users who turned off animations in their OS don't get it.

Try it locally
Put the yevhen-portfolio folder in wp-content/themes/. LocalWP, MAMP and wp-env all work.
In wp-admin, go to Appearance → Themes and activate Yevhen Portfolio.
Open the site. front-page.php renders the home page automatically.
Good next steps for the assignment: move the projects into a Custom Post Type, add real project links and screenshots, and wrap the text strings in translation functions like esc_html_e().

I can also create these six files as a ready-to-use theme folder if you'd like