<?php get_header(); ?>

<main id="main" class="site-main">
	<h2>TEST: front-page.php</h2>
	<section class="hero" aria-labelledby="hero-title">
		<div class="container">
			<p class="status">
				<span class="status__dot" aria-hidden="true"></span>
				Open to internships
			</p>
			<h1 id="hero-title" class="hero__title">Yevhen Tiutiunnyk</h1>
			<p class="hero__role">FullStack Developer · Amsterdam</p>
			<a class="button" href="#work">View my work</a>
		</div>
	</section>

	<section id="about" class="section" aria-labelledby="about-title">
		<div class="container">
			<h2 id="about-title" class="section__title">About</h2>
			<p>
				I'm a third-year Software Development student at Mediacollege Amsterdam
				and a FullStack developer. I build real products end to end, from
				interfaces to APIs and bots, and I'm looking for an internship.
			</p>

			<h3 class="subheading">Tech stack</h3>
			<ul class="tag-list">
				<li>React</li>
				<li>TypeScript</li>
				<li>Node.js</li>
				<li>Python</li>
				<li>Laravel</li>
			</ul>
		</div>
	</section>
		<section id="work" class="section" aria-labelledby="work-title">
		<div class="container">
			<h2 id="work-title" class="section__title">Selected Work</h2>

			<div class="project-grid">
				<?php foreach ( yevhen_get_projects() as $project ) : ?>
					<article class="project-card">
						<h3 class="project-card__title"><?php echo esc_html( $project['title'] ); ?></h3>
						<p class="project-card__text"><?php echo esc_html( $project['description'] ); ?></p>

						<ul class="tag-list" aria-label="Technologies used">
							<?php foreach ( $project['stack'] as $tech ) : ?>
								<li><?php echo esc_html( $tech ); ?></li>
							<?php endforeach; ?>
						</ul>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
		<section id="contact" class="section" aria-labelledby="contact-title">
		<div class="container">
			<h2 id="contact-title" class="section__title">Contact</h2>
			<p>Looking for an intern who ships real projects? Let's talk.</p>

			<address class="contact">
				<a class="button" href="mailto:yevhen.tuk@gmail.com">Email me</a>
				<a class="contact__link" href="https://github.com/YevhenTiutiunnyk">GitHub</a>
				<a class="contact__link" href="https://www.linkedin.com/in/yevhen-tiutiunnyk-a733012b3/">LinkedIn</a>
			</address>
		</div>
	</section>

</main>

<?php get_footer(); ?>