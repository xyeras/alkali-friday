<?php
/**
 * alkali-friday functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package alkali-friday
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'alkali_friday_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function alkali_friday_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on alkali-friday, use a find and replace
		 * to change 'alkali-friday' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'alkali-friday', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

	// bootstrap 5 wp_nav_menu walker
	class bootstrap_5_wp_nav_menu_walker extends Walker_Nav_menu
	{
		private $current_item;
		private $dropdown_menu_alignment_values = [
			'dropdown-menu-start',
			'dropdown-menu-end',
			'dropdown-menu-sm-start',
			'dropdown-menu-sm-end',
			'dropdown-menu-md-start',
			'dropdown-menu-md-end',
			'dropdown-menu-lg-start',
			'dropdown-menu-lg-end',
			'dropdown-menu-xl-start',
			'dropdown-menu-xl-end',
			'dropdown-menu-xxl-start',
			'dropdown-menu-xxl-end'
		];
		function start_lvl(&$output, $depth = 0, $args = null)
		{
			$dropdown_menu_class[] = '';
			foreach($this->current_item->classes as $class) {
			if(in_array($class, $this->dropdown_menu_alignment_values)) {
				$dropdown_menu_class[] = $class;
			}
			}
			$indent = str_repeat("\t", $depth);
			$submenu = ($depth > 0) ? ' sub-menu' : '';
			$output .= "\n$indent<ul class=\"dropdown-menu$submenu " . esc_attr(implode(" ",$dropdown_menu_class)) . " depth_$depth\">\n";
		}
		function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
		{
			$this->current_item = $item;
			$indent = ($depth) ? str_repeat("\t", $depth) : '';
			$li_attributes = '';
			$class_names = $value = '';
			$classes = empty($item->classes) ? array() : (array) $item->classes;
			$classes[] = ($args->walker->has_children) ? 'dropdown' : '';
			$classes[] = 'nav-item';
			$classes[] = 'nav-item-' . $item->ID;
			if ($depth && $args->walker->has_children) {
			$classes[] = 'dropdown-menu dropdown-menu-end';
			}
			$class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
			$class_names = ' class="' . esc_attr($class_names) . '"';
			$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
			$id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';
			$output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';
			$attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
			$attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
			$attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
			$attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
			$active_class = ($item->current || $item->current_item_ancestor) ? 'active' : '';
			$nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link ';
			$attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';
			$item_output = $args->before;
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;
			$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
		}
	}

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'alkali-friday' ),
				'header-menu' => 'Main Menu'
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'alkali_friday_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'alkali_friday_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function alkali_friday_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'alkali_friday_content_width', 640 );
}
add_action( 'after_setup_theme', 'alkali_friday_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function alkali_friday_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'alkali-friday' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'alkali-friday' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'alkali_friday_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function alkali_friday_scripts() {
	
	// ENQUEUE BOOSTRAP AND FONT AWESOME
	wp_enqueue_style('alkali-friday-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '5.0.1', 'all');
	wp_enqueue_style('alkali-friday-fontawesome', get_template_directory_uri() . '/css/all.min.css', array(), '5.15.3', 'all');
	
	// UNDERSCORES STYLE ENQUEUE
	wp_enqueue_style( 'alkali-friday-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'alkali-friday-style', 'rtl', 'replace' );

	// ENQUEUE BOOTSTRAP JS FILE
	wp_enqueue_script('alkali-friday-bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '5.0.1', true);
	
	// ENQUEUE UNDERSCORE SCRIPT ENQUEUE
	wp_enqueue_script( 'alkali-friday-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'alkali_friday_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Register Custom Navigation Walker
 */
function register_navwalker(){
    require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
}
add_action( 'after_setup_theme', 'register_navwalker' );

