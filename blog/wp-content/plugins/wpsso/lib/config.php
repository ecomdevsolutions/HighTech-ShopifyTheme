<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoConfig' ) ) {

	class WpssoConfig {

		public static $cf = array(
			'lca' => 'wpsso',	// Main plugin lowercase acronym (deprecated on 2017/11/18).
			'plugin' => array(
				'wpsso' => array(			// Plugin acronym.
					'version' => '4.5.1',		// Plugin version.
					'opt_version' => '579',		// Increment when changing default option values.
					'short' => 'WPSSO Core',	// Short plugin name.
					'name' => 'WPSSO Core [Main Plugin]',
					'desc' => 'WPSSO Core creates complete and accurate meta tags and Schema markup automatically from your content for social sharing, Google\'s Knowledge Graph and Rich Card SEO, Pinterest Rich Pins, Twitter Cards and more.',
					'slug' => 'wpsso',
					'base' => 'wpsso/wpsso.php',
					'update_auth' => 'tid',
					'text_domain' => 'wpsso',
					'domain_path' => '/languages',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'images/icon-128x128.png',
							'high' => 'images/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso',
						'review' => 'https://wordpress.org/support/plugin/wpsso/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso/master/readme.txt',
						'setup_html' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso/master/html/setup.html',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso/',
						'faqs' => 'https://wpsso.com/docs/plugins/wpsso/faqs/',
						'notes' => 'https://wpsso.com/docs/plugins/wpsso/notes/',
						'support' => 'http://wpsso.support.wpsso.com/support/tickets/new',	// Pro support ticket.
						'purchase' => 'https://wpsso.com/extend/plugins/wpsso/',	// Purchase page.
						'info' => 'https://wpsso.com/extend/plugins/wpsso/info/',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso/update/',
						'latest' => '',
					),
					'lib' => array(
						'profile' => array(
							'your-sso' => 'Your SSO',
						),
						'setting' => array(
							'image-dimensions' => 'Social and Search Image Dimensions',
							'social-accounts' => 'WebSite Social Pages and Accounts',
							'contact-fields' => 'User Profile Contact Methods',
						),
						'submenu' => array(	// Note that submenu elements must have unique keys.
							'essential' => 'Essential',
							'general' => 'General',
							'advanced' => 'Advanced',
							'licenses' => 'Add-ons',
							'dashboard' => 'Dashboard',
							'setup' => 'Setup Guide',
						),
						'sitesubmenu' => array(	// Note that submenu elements must have unique keys.
							'siteadvanced' => 'Advanced',
							'sitelicenses' => 'Add-ons',
							'sitesetup' => 'Setup Guide',
						),
						'gpl' => array(
							'admin' => array(
								'general' => 'Extend General Settings',
								'advanced' => 'Extend Advanced Settings',
								'post' => 'Extend Post Settings',
								'meta' => 'Extend Term and User Settings',
							),
							'social' => array(
								'buddypress' => '(plugin) BuddyPress',
							),
							'util' => array(
								'post' => '(tool) Custom Post Meta',
								'term' => '(tool) Custom Term Meta',
								'user' => '(tool) Custom User Meta',
							),
						),
						'pro' => array(
							'admin' => array(
								'general' => 'Extend General Settings',
								'advanced' => 'Extend Advanced Settings',
								'post' => 'Extend Post Settings',
								'meta' => 'Extend Term and User Settings',
							),
							'ecom' => array(
								'edd' => '(plugin) Easy Digital Downloads',
								'marketpress' => '(plugin) MarketPress',
								'woocommerce' => '(plugin) WooCommerce',
								'wpecommerce' => '(plugin) WP eCommerce',
							),
							'event' => array(
								'tribe_events' => '(plugin) The Events Calendar',
							),
							'form' => array(
								'gravityview' => '(plugin) GravityView',
							),
							'forum' => array(
								'bbpress' => '(plugin) bbPress',
							),
							'lang' => array(
								'polylang' => '(plugin) Polylang',
							),
							'media' => array(
								'facebook' => '(api) Facebook Video API',
								'gravatar' => '(api) Author Gravatar Image',
								'ngg' => '(plugin) NextGEN Gallery, NextCellent Gallery',
								'rtmedia' => '(plugin) rtMedia for WordPress, BuddyPress and bbPress',
								'slideshare' => '(api) Slideshare API',
								'soundcloud' => '(api) Soundcloud API',
								'upscale' => '(tool) WP Media Library Image Upscaling',
								'vimeo' => '(api) Vimeo Video API',
								'wistia' => '(api) Wistia Video API',
								'wpvideo' => '(api) WordPress Video Shortcode',
								'youtube' => '(api) YouTube Video / Playlist API',
							),
							'rating' => array(
								'wppostratings' => '(plugin) WP-PostRatings',
								'yotpowc' => '(plugin) Yotpo Social Reviews for WooCommerce',
							),
							'seo' => array(
								'aioseop' => '(plugin) All in One SEO Pack',
								'autodescription' => '(plugin) The SEO Framework',
								'headspace2' => '(plugin) HeadSpace2 SEO',
								'wpmetaseo' => '(plugin) WP Meta SEO',
								'wpseo' => '(plugin) Yoast SEO',
							),
							'social' => array(
								'buddypress' => '(plugin) BuddyPress',
							),
							'util' => array(
								'checkimgdims' => '(tool) Verify Image Dimensions',
								'coauthors' => '(plugin) Co-Authors Plus',
								'language' => '(tool) WP Locale to Publisher Language Mapping',
								'shorten' => '(api) URL Shortening Service APIs',
								'post' => '(tool) Custom Post Meta',
								'restapi' => '(plugin) WordPress REST API (Version 2)',
								'term' => '(tool) Custom Term Meta',
								'user' => '(tool) Custom User Meta',
								'wpseo_meta' => '(tool) Yoast SEO Social Meta',
							),
						),
					),
				),
				'wpssoam' => array(			// Plugin acronym.
					'short' => 'WPSSO AM',		// Short plugin name.
					'name' => 'WPSSO Mobile App Meta',
					'desc' => 'WPSSO Core add-on to provide Apple Store / iTunes and Google Play App meta tags for Apple\'s mobile Safari and Twitter\'s App Card.',
					'slug' => 'wpsso-am',
					'base' => 'wpsso-am/wpsso-am.php',
					'update_auth' => 'tid',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-am/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-am/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-am/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-am/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-am/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-am',
						'review' => 'https://wordpress.org/support/plugin/wpsso-am/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-am/master/readme.txt',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-am/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-am/',
						'faqs' => '',
						'notes' => '',
						'support' => 'http://wpsso-am.support.wpsso.com/support/tickets/new',	// Pro support ticket.
						'purchase' => 'https://wpsso.com/extend/plugins/wpsso-am/',	// Purchase page.
						'info' => 'https://wpsso.com/extend/plugins/wpsso-am/info/',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-am/update/',
						'latest' => '',
					),
				),
				'wpssobc' => array(			// Plugin acronym.
					'short' => 'WPSSO BC',		// Short plugin name.
					'name' => 'WPSSO Schema Breadcrumbs Markup',
					'desc' => 'WPSSO Core add-on to provide JSON-LD formatted Schema BreadcrumbList markup for Google and Search Engine Optimization (SEO).',
					'slug' => 'wpsso-breadcrumbs',
					'base' => 'wpsso-breadcrumbs/wpsso-breadcrumbs.php',
					'update_auth' => '',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-breadcrumbs/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-breadcrumbs/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-breadcrumbs/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-breadcrumbs/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-breadcrumbs/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-breadcrumbs',
						'review' => 'https://wordpress.org/support/plugin/wpsso-breadcrumbs/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-breadcrumbs/master/readme.txt',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-breadcrumbs/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-breadcrumbs/',
						'faqs' => '',
						'notes' => '',
						'support' => '',	// Pro support ticket.
						'purchase' => '',	// Purchase page.
						'info' => '',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-breadcrumbs/update/',
						'latest' => '',
					),
				),
				'wpssojson' => array(			// Plugin acronym.
					'short' => 'WPSSO JSON',	// Short plugin name.
					'name' => 'WPSSO Schema JSON-LD Markup',
					'desc' => 'WPSSO Core add-on to provide Schema JSON-LD / SEO markup for Articles, Events, Local Business, Products, Recipes, Reviews and many more.',
					'slug' => 'wpsso-schema-json-ld',
					'base' => 'wpsso-schema-json-ld/wpsso-schema-json-ld.php',
					'update_auth' => 'tid',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-schema-json-ld/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-schema-json-ld/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-schema-json-ld/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-schema-json-ld/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-schema-json-ld/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-schema-json-ld',
						'review' => 'https://wordpress.org/support/plugin/wpsso-schema-json-ld/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-schema-json-ld/master/readme.txt',
						'shortcode_html' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-schema-json-ld/master/html/shortcode.html',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-schema-json-ld/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-schema-json-ld/',
						'faqs' => '',
						'notes' => 'https://wpsso.com/docs/plugins/wpsso-schema-json-ld/notes/',
						'support' => 'http://wpsso-schema-json-ld.support.wpsso.com/support/tickets/new',	// Pro support ticket.
						'purchase' => 'https://wpsso.com/extend/plugins/wpsso-schema-json-ld/',	// Purchase page.
						'info' => 'https://wpsso.com/extend/plugins/wpsso-schema-json-ld/info/',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-schema-json-ld/update/',
						'latest' => '',
					),
				),
				'wpssoorg' => array(			// Plugin acronym.
					'short' => 'WPSSO ORG',		// Short plugin name.
					'name' => 'WPSSO Organization Markup',
					'desc' => 'WPSSO Core add-on to manage Organizations and additional Schema markup properties (Organizer, Performer, Publisher, etc.) for Google Rich Cards / Knowledge Graph.',
					'slug' => 'wpsso-organization',
					'base' => 'wpsso-organization/wpsso-organization.php',
					'update_auth' => 'tid',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-organization/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-organization/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-organization/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-organization/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-organization/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-organization',
						'review' => 'https://wordpress.org/support/plugin/wpsso-organization/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-organization/master/readme.txt',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-organization/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-organization/',
						'faqs' => '',
						'notes' => '',
						'support' => 'http://wpsso-organization.support.wpsso.com/support/tickets/new',	// Pro support ticket.
						'purchase' => 'https://wpsso.com/extend/plugins/wpsso-organization/',	// Purchase page.
						'info' => 'https://wpsso.com/extend/plugins/wpsso-organization/info/',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-organization/update/',
						'latest' => '',
					),
				),
				'wpssoplm' => array(			// Plugin acronym.
					'short' => 'WPSSO PLM',		// Short plugin name.
					'name' => 'WPSSO Place / Location and Local Business Meta',
					'desc' => 'WPSSO Core add-on to provide Pinterest Place, Facebook / Open Graph Location, Schema Local Business, and Local SEO meta tags.',
					'slug' => 'wpsso-plm',
					'base' => 'wpsso-plm/wpsso-plm.php',
					'update_auth' => 'tid',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-plm/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-plm/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-plm/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-plm/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-plm/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-plm',
						'review' => 'https://wordpress.org/support/plugin/wpsso-plm/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-plm/master/readme.txt',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-plm/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-plm/',
						'faqs' => '',
						'notes' => '',
						'support' => 'http://wpsso-plm.support.wpsso.com/support/tickets/new',	// Pro support ticket.
						'purchase' => 'https://wpsso.com/extend/plugins/wpsso-plm/',	// Purchase page.
						'info' => 'https://wpsso.com/extend/plugins/wpsso-plm/info/',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-plm/update/',
						'latest' => '',
					),
				),
				'wpssorar' => array(			// Plugin acronym.
					'short' => 'WPSSO RAR',		// Short plugin name.
					'name' => 'WPSSO Ratings and Reviews',
					'desc' => 'WPSSO Core add-on to provide ratings and reviews for WordPress comments, with Aggregate Rating meta tags and optional Schema Review markup.',
					'slug' => 'wpsso-ratings-and-reviews',
					'base' => 'wpsso-ratings-and-reviews/wpsso-ratings-and-reviews.php',
					'update_auth' => '',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-ratings-and-reviews/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-ratings-and-reviews/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-ratings-and-reviews/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-ratings-and-reviews/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-ratings-and-reviews/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-ratings-and-reviews',
						'review' => 'https://wordpress.org/support/plugin/wpsso-ratings-and-reviews/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-ratings-and-reviews/master/readme.txt',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-ratings-and-reviews/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-ratings-and-reviews/',
						'faqs' => '',
						'notes' => '',
						'support' => '',	// Pro support ticket.
						'purchase' => '',	// Purchase page.
						'info' => '',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-ratings-and-reviews/update/',
						'latest' => '',
					),
				),
				'wpssorrssb' => array(			// Plugin acronym.
					'short' => 'WPSSO RRSSB',	// Short plugin name.
					'name' => 'WPSSO Ridiculously Responsive Social Sharing Buttons',
					'desc' => 'WPSSO Core add-on to provide Ridiculously Responsive (SVG) Social Sharing Buttons in your content, excerpts, CSS sidebar, widget, shortcode, post edit pages, etc.',
					'slug' => 'wpsso-rrssb',
					'base' => 'wpsso-rrssb/wpsso-rrssb.php',
					'update_auth' => 'tid',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-rrssb/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-rrssb/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-rrssb/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-rrssb/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-rrssb/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-rrssb',
						'review' => 'https://wordpress.org/support/plugin/wpsso-rrssb/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-rrssb/master/readme.txt',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-rrssb/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-rrssb/',
						'faqs' => '',
						'notes' => 'https://wpsso.com/docs/plugins/wpsso-rrssb/notes/',
						'support' => 'http://wpsso-rrssb.support.wpsso.com/support/tickets/new',	// Pro support ticket.
						'purchase' => 'https://wpsso.com/extend/plugins/wpsso-rrssb/',	// Purchase page.
						'info' => 'https://wpsso.com/extend/plugins/wpsso-rrssb/info/',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-rrssb/update/',
						'latest' => '',
					),
				),
				'wpssossb' => array(			// Plugin acronym.
					'short' => 'WPSSO SSB',		// Short plugin name.
					'name' => 'WPSSO Social Sharing Buttons',
					'desc' => 'WPSSO Core add-on to provide Social Sharing Buttons with support for hashtags, short URLs, bbPress, BuddyPress, WooCommerce, and much more.',
					'slug' => 'wpsso-ssb',
					'base' => 'wpsso-ssb/wpsso-ssb.php',
					'update_auth' => 'tid',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-ssb/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-ssb/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-ssb/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-ssb/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-ssb/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-ssb',
						'review' => 'https://wordpress.org/support/plugin/wpsso-ssb/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-ssb/master/readme.txt',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-ssb/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-ssb/',
						'faqs' => 'https://wpsso.com/docs/plugins/wpsso-ssb/faqs/',
						'notes' => 'https://wpsso.com/docs/plugins/wpsso-ssb/notes/',
						'support' => 'http://wpsso-ssb.support.wpsso.com/support/tickets/new',	// Pro support ticket.
						'purchase' => 'https://wpsso.com/extend/plugins/wpsso-ssb/',	// Purchase page.
						'info' => 'https://wpsso.com/extend/plugins/wpsso-ssb/info/',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-ssb/update/',
						'latest' => '',
					),
				),
				'wpssossm' => array(			// Plugin acronym.
					'short' => 'WPSSO SSM',		// Short plugin name.
					'name' => 'WPSSO Strip Schema Microdata',
					'desc' => 'WPSSO Core add-on to remove outdated / incomplete Schema Microdata, leaving the Google recommended Schema JSON-LD markup untouched.',
					'slug' => 'wpsso-strip-schema-microdata',
					'base' => 'wpsso-strip-schema-microdata/wpsso-strip-schema-microdata.php',
					'update_auth' => '',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-strip-schema-microdata/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-strip-schema-microdata/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-strip-schema-microdata/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-strip-schema-microdata/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-strip-schema-microdata/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-strip-schema-microdata',
						'review' => 'https://wordpress.org/support/plugin/wpsso-strip-schema-microdata/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-strip-schema-microdata/master/readme.txt',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-strip-schema-microdata/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-strip-schema-microdata/',
						'faqs' => '',
						'notes' => '',
						'support' => '',	// Pro support ticket.
						'purchase' => '',	// Purchase page.
						'info' => '',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-strip-schema-microdata/update/',
						'latest' => '',
					),
				),
				'wpssotaq' => array(			// Plugin acronym.
					'short' => 'WPSSO TAQ',		// Short plugin name.
					'name' => 'WPSSO Tweet a Quote',
					'desc' => 'WPSSO Core add-on to provide Twitter-style quoted text for your content, with a convenient Tweet share link and customizable CSS.',
					'slug' => 'wpsso-tweet-a-quote',
					'base' => 'wpsso-tweet-a-quote/wpsso-tweet-a-quote.php',
					'update_auth' => '',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-tweet-a-quote/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-tweet-a-quote/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-tweet-a-quote/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-tweet-a-quote/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-tweet-a-quote/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-tweet-a-quote',
						'review' => 'https://wordpress.org/support/plugin/wpsso-tweet-a-quote/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-tweet-a-quote/master/readme.txt',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-tweet-a-quote/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-tweet-a-quote/',
						'faqs' => '',
						'notes' => '',
						'support' => '',	// Pro support ticket.
						'purchase' => '',	// Purchase page.
						'info' => '',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-tweet-a-quote/update/',
						'latest' => '',
					),
				),
				'wpssotie' => array(			// Plugin acronym.
					'short' => 'WPSSO TIE',		// Short plugin name.
					'name' => 'WPSSO Tune WP Image Editors',
					'desc' => 'WPSSO Core add-on to provide tuning options for the WordPress image editors and PHP image extensions.',
					'slug' => 'wpsso-tune-image-editors',
					'base' => 'wpsso-tune-image-editors/wpsso-tune-image-editors.php',
					'update_auth' => 'tid',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-tune-image-editors/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-tune-image-editors/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-tune-image-editors/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-tune-image-editors/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-tune-image-editors/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-tune-image-editors',
						'review' => 'https://wordpress.org/support/plugin/wpsso-tune-image-editors/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-tune-image-editors/master/readme.txt',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-tune-image-editors/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-tune-image-editors/',
						'faqs' => '',
						'notes' => '',
						'support' => 'http://wpsso-tune-image-editors.support.wpsso.com/support/tickets/new',	// Pro support ticket.
						'purchase' => 'https://wpsso.com/extend/plugins/wpsso-tune-image-editors/',	// Purchase page.
						'info' => 'https://wpsso.com/extend/plugins/wpsso-tune-image-editors/info/',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-tune-image-editors/update/',
						'latest' => '',
					),
				),
				'wpssoul' => array(			// Plugin acronym.
					'short' => 'WPSSO UL',		// Short plugin name.
					'name' => 'WPSSO User Locale Selector',
					'desc' => 'WPSSO Core add-on to provide a convenient locale / language / region selector in the WordPress admin toolbar.',
					'slug' => 'wpsso-user-locale',
					'base' => 'wpsso-user-locale/wpsso-user-locale.php',
					'update_auth' => '',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-user-locale/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-user-locale/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-user-locale/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-user-locale/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'home' => 'https://wordpress.org/plugins/wpsso-user-locale/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-user-locale',
						'review' => 'https://wordpress.org/support/plugin/wpsso-user-locale/reviews/?rate=5#new-post',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-user-locale/master/readme.txt',
						// WPSSO
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-user-locale/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-user-locale/',
						'faqs' => '',
						'notes' => 'https://wpsso.com/docs/plugins/wpsso-user-locale/notes/',
						'support' => '',	// Pro support ticket.
						'purchase' => '',	// Purchase page.
						'info' => '',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-user-locale/update/',
						'latest' => '',
					),
				),
				'wpssoum' => array(			// Plugin acronym.
					'short' => 'WPSSO UM',		// Short plugin name.
					'name' => 'WPSSO Update Manager',
					'desc' => 'WPSSO Core add-on to provide updates for the WPSSO Core Pro plugin and its Pro add-ons.',
					'slug' => 'wpsso-um',
					'base' => 'wpsso-um/wpsso-um.php',
					'update_auth' => '',
					'img' => array(
						'banners' => array(
							'low' => 'https://surniaulula.github.io/wpsso-um/assets/banner-772x250.jpg',
							'high' => 'https://surniaulula.github.io/wpsso-um/assets/banner-1544x500.jpg',
						),
						'icons' => array(
							'low' => 'https://surniaulula.github.io/wpsso-um/assets/icon-128x128.png',
							'high' => 'https://surniaulula.github.io/wpsso-um/assets/icon-256x256.png',
						),
					),
					'url' => array(
						// WordPress
						'forum' => '',
						'review' => '',
						// GitHub
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-um/master/readme.txt',
						// WPSSO
						'home' => 'https://wpsso.com/extend/plugins/wpsso-um/',
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-um/changelog/',
						'docs' => 'https://wpsso.com/docs/plugins/wpsso-um/',
						'faqs' => '',
						'notes' => '',
						'support' => '',	// Pro support ticket.
						'purchase' => '',	// Purchase page.
						'info' => '',	// License information.
						'update' => 'https://wpsso.com/extend/plugins/wpsso-um/update/',
						'latest' => 'https://wpsso.com/extend/plugins/wpsso-um/latest/',
					),
				),
			),
			'opt' => array(						// Plugin options.
				'defaults' => array(
					'options_version' => '',		// Example: -wpsso512pro-wpssoum3gpl
					'options_filtered' => false,
					'site_name' => '',			// (localized)
					'site_name_alt' => '',			// (localized)
					'site_desc' => '',			// (localized)
					'site_url' => '',
					'site_org_type' => 'organization',
					'site_place_id' => 'none',
					'schema_add_noscript' => 1,
					'schema_add_home_organization' => 1,
					'schema_add_home_person' => 0,
					'schema_add_home_website' => 1,
					'schema_home_person_id' => 'none',
					'schema_logo_url' => '',
					'schema_banner_url' => '',
					'schema_img_max' => 1,
					'schema_img_width' => 800,		// Must be at least 696px for Articles.
					'schema_img_height' => 1600,
					'schema_img_crop' => 0,
					'schema_img_crop_x' => 'center',
					'schema_img_crop_y' => 'center',
					'schema_desc_len' => 250,		// Meta itemprop="description" maximum text length (hard limit).
					'schema_author_name' => 'display_name',
					/**
					 * Standard WordPress types.
					 */
					'schema_type_for_tax_category' => 'item.list',
					'schema_type_for_tax_post_tag' => 'item.list',
					'schema_type_for_archive_page' => 'webpage.collection',	// Date-based archives.
					'schema_type_for_attachment' => 'webpage',
					'schema_type_for_home_index' => 'blog',
					'schema_type_for_home_page' => 'website',
					'schema_type_for_page' => 'webpage',
					'schema_type_for_post' => 'blog.posting',
					'schema_type_for_post_archive' => 'item.list',	// Post type archive page.
					'schema_type_for_search_page' => 'webpage.search.results',
					'schema_type_for_user_page' => 'webpage.profile',
					/**
					 * Custom post types.
					 */
					'schema_type_for_article' => 'article',
					'schema_type_for_book' => 'book',
					'schema_type_for_blog' => 'blog',
					'schema_type_for_business' => 'local.business',
					'schema_type_for_download' => 'product',	// For Easy Digital Downloads.
					'schema_type_for_event' => 'event',
					'schema_type_for_howto' => 'howto',
					'schema_type_for_job_listing' => 'job.posting',	// For WP Job Manager
					'schema_type_for_jobpost' => 'job.posting',	// For Simple Job Board
					'schema_type_for_organization' => 'organization',
					'schema_type_for_other' => 'other',
					'schema_type_for_person' => 'person',
					'schema_type_for_place' => 'place',
					'schema_type_for_product' => 'product',
					'schema_type_for_recipe' => 'recipe',
					'schema_type_for_review' => 'review',
					'schema_type_for_tribe_events' => 'event',
					'schema_type_for_webpage' => 'webpage',
					'schema_type_for_website' => 'website',
					'schema_review_item_type' => 'none',		// Default Reviewed Item Type
					'fb_publisher_url' => '',			// (localized)
					'fb_app_id' => '',
					'fb_admins' => '',
					'fb_author_name' => 'display_name',
					'fb_locale' => 'en_US',
					'instgram_publisher_url' => '',			// (localized)
					'linkedin_publisher_url' => '',			// (localized)
					'myspace_publisher_url' => '',			// (localized)
					'og_post_type' => 'article',
					'og_art_section' => 'none',
					'og_img_width' => 600,
					'og_img_height' => 315,
					'og_img_crop' => 1,
					'og_img_crop_x' => 'center',
					'og_img_crop_y' => 'center',
					'og_img_max' => 1,
					'og_vid_max' => 1,
					'og_vid_https' => 1,
					'og_vid_autoplay' => 1,
					'og_vid_prev_img' => 0,
					'og_vid_html_type' => 1,
					'og_def_img_id' => '',
					'og_def_img_id_pre' => 'wp',
					'og_def_img_url' => '',
					'og_def_img_on_index' => 1,
					'og_def_img_on_search' => 0,
					'og_ngg_tags' => 0,
					'og_page_parent_tags' => 0,
					'og_page_title_tag' => 0,
					'og_author_field' => 'facebook',
					'og_author_fallback' => 0,
					'og_title_sep' => '-',
					'og_title_len' => 70,
					'og_title_warn' => 40,
					'og_desc_len' => 300,				// Maximum length in characters (hard limit).
					'og_desc_warn' => 200,				// Recommended maximum length in characters for Facebook (soft limit).
					'og_desc_hashtags' => 0,
					'p_publisher_url' => '',			// (localized)
					'p_author_name' => 'display_name',		// Rich-pin specific article:author.
					'p_dom_verify' => '',
					'p_add_img_html' => 1,
					'p_add_nopin_media_img_tag' => 1,
					'p_add_nopin_header_img_tag' => 1,
					'sc_publisher_url' => '',
					'seo_desc_len' => 156,				// Meta name="description" maximum text length (hard limit).
					'seo_author_field' => 'gplus',
					'seo_publisher_url' => '',			// (localized)
					'tumblr_publisher_url' => '',
					'yt_publisher_url' => '',
					/**
					 * Twitter Card options.
					 */
					'tc_site' => '',				// Twitter Business @username (localized).
					'tc_desc_len' => 200,				// Maximum Description Length (hard limit).
					'tc_type_post' => 'summary_large_image',
					'tc_type_default' => 'summary',
					'tc_sum_img_width' => 600,			// Summary Card Image Dimensions.
					'tc_sum_img_height' => 315,
					'tc_sum_img_crop' => 1,
					'tc_sum_img_crop_x' => 'center',
					'tc_sum_img_crop_y' => 'center',
					'tc_lrg_img_width' => 600,			// Large Image Card Img Dimensions.
					'tc_lrg_img_height' => 1600,
					'tc_lrg_img_crop' => 0,
					'tc_lrg_img_crop_x' => 'center',
					'tc_lrg_img_crop_y' => 'center',
					/**
					 * Enable / disable individual head HTML tags.
					 */
					'add_link_rel_author' => 1,
					'add_link_rel_canonical' => 0,
					'add_link_rel_publisher' => 1,
					'add_link_rel_shortlink' => 1,
					'add_meta_property_fb:admins' => 1,		// Facebook.
					'add_meta_property_fb:app_id' => 1,
					'add_meta_property_al:ios:app_name' => 1,	// Facebook applink.
					'add_meta_property_al:ios:app_store_id' => 1,
					'add_meta_property_al:ios:url' => 1,
					'add_meta_property_al:android:app_name' => 1,
					'add_meta_property_al:android:package' => 1,
					'add_meta_property_al:android:url' => 1,
					'add_meta_property_al:web:url' => 1,
					'add_meta_property_al:web:should_fallback' => 1,
					'add_meta_property_og:altitude' => 1,		// Open Graph.
					'add_meta_property_og:description' => 1,
					'add_meta_property_og:image:secure_url' => 1,
					'add_meta_property_og:image' => 1,
					'add_meta_property_og:image:width' => 1,
					'add_meta_property_og:image:height' => 1,
					'add_meta_property_og:latitude' => 1,
					'add_meta_property_og:locale' => 1,
					'add_meta_property_og:longitude' => 1,
					'add_meta_property_og:site_name' => 1,
					'add_meta_property_og:title' => 1,
					'add_meta_property_og:type' => 1,
					'add_meta_property_og:updated_time' => 1,
					'add_meta_property_og:url' => 1,
					'add_meta_property_og:video:secure_url' => 1,
					'add_meta_property_og:video:url' => 1,
					'add_meta_property_og:video:type' => 1,
					'add_meta_property_og:video:width' => 1,
					'add_meta_property_og:video:height' => 1,
					'add_meta_property_og:video:tag' => 0,
					'add_meta_property_article:author' => 1,	// Open Graph (article).
					'add_meta_property_article:publisher' => 1,
					'add_meta_property_article:published_time' => 1,
					'add_meta_property_article:modified_time' => 1,
					'add_meta_property_article:expiration_time' => 1,
					'add_meta_property_article:section' => 1,
					'add_meta_property_article:tag' => 1,
					'add_meta_property_book:author' => 1,		// Open graph (book).
					'add_meta_property_book:isbn' => 1,
					'add_meta_property_book:release_date' => 1,
					'add_meta_property_book:tag' => 1,
					'add_meta_property_music:album' => 1,		// Open graph (music).
					'add_meta_property_music:album:disc' => 1,
					'add_meta_property_music:album:track' => 1,
					'add_meta_property_music:creator' => 1,
					'add_meta_property_music:duration' => 1,
					'add_meta_property_music:musician' => 1,
					'add_meta_property_music:release_date' => 1,
					'add_meta_property_music:song' => 1,
					'add_meta_property_music:song:disc' => 1,
					'add_meta_property_music:song:track' => 1,
					'add_meta_property_place:location:altitude' => 1,	// Open graph (place)
					'add_meta_property_place:location:latitude' => 1,
					'add_meta_property_place:location:longitude' => 1,
					'add_meta_property_place:street_address' => 1,
					'add_meta_property_place:locality' => 1,
					'add_meta_property_place:region' => 1,
					'add_meta_property_place:postal_code' => 1,
					'add_meta_property_place:country_name' => 1,
					'add_meta_property_product:availability' => 1,	// Open graph (product).
					'add_meta_property_product:brand' => 1,
					'add_meta_property_product:category' => 1,
					'add_meta_property_product:color' => 1,
					'add_meta_property_product:condition' => 1,
					'add_meta_property_product:material' => 0,
					'add_meta_property_product:price:amount' => 1,
					'add_meta_property_product:price:currency' => 1,
					'add_meta_property_product:size' => 1,
					'add_meta_property_product:weight:value' => 1,
					'add_meta_property_product:weight:units' => 1,
					'add_meta_property_profile:first_name' => 1,	// Open graph (profile).
					'add_meta_property_profile:last_name' => 1,
					'add_meta_property_profile:username' => 1,
					'add_meta_property_profile:gender' => 1,
					'add_meta_property_video:actor' => 1,		// Open graph (video).
					'add_meta_property_video:actor:role' => 1,
					'add_meta_property_video:director' => 1,
					'add_meta_property_video:writer' => 1,
					'add_meta_property_video:duration' => 1,
					'add_meta_property_video:release_date' => 1,
					'add_meta_property_video:tag' => 1,
					'add_meta_property_video:series' => 1,
					'add_meta_name_author' => 1,			// SEO.
					'add_meta_name_description' => 1,
					'add_meta_name_generator' => 1,
					'add_meta_name_robots' => 1,
					'add_meta_name_p:domain_verify' => 1,		// Pinterest.
					'add_meta_name_weibo:article:create_at' => 1,	// Weibo.
					'add_meta_name_weibo:article:update_at' => 1,
					'add_meta_name_twitter:card' => 1,		// Twitter cards.
					'add_meta_name_twitter:creator' => 1,
					'add_meta_name_twitter:domain' => 1,
					'add_meta_name_twitter:site' => 1,
					'add_meta_name_twitter:title' => 1,
					'add_meta_name_twitter:description' => 1,
					'add_meta_name_twitter:image' => 1,
					'add_meta_name_twitter:image:width' => 1,
					'add_meta_name_twitter:image:height' => 1,
					'add_meta_name_twitter:player' => 1,
					'add_meta_name_twitter:player:stream' => 1,
					'add_meta_name_twitter:player:stream:content_type' => 1,
					'add_meta_name_twitter:player:width' => 1,
					'add_meta_name_twitter:player:height' => 1,
					'add_meta_name_twitter:app:name:iphone' => 1,
					'add_meta_name_twitter:app:id:iphone' => 1,
					'add_meta_name_twitter:app:url:iphone' => 1,
					'add_meta_name_twitter:app:name:ipad' => 1,
					'add_meta_name_twitter:app:id:ipad' => 1,
					'add_meta_name_twitter:app:url:ipad' => 1,
					'add_meta_name_twitter:app:name:googleplay' => 1,
					'add_meta_name_twitter:app:id:googleplay' => 1,
					'add_meta_name_twitter:app:url:googleplay' => 1,
					'add_link_itemprop_url' => 1,			// Schema link.
					'add_link_itemprop_image' => 1,
					'add_link_itemprop_image.url' => 1,
					'add_link_itemprop_author.url' => 1,
					'add_link_itemprop_author.image' => 1,
					'add_link_itemprop_contributor.url' => 1,
					'add_link_itemprop_contributor.image' => 1,
					'add_link_itemprop_menu' => 1,
					'add_meta_itemprop_name' => 1,			// Schema meta.
					'add_meta_itemprop_alternatename' => 1,
					'add_meta_itemprop_description' => 1,
					'add_meta_itemprop_email' => 1,
					'add_meta_itemprop_telephone' => 1,
					'add_meta_itemprop_address' => 1,
					'add_meta_itemprop_datepublished' => 1,
					'add_meta_itemprop_datemodified' => 1,
					'add_meta_itemprop_image.width' => 1,
					'add_meta_itemprop_image.height' => 1,
					'add_meta_itemprop_publisher.name' => 1,
					'add_meta_itemprop_author.name' => 1,
					'add_meta_itemprop_author.description' => 1,
					'add_meta_itemprop_contributor.name' => 1,
					'add_meta_itemprop_contributor.description' => 1,
					'add_meta_itemprop_openinghoursspecification.dayofweek' => 1,
					'add_meta_itemprop_openinghoursspecification.opens' => 1,
					'add_meta_itemprop_openinghoursspecification.closes' => 1,
					'add_meta_itemprop_openinghoursspecification.validfrom' => 1,
					'add_meta_itemprop_openinghoursspecification.validthrough' => 1,
					'add_meta_itemprop_currenciesaccepted' => 1,
					'add_meta_itemprop_paymentaccepted' => 1,
					'add_meta_itemprop_pricerange' => 1,
					'add_meta_itemprop_acceptsreservations' => 1,
					'add_meta_itemprop_aggregaterating.ratingvalue' => 1,
					'add_meta_itemprop_aggregaterating.ratingcount' => 1,
					'add_meta_itemprop_aggregaterating.worstrating' => 1,
					'add_meta_itemprop_aggregaterating.bestrating' => 1,
					'add_meta_itemprop_aggregaterating.reviewcount' => 1,
					'add_meta_itemprop_startdate' => 1,		// Schema Event.
					'add_meta_itemprop_enddate' => 1,		// Schema Event.
					'add_meta_itemprop_location' => 1,		// Schema Event.
					'add_meta_itemprop_preptime' => 1,		// Schema Recipe.
					'add_meta_itemprop_cooktime' => 1,		// Schema Recipe.
					'add_meta_itemprop_totaltime' => 1,		// Schema Recipe.
					'add_meta_itemprop_recipeyield' => 1,		// Schema Recipe.
					'add_meta_itemprop_recipeingredient' => 1,	// Schema Recipe (supersedes ingredients).
					'add_meta_itemprop_recipeinstructions' => 1,	// Schema Recipe.
					/**
					 * Advanced settings - Plugin Settings tab.
					 */
					'plugin_preserve' => 0,				// Preserve Settings on Uninstall.
					'plugin_debug' => 0,				// Add Hidden Debug Messages.
					'plugin_hide_pro' => 0,				// Hide All Pro Version Options.
					'plugin_show_opts' => 'basic',			// Options to Show by Default.
					'plugin_shortcodes' => 1,			// Enable Plugin Shortcode(s).
					'plugin_widgets' => 1,				// Enable Plugin Widget(s).
					/**
					 * Advanced settings - Content and Filters tab.
					 */
					'plugin_filter_title' => 0,			// Use Filtered (SEO) Title.
					'plugin_filter_content' => 0,			// Apply WordPress Content Filters.
					'plugin_filter_excerpt' => 0,			// Apply WordPress Excerpt Filters.
					'plugin_p_strip' => 0,				// Content Starts at 1st Paragraph.
					'plugin_use_img_alt' => 1,			// Use Image Alt if No Content.
					'plugin_img_alt_prefix' => 'Image:',		// Image Alt Text Prefix.
					'plugin_p_cap_prefix' => 'Caption:',		// WP Caption Prefix.
					'plugin_gravatar_api' => 0,			// Include Author Gravatar Image.
					'plugin_facebook_api' => 1,			// Check for Embedded Media: Facebook Videos.
					'plugin_slideshare_api' => 1,			// Check for Embedded Media: Slideshare Presentations.
					'plugin_soundcloud_api' => 1,			// Check for Embedded Media: Soundcloud Tracks.
					'plugin_vimeo_api' => 1,			// Check for Embedded Media: Vimeo Videos.
					'plugin_wistia_api' => 1,			// Check for Embedded Media: Wistia Videos.
					'plugin_wpvideo_api' => 1,			// Check for Embedded Media: WordPress Video Shortcode.
					'plugin_youtube_api' => 1,			// Check for Embedded Media: Youtube Videos and Playlists.
					/**
					 * Advanced settings - Integration tab.
					 */
					'plugin_honor_force_ssl' => 1,			// Honor the FORCE_SSL Constant.
					'plugin_html_attr_filter_name' => 'language_attributes',
					'plugin_html_attr_filter_prio' => 100,
					'plugin_head_attr_filter_name' => 'head_attributes',
					'plugin_head_attr_filter_prio' => 100,
					'plugin_check_head' => 1,			// Check for Duplicate Meta Tags.
					'plugin_filter_lang' => 1,			// Use WP Locale for Language.
					'plugin_create_wp_sizes' => 1,			// Create Missing WP Media Sizes.
					'plugin_check_img_dims' => 0,			// Enforce Image Dimensions Check.
					'plugin_upscale_images' => 0,			// Allow Upscale of Smaller Images.
					'plugin_upscale_img_max' => 33,			// Maximum Image Upscale Percent.
					'plugin_page_excerpt' => 1,			// Enable WP Excerpt for Pages.
					'plugin_page_tags' => 1,			// Enable WP Tags for Pages.
					'plugin_add_person_role' => 1,			// Add Person Role for New Users.
					/**
					 * Advanced settings - Custom Meta tab.
					 */
					'plugin_schema_type_col_media' => 0,
					'plugin_schema_type_col_post' => 1,
					'plugin_schema_type_col_term' => 0,
					'plugin_schema_type_col_user' => 0,
					'plugin_og_img_col_media' => 0,
					'plugin_og_img_col_post' => 1,
					'plugin_og_img_col_term' => 1,
					'plugin_og_img_col_user' => 1,
					'plugin_og_desc_col_media' => 0,
					'plugin_og_desc_col_post' => 0,
					'plugin_og_desc_col_term' => 0,
					'plugin_og_desc_col_user' => 1,
					'plugin_add_to_attachment' => 1,
					'plugin_add_to_page' => 1,
					'plugin_add_to_post' => 1,
					'plugin_add_to_product' => 1,
					'plugin_add_to_reply' => 0,			// Bbpress
					'plugin_add_to_term' => 1,
					'plugin_add_to_topic' => 0,			// Bbpress
					'plugin_add_to_user' => 1,
					'plugin_wpseo_social_meta' => 0,		// Read Yoast SEO Social Meta.
					'plugin_def_currency' => 'USD',			// Default Currency.
					'plugin_cf_img_url' => '_format_image_url',	// Image URL Custom Field.
					'plugin_cf_vid_url' => '_format_video_url',	// Video URL Custom Field.
					'plugin_cf_vid_embed' => '',			// Video Embed HTML Custom Field.
					'plugin_cf_addl_type_urls' => '',		// Microdata Type URLs Custom Field.
					'plugin_cf_recipe_ingredients' => '',		// Recipe Ingredients Custom Field.
					'plugin_cf_recipe_instructions' => '',		// Recipe Instructions Custom Field.
					'plugin_cf_product_avail' => '',		// Product Availability Custom Field.
					'plugin_cf_product_brand' => '',		// Product Brand Custom Field.
					'plugin_cf_product_color' => '',		// Product Color Custom Field.
					'plugin_cf_product_condition' => '',		// Product Condition Custom Field.
					'plugin_cf_product_currency' => '',		// Product Currency Custom Field.
					'plugin_cf_product_material' => '',		// Product Material Custom Field.
					'plugin_cf_product_price' => '',		// Product Price Custom Field.
					'plugin_cf_product_size' => '',			// Product Size Custom Field.
					'plugin_cf_sameas_urls' => '',			// Same-As URLs Custom Field.
					/**
					 * Advanced settings - Cache Settings tab.
					 */
					'plugin_head_cache_exp' => WEEK_IN_SECONDS,	// Head Markup Array Cache Expiry (1 week).
					'plugin_content_cache_exp' => HOUR_IN_SECONDS,	// Filtered Content Text Cache Expiry (1 hour).
					'plugin_json_data_cache_exp' => 1209600,	// Schema JSON Data Cache Expiry (2 weeks).
					'plugin_imgsize_cache_exp' => DAY_IN_SECONDS,	// Get Image URL Info Cache Expiry (1 day).
					'plugin_short_url_cache_exp' => 7776000,	// Get Shortened URL Cache Expiry (90 days / 3 months).
					'plugin_topics_cache_exp' => MONTH_IN_SECONDS,	// Article Topics Array Cache Expiry (1 month).
					'plugin_types_cache_exp' => MONTH_IN_SECONDS,	// Schema Types Array Cache Expiry (1 month).
					'plugin_show_purge_count' => 0,			// Show Cache Purge Count on Update.
					'plugin_clear_on_save' => 1,			// Clear All Caches on Save Settings.
					'plugin_clear_all_refresh' => 1,		// Auto-Refresh Cache After Clear All.
					'plugin_clear_short_urls' => 0,			// Clear Short URLs on Clear All Caches.
					'plugin_clear_for_comment' => 1,		// Clear Post Cache for New Comment.
					/**
					 * Advanced settings - Service APIs tab.
					 */
					'plugin_shortener' => 'none',			// Preferred URL Shortening Service.
					'plugin_wp_shortlink' => 1,			// Use Shortnened URL for WP Shortlink.
					'plugin_min_shorten' => 23,
					'plugin_bitly_login' => '',
					'plugin_bitly_access_token' => '',
					'plugin_bitly_api_key' => '',
					'plugin_bitly_domain' => '',
					'plugin_dlmyapp_api_key' => '',
					'plugin_google_api_key' => '',			// Google Project API Key.
					'plugin_google_shorten' => 0,			// URL Shortener API is Enabled.
					'plugin_owly_api_key' => '',
					'plugin_yourls_api_url' => '',
					'plugin_yourls_username' => '',
					'plugin_yourls_password' => '',
					'plugin_yourls_token' => '',
					/**
					 * Advanced settings - Contact Field Names and Labels.
					 */
					'plugin_cm_fb_name' => 'facebook',
					'plugin_cm_fb_label' => 'Facebook User URL',
					'plugin_cm_fb_enabled' => 1,
					'plugin_cm_gp_name' => 'gplus',
					'plugin_cm_gp_label' => 'Google+ URL',
					'plugin_cm_gp_enabled' => 1,
					'plugin_cm_instgram_name' => 'instagram',
					'plugin_cm_instgram_label' => 'Instagram URL',
					'plugin_cm_instgram_enabled' => 1,
					'plugin_cm_linkedin_name' => 'linkedin',
					'plugin_cm_linkedin_label' => 'LinkedIn URL',
					'plugin_cm_linkedin_enabled' => 1,
					'plugin_cm_myspace_name' => 'myspace',
					'plugin_cm_myspace_label' => 'Myspace URL',
					'plugin_cm_myspace_enabled' => 1,
					'plugin_cm_pin_name' => 'pinterest',
					'plugin_cm_pin_label' => 'Pinterest URL',
					'plugin_cm_pin_enabled' => 1,
					'plugin_cm_sc_name' => 'soundcloud',
					'plugin_cm_sc_label' => 'Soundcloud URL',
					'plugin_cm_sc_enabled' => 1,
					'plugin_cm_tumblr_name' => 'tumblr',
					'plugin_cm_tumblr_label' => 'Tumblr URL',
					'plugin_cm_tumblr_enabled' => 1,
					'plugin_cm_twitter_name' => 'twitter',
					'plugin_cm_twitter_label' => 'Twitter @username',
					'plugin_cm_twitter_enabled' => 1,
					'plugin_cm_yt_name' => 'youtube',
					'plugin_cm_yt_label' => 'YouTube Channel URL',
					'plugin_cm_yt_enabled' => 1,
					'plugin_cm_skype_name' => 'skype',
					'plugin_cm_skype_label' => 'Skype Username',
					'plugin_cm_skype_enabled' => 1,
					'wp_cm_aim_name' => 'aim',
					'wp_cm_aim_label' => 'AIM',
					'wp_cm_aim_enabled' => 1,
					'wp_cm_jabber_name' => 'jabber',
					'wp_cm_jabber_label' => 'Google Talk',
					'wp_cm_jabber_enabled' => 1,
					'wp_cm_yim_name' => 'yim',
					'wp_cm_yim_label' => 'Yahoo Messenger',
					'wp_cm_yim_enabled' => 1,
				),
				/**
				 * Multisite options.
				 */
				'site_defaults' => array(
					'options_version' => '',			// Example: -wpsso512pro-wpssoum3gpl
					'options_filtered' => false,
					/**
					 * Advanced settings - Plugin Settings tab.
					 */
					'plugin_preserve' => 0,				// Preserve Settings on Uninstall
					'plugin_preserve:use' => 'default',
					'plugin_debug' => 0,				// Add Hidden Debug Messages
					'plugin_debug:use' => 'default',
					'plugin_hide_pro' => 0,				// Hide All Pro Version Options
					'plugin_hide_pro:use' => 'default',
					'plugin_show_opts' => 'basic',			// Options to Show by Default
					'plugin_show_opts:use' => 'default',
					/**
					 * Advanced settings - Integration tab.
					 */
					'plugin_honor_force_ssl' => 1,			// Honor the FORCE_SSL Constant
					'plugin_honor_force_ssl:use' => 'default',
					'plugin_html_attr_filter_name' => 'language_attributes',
					'plugin_html_attr_filter_name:use' => 'default',
					'plugin_html_attr_filter_prio' => 100,
					'plugin_html_attr_filter_prio:use' => 'default',
					'plugin_head_attr_filter_name' => 'head_attributes',
					'plugin_head_attr_filter_name:use' => 'default',
					'plugin_head_attr_filter_prio' => 100,
					'plugin_head_attr_filter_prio:use' => 'default',
					'plugin_check_head' => 1,			// Check for Duplicate Meta Tags
					'plugin_check_head:use' => 'default',
					'plugin_filter_lang' => 1,			// Use WP Locale for Language
					'plugin_filter_lang:use' => 'default',
					'plugin_create_wp_sizes' => 1,			// Recreate Missing WP Media Sizes
					'plugin_create_wp_sizes:use' => 'default',
					'plugin_check_img_dims' => 0,			// Enforce Image Dimensions Check
					'plugin_check_img_dims:use' => 'default',
					'plugin_upscale_images' => 0,			// Allow Upscale of Smaller Images
					'plugin_upscale_images:use' => 'default',
					'plugin_upscale_img_max' => 33,			// Maximum Image Upscale Percent
					'plugin_upscale_img_max:use' => 'default',
					'plugin_shortcodes' => 1,			// Enable Plugin Shortcode(s)
					'plugin_shortcodes:use' => 'default',
					'plugin_widgets' => 1,				// Enable Plugin Widget(s)
					'plugin_widgets:use' => 'default',
					'plugin_page_excerpt' => 1,			// Enable WP Excerpt for Pages
					'plugin_page_excerpt:use' => 'default',
					'plugin_page_tags' => 1,			// Enable WP Tags for Pages
					'plugin_page_tags:use' => 'default',
					'plugin_add_person_role' => 1,			// Add Person Role for New Users
					'plugin_add_person_role:use' => 'default',
					/**
					 * Advanced settings - Cache Settings tab.
					 */
					'plugin_head_cache_exp' => WEEK_IN_SECONDS,	// Head Markup Array Cache Expiry (1 week)
					'plugin_head_cache_exp:use' => 'default',
					'plugin_content_cache_exp' => HOUR_IN_SECONDS,	// Filtered Content Text Cache Expiry (1 hour)
					'plugin_content_cache_exp:use' => 'default',
					'plugin_json_data_cache_exp' => 1209600,	// Schema JSON Data Cache Expiry (2 weeks)
					'plugin_json_data_cache_exp:use' => 'default',
					'plugin_imgsize_cache_exp' => DAY_IN_SECONDS,	// Get Image URL Info Cache Expiry (1 day)
					'plugin_imgsize_cache_exp:use' => 'default',
					'plugin_short_url_cache_exp' => 7776000,	// Get Shortened URL Cache Expiry (90 days / 3 months)
					'plugin_short_url_cache_exp:use' => 'default',
					'plugin_topics_cache_exp' => MONTH_IN_SECONDS,	// Article Topics Array Cache Expiry (1 month)
					'plugin_topics_cache_exp:use' => 'default',
					'plugin_types_cache_exp' => MONTH_IN_SECONDS,	// Schema Types Array Cache Expiry (1 month)
					'plugin_types_cache_exp:use' => 'default',
					'plugin_show_purge_count' => 0,			// Show Cache Purge Count on Update
					'plugin_show_purge_count:use' => 'default',
					'plugin_clear_on_save' => 1,			// Clear All Caches on Save Settings
					'plugin_clear_on_save:use' => 'default',
					'plugin_clear_all_refresh' => 1,		// Auto-Refresh Cache After Clear All
					'plugin_clear_all_refresh:use' => 'default',
					'plugin_clear_short_urls' => 0,			// Clear Short URLs on Clear All Caches
					'plugin_clear_short_urls:use' => 'default',
					'plugin_clear_for_comment' => 1,		// Clear Post Cache for New Comment
					'plugin_clear_for_comment:use' => 'default',
				),
				/**
				 * Contact method options prefix.
				 */
				'cm_prefix' => array(
					'email' => 'email',
					'facebook' => 'fb',
					'gplus' => 'gp',
					'twitter' => 'twitter',
					'instagram' => 'instgram',
					'linkedin' => 'linkedin',
					'myspace' => 'myspace',
					'pinterest' => 'pin',
					'pocket' => 'pocket',
					'buffer' => 'buffer',
					'reddit' => 'reddit',
					'managewp' => 'managewp',
					'soundcloud' => 'sc',
					'stumbleupon' => 'stumble',
					'tumblr' => 'tumblr',
					'youtube' => 'yt',
					'skype' => 'skype',
					'vk' => 'vk',
					'whatsapp' => 'wa',
				),
				/**
				 * Custom field to meta data index.
				 */
				'cf_md_idx' => array(
					'plugin_cf_img_url'             => 'og_img_url',
					'plugin_cf_vid_url'             => 'og_vid_url',
					'plugin_cf_vid_embed'           => 'og_vid_embed',
					'plugin_cf_addl_type_urls'      => 'schema_addl_type_url',
					'plugin_cf_recipe_ingredients'  => 'schema_recipe_ingredient',
					'plugin_cf_recipe_instructions' => 'schema_recipe_instruction',
					'plugin_cf_product_avail'       => 'product_avail',
					'plugin_cf_product_brand'       => 'product_brand',
					'plugin_cf_product_color'       => 'product_color',
					'plugin_cf_product_condition'   => 'product_condition',
					'plugin_cf_product_currency'    => 'product_currency',
					'plugin_cf_product_material'    => 'product_material',
					'plugin_cf_product_price'       => 'product_price',
					'plugin_cf_product_size'        => 'product_size',
					'plugin_cf_sameas_urls'         => 'schema_sameas_url',
				),
				/**
				 * Read meta values into numeric meta data index.
				 */
				'cf_md_multi' => array(
					'schema_addl_type_url'      => true,
					'schema_recipe_ingredient'  => true,
					'schema_recipe_instruction' => true,
					'schema_sameas_url'         => true,
				),
			),
			/**
			 * Update manager config.
			 */
			'um' => array(
				'rec_version' => '1.10.1',	// Minimum update manager version (soft limit).
				'check_hours' => array(
					24  => 'Every day',
					48  => 'Every two days',
					72  => 'Every three days',
					96  => 'Every four days',
					120 => 'Every five days',
					144 => 'Every six days',
					168 => 'Every week',
				),
				'version_filter' => array(
					'dev'    => 'Development and Up',
					'alpha'  => 'Alpha and Up',
					'beta'   => 'Beta and Up',
					'rc'     => 'Release Candidate and Up',
					'stable' => 'Stable / Production',
				),
				'version_regex' => array(
					'dev'    => '/^[0-9][0-9\.\-]+((dev|a|alpha|b|beta|rc)[0-9\.\-]+)?$/',
					'alpha'  => '/^[0-9][0-9\.\-]+((a|alpha|b|beta|rc)[0-9\.\-]+)?$/',
					'beta'   => '/^[0-9][0-9\.\-]+((b|beta|rc)[0-9\.\-]+)?$/',
					'rc'     => '/^[0-9][0-9\.\-]+((rc)[0-9\.\-]+)?$/',
					'stable' => '/^[0-9][0-9\.\-]+$/',
				),
			),
			/**
			 * PHP config.
			 */
			'php' => array(
				'label' => 'PHP',
				'min_version' => '5.4',		// Hard limit - deactivate the plugin when activating.
				'rec_version' => '7.0',		// Soft limit - issue warning if lower version found.
				'version_url' => 'https://secure.php.net/supported-versions.php',
				'extensions' => array(
					'curl' => array(	// PHP extension name.
						'label' => 'Client URL Library (cURL)',
						'url' => 'https://secure.php.net/manual/en/book.curl.php',
					),
					'gd' => array(		// PHP extension name.
						'label' => 'Image Processing (GD)',
						'url'   => 'https://secure.php.net/manual/en/book.image.php',
						'wp_image_editor' => array(
							'class' => 'WP_Image_Editor_GD',
							'url'   => 'https://developer.wordpress.org/reference/classes/wp_image_editor_gd/',
						),
					),
					'json' => array(	// PHP extension name.
						'label' => 'JavaScript Object Notation (JSON)',
						'url' => 'https://secure.php.net/manual/en/book.json.php',
					),
					'mbstring' => array(	// PHP extension name.
						'label' => 'Multibyte String',
						'url' => 'https://secure.php.net/manual/en/book.mbstring.php',
						'functions' => array(	// Extra checks to make sure the PHP extension is complete.
							'mb_strlen',
							'mb_substr',
							'mb_convert_encoding',
						),
					),
					'simplexml' => array(	// PHP extension name.
						'label' => 'SimpleXML',
						'url' => 'https://secure.php.net/manual/en/book.simplexml.php',
					),
				),
			),
			/**
			 * WordPress config.
			 */
			'wp' => array(
				'label' => 'WordPress',
				'min_version' => '3.8',		// Hard limit - deactivate the plugin when activating.
				'rec_version' => '4.9.6',	// Soft limit - issue warning if lower version found.
				'version_url' => 'https://codex.wordpress.org/Supported_Versions?nocache=1',
				'tb_iframe' => array(		// Thickbox iframe.
					'width' => 772,		// Url query argument.
					'height' => 550,	// Url query argument.
				),
				'cm_names' => array(
					'aim' => 'AIM',
					'jabber' => 'Google Talk',
					'yim' => 'Yahoo Messenger',
				),
				'admin' => array(
					'users' => array(
						'page' => 'users.php',
						'cap' => 'list_users',
					),
					'profile' => array(
						'page' => 'profile.php',
						'cap' => 'edit_posts',
					),
					'setting' => array(
						'page' => 'options-general.php',
						'cap' => 'manage_options',
					),
					'submenu' => array(
						'page' => 'admin.php',
						'cap' => 'manage_options',
					),
					'sitesubmenu' => array(
						'page' => 'admin.php',
						'cap' => 'manage_options',
					),
				),
				/**
				 * Transient id prefix.
				 */
				'transient' => array(
					'wpsso_a_' => array(
						'label' => 'Article Topics',
						'opt_key' => 'plugin_topics_cache_exp',
						'filter' => 'wpsso_cache_expire_article_topics',
					),
					'wpsso_b_' => array(	// Sharing buttons html cache.
					),
					'wpsso_h_' => array(
						'label' => 'Head Markup',
						'opt_key' => 'plugin_head_cache_exp',
						'filter' => 'wpsso_cache_expire_head_array',
					),
					'wpsso_i_' => array(
						'label' => 'Image URL Info',
						'opt_key' => 'plugin_imgsize_cache_exp',
						'filter' => 'wpsso_cache_expire_image_url_size',
					),
					'wpsso_j_' => array(
						'label' => 'Schema Data',
						'opt_key' => 'plugin_json_data_cache_exp',
						'filter' => 'wpsso_cache_expire_schema_json_data',
					),
					'wpsso_t_' => array(
						'label' => 'Schema Types',
						'opt_key' => 'plugin_types_cache_exp',
						'filter' => 'wpsso_cache_expire_schema_types',
					),
					'wpsso_s_' => array(
						'label' => 'Shortened URLs',
						'opt_key' => 'plugin_short_url_cache_exp',
						'filter' => 'wpsso_cache_expire_short_url',
					),
					'wpsso_' => array(
						'label' => 'All Transients',
					),
				),
				'wp_cache' => array(
					'wpsso_c_' => array(	// Filtered post content cache.
						'label' => 'Filtered Content',
						'opt_key' => 'plugin_content_cache_exp',
						'filter' => 'wpsso_cache_expire_content_text',
					),
					'wpsso_' => array(
						'label' => 'All WP Objects',
					),
				),
			),
			'jquery-qtip' => array(			// http://qtip2.com/download
				'label' => 'jQuery qTip',
				'version' => '3.0.3',
			),
			'jquery-ui' => array(			// https://developers.google.com/speed/libraries/
				'label' => 'jQuery UI',
				'version' => '1.12.1',
			),
			'menu' => array(
				'title' => 'SSO',		// menu title
				'before' => '\0229b',		// circle asterix
				'icon_html' => '&oast;',	// circle asterix
				'dashicons' => array(
					'licenses' => 'editor-justify',
					'sitelicenses' => 'editor-justify',
					'dashboard' => 'dashboard',
					'sitedashboard' => 'dashboard',
					'setup' => 'info',
					'sitesetup' => 'info',
				),
			),
			'nag_colors' => array(
				'update-nag' => array(
					'border' => '1px solid #33cc33',
					'background-color' => '#e0f7e0',
					'color' => '#144e14',
				),
				'update-nag a' => array(
					'color' => '#1f7e1f',
				),
				'update-nag a:active' => array(
					'color' => '#279d27',
				),
				'update-nag a:hover' => array(
					'color' => '#279d27',
				),
			),
			'meta' => array(			// post, term, user add_meta_box() settings
				'id' => 'sso',
				'title' => 'Document SSO (Social and Search Optimization)',
			),
			'edit' => array(			// post, term, user lists
				'columns' => array(
					'schema_type' => array(
						'header' => 'SSO Schema',
						'meta_key' => '_wpsso_head_info_schema_type',
						'orderby' => 'meta_value',
						'width' => '130px',	// 120 + 10 for the sorting arrow
						'height' => 'auto',
					),
					'og_img' => array(
						'header' => 'SSO Image',
						'meta_key' => '_wpsso_head_info_og_img_thumb',
						'orderby' => false,	// do not offer column sorting
						'width' => '75px',
						'height' => '40px',
					),
					'og_desc' => array(
						'header' => 'SSO Desc',
						'meta_key' => '_wpsso_head_info_og_desc',
						'orderby' => false,	// do not offer column sorting
						'width' => '12%',
						'height' => 'auto',
					),
				),
			),
			'form' => array(
				'max_hashtags' => 5,
				'max_media_items' => 10,
				'tooltip_class' => 'sucom_tooltip',
				'yes_no' => array(
					1 => 'Yes',
					0 => 'No',
				),
				'true_false' => array(
					1 => 'True',
					0 => 'False',
				),
				'on_off' => array(
					1 => 'On',
					0 => 'Off',
				),
				'weekdays' => array(
					'sunday'         => 'Sunday',
					'monday'         => 'Monday',
					'tuesday'        => 'Tuesday',
					'wednesday'      => 'Wednesday',
					'thursday'       => 'Thursday',
					'friday'         => 'Friday',
					'saturday'       => 'Saturday',
					'publicholidays' => 'Public Holidays',
				),
				'time_seconds' => array(	// in seconds
					'hour'  => HOUR_IN_SECONDS,
					'day'   => DAY_IN_SECONDS,
					'week'  => WEEK_IN_SECONDS,	// 7 days
					'month' => MONTH_IN_SECONDS,	// 30 days
					'year'  => YEAR_IN_SECONDS,	// 365 days
				),
				'time_text' => array(
					'hour'  => 'Hour',
					'day'   => 'Day',
					'week'  => 'Week',
					'month' => 'Month',
					'year'  => 'Year',
				),
				'qualifiers' => array(
					'default'    => '(default)',
					'no_images'  => '(no images)',
					'no_videos'  => '(no videos)',
					'settings'   => '(settings value)',
					'disabled'   => '(option disabled)',
					'default_is' => 'default is %s',
					'checked'    => 'checked',
					'unchecked'  => 'unchecked',
					'at'         => 'at',
					'tz'         => 'tz',
				),
				'script_locations' => array(
					'none'   => '[None]',
					'header' => 'Header',
					'footer' => 'Footer',
				),
				'caption_types' => array(
					'none'    => '[None]',
					'title'   => 'Title Only',
					'excerpt' => 'Excerpt Only',
					'both'    => 'Title and Excerpt',
				),
				'user_name_fields' => array(
					'none'         => '[None]',
					'fullname'     => 'First and Last Names',
					'display_name' => 'Display Name',
					'nickname'     => 'Nickname',
				),
				'org_select' => array(
					'none' => '[None]',
					'new'  => '[New Organization]',
					'site' => '[WebSite Organization]',
				),
				'addr_select' => array(
					'none'   => '[None]',
					'custom' => '[Custom Address]',
					'new'    => '[New Address]',
				),
				'show_options' => array(
					'basic' => 'Basic Options',
					'all'   => 'All Options',
				),
				'site_option_use' => array(
					'default' => 'New activation',
					'empty'   => 'If value is empty',
					'force'   => 'Force this value',
				),
				'position_crop_x' => array(
					'left'   => 'Left',
					'center' => 'Center',
					'right'  => 'Right',
				),
				'position_crop_y' => array(
					'top' => 'Top',
					'center' => 'Center',
					'bottom' => 'Bottom',
				),
				'breadcrumbs_for_posts' => array(
					'none'       => '[None]',
					'categories' => 'Categories',
					'ancestors'  => 'Parents',
				),
				'breadcrumbs_for_terms' => array(
					'none'      => '[None]',
					'ancestors' => 'Parents',
				),
				// shortener key is also its filename under lib/pro/ext/
				'shorteners' => array(
					'none'    => '[None]',
					'bitly'   => 'Bitly (suggested)',
					'dlmyapp'   => 'DLMY.App',
					'googl'   => 'Google',
					'owly'    => 'Ow.ly',
					'tinyurl' => 'TinyURL',
					'yourls'  => 'YOURLS',
				),
				// social account keys and labels for organization sameas
				'social_accounts' => array(
					'fb_publisher_url'       => 'Facebook Business Page URL',
					'instgram_publisher_url' => 'Instagram Business Page URL',
					'linkedin_publisher_url' => 'LinkedIn Company Page URL',
					'myspace_publisher_url'  => 'Myspace Business Page URL',
					'p_publisher_url'        => 'Pinterest Company Page URL',
					'sc_publisher_url'       => 'Soundcloud Business Page URL',
					'seo_publisher_url'      => 'Google+ Business Page URL',
					'tc_site'                => 'Twitter Business @username',
					'tumblr_publisher_url'   => 'Tumblr Business Page URL',
					'yt_publisher_url'       => 'YouTube Business Channel URL',
				),
				'embed_media_apis' => array(
					'plugin_facebook_api'   => 'Facebook Videos',
					'plugin_slideshare_api' => 'Slideshare Presentations',
					'plugin_soundcloud_api' => 'Soundcloud Tracks',
					'plugin_vimeo_api'      => 'Vimeo Videos',
					'plugin_wistia_api'     => 'Wistia Videos',
					'plugin_wpvideo_api'    => 'WordPress Video Shortcode',
					'plugin_youtube_api'    => 'YouTube Videos and Playlists',
				),
				// https://schema.org/ItemAvailability
				// {schema_property_value} => {select_value_shown}
				'item_availability' => array(
					'none'                => '[None]',
			 		'Discontinued'        => 'Discontinued',
			 		'InStock'             => 'In Stock',
			 		'InStoreOnly'         => 'In Store Only',
			 		'LimitedAvailability' => 'Limited Availability',
			 		'OnlineOnly'          => 'Online Only',
			 		'OutOfStock'          => 'Out of Stock',
			 		'PreOrder'            => 'Pre-Order',
			 		'SoldOut '            => 'Sold Out',
				),
				// https://schema.org/OfferItemCondition
				// {schema_property_value} => {select_value_shown}
				'item_condition' => array(
					'none'                 => '[None]',
					'DamagedCondition'     => 'Damaged',
					'NewCondition'         => 'New',
					'RefurbishedCondition' => 'Refurbished',
					'UsedCondition'        => 'Used',
				),
				/**
				 * See https://developers.google.com/search/docs/data-types/job-postings.
				 * {schema_property_value} => {select_value_shown}
				 */
				'employment_type' => array(
					'full_time'  => 'Full Time',
					'part_time'  => 'Part Time',
					'contractor' => 'Contractor',
					'temporary'  => 'Temporary',
					'intern'     => 'Intern',
					'volunteer'  => 'Volunteer',
					'per_diem'   => 'Per Diem',
					'other'      => 'Other',
				),
				'cf_labels' => array(		// Custom field option labels.
					'plugin_cf_img_url'             => 'Image URL Custom Field',
					'plugin_cf_vid_url'             => 'Video URL Custom Field',
					'plugin_cf_vid_embed'           => 'Video Embed HTML Custom Field',
					'plugin_cf_addl_type_urls'      => 'Microdata Type URLs Custom Field',
					'plugin_cf_recipe_ingredients'  => 'Recipe Ingredients Custom Field',
					'plugin_cf_recipe_instructions' => 'Recipe Instructions Custom Field',
					'plugin_cf_product_avail'       => 'Product Availability Custom Field',
					'plugin_cf_product_brand'       => 'Product Brand Custom Field',
					'plugin_cf_product_color'       => 'Product Color Custom Field',
					'plugin_cf_product_condition'   => 'Product Condition Custom Field',
					'plugin_cf_product_currency'    => 'Product Currency Custom Field',
					'plugin_cf_product_material'    => 'Product Material Custom Field',
					'plugin_cf_product_price'       => 'Product Price Custom Field',
					'plugin_cf_product_size'        => 'Product Size Custom Field',
					'plugin_cf_sameas_urls'         => 'Same-As URLs Custom Field',
				),
			),
			'head' => array(
				'limit_min' => array(
					'og_desc_len'               => 160,
					'og_img_width'              => 200,	// See https://developers.facebook.com/docs/sharing/best-practices.
					'og_img_height'             => 200,
					'schema_article_img_width'  => 696,	// See https://developers.google.com/search/docs/data-types/articles.
					'schema_article_img_height' => 279,	// Calculated from the Article minimum image width and maximum image ratio.
					'schema_img_width'          => 400,	// See https://developers.google.com/+/web/snippet/article-rendering.
					'schema_img_height'         => 160,
				),
				'limit_max' => array(
					'og_img_ratio'                => 3,
					'schema_article_headline_len' => 110,
					'schema_article_img_ratio'    => 2.5,
					'schema_img_ratio'            => 2.5,	// See https://developers.google.com/+/web/snippet/article-rendering.
				),
				'og_type_ns' => array(		// See http://ogp.me/#types.
					'article'             => 'http://ogp.me/ns/article#',
					'book'                => 'http://ogp.me/ns/book#',
					'music.album'         => 'http://ogp.me/ns/music#',
					'music.playlist'      => 'http://ogp.me/ns/music#',
					'music.radio_station' => 'http://ogp.me/ns/music#',
					'music.song'          => 'http://ogp.me/ns/music#',
					'place'               => 'http://ogp.me/ns/place#',	// Supported by facebook and pinterest.
					'product'             => 'http://ogp.me/ns/product#',	// Supported by facebook and pinterest.
					'profile'             => 'http://ogp.me/ns/profile#',
					'video.episode'       => 'http://ogp.me/ns/video#',
					'video.movie'         => 'http://ogp.me/ns/video#',
					'video.other'         => 'http://ogp.me/ns/video#',
					'video.tv_show'       => 'http://ogp.me/ns/video#',
					'website'             => 'http://ogp.me/ns/website#',
				),
				'og_type_ns_compat' => array(
					'article'             => 'http://ogp.me/ns/article#',
					'place'               => 'http://ogp.me/ns/place#',	// Supported by facebook and pinterest.
					'product'             => 'http://ogp.me/ns/product#',	// Supported by facebook and pinterest.
					'website'             => 'http://ogp.me/ns/website#',
				),
				'og_type_mt' => array(	// See https://developers.facebook.com/docs/reference/opengraph/.
					'article' => array(	// See https://developers.facebook.com/docs/reference/opengraph/object-type/article/.
						'article:author' => '',
						'article:publisher' => '',
						'article:published_time' => '',
						'article:modified_time' => '',
						'article:expiration_time' => '',
						'article:section' => '',
						'article:tag' => '',
					),
					'book' => array(
						'book:author' => '',
						'book:isbn' => '',
						'book:release_date' => '',
						'book:tag' => '',
					),
					'music.album' => array(
						'music:song' => '',
						'music:song:disc' => '',
						'music:song:track' => '',
						'music:musician' => '',
						'music:release_date' => '',
					),
					'music.playlist' => array(
						'music:creator' => '',
						'music:song' => '',
						'music:song:disc' => '',
						'music:song:track' => '',
					),
					'music.radio_station' => array(
						'music:creator' => '',
					),
					'music.song' => array(
						'music:album' => '',
						'music:album:disc' => '',
						'music:album:track' => '',
						'music:duration' => '',
						'music:musician' => '',
					),
					'place' => array(
						'place:location:latitude' => '',
						'place:location:longitude' => '',
						'place:location:altitude' => '',
						'place:street_address' => '',
						'place:locality' => '',
						'place:region' => '',
						'place:postal_code' => '',
						'place:country_name' => '',
					),
					'product' => array(	// See https://developers.facebook.com/docs/reference/opengraph/object-type/product/.
						'product:availability' => 'product_avail',
						'product:brand' => 'product_brand',
						'product:category' => '',
						'product:color' => 'product_color',
						'product:condition' => 'product_condition',
						'product:material' => 'product_material',
						'product:price:amount' => 'product_price',
						'product:price:currency' => 'product_currency',
						'product:size' => 'product_size',
						'product:weight:value' => '',
						'product:weight:units' => '',
					),
					'profile' => array(
						'profile:first_name' => '',
						'profile:last_name' => '',
						'profile:username' => '',
						'profile:gender' => '',
					),
					'video.episode' => array(
						'video:actor' => '',
						'video:actor:role' => '',
						'video:director' => '',
						'video:writer' => '',
						'video:duration' => '',
						'video:release_date' => '',
						'video:tag' => '',
						'video:series' => '',
					),
					'video.movie' => array(
						'video:actor' => '',
						'video:actor:role' => '',
						'video:director' => '',
						'video:writer' => '',
						'video:duration' => '',
						'video:release_date' => '',
						'video:tag' => '',
					),
					'video.other' => array(
						'video:actor' => '',
						'video:actor:role' => '',
						'video:director' => '',
						'video:writer' => '',
						'video:duration' => '',
						'video:release_date' => '',
						'video:tag' => '',
					),
					'video.tv_show' => array(
						'video:actor' => '',
						'video:actor:role' => '',
						'video:director' => '',
						'video:writer' => '',
						'video:duration' => '',
						'video:release_date' => '',
						'video:tag' => '',
					),
				),
				'og_content_map' => array(
					'product:availability' => array(	// 'instock', 'oos', or 'pending'
				 		'Discontinued' => 'oos',
				 		'InStock' => 'instock',
				 		'InStoreOnly' => 'instock',
				 		'LimitedAvailability' => 'instock',
				 		'OnlineOnly' => 'instock',
				 		'OutOfStock' => 'oos',
				 		'PreOrder' => 'pending',
				 		'SoldOut ' => 'oos',
					),
					'product:condition' => array(		// 'new', 'refurbished', or 'used'
						'DamagedCondition' => 'used',
						'NewCondition' => 'new',
						'RefurbishedCondition' => 'refurbished',
						'UsedCondition' => 'used',
					),
				),
				/**
				 * WpssoSchema::get_schema_types_array() flattens the array, so AVOID DUPLICATE KEY NAMES.
				 *
				 * https URLs are preferred - for more info, see https://schema.org/docs/faq.html#19:
				 *
				 * Q: Should we write 'https://schema.org' or 'http://schema.org' in our markup?
				 * A: There is a general trend towards using 'https' more widely, and you can
				 *    already write 'https://schema.org' in your structured data. Over time we will
				 *    migrate the schema.org site itself towards using https: as the default
				 *    version of the site and our preferred form in examples.
				 */
				'schema_type' => array(	// Element of 'head' array.
					'thing' => array(	// Most generic type.
						'creative.work' => array(	// Creative work, including books, movies, photographs, software programs, etc.
							'article' => array(
								'article' => 'https://schema.org/Article',
								'article.news' => 'https://schema.org/NewsArticle',
								'article.tech' => 'https://schema.org/TechArticle',
								'article.scholarly' => 'https://schema.org/ScholarlyArticle',
								'report' => 'https://schema.org/Report',
								'social.media.posting' => array(
									'blog.posting' => 'https://schema.org/BlogPosting',
									'discussion.forum.posting' => 'https://schema.org/DiscussionForumPosting',
									'social.media.posting' => 'https://schema.org/SocialMediaPosting',
								),
							),
							'blog' => 'https://schema.org/Blog',
							'book' => 'https://schema.org/Book',
							'clip' => 'https://schema.org/Clip',
							'comment' => 'https://schema.org/Comment',
							'conversation' => 'https://schema.org/Conversation',
							'course' => 'https://schema.org/Course',
							'creative.work' => 'https://schema.org/CreativeWork',
							'creative.work.season' => 'https://schema.org/CreativeWorkSeason',
							'creative.work.series' => 'https://schema.org/CreativeWorkSeries',
							'data.catalog' => 'https://schema.org/DataCatalog',
							'data.set' => 'https://schema.org/DataSet',
							'digital.document' => 'https://schema.org/DigitalDocument',
							'episode' => 'https://schema.org/Episode',
							'game' => array(
								'game' => 'https://schema.org/Game',
								'video.game' => 'https://schema.org/VideoGame',
							),
							'howto' => array(
								'howto' => 'https://schema.org/HowTo',
								'recipe' => 'https://schema.org/Recipe',
							),
							'map' => 'https://schema.org/Map',
							'media.object' => array(
								'audio.object' => 'https://schema.org/AudioObject',
								'data.download' => 'https://schema.org/DataDownload',
								'image.object' => 'https://schema.org/ImageObject',
								'media.object' => 'https://schema.org/MediaObject',
								'music.video.object' => 'https://schema.org/MusicVideoObject',
								'video.object' => 'https://schema.org/VideoObject',
							),
							'menu' => 'https://schema.org/Menu',
							'menu.section' => 'https://schema.org/MenuSection',
							'message' => 'https://schema.org/Message',
							'movie' => 'https://schema.org/Movie',
							'music.composition' => 'https://schema.org/MusicComposition',
							'music.playlist' => array(
								'music.album' => 'https://schema.org/MusicAlbum',
								'music.playlist' => 'https://schema.org/MusicPlaylist',
								'music.release' => 'https://schema.org/MusicRelease',
							),
							'music.recording' => 'https://schema.org/MusicRecording ',
							'painting' => 'https://schema.org/Painting',
							'photograph' => 'https://schema.org/Photograph',
							'publication.issue' => 'https://schema.org/PublicationIssue',
							'publication.volume' => 'https://schema.org/PublicationVolume',
							'question' => 'https://schema.org/Question',
							'review' => array(
								'review' => 'https://schema.org/Review',
								'review.claim' => 'https://schema.org/ClaimReview',
							),
							'sculpture' => 'https://schema.org/Sculpture',
							'series' => 'https://schema.org/Series',
							'software.application' => 'https://schema.org/SoftwareApplication',
							'software.source.code' => 'https://schema.org/SoftwareSourceCode',
							'tv.season' => 'https://schema.org/TVSeason',
							'tv.series' => 'https://schema.org/TVSeries',
							'visual.artwork' => 'https://schema.org/VisualArtwork',
							'webpage' => array(
								'webpage' => 'https://schema.org/WebPage',
								'webpage.about' => 'https://schema.org/AboutPage',
								'webpage.checkout' => 'https://schema.org/CheckoutPage',
								'webpage.collection' => 'https://schema.org/CollectionPage',
								'webpage.contact' => 'https://schema.org/ContactPage',
								'webpage.item' => 'https://schema.org/ItemPage',
								'webpage.medical' => 'https://health-lifesci.schema.org/MedicalWebPage',
								'webpage.profile' => 'https://schema.org/ProfilePage',
								'webpage.qa' => 'https://schema.org/QAPage',
								'webpage.search.results' => 'https://schema.org/SearchResultsPage',
							),
							'webpage.element' => 'https://schema.org/WebPageElement',
							'website' => 'https://schema.org/WebSite',
						),
						'event' => array(
							'event' => 'https://schema.org/Event',
							'event.business' => 'https://schema.org/BusinessEvent',
							'event.childrens' => 'https://schema.org/ChildrensEvent',
							'event.comedy' => 'https://schema.org/ComedyEvent',
							'event.dance' => 'https://schema.org/DanceEvent',
							'event.delivery' => 'https://schema.org/DeliveryEvent',
							'event.education' => 'https://schema.org/EducationEvent',
							'event.exhibition' => 'https://schema.org/ExhibitionEvent',
							'event.festival' => 'https://schema.org/Festival',
							'event.food' => 'https://schema.org/FoodEvent',
							'event.literary' => 'https://schema.org/LiteraryEvent',
							'event.music' => 'https://schema.org/MusicEvent',
							'event.publication' => 'https://schema.org/PublicationEvent',
							'event.sale' => 'https://schema.org/SaleEvent',
							'event.screening' => 'https://schema.org/ScreeningEvent',
							'event.social' => 'https://schema.org/SocialEvent',
							'event.sports' => 'https://schema.org/SportsEvent',
							'event.theater' => 'https://schema.org/TheaterEvent',
							'event.visual.arts' => 'https://schema.org/VisualArtsEvent',
						),
						'intangible' => array(
							'alignment.object' => 'https://schema.org/AlignmentObject',
							'audience' => 'https://schema.org/Audience',
							'bed.details' => 'https://schema.org/BedDetails',
							'brand' => 'https://schema.org/Brand',
							'broadcast.channel' => 'https://schema.org/BroadcastChannel',
							'bus.trip' => 'https://schema.org/BusTrip',
							'computer.language' => 'https://schema.org/ComputerLanguage',
							'data.feed.item' => 'https://schema.org/DataFeedItem',
							'demand' => 'https://schema.org/Demand',
							'digital.document.permission' => 'https://schema.org/DigitalDocumentPermission',
							'entry.point' => 'https://schema.org/EntryPoint',
							'enumeration' => array(
								'action.status.type' => 'https://schema.org/ActionStatusType',
								'boarding.policy.type' => 'https://schema.org/BoardingPolicyType',
								'book.format.type' => 'https://schema.org/BookFormatType',
								'business.entity.type' => 'https://schema.org/BusinessEntityType',
								'business.function' => 'https://schema.org/BusinessFunction',
								'contact.point.option' => 'https://schema.org/ContactPointOption',
								'day.of.week' => 'https://schema.org/DayOfWeek',
								'delivery.method' => 'https://schema.org/DeliveryMethod',
								'digital.document.permission.type' => 'https://schema.org/DigitalDocumentPermissionType',
								'enumeration' => 'https://schema.org/Enumeration',
								'event.status.type' => 'https://schema.org/EventStatusType',
								'game.play.mode' => 'https://schema.org/GamePlayMode',
								'game.server.status' => 'https://schema.org/GameServerStatus',
								'gender.type' => 'https://schema.org/GenderType',
								'item.availability' => 'https://schema.org/ItemAvailability',
								'item.list.order.type' => 'https://schema.org/ItemListOrderType',
								'map.category.type' => 'https://schema.org/MapCategoryType',
								'music.album.production.type' => 'https://schema.org/MusicAlbumProductionType',
								'music.album.release.type' => 'https://schema.org/MusicAlbumReleaseType',
								'music.release.format.type' => 'https://schema.org/MusicReleaseFormatType',
								'offer.item.condition' => 'https://schema.org/OfferItemCondition',
								'order.status' => 'https://schema.org/OrderStatus',
								'payment.method' => 'https://schema.org/PaymentMethod',
								'payment.status.type' => 'https://schema.org/PaymentStatusType',
								'qualitative.value' => 'https://schema.org/QualitativeValue',
								'reservation.status.type' => 'https://schema.org/ReservationStatusType',
								'restricted.diet' => 'https://schema.org/RestrictedDiet',
								'rsvp.response.type' => 'https://schema.org/RsvpResponseType',
								'specialty' => array(
									'medical.specialty' => array( 
										'anesthesia.specialty' => 'https://health-lifesci.schema.org/Anesthesia',
										'cardiovascular.specialty' => 'https://health-lifesci.schema.org/Cardiovascular',
										'community.health.specialty' => 'https://health-lifesci.schema.org/CommunityHealth',
										'dentistry.specialty' => 'https://health-lifesci.schema.org/Dentistry',
										'dermatologic.specialty' => 'https://health-lifesci.schema.org/Dermatologic',
										'dermatology.specialty' => 'https://health-lifesci.schema.org/Dermatology',
										'diet.nutrition.specialty' => 'https://health-lifesci.schema.org/DietNutrition',
										'emergency.specialty' => 'https://health-lifesci.schema.org/Emergency',
										'endocrine.specialty' => 'https://health-lifesci.schema.org/Endocrine',
										'gastroenterologic.specialty' => 'https://health-lifesci.schema.org/Gastroenterologic',
										'genetic.specialty' => 'https://health-lifesci.schema.org/Genetic',
										'geriatric.specialty' => 'https://health-lifesci.schema.org/Geriatric',
										'gynecologic.specialty' => 'https://health-lifesci.schema.org/Gynecologic',
										'hematologic.specialty' => 'https://health-lifesci.schema.org/Hematologic',
										'infectious.specialty' => 'https://health-lifesci.schema.org/Infectious',
										'laboratory.science.specialty' => 'https://health-lifesci.schema.org/LaboratoryScience',
										'medical.specialty' => 'https://health-lifesci.schema.org/MedicalSpecialty',
										'midwifery.specialty' => 'https://health-lifesci.schema.org/Midwifery',
										'musculoskeletal.specialty' => 'https://health-lifesci.schema.org/Musculoskeletal',
										'neurologic.specialty' => 'https://health-lifesci.schema.org/Neurologic',
										'nursing.specialty' => 'https://health-lifesci.schema.org/Nursing',
										'obstetric.specialty' => 'https://health-lifesci.schema.org/Obstetric',
										'occupational.therapy.specialty' => 'https://health-lifesci.schema.org/OccupationalTherapy',
										'oncologic.specialty' => 'https://health-lifesci.schema.org/Oncologic',
										'optometric.specialty' => 'https://health-lifesci.schema.org/Optometric',
										'otolaryngologic.specialty' => 'https://health-lifesci.schema.org/Otolaryngologic',
										'pathology.specialty' => 'https://health-lifesci.schema.org/Pathology',
										'pediatric.specialty' => 'https://health-lifesci.schema.org/Pediatric',
										'pharmacy.specialty' => 'https://health-lifesci.schema.org/PharmacySpecialty',
										'physiotherapy.specialty' => 'https://health-lifesci.schema.org/Physiotherapy',
										'plastic.surgery.specialty' => 'https://health-lifesci.schema.org/PlasticSurgery',
										'podiatric.specialty' => 'https://health-lifesci.schema.org/Podiatric',
										'primary.care.specialty' => 'https://health-lifesci.schema.org/PrimaryCare',
										'psychiatric.specialty' => 'https://health-lifesci.schema.org/Psychiatric',
										'public.health.specialty' => 'https://health-lifesci.schema.org/PublicHealth',
										'pulmonary.specialty' => 'https://health-lifesci.schema.org/Pulmonary',
										'radiography.specialty' => 'https://health-lifesci.schema.org/Radiography',
										'renal.specialty' => 'https://health-lifesci.schema.org/Renal',
										'respiratory.therapy.specialty' => 'https://health-lifesci.schema.org/RespiratoryTherapy',
										'rheumatologic.specialty' => 'https://health-lifesci.schema.org/Rheumatologic',
										'speech.pathology.specialty' => 'https://health-lifesci.schema.org/SpeechPathology',
										'surgical.specialty' => 'https://health-lifesci.schema.org/Surgical',
										'toxicologic.specialty' => 'https://health-lifesci.schema.org/Toxicologic',
										'urologic.specialty' => 'https://health-lifesci.schema.org/Urologic',
									),
									'specialty' => 'https://schema.org/Specialty',
								),
								'warranty.scope' => 'https://schema.org/WarrantyScope',
							),
							'flight' => 'https://schema.org/Flight',
							'game.server' => 'https://schema.org/GameServer',
							'intangible' => 'https://schema.org/Intangible',
							'invoice' => 'https://schema.org/Invoice',
							'item.list' => array(
								'breadcrumb.list' => 'https://schema.org/BreadcrumbList',
								'how.to.section' => 'https://schema.org/HowToSection',
								'how.to.step' => 'https://schema.org/HowToStep',
								'item.list' => 'https://schema.org/ItemList',
								'offer.catalog' => 'https://schema.org/OfferCatalog ',
							),
							'job.posting' => 'https://schema.org/JobPosting',
							'language' => 'https://schema.org/Language',
							'list.item' => 'https://schema.org/ListItem',
							'medical.enumeration' => array(
								'medical.enumeration' => 'https://health-lifesci.schema.org/MedicalEnumeration',
							),
							'menu.item' => 'https://schema.org/MenuItem',
							'offer' => 'https://schema.org/Offer',
							'order' => 'https://schema.org/Order',
							'order.item' => 'https://schema.org/OrderItem',
							'parcel.delivery' => 'https://schema.org/ParcelDelivery',
							'permit' => 'https://schema.org/Permit',
							'program.membership' => 'https://schema.org/ProgramMembership',
							'property.value.specification' => 'https://schema.org/PropertyValueSpecification',
							'quantity' => 'https://schema.org/Quantity',
							'rating' => 'https://schema.org/Rating',
							'reservation' => 'https://schema.org/Reservation',
							'role' => 'https://schema.org/Role',
							'seat' => 'https://schema.org/Seat',
							// a service provided by an organization, e.g. delivery service, print services, etc.
							'service' => array(
								'service' => 'https://schema.org/Service',
								'service.broadcast' => 'https://schema.org/BroadcastService',
								'service.cable.or.satellite' => 'https://schema.org/CableOrSatelliteService',
								'service.financial.product' => 'https://schema.org/FinancialProduct',
									'service.bank.account' => 'https://schema.org/BankAccount',
									'service.currency.conversion' => 'https://schema.org/CurrencyConversionService',
									'service.investment.or.deposit' => 'https://schema.org/InvestmentOrDeposit',
									'service.loan.or.credit' => 'https://schema.org/LoanOrCredit',
									'service.payment.card' => 'https://schema.org/PaymentCard',
									'service.payment' => 'https://schema.org/PaymentService ',
								'service.food' => 'https://schema.org/FoodService',
								'service.government' => 'https://schema.org/GovernmentService',
								'service.taxi' => 'https://schema.org/TaxiService ',
							),
							'service.channel' => 'https://schema.org/ServiceChannel',
							'structured.value' => 'https://schema.org/StructuredValue',
							'ticket' => 'https://schema.org/Ticket',
							'train.trip' => 'https://schema.org/TrainTrip',
						),
						'organization' => array(
							'airline' => 'https://schema.org/Airline',
							'corporation' => 'https://schema.org/Corporation',
							'educational.organization' => array(
								'college.or.university' => 'https://schema.org/CollegeOrUniversity',
								'educational.organization' => 'https://schema.org/EducationalOrganization',
								'elementary.school' => 'https://schema.org/ElementarySchool',
								'high.school' => 'https://schema.org/HighSchool',
								'middle.school' => 'https://schema.org/MiddleSchool',
								'preschool' => 'https://schema.org/Preschool',
								'school' => 'https://schema.org/School',
							),
							'government.organization' => 'https://schema.org/GovernmentOrganization',
							'medical.organization' => array(
								'dentist.organization' => 'https://schema.org/Dentist',
								// xref hospital -> place/local.business/emergency.service/hospital
								'medical.organization' => 'https://schema.org/MedicalOrganization',
								'pharmacy.organization' => 'https://schema.org/Pharmacy',
								'physician.organization' => 'https://schema.org/Physician',
							),
							'non-governmental.organization' => 'https://schema.org/NGO',
							'organization' => 'https://schema.org/Organization',
							'performing.group' => array(
								'dance.group' => 'https://schema.org/DanceGroup',
								'music.group' => 'https://schema.org/MusicGroup',
								'performing.group' => 'https://schema.org/PerformingGroup',
								'theater.group' => 'https://schema.org/TheaterGroup',
							),
							'sports.organization' => array(
								'sports.team' => 'https://schema.org/SportsTeam',
								'sports.organization' => 'https://schema.org/SportsOrganization',
							),
						),
						'person' => 'https://schema.org/Person',
						'place' => array(
							'accommodation' => array(
								'accommodation' => 'https://schema.org/Accommodation',
								'apartment' => 'https://schema.org/Apartment',
								'camping.pitch' => 'https://schema.org/CampingPitch',
								'house' => array(
									'house' => 'https://schema.org/House',
									'residence.single.family' => 'https://schema.org/SingleFamilyResidence',
								),
								'room' => array(
									'room' => 'https://schema.org/Room',
									'room.hotel' => 'https://schema.org/HotelRoom',
									'room.meeting' => 'https://schema.org/MeetingRoom',
								),
								'suite' => 'https://schema.org/Suite',
							),
							'administrative.area' => 'https://schema.org/AdministrativeArea',
							'civic.structure' => array(
								'airport' => 'https://schema.org/Airport',
								'aquarium' => 'https://schema.org/Aquarium',
								'beach' => 'https://schema.org/Beach',
								'bridge' => 'https://schema.org/Bridge',
								'bus.station' => 'https://schema.org/BusStation',
								'bus.stop' => 'https://schema.org/BusStop',
								'cemetary' => 'https://schema.org/Cemetery',
								'civic.structure' => 'https://schema.org/CivicStructure',
								'crematorium' => 'https://schema.org/Crematorium',
								'event.venue' => 'https://schema.org/EventVenue',
								'government.building' => 'https://schema.org/GovernmentBuilding',
								'museum' => 'https://schema.org/Museum',
								'music.venue' => 'https://schema.org/MusicVenue',
								'park' => 'https://schema.org/Park',
								'parking.facility' => 'https://schema.org/ParkingFacility',
								'performing.arts.theatre' => 'https://schema.org/PerformingArtsTheater',
								'place.of.worship' => 'https://schema.org/PlaceOfWorship',
								'playground' => 'https://schema.org/Playground',
								'rv.park' => 'https://schema.org/RVPark',
								'subway.station' => 'https://schema.org/SubwayStation',
								'taxi.stand' => 'https://schema.org/TaxiStand',
								'train.station' => 'https://schema.org/TrainStation',
								'zoo' => 'https://schema.org/Zoo',
							),
							'landform' => 'https://schema.org/Landform',
							'landmarks.or.historical.buildings' => 'https://schema.org/LandmarksOrHistoricalBuildings',
							'local.business' => array(
								'animal.shelter' => 'https://schema.org/AnimalShelter',
								'automotive.business' => array(
									'auto.body.shop' => 'https://schema.org/AutoBodyShop',
									'auto.dealer' => 'https://schema.org/AutoDealer',
									'auto.parts.store' => 'https://schema.org/AutoPartsStore',
									'auto.rental' => 'https://schema.org/AutoRental',
									'auto.repair' => 'https://schema.org/AutoRepair',
									'auto.wash' => 'https://schema.org/AutoWash',
									'automotive.business' => 'https://schema.org/AutomotiveBusiness',
									'gas.station' => 'https://schema.org/GasStation',
									'motorcycle.dealer' => 'https://schema.org/MotorcycleDealer',
									'motorcycle.repair' => 'https://schema.org/MotorcycleRepair ',
								),
								'child.care' => 'https://schema.org/ChildCare',
								'dry.cleaning.or.laundry' => 'https://schema.org/DryCleaningOrLaundry',
								'emergency.service' => array(
									'emergency.service' => 'https://schema.org/EmergencyService',
									'fire.station' => 'https://schema.org/FireStation',
									'hospital' => 'https://schema.org/Hospital',
									'police.station' => 'https://schema.org/PoliceStation',
								),
								'employment.agency' => 'https://schema.org/EmploymentAgency',
								'entertainment.business' => array(
									'adult.entertainment' => 'https://schema.org/AdultEntertainment',
									'amusement.park' => 'https://schema.org/AmusementPark',
									'art.gallery' => 'https://schema.org/ArtGallery',
									'casino' => 'https://schema.org/Casino',
									'comedy.club' => 'https://schema.org/ComedyClub',
									'entertainment.business' => 'https://schema.org/EntertainmentBusiness',
									'movie.theatre' => 'https://schema.org/MovieTheatre',
									'night.club' => 'https://schema.org/NightClub',
								),
								'financial.service' => 'https://schema.org/FinancialService',
								'food.establishment' => array(
									'bakery' => 'https://schema.org/Bakery',
									'bar.or.pub' => 'https://schema.org/BarOrPub',
									'brewery' => 'https://schema.org/Brewery',
									'cafe.or.coffee.shop' => 'https://schema.org/CafeOrCoffeeShop',
									'fast.food.restaurant' => 'https://schema.org/FastFoodRestaurant',
									'food.establishment' => 'https://schema.org/FoodEstablishment',
									'ice.cream.shop' => 'https://schema.org/IceCreamShop',
									'restaurant' => 'https://schema.org/Restaurant',
									'winery' => 'https://schema.org/Winery',
								),
								'government.office' => 'https://schema.org/GovernmentOffice',
								'health.and.beauty.business' => array(
									'beauty.salon' => 'https://schema.org/BeautySalon',
									'day.spa' => 'https://schema.org/DaySpa',
									'health.and.beauty.business' => 'https://schema.org/HealthAndBeautyBusiness',
									'hair.salon' => 'https://schema.org/HairSalon',
									'health.club' => 'https://schema.org/HealthClub',
									'nail.salon' => 'https://schema.org/NailSalon',
									'tattoo.parlor' => 'https://schema.org/TattooParlor',
								),
								'home.and.construction.business' => array(
									'electrician' => 'https://schema.org/Electrician',
									'general.contractor' => 'https://schema.org/GeneralContractor',
									'hvac.business' => 'https://schema.org/HVACBusiness',
									'home.and.construction.business' => 'https://schema.org/HomeAndConstructionBusiness',
									'house.painter' => 'https://schema.org/HousePainter',
									'locksmith' => 'https://schema.org/Locksmith',
									'moving.company' => 'https://schema.org/MovingCompany',
									'plumber' => 'https://schema.org/Plumber',
									'roofing.contractor' => 'https://schema.org/RoofingContractor',
								),
								'internet.cafe' => 'https://schema.org/InternetCafe',
								'legal.service' => array(
									'attorney' => 'https://schema.org/Attorney',
									'legal.service' => 'https://schema.org/LegalService',
									'notary' => 'https://schema.org/Notary',
								),
								'library' => 'https://schema.org/Library',
								'local.business' => 'https://schema.org/LocalBusiness',
								'lodging.business' => array(
									'bed.and.breakfast' => 'https://schema.org/BedAndBreakfast',
									'campground' => 'https://schema.org/Campground',
									'lodging.business' => 'https://schema.org/LodgingBusiness',
									'hostel' => 'https://schema.org/Hostel',
									'hotel' => 'https://schema.org/Hotel',
									'motel' => 'https://schema.org/Motel',
									'resort' => 'https://schema.org/Resort',
								),
								'medical.business' => array(
									'community.health.business' => 'https://health-lifesci.schema.org/CommunityHealth',
									'dentist.business' => 'https://health-lifesci.schema.org/Dentist',
									'dermatology.business' => 'https://health-lifesci.schema.org/Dermatology',
									'diet.nutrition.business' => 'https://health-lifesci.schema.org/DietNutrition',
									'emergency.business' => 'https://health-lifesci.schema.org/Emergency',
									'geriatric.business' => 'https://health-lifesci.schema.org/Geriatric',
									'gynecologic.business' => 'https://health-lifesci.schema.org/Gynecologic',
									'medical.business' => 'https://health-lifesci.schema.org/MedicalBusiness',
									'medical.clinic.business' => 'https://health-lifesci.schema.org/MedicalClinic',
									'midwifery.business' => 'https://health-lifesci.schema.org/Midwifery',
									'nursing.business' => 'https://health-lifesci.schema.org/Nursing',
									'obstetric.business' => 'https://health-lifesci.schema.org/Obstetric',
									'oncologic.business' => 'https://health-lifesci.schema.org/Oncologic',
									'optician.business' => 'https://health-lifesci.schema.org/Optician',
									'optometric.business' => 'https://health-lifesci.schema.org/Optometric',
									'otolaryngologic.business' => 'https://health-lifesci.schema.org/Otolaryngologic',
									'pediatric.business' => 'https://health-lifesci.schema.org/Pediatric',
									'pharmacy.business' => 'https://health-lifesci.schema.org/Pharmacy',
									'physician.business' => 'https://health-lifesci.schema.org/Physician',
									'physiotherapy.business' => 'https://health-lifesci.schema.org/Physiotherapy',
									'plastic.surgery.business' => 'https://health-lifesci.schema.org/PlasticSurgery',
									'podiatric.business' => 'https://health-lifesci.schema.org/Podiatric',
									'primary.care.business' => 'https://health-lifesci.schema.org/PrimaryCare',
									'psychiatric.business' => 'https://health-lifesci.schema.org/Psychiatric',
									'public.health.business' => 'https://health-lifesci.schema.org/PublicHealth',
								),
								'professional.service' => 'https://schema.org/ProfessionalService',
								'radio.station' => 'https://schema.org/RadioStation',
								'real.estate.agent' => 'https://schema.org/RealEstateAgent',
								'recycling.center' => 'https://schema.org/RecyclingCenter',
								'self.storage' => 'https://schema.org/SelfStorage',
								'shopping.center' => 'https://schema.org/ShoppingCenter',
								'sports.activity.location' => array(
									'sports.activity.location' => 'https://schema.org/SportsActivityLocation',
									'stadium.or.arena' => 'https://schema.org/StadiumOrArena',
								),
								'store' => array(
									'bike.store' => 'https://schema.org/BikeStore',
									'book.store' => 'https://schema.org/BookStore',
									'clothing.store' => 'https://schema.org/ClothingStore',
									'computer.store' => 'https://schema.org/ComputerStore',
									'convenience.store' => 'https://schema.org/ConvenienceStore',
									'department.store' => 'https://schema.org/DepartmentStore',
									'electronics.store' => 'https://schema.org/ElectronicsStore',
									'florist' => 'https://schema.org/Florist',
									'flower.shop' => 'https://schema.org/Florist',
									'furniture.store' => 'https://schema.org/FurnitureStore',
									'garden.store' => 'https://schema.org/GardenStore',
									'grocery.store' => 'https://schema.org/GroceryStore',
									'hardware.store' => 'https://schema.org/HardwareStore',
									'hobby.shop' => 'https://schema.org/HobbyShop',
									'home.goods.store' => 'https://schema.org/HomeGoodsStore',
									'jewelry.store' => 'https://schema.org/JewelryStore',
									'liquor.store' => 'https://schema.org/LiquorStore',
									'mens.clothing.store' => 'https://schema.org/MensClothingStore',
									'mobile.phone.store' => 'https://schema.org/MobilePhoneStore',
									'movie.rental.store' => 'https://schema.org/MovieRentalStore',
									'music.store' => 'https://schema.org/MusicStore',
									'office.equipment.store' => 'https://schema.org/OfficeEquipmentStore',
									'outlet.store' => 'https://schema.org/OutletStore',
									'pawn.shop' => 'https://schema.org/PawnShop',
									'pet.store' => 'https://schema.org/PetStore',
									'shoe.store' => 'https://schema.org/ShoeStore',
									'sporting.goods.store' => 'https://schema.org/SportingGoodsStore',
									'store' => 'https://schema.org/Store',
									'tire.shop' => 'https://schema.org/TireShop',
									'toy.store' => 'https://schema.org/ToyStore',
									'wholesale.store' => 'https://schema.org/WholesaleStore',
								),
								'television.station' => 'https://schema.org/TelevisionStation',
								'tourist.information.center' => 'https://schema.org/TouristInformationCenter',
								'travel.agency' => 'https://schema.org/TravelAgency',
							),
							'place' => 'https://schema.org/Place',
							'residence' => array(
								'residence' => 'https://schema.org/Residence',
								'residence.apartment.complex' => 'https://schema.org/ApartmentComplex',
								'residence.gated.community' => 'https://schema.org/GatedResidenceCommunity',
							),
							'tourist.attraction' => 'https://schema.org/TouristAttraction',
						),
						'product' => array(
							'individual.product' => 'https://schema.org/IndividualProduct',	// individual product w unique serial number
							'product' => 'https://schema.org/Product',
							'product.model' => 'https://schema.org/ProductModel',
							'some.products' => 'https://schema.org/SomeProducts',
							'vehicle' => array(
								'bus.or.coach' => 'https://auto.schema.org/BusOrCoach',
								'car' => 'https://auto.schema.org/Car',
								'motorcycle' => 'https://auto.schema.org/Motorcycle',
								'motorized.bicycle' => 'https://auto.schema.org/MotorizedBicycle',
								'vehicle' => 'https://auto.schema.org/Vehicle',
							),
						),
						'thing' => 'https://schema.org/Thing',
					),
				),
				'schema_renamed' => array(	// element of 'head' array
					'anesthesia' => 'anesthesia.specialty',
					'cardiovascular' => 'cardiovascular.specialty',
					'community.health' => 'community.health.specialty',
					'dentist' => 'dentist.organization',
					'dentistry' => 'dentistry.specialty',
					'dermatologic' => 'dermatologic.specialty',
					'dermatology' => 'dermatology.specialty',
					'diet.nutrition' => 'diet.nutrition.specialty',
					'emergency' => 'emergency.specialty',
					'endocrine' => 'endocrine.specialty',
					'event.venu' => 'event.venue',
					'gastroenterologic' => 'gastroenterologic.specialty',
					'genetic' => 'genetic.specialty',
					'geriatric' => 'geriatric.specialty',
					'gynecologic' => 'gynecologic.specialty',
					'hematologic' => 'hematologic.specialty',
					'infectious' => 'infectious.specialty',
					'laboratory.science' => 'laboratory.science.specialty',
					'midwifery' => 'midwifery.specialty',
					'musculoskeletal' => 'musculoskeletal.specialty',
					'music.venu' => 'music.venue',
					'neurologic' => 'neurologic.specialty',
					'nursing' => 'nursing.specialty',
					'obstetric' => 'obstetric.specialty',
					'occupational.therapy' => 'occupational.therapy.specialty',
					'oncologic' => 'oncologic.specialty',
					'optometric' => 'optometric.specialty',
					'otolaryngologic' => 'otolaryngologic.specialty',
					'pathology' => 'pathology.specialty',
					'pediatric' => 'pediatric.specialty',
					'pharmacy' => 'pharmacy.organization',
					'physician' => 'physician.organization',
					'physiotherapy' => 'physiotherapy.specialty',
					'plastic.surgery' => 'plastic.surgery.specialty',
					'podiatric' => 'podiatric.specialty',
					'primary.care' => 'primary.care.specialty',
					'psychiatric' => 'psychiatric.specialty',
					'public.health' => 'public.health.specialty',
					'pulmonary' => 'pulmonary.specialty',
					'radiography' => 'radiography.specialty',
					'renal' => 'renal.specialty',
					'respiratory.therapy' => 'respiratory.therapy.specialty',
					'rheumatologic' => 'rheumatologic.specialty',
					'speech.pathology' => 'speech.pathology.specialty',
					'surgical' => 'surgical.specialty',
					'toxicologic' => 'toxicologic.specialty',
					'urologic' => 'urologic.specialty',
				),
			),
			'extend' => array(
				'https://wpsso.com/extend/plugins/',
			),
			'follow' => array(
				'size' => 32,
				'src' => array(
					'images/follow/Wordpress.png' => 'https://profiles.wordpress.org/jsmoriss/',
					'images/follow/Github.png' => 'https://github.com/SurniaUlula',
					'images/follow/Facebook.png' => 'https://www.facebook.com/SurniaUlulaCom',
					'images/follow/GooglePlus.png' => 'https://plus.google.com/+SurniaUlula/',
					'images/follow/Twitter.png' => 'https://twitter.com/surniaululacom',
					//'images/follow/Rss.png' => 'https://wpsso.com/category/application/wordpress/wp-plugins/wpsso/feed/',
				),
			),
		);

		public static function get_version( $add_slug = false ) {
			$ext = 'wpsso';
			$info =& self::$cf['plugin'][$ext];
			return $add_slug ? $info['slug'] . '-' . $info['version'] : $info['version'];
		}

		/**
		 * get_config() is called very early, so don't apply filters unless instructed ($filter_cf = true).
		 */
		public static function get_config( $idx = false, $filter_cf = false ) {

			if ( ! isset( self::$cf['config_filtered'] ) || self::$cf['config_filtered'] !== true ) {

				self::$cf['*'] = array(
					'base' => array(),
					'lib' => array(
						'gpl' => array(),
						'pro' => array(),
					),
					'version' => '',		// -wpsso3.29.0pro-wpssoplm1.5.1pro-wpssoum1.4.0gpl
				);

				self::$cf['opt']['version'] = '';	// -wpsso416pro-wpssoplm8pro

				if ( $filter_cf ) {

					self::$cf['config_filtered'] = true;	// set before calling filter to prevent recursion

					self::$cf = apply_filters( 'wpsso_get_config', self::$cf, self::get_version() );

					foreach ( self::$cf['plugin'] as $ext => $info ) {

						if ( defined( strtoupper( $ext ) . '_PLUGINDIR' ) ) {
							$pkg_lctype = is_dir( constant( strtoupper( $ext ) . 
								'_PLUGINDIR' ) . 'lib/pro/' ) ? 'pro' : 'gpl';
						} else {
							$pkg_lctype = '';
						}

						if ( isset( $info['slug'] ) ) {
							self::$cf['*']['slug'][$info['slug']] = $ext;
						}

						if ( isset( $info['base'] ) ) {
							self::$cf['*']['base'][$info['base']] = $ext;
						}

						if ( isset( $info['lib'] ) && is_array( $info['lib'] ) ) {
							self::$cf['*']['lib'] = SucomUtil::array_merge_recursive_distinct(
								self::$cf['*']['lib'], $info['lib'] );
						}

						if ( isset( $info['version'] ) ) {
							self::$cf['*']['version'] .= '-' . $ext . $info['version'] . $pkg_lctype;
						}

						if ( isset( $info['opt_version'] ) ) {
							self::$cf['opt']['version'] .= '-' . $ext . $info['opt_version'] . $pkg_lctype;
						}

						// complete relative paths in the image arrays
						$plugin_base = trailingslashit( plugins_url( '', $info['base'] ) );
						array_walk_recursive( self::$cf['plugin'][$ext]['img'], 
							array( __CLASS__, 'maybe_prefix_base_url' ), $plugin_base );
					}
				}
			}

			if ( $idx !== false ) {
				if ( isset( self::$cf[$idx] ) ) {
					return self::$cf[$idx];
				} else {
					return null;
				}
			} else {
				return self::$cf;
			}
		}

		private static function maybe_prefix_base_url( &$url, $key, $plugin_base ) {
			if ( ! empty( $url ) && strpos( $url, '//' ) === false ) {
				$url = $plugin_base . $url;
			}
		}

		public static function get_ext_sorted( $filter_cf = false, $lca_top = true ) {
			$idx = 'plugin';
			$ext = self::get_config( 'plugin', $filter_cf );
			uasort( $ext, array( 'self', 'sort_ext_by_name' ) );	// sort array and maintain index association
			if ( $lca_top && isset( $ext['wpsso'] ) ) {
				SucomUtil::move_to_front( $ext, 'wpsso' );
			}
			return $ext;
		}

		private static function sort_ext_by_name( $a, $b ) {
			if ( isset( $a['name'] ) && isset( $b['name'] ) ) {	// Just in case.
				return strcasecmp( $a['name'], $b['name'] );	// case-insensitive string comparison
			} else {
				return 0;					// no change
			}
		}

		public static function set_constants( $plugin_filepath ) {

			if ( defined( 'WPSSO_VERSION' ) ) {			// Execute and define constants only once.
				return;
			}

			/**
			 * Define fixed constants.
			 */
			define( 'WPSSO_VERSION', self::$cf['plugin']['wpsso']['version'] );						
			define( 'WPSSO_FILEPATH', $plugin_filepath );						
			define( 'WPSSO_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'WPSSO_PLUGINSLUG', self::$cf['plugin']['wpsso']['slug'] );		// wpsso
			define( 'WPSSO_PLUGINBASE', self::$cf['plugin']['wpsso']['base'] );		// wpsso/wpsso.php
			define( 'WPSSO_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );

			/**
			 * Define variable constants. Default values can be changed by defining 
			 * constants in the wp-config.php file.
			 */
			self::set_variable_constants();
		}

		public static function set_variable_constants( $var_const = null ) {

			if ( null === $var_const ) {
				$var_const = self::get_variable_constants();
			}

			foreach ( $var_const as $name => $value ) {
				if ( ! defined( $name ) ) {
					define( $name, $value );
				}
			}
		}

		public static function get_variable_constants() {

			$var_const = array();

			/**
			 * Create a unique md5 query name from the config array and the local wp nonce key.
			 */
			$var_const['WPSSO_NONCE_NAME'] = md5( var_export( self::$cf, true ) . 
				( defined( 'NONCE_KEY' ) ? NONCE_KEY : '' ) );

			if ( defined( 'WPSSO_PLUGINDIR' ) ) {
				$var_const['WPSSO_CACHEDIR'] = WPSSO_PLUGINDIR . 'cache/';
				$var_const['WPSSO_TOPICS_LIST'] = WPSSO_PLUGINDIR . 'share/topics.txt';
			}

			if ( defined( 'WPSSO_URLPATH' ) ) {
				$var_const['WPSSO_CACHEURL'] = WPSSO_URLPATH . 'cache/';
			}

			$var_const['WPSSO_MENU_ORDER'] = '99.10';			// Position of the SSO menu item.
			$var_const['WPSSO_TB_NOTICE_MENU_ORDER'] = '55';		// Position of the SSO notices toolbar menu item.
			$var_const['WPSSO_TB_LOCALE_MENU_ORDER'] = '60';		// Position of the user locale toolbar menu item.
			$var_const['WPSSO_TOOLBAR_NOTICES'] = true;			// Show error, warning, and info notices in the toolbar menu.
			$var_const['WPSSO_JSON_PRETTY_PRINT'] = false;			// Output pretty / human readable json.
			$var_const['WPSSO_UNDEF_INT'] = -1;				// Undefined image width / height value.

			$var_const['WPSSO_CONTENT_BLOCK_FILTER_OUTPUT'] = true;
			$var_const['WPSSO_CONTENT_FILTERS_MAX_TIME'] = 1.00;
			$var_const['WPSSO_CONTENT_IMAGES_MAX_LIMIT'] = 5;		// Maximum number of images extracted from the content.
			$var_const['WPSSO_CONTENT_VIDEOS_MAX_LIMIT'] = 5;		// Maximum number of videos extracted from the content.

			$var_const['WPSSO_DUPE_CHECK_HEADER_COUNT'] = 5;		// Maximum number of times to check for duplicates.
			$var_const['WPSSO_DUPE_CHECK_CLEAR_SHORTLINK'] = true;		// Clear / remove cache file before fetching shortlink url.
			$var_const['WPSSO_DUPE_CHECK_WARNING_TIME'] = 2.50;		// Issue a warning if getting shortlink took more than 2.5 seconds.
			$var_const['WPSSO_DUPE_CHECK_TIMEOUT_TIME'] = 3.00;		// Hard-limit - most crawlers time-out after 3 seconds.

			$var_const['WPSSO_GET_POSTS_MAX_TIME'] = 0.10;			// Send an error to trigger_error() if get_posts() takes longer.
			$var_const['WPSSO_PHP_GETIMGSIZE_MAX_TIME'] = 1.50;		// Send an error to trigger_error() if getimagesize() takes longer.

			/**
			 * Limits for some multi-options and Schema property arrays.
			 */
			$var_const['WPSSO_SCHEMA_ADDL_TYPE_URL_MAX'] = 5;
			$var_const['WPSSO_SCHEMA_EVENT_OFFERS_MAX'] = 10;
			$var_const['WPSSO_SCHEMA_HOWTO_STEPS_MAX'] = 90;
			$var_const['WPSSO_SCHEMA_HOWTO_SUPPLIES_MAX'] = 60;
			$var_const['WPSSO_SCHEMA_HOWTO_TOOLS_MAX'] = 30;
			$var_const['WPSSO_SCHEMA_RECIPE_INGREDIENTS_MAX'] = 60;
			$var_const['WPSSO_SCHEMA_RECIPE_INSTRUCTIONS_MAX'] = 90;
			$var_const['WPSSO_SCHEMA_POSTS_PER_PAGE_MAX'] = 10;
			$var_const['WPSSO_SCHEMA_POSTS_PER_SEARCH_MAX'] = 3;
			$var_const['WPSSO_SCHEMA_REVIEWS_PER_PAGE_MAX'] = 30;
			$var_const['WPSSO_SCHEMA_SAMEAS_URL_MAX'] = 5;
			$var_const['WPSSO_SCHEMA_BREADCRUMB_SCRIPTS_MAX'] = 5;

			/**
			 * WPSSO option and meta array names.
			 */
			$var_const['WPSSO_TS_NAME'] = 'wpsso_timestamps';
			$var_const['WPSSO_OPTIONS_NAME'] = 'wpsso_options';
			$var_const['WPSSO_SITE_OPTIONS_NAME'] = 'wpsso_site_options';
			$var_const['WPSSO_DISMISS_NAME'] = 'wpsso_dismissed';		// Dismissed notices.
			$var_const['WPSSO_META_NAME'] = '_wpsso_meta';			// Post meta.
			$var_const['WPSSO_PREF_NAME'] = '_wpsso_pref';			// User meta.
			$var_const['WPSSO_POST_CHECK_NAME'] = 'wpsso_post_head_count';	// Duplicate check counter.

			/**
			 * WPSSO option and meta array alternate names.
			 */
			$var_const['WPSSO_OPTIONS_NAME_ALT'] = 'ngfb_options';			// Fallback name.
			$var_const['WPSSO_SITE_OPTIONS_NAME_ALT'] = 'ngfb_site_options';	// Fallback name.
			$var_const['WPSSO_META_NAME_ALT'] = '_ngfb_meta';			// Fallback name.
			$var_const['WPSSO_PREF_NAME_ALT'] = '_ngfb_pref';			// Fallback name.

			/**
			 * WPSSO hook priorities.
			 */
			$var_const['WPSSO_ADD_MENU_PRIORITY'] = -20;
			$var_const['WPSSO_ADD_SUBMENU_PRIORITY'] = -10;
			$var_const['WPSSO_ADD_COLUMN_PRIORITY'] = 100;
			$var_const['WPSSO_META_SAVE_PRIORITY'] = 5;
			$var_const['WPSSO_META_CACHE_PRIORITY'] = 10;
			$var_const['WPSSO_INIT_PRIORITY'] = 12;
			$var_const['WPSSO_HEAD_PRIORITY'] = 10;
			$var_const['WPSSO_FOOTER_PRIORITY'] = 100;
			$var_const['WPSSO_SEO_FILTERS_PRIORITY'] = 100;

			/**
			 * WPSSO PHP cURL library settings.
			 */
			$var_const['WPSSO_PHP_CURL_CAINFO'] = ABSPATH . WPINC . '/certificates/ca-bundle.crt';
			$var_const['WPSSO_PHP_CURL_USERAGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:59.0) Gecko/20100101 Firefox/59.0';
			$var_const['WPSSO_PHP_CURL_USERAGENT_FACEBOOK'] = 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)';

			foreach ( $var_const as $name => $value ) {
				if ( defined( $name ) ) {
					$var_const[$name] = constant( $name );	// Inherit existing values.
				}
			}

			return $var_const;
		}

		public static function require_libs( $plugin_filepath ) {

			require_once WPSSO_PLUGINDIR . 'lib/com/cache.php';
			require_once WPSSO_PLUGINDIR . 'lib/com/nodebug.php';	// Always load fallback class.
			require_once WPSSO_PLUGINDIR . 'lib/com/nonotice.php';	// Always load fallback class.
			require_once WPSSO_PLUGINDIR . 'lib/com/util.php';

			require_once WPSSO_PLUGINDIR . 'lib/check.php';
			require_once WPSSO_PLUGINDIR . 'lib/exception.php';	// Extends exception.
			require_once WPSSO_PLUGINDIR . 'lib/filters.php';
			require_once WPSSO_PLUGINDIR . 'lib/functions.php';
			require_once WPSSO_PLUGINDIR . 'lib/head.php';
			require_once WPSSO_PLUGINDIR . 'lib/link_rel.php';
			require_once WPSSO_PLUGINDIR . 'lib/media.php';
			require_once WPSSO_PLUGINDIR . 'lib/meta.php';
			require_once WPSSO_PLUGINDIR . 'lib/opengraph.php';
			require_once WPSSO_PLUGINDIR . 'lib/options.php';
			require_once WPSSO_PLUGINDIR . 'lib/page.php';
			require_once WPSSO_PLUGINDIR . 'lib/post.php';		// Extends meta.php
			require_once WPSSO_PLUGINDIR . 'lib/register.php';
			require_once WPSSO_PLUGINDIR . 'lib/schema.php';
			require_once WPSSO_PLUGINDIR . 'lib/script.php';
			require_once WPSSO_PLUGINDIR . 'lib/style.php';
			require_once WPSSO_PLUGINDIR . 'lib/term.php';		// Extends meta.php
			require_once WPSSO_PLUGINDIR . 'lib/twittercard.php';
			require_once WPSSO_PLUGINDIR . 'lib/user.php';		// Extends meta.php
			require_once WPSSO_PLUGINDIR . 'lib/util.php';
			require_once WPSSO_PLUGINDIR . 'lib/weibo.php';

			if ( is_admin() ) {
				require_once WPSSO_PLUGINDIR . 'lib/messages.php';
				require_once WPSSO_PLUGINDIR . 'lib/admin.php';
				require_once WPSSO_PLUGINDIR . 'lib/com/form.php';
				require_once WPSSO_PLUGINDIR . 'lib/ext/parse-readme.php';
			}

			if ( file_exists( WPSSO_PLUGINDIR . 'lib/loader.php' ) ) {
				require_once WPSSO_PLUGINDIR . 'lib/loader.php';
			}

			add_filter( 'wpsso_load_lib', array( 'WpssoConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {
			if ( false === $ret && ! empty( $filespec ) ) {
				$filepath = WPSSO_PLUGINDIR.'lib/'.$filespec.'.php';
				if ( file_exists( $filepath ) ) {
					require_once $filepath;
					if ( empty( $classname ) ) {
						return SucomUtil::sanitize_classname( 'wpsso'.$filespec, false );	// $underscore = false
					} else {
						return $classname;
					}
				}
			}
			return $ret;
		}
	}
}
