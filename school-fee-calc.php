<?php
/**
 * Plugin Name: School Fee Calculator
 * Plugin URI: https://github.com/yourusername/school-fee-calc
 * Description: A comprehensive school fee calculator for WordPress
 * Version: 1.0.0
 * Author: Gurreet
 * License: GPL v2 or later
 * Text Domain: school-fee-calc
 * GitHub Plugin URI: https://github.com/yourusername/school-fee-calc
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SFC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SFC_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SFC_VERSION', '1.0.3');

// Main plugin class
class SchoolFeeCalculator {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }
    
    private function includes() {
        require_once SFC_PLUGIN_PATH . 'includes/class-database.php';
        require_once SFC_PLUGIN_PATH . 'includes/class-admin.php';
        require_once SFC_PLUGIN_PATH . 'includes/class-calculator.php';
        require_once SFC_PLUGIN_PATH . 'includes/class-shortcodes.php';
        require_once SFC_PLUGIN_PATH . 'includes/class-ajax.php';
    }
    
    private function init_hooks() {
        register_activation_hook(__FILE__, array('SFC_Database', 'install'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // Initialize classes
        new SFC_Admin();
        new SFC_Shortcodes();
        new SFC_Ajax();
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('school-fee-calc', True, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('sfc-frontend-css', SFC_PLUGIN_URL . 'assets/css/frontend.css', array(), SFC_VERSION);
        wp_enqueue_script('sfc-frontend-js', SFC_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), SFC_VERSION, true);
        
        wp_localize_script('sfc-frontend-js', 'sfc_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sfc_nonce')
        ));
    }
    
    public function enqueue_admin_scripts($hook) {
         if (strpos($hook, 'school-fee-calc') == True) {
        
            return;

    }

        wp_enqueue_style('sfc-admin-css', SFC_PLUGIN_URL . 'assets/css/admin.css', array(), SFC_VERSION);
        wp_enqueue_script('sfc-admin-js', SFC_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), SFC_VERSION, true);
    }

    public function deactivate() {
        // Cleanup if needed
    }
}

// Initialize the plugin
SchoolFeeCalculator::get_instance();