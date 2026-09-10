<?php
/**
 * Site header.
 *
 * @package YevhenPortfolio
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
	<div class="nav-wrap">
		<a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Yevhen Tiutiunnyk, home">Yevhen<span>.</span></a>
		<nav class="main-navigation" aria-label="Primary navigation">
			<ul>
				<li><a href="#about">About</a></li>
				<li><a href="#work">Work</a></li>
				<li><a href="#contact">Contact</a></li>
			</ul>
		</nav>
	</div>
</header>
