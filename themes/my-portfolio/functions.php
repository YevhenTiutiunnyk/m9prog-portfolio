<?php
/**
 * Theme setup and asset loading.
 *
 * @package YevhenPortfolio
 */

function yevhen_portfolio_setup() {
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'yevhen_portfolio_setup' );

function yevhen_portfolio_enqueue_styles() {
	wp_enqueue_style(
		'yevhen-portfolio-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'yevhen_portfolio_enqueue_styles' );
