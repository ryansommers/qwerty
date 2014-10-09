<?php
/**
 * qwerty functions and definitions
 *
 * @package qwerty
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'qwerty_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function qwerty_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on qwerty, use a find and replace
	 * to change 'qwerty' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'qwerty', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'qwerty' ),
		) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
		) );

}
endif; // qwerty_setup
add_action( 'after_setup_theme', 'qwerty_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function qwerty_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer', 'qwerty' ),
		'id'            => 'footer-1',
		'description'   => 'Footer Items',
		'before_widget'  => '<div id="%1$s" class="footer-widget %2$s '. qwerty_slbd_count_widgets( 'footer-1' ) .'">', 
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
		) );
}
add_action( 'widgets_init', 'qwerty_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function qwerty_scripts() {
	wp_enqueue_style( 'qwerty-style', get_stylesheet_uri() );

	wp_enqueue_style( 'google-fonts', 'http://fonts.googleapis.com/css?family=Lato:400,400italic|Merriweather:400,400italic,700,700italic' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'qwerty_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

// Remove <p> tags from images in the content.
function qwerty_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', 'qwerty_filter_ptags_on_images');

/**
	* From https://gist.github.com/slobodan/6156076
	* Count number of widgets in a sidebar
	* Used to add classes to widget areas so widgets can bedisplayed one, two, three or four per row
*/
function qwerty_slbd_count_widgets( $sidebar_id ) {
	// If loading from front page, consult $_wp_sidebars_widgets rather than options
	// to see if wp_convert_widget_settings() has made manipulations in memory.
	global $_wp_sidebars_widgets;
	if ( empty( $_wp_sidebars_widgets ) ) :
		$_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
	endif;
	
	$sidebars_widgets_count = $_wp_sidebars_widgets;
	
	if ( isset( $sidebars_widgets_count[ $sidebar_id ] ) ) :
		$widget_count = count( $sidebars_widgets_count[ $sidebar_id ] );
	$widget_classes = 'widget-count-' . count( $sidebars_widgets_count[ $sidebar_id ] );
	if ( $widget_count % 4 == 0 || $widget_count > 6 ) :
			// Four widgets er row if there are exactly four or more than six
		$widget_classes .= ' per-row-4';
	elseif ( $widget_count >= 3 ) :
			// Three widgets per row if there's three or more widgets 
		$widget_classes .= ' per-row-3';
	elseif ( 2 == $widget_count ) :
			// Otherwise show two widgets per row
		$widget_classes .= ' per-row-2';
	endif; 

	return $widget_classes;
	endif;
}

// From http://www.visiv.ca/wordpress-wp-nav-select-menus/
class qwerty_select_menu_walker extends Walker_Nav_Menu{
	 
	 function start_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "";
	}

	function end_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "";
	}

	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		
		$class_names = $value = '';
		
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
		
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
		
		//check if current page is selected page and add selected value to select element  
		$selc = '';
		$curr_class = 'current-menu-item';
		$is_current = strpos($class_names, $curr_class);
		if($is_current === false){
			$selc = "";
		}else{
			$selc = "selected ";
		}
		
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		
		$sel_val =  ' value="'   . esc_attr( $item->url        ) .'"';
		
		//check if the menu is a submenu
		switch ($depth){
			case 0:
			$dp = "";
			break;
			case 1:
			$dp = "- ";
			break;
			case 2:
			$dp = "-- ";
			break;
			case 3:
			$dp = "--- ";
			break;
			case 4:
			$dp = "---- ";
			break;
			default:
			$dp = "";
		}
		
		$output .= $indent . '<option'. $sel_val . $id . $value . $class_names . $selc . '>'.$dp;
		
		$item_output = $args->before;
		//$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		//$item_output .= '</a>';
		$item_output .= $args->after;
		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	
	function end_el(&$output, $item, $depth) {
		$output .= "</option>\n";
	}
}

?>