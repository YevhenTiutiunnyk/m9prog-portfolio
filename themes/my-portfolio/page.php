<?php get_header(); ?>

<main id="main" class="site-main section">
	<div class="container">
		<h2>TEST: page.php</h2>
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php the_title( '<h1>', '</h1>' ); ?>
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="page-thumbnail">
								<?php the_post_thumbnail( 'large' ); ?>
							</div>
						<?php endif; ?>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p>Nothing found.</p>
		<?php endif; ?>
	</div>
</main>

<?php get_footer(); ?>