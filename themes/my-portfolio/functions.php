<?php
/**
 * Theme setup, assets and content helpers.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Block direct access to this file.
}

function yevhen_setup() {
	// Let WordPress generate the <title> tag.
	add_theme_support( 'title-tag' );

	// Output modern HTML5 markup for core elements.
	add_theme_support( 'html5', array( 'search-form', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'yevhen_setup' );

function yevhen_enqueue_assets() {
	wp_enqueue_style(
		'yevhen-style',
		get_stylesheet_uri(),                // URL of style.css
		array(),
		wp_get_theme()->get( 'Version' )     // ?ver=0.1.0 → cache busting
	);
}
add_action( 'wp_enqueue_scripts', 'yevhen_enqueue_assets' );

/**
 * Projects shown in the "Selected Work" section.
 */
function yevhen_get_projects() {
	return array(
		array(
			'title'       => 'AI Helper SD',
			'description' => 'An AI tutor for Software Development students.',
			'stack'       => array( 'React', 'Express', 'Groq LLM' ),
		),
		array(
			'title'       => 'Storefront Bot',
			'description' => 'A multilingual Telegram bot for a product catalogue and order processing.',
			'stack'       => array( 'Python', 'aiogram', 'SQLAlchemy' ),
		),
		array(
			'title'       => 'Spoor / Spår',
			'description' => 'A marketing website for a 2D+3D puzzle platformer.',
			'stack'       => array( 'HTML', 'CSS', 'JavaScript' ),
		),
	);
}