<?php
/**
 * Plugin Name: Vik Rental Car Widgets by The Creators
 * Plugin URI: https://thecreators.gr
 * Description: Professional car rental widgets for Elementor - Car Search Form, Cars List, Car Details, Car Availability, Rental Locations, Car Promotions, and My Bookings.
 * Version: 1.0.0
 * Author: The Creators
 * Author URI: https://thecreators.gr
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: vik-car-booking-elementor-widgets
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('VRC_ELEMENTOR_WIDGETS_VERSION', '1.0.0');
define('VRC_ELEMENTOR_WIDGETS_PATH', plugin_dir_path(__FILE__));
define('VRC_ELEMENTOR_WIDGETS_URL', plugin_dir_url(__FILE__));
define('VRC_ELEMENTOR_WIDGETS_BASENAME', plugin_basename(__FILE__));

/**
 * Main VikRentCar Elementor Widgets Class
 */
class VRC_Elementor_Widgets {
    
    /**
     * Single instance of the class
     */
    private static $instance = null;
    
    /**
     * Get single instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }
    
    /**
     * Initialize the plugin
     */
    public function init() {
        // Check if Elementor is active
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', array($this, 'elementor_missing_notice'));
            return;
        }
        
        // Check if VikRentCar is active
        if (!defined('VIKRENTCAR_SOFTWARE_VERSION')) {
            add_action('admin_notices', array($this, 'vikrentcar_missing_notice'));
            return;
        }
        
        // Register Elementor widgets
        add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
        
        // Add plugin links
        add_filter('plugin_action_links_' . VRC_ELEMENTOR_WIDGETS_BASENAME, array($this, 'add_plugin_links'));
    }
    
    /**
     * Load plugin files
     */
    public function load_files() {
        // Only load widget files if Elementor is available
        if (class_exists('\Elementor\Widget_Base')) {
            require_once VRC_ELEMENTOR_WIDGETS_PATH . 'includes/base-widget.php';
            require_once VRC_ELEMENTOR_WIDGETS_PATH . 'includes/widgets/search-form-widget.php';
            require_once VRC_ELEMENTOR_WIDGETS_PATH . 'includes/widgets/cars-list-widget.php';
            require_once VRC_ELEMENTOR_WIDGETS_PATH . 'includes/widgets/search-results-widget.php';
            require_once VRC_ELEMENTOR_WIDGETS_PATH . 'includes/widgets/car-details-widget.php';
            require_once VRC_ELEMENTOR_WIDGETS_PATH . 'includes/widgets/availability-widget.php';
            require_once VRC_ELEMENTOR_WIDGETS_PATH . 'includes/widgets/locations-list-widget.php';
            require_once VRC_ELEMENTOR_WIDGETS_PATH . 'includes/widgets/promotions-widget.php';
            require_once VRC_ELEMENTOR_WIDGETS_PATH . 'includes/widgets/user-orders-widget.php';
        }
    }
    
    /**
     * Register Elementor widgets
     */
    public function register_widgets($widgets_manager) {
        // Load files first
        $this->load_files();
        
        // Register widgets only if classes exist
        if (class_exists('VRC_Search_Form_Widget')) {
            $widgets_manager->register_widget_type(new VRC_Search_Form_Widget());
        }
        if (class_exists('VRC_Cars_List_Widget')) {
            $widgets_manager->register_widget_type(new VRC_Cars_List_Widget());
        }
        if (class_exists('VRC_Search_Results_Widget')) {
            $widgets_manager->register_widget_type(new VRC_Search_Results_Widget());
        }
        if (class_exists('VRC_Car_Details_Widget')) {
            $widgets_manager->register_widget_type(new VRC_Car_Details_Widget());
        }
        if (class_exists('VRC_Availability_Widget')) {
            $widgets_manager->register_widget_type(new VRC_Availability_Widget());
        }
        if (class_exists('VRC_Locations_List_Widget')) {
            $widgets_manager->register_widget_type(new VRC_Locations_List_Widget());
        }
        if (class_exists('VRC_Promotions_Widget')) {
            $widgets_manager->register_widget_type(new VRC_Promotions_Widget());
        }
        if (class_exists('VRC_User_Orders_Widget')) {
            $widgets_manager->register_widget_type(new VRC_User_Orders_Widget());
        }
    }
    
    /**
     * Elementor missing notice
     */
    public function elementor_missing_notice() {
        echo '<div class="notice notice-error"><p>';
        echo '<strong>VikRentCar Elementor Widgets</strong> requires <strong>Elementor</strong> plugin to be installed and activated.';
        echo '</p></div>';
    }
    
    /**
     * VikRentCar missing notice
     */
    public function vikrentcar_missing_notice() {
        echo '<div class="notice notice-error"><p>';
        echo '<strong>VikRentCar Elementor Widgets</strong> requires <strong>VikRentCar</strong> plugin to be installed and activated.';
        echo '</p></div>';
    }
    
    /**
     * Add plugin links
     */
    public function add_plugin_links($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=vikrentcar') . '">' . __('VikRentCar Settings', 'vik-car-booking-elementor-widgets') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}

// Initialize the plugin
VRC_Elementor_Widgets::get_instance();
