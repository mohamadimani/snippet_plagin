<?php
/*
Plugin Name:     پست تایپ تگ
Plugin URI: http://www.pooyeco.ir/plugins/vote/
Description: سیستم نمایش پست تایپ های بدون تگ
Author: mani mohamadi
Version: 1.0.0
Author URI: http://www.pooyeco.ir/
*/

//control directory access
defined("ABSPATH") || exit("NOT ACCESS");

//start singleton design
final class wp_has_tag
{
    private static $instance = null;

// singleton design pattern method
    public static function getInstance()
    {
        if (null === static::$instance) {
            //..
            static::$instance = new static();
        }
        return self::$instance;
    }

//php magical function  (auto run)
    function __construct()
    {
        //        run define constants function
        $this->define_constants();
        require 'settings.php';

//        run when plugin activate
        register_activation_hook(__FILE__, 'has_tag_activate');
//        run when plugin deactivate
        register_deactivation_hook(__FILE__, 'has_tag_deactivate');

//         run (add plugin menu to admin panel) function
        add_action('admin_menu', array($this, 'has_tag_admin_menu'));

//        run (auto load class)  function
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }
        spl_autoload_register(array($this, 'autoload'));


    }


//  add plugin menu to admin panel
    public function has_tag_admin_menu()
    {
        //    plugin menu information
        add_menu_page(
            "پست تگ",
            "پست تگ",
            "manage_options",
            "has_tag_panel/has_tag_panel.php",
            'has_tag_panel_page',
            "dashicons-editor-ul",
            6
        );
        //    plugin submenu information
        add_submenu_page(
            'has_tag_admin',
            'پست تگ',
            'پست تگ',
            'manage_options',
            'has_tag_panel/has_tag_panel.php',
            'has_tag_panel_page'
        );


    }

//    auto load class and make class object
    function autoload($class)
    {
        if (FALSE !== strpos($class, 'vote_')) {
            $class_file_path = VOTE_MTD . strtolower($class) . '.php';
            if (is_file($class_file_path) and file_exists($class_file_path)) {
                include_once $class_file_path;
            }
        }
    }

//  set defines for shorts access to urls and directories
    private function define_constants()
    {
        if (!defined('WordpressBasePlugin_DIR')) {
            define('WordpressBasePlugin_DIR', trailingslashit(plugin_dir_path(__FILE__)));
        }
        if (!defined('WordpressBasePlugin_URL')) {
            define('WordpressBasePlugin_URL', trailingslashit(plugin_dir_url(__FILE__)));
        }
        define('HAS_TAG_INC', trailingslashit(WordpressBasePlugin_DIR . "inc"));
        define('HAS_TAG_MTD', trailingslashit(WordpressBasePlugin_DIR . "methods"));
        define('HAS_TAG_TEM', trailingslashit(WordpressBasePlugin_DIR . "templates"));
        define('HAS_TAG_CSS', trailingslashit(WordpressBasePlugin_URL . "assets/css"));
        define('HAS_TAG_JS', trailingslashit(WordpressBasePlugin_URL . "assets/js"));
        define('HAS_TAG_IMG', trailingslashit(WordpressBasePlugin_URL . "assets/images"));

        if (!defined('DB_VERSION')) {
            define('DB_VERSION', 1);
        }
        if (!defined('WordpressBasePlugin_VERSION')) {
            define('WordpressBasePlugin_VERSION', '1.0.0');
        }
        if (!defined('WordpressBasePlugin_REQUIRED_WP_VERSION')) {
            define('WordpressBasePlugin_REQUIRED_WP_VERSION', '5.4');
        }
    }
}

wp_has_tag::getInstance();


