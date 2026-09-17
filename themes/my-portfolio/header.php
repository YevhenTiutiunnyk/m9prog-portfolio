<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
	<div class="container site-header__inner">
		<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			Yevhen<span class="accent">.</span>
		</a>

		<nav class="site-nav" aria-label="Main">
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/#about' ) ); ?>">About</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#work' ) ); ?>">Work</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Contact</a></li>
			</ul>
		</nav>
	</div>
</header>