<?php get_header(); ?>

<main id="main" class="site-main">
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
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			<?php endif; ?>

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
				<?php foreach (yevhen_get_projects() as $project) : ?>
					<article class="project-card">
						<h3 class="project-card__title"><?php echo esc_html($project['title']); ?></h3>
						<p class="project-card__text"><?php echo esc_html($project['description']); ?></p>

						<ul class="tag-list" aria-label="Technologies used">
							<?php foreach ($project['stack'] as $tech) : ?>
								<li><?php echo esc_html($tech); ?></li>
							<?php endforeach; ?>
						</ul>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>