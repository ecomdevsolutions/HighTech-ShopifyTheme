<?php

define('RR_PLUGIN_PATH',      plugin_dir_path( __FILE__ ));
define('RR_PLUGIN_URL',       plugin_dir_url( __FILE__ ));
define('RR_TEMPLATE_DIR_URL', get_template_directory_uri());
define('RR_TEMPLATE_DIR',     get_template_directory());

/**
 * RR
 *
 * @since 0.1
 */

class RR {

    /**
     * Initialized flag
     *
     * @since 0.1
     */
    private static $initialized = false;

    /**
     * Initialize plugin
     *
     * @since 0.1
     */
    public static function initialize () {
        self::initialize_hooks();
    }

    /**
     * Initialize action and filter hooks
     *
     * @since 0.1
     */
    private static function initialize_hooks () {
        self::$initialized = true;

        add_action('init',                           array('RR', 'init'));
        add_action('after_setup_theme',              array('RR', 'after_setup_theme'));
        add_action('wp_enqueue_scripts',             array('RR', 'wp_enqueue_scripts'));
        
        add_filter('nav_menu_css_class',             array('RR', 'nav_menu_css_class'), 10, 2);
        add_filter('show_admin_bar',                 '__return_false');
        add_filter('body_class',                     array('RR', 'body_class'));
        
        add_shortcode('row',                         array('RR', 'row_shortcode'));
        add_shortcode('col',                         array('RR', 'col_shortcode'));
    }

    /**
     * General Initialization
     *
     * @since 0.1
     */
    public static function init () {        

    }

    public static function body_class ( $classes ) {
        global $post;
        
        if ( is_page() ) {
            $classes[]= 'page--' . $post->post_name;
        }        
        
        return $classes;
    }

    /**
     * After setup theme
     *
     * @since 0.1
     */
    public static function after_setup_theme () {
        add_theme_support( 'post-thumbnails' );

        add_image_size('xlarge', 1800, 1800);

        register_nav_menus(
            array(
                'main_menu' => __('Main Menu'),
                'footer_menu' => __('Footer Menu'),
                'footer_sub_menu' => __('Footer Sub Menu'),                
                'social_menu' => __('Social Menu')
            )
        );               

        if ( function_exists('acf_add_options_page') ) {
            acf_add_options_page();
        }
    }

    public static function nav_menu_css_class ( $classes, $item ) {
        $item_host = parse_url($item->url, PHP_URL_HOST);
        $site_host = parse_url(get_home_url(), PHP_URL_HOST);

        if ( $item_host == $site_host ) {
            $classes[]= 'active';
        }
        
        return $classes;
    }    

    /**
     * Shortcode for row divs
     *
     * @since 0.1
     */
    public static function row_shortcode( $attrs, $content=null ) {
        return "<div class='row'>" . do_shortcode($content) . "</div>";
    }

    /**
     * Shortcode for column divs
     *
     * @since 0.1
     */
    public static function col_shortcode( $attrs, $content=null ) {
        $class = "";

        foreach ( $attrs as $k => $v ) {
            $class .= 'col-' . $v . ' ';
        }

        return "<div class='$class'>" . $border . do_shortcode($content) . "</div>";
    }

    /**
     * Enqueue client side javascript and css
     *
     * @since 0.1
     */
    public static function wp_enqueue_scripts () {
        wp_register_style(
            'application', 
            RR_TEMPLATE_DIR_URL . '/assets/css/application.css', 
            null, 
            filemtime(RR_TEMPLATE_DIR . '/assets/css/application.css')
        );

        wp_register_script(
            'application',
            RR_TEMPLATE_DIR_URL . '/assets/js/application.js',
            null,
            filemtime(RR_TEMPLATE_DIR . '/assets/js/application.js')
        );

        wp_enqueue_style('application');
        wp_enqueue_script('application');
    }

}

RR::Initialize();
