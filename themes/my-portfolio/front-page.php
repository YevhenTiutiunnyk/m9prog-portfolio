<?php
/**
 * Homepage template.
 *
 * @package YevhenPortfolio
 */

get_header();
?>

<main id="main-content">
	<section class="hero" aria-labelledby="hero-title">
		<div class="hero-content">
			<p class="eyebrow">Open to internships</p>
			<h1 id="hero-title">Yevhen Tiutiunnyk, <em>FullStack Developer.</em></h1>
			<p class="intro">I build clear, useful web experiences and enjoy turning ideas into reliable products. Based in Amsterdam.</p>
			<div class="hero-actions">
				<a class="button" href="#work">View selected work</a>
				<a class="button button--secondary" href="#contact">Get in touch</a>
			</div>
		</div>
	</section>

	<section class="section" id="about" aria-labelledby="about-title">
		<div class="section-content">
			<div class="section-heading">
				<p class="section-label">01 / About</p>
				<div>
					<h2 id="about-title">Building for people, learning every day.</h2>
					<div class="about-copy">
						<p>I am a third-year Software Development student at Mediacollege Amsterdam and a FullStack Developer looking for an internship.</p>
						<p><strong>I care about useful interfaces, maintainable code and learning from real product teams.</strong> My work ranges from AI learning tools to automation and interactive promotional sites.</p>
					</div>
					<ul class="stack-list" aria-label="Technology stack">
						<li>React</li>
						<li>TypeScript</li>
						<li>Node.js</li>
						<li>Python</li>
						<li>Laravel</li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<section class="section" id="work" aria-labelledby="work-title">
		<div class="section-content">
			<div class="section-heading">
				<p class="section-label">02 / Selected work</p>
				<h2 id="work-title">Projects with a practical purpose.</h2>
			</div>
			<div class="projects">
				<article class="project-card">
					<span class="project-number" aria-hidden="true">01</span>
					<h3>AI Helper SD</h3>
					<p>An AI tutor that helps Software Development students learn and work through problems.</p>
					<ul class="project-stack" aria-label="Technologies used">
						<li>React</li><li>Express</li><li>Groq LLM</li>
					</ul>
				</article>
				<article class="project-card">
					<span class="project-number" aria-hidden="true">02</span>
					<h3>Storefront Bot</h3>
					<p>A multilingual Telegram bot for product catalogues and order placement.</p>
					<ul class="project-stack" aria-label="Technologies used">
						<li>Python</li><li>aiogram</li><li>SQLAlchemy</li>
					</ul>
				</article>
				<article class="project-card">
					<span class="project-number" aria-hidden="true">03</span>
					<h3>Spoor / Spår</h3>
					<p>A promotional site for a 2D + 3D puzzle platformer.</p>
					<ul class="project-stack" aria-label="Technologies used">
						<li>HTML</li><li>CSS</li><li>JavaScript</li>
					</ul>
				</article>
			</div>
		</div>
	</section>

	<section class="section" id="contact" aria-labelledby="contact-title">
		<div class="section-content">
			<div class="section-heading">
				<p class="section-label">03 / Contact</p>
				<div class="contact-panel">
					<div>
						<h2 id="contact-title">Let&rsquo;s build something useful.</h2>
						<p>I am currently open to internship opportunities in Amsterdam and would be glad to talk about how I can contribute to your team.</p>
					</div>
					<a class="button" href="https://yevhent.com">Visit yevhent.com</a>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
