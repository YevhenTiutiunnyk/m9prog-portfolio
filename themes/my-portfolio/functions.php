<?php

/**
 * Theme setup, assets and content helpers.
 */

if (! defined('ABSPATH')) {
	exit; // Block direct access to this file.
}

function yevhen_setup()
{
	// Let WordPress generate the <title> tag.
	add_theme_support('title-tag');

	// Output modern HTML5 markup for core elements.
	add_theme_support('html5', array('search-form', 'style', 'script'));

	add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'yevhen_setup');

function yevhen_enqueue_assets()
{
	wp_enqueue_style(
		'yevhen-style',
		get_template_directory_uri() . '/dist/css/main.min.css',  // URL of style.css
		array(),
		wp_get_theme()->get('Version')     // ?ver=0.1.0 → cache busting
	);
	wp_enqueue_script(
		'yevhen-script',
		get_template_directory_uri() . '/dist/js/main.min.js',  // URL of main.js
		array(),
		wp_get_theme()->get('Version'),
		true
	);
}
add_action('wp_enqueue_scripts', 'yevhen_enqueue_assets');

add_action('phpmailer_init', function ($phpmailer) {
	$phpmailer->isSMTP();
	$phpmailer->Host     = 'mailpit';
	$phpmailer->Port     = 1025;
	$phpmailer->SMTPAuth = false;
});

add_filter('wp_mail_from', function () {
	return 'noreply@yevhent.com';
});

add_filter('wp_mail_from_name', function () {
	return 'Yevhen Portfolio';
});

/**
 * Projects shown in the "Selected Work" section.
 */
function yevhen_get_projects()
{
	return array(
		array(
			'title'       => 'AI Helper SD',
			'description' => 'An AI tutor for Software Development students.',
			'stack'       => array('React', 'Express', 'Groq LLM'),
		),
		array(
			'title'       => 'Storefront Bot',
			'description' => 'A multilingual Telegram bot for a product catalogue and order processing.',
			'stack'       => array('Python', 'aiogram', 'SQLAlchemy'),
		),
		array(
			'title'       => 'Spoor / Spår',
			'description' => 'A marketing website for a 2D+3D puzzle platformer.',
			'stack'       => array('HTML', 'CSS', 'JavaScript'),
		),
	);
}

function yevhen_is_spam($values)
{

	// Плагин не активен или ключ не введён — не блокируем никого.
	if (! class_exists('Akismet') || ! Akismet::get_api_key()) {
		return false;
	}

	$request = array(
		'blog'                 => get_option('home'),
		'blog_lang'            => get_locale(),
		'blog_charset'         => get_option('blog_charset'),
		'user_ip'              => $_SERVER['REMOTE_ADDR'] ?? '',
		'user_agent'           => $_SERVER['HTTP_USER_AGENT'] ?? '',
		'referrer'             => $_SERVER['HTTP_REFERER'] ?? '',
		'comment_type'         => 'contact-form',
		'comment_author'       => $values['contact_name'],
		'comment_author_email' => $values['contact_email'],
		'comment_content'      => $values['contact_message'],
	);

	$response = Akismet::http_post(Akismet::build_query($request), 'comment-check');

	return ! empty($response[1]) && 'true' === $response[1];
}
