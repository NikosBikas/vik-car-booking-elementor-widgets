<?php
/**
 * Base Widget Class for VikRentCar Elementor Widgets
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Base class for all VikRentCar Elementor widgets
 */
abstract class VRC_Base_Widget extends \Elementor\Widget_Base {
    
    /**
     * Get widget categories
     */
    public function get_categories() {
        return array('vikrentcar');
    }
    
    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return array('vikrentcar', 'car', 'rental', 'booking', 'search', 'cars', 'availability');
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-car';
    }
    
    /**
     * Check if VikRentCar is available
     */
    protected function is_vikrentcar_available() {
        return defined('VIKRENTCAR_SOFTWARE_VERSION');
    }
    
    /**
     * Get VikRentCar version
     */
    protected function get_vikrentcar_version() {
        return defined('VIKRENTCAR_SOFTWARE_VERSION') ? VIKRENTCAR_SOFTWARE_VERSION : 'Not Available';
    }
    
    /**
     * Execute VikRentCar shortcode safely
     */
    protected function execute_vrc_shortcode($shortcode) {
        if (!$this->is_vikrentcar_available()) {
            return '<div class="vrc-error">VikRentCar is not available.</div>';
        }
        
        try {
            $output = do_shortcode($shortcode);
            
            // Check if shortcode was processed successfully
            if (empty($output) || $output === $shortcode || strpos($output, '[vikrentcar') !== false) {
                return '<div class="vrc-error">VikRentCar shortcode failed to execute properly.</div>';
            }
            
            return $output;
            
        } catch (Exception $e) {
            return '<div class="vrc-error">VikRentCar shortcode error: ' . esc_html($e->getMessage()) . '</div>';
        } catch (Error $e) {
            return '<div class="vrc-error">VikRentCar shortcode fatal error: ' . esc_html($e->getMessage()) . '</div>';
        }
    }
    
    /**
     * Add debug information for admins
     */
    protected function add_debug_info($shortcode, $settings = array()) {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        echo '<div class="vrc-debug-info" style="background: #e7f3ff; border: 1px solid #b3d9ff; padding: 10px; margin: 10px 0; border-radius: 4px; font-size: 12px;">';
        echo '<strong>VikRentCar Widget Debug:</strong><br>';
        echo '<strong>Widget:</strong> ' . esc_html($this->get_title()) . '<br>';
        echo '<strong>Shortcode:</strong> ' . esc_html($shortcode) . '<br>';
        echo '<strong>VikRentCar Version:</strong> ' . esc_html($this->get_vikrentcar_version()) . '<br>';
        
        if (!empty($settings)) {
            echo '<strong>Settings:</strong> ' . esc_html(json_encode($settings, JSON_PRETTY_PRINT));
        }
        
        echo '</div>';
    }
    
    /**
     * Get categories from VikRentCar database
     */
    protected function get_vrc_categories() {
        global $wpdb;
        
        $categories = array();
        
        if ($this->is_vikrentcar_available()) {
            $table_name = $wpdb->prefix . 'vikrentcar_categories';
            
            if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
                $results = $wpdb->get_results("SELECT `id`, `name` FROM `{$table_name}` ORDER BY `ordering` ASC, `name` ASC");
                
                if ($results) {
                    foreach ($results as $category) {
                        $categories[] = array(
                            'id' => $category->id,
                            'name' => $category->name
                        );
                    }
                }
            }
        }
        
        return $categories;
    }
    
    /**
     * Get cars from VikRentCar database
     */
    protected function get_cars() {
        global $wpdb;
        
        $cars = array();
        
        if ($this->is_vikrentcar_available()) {
            $table_name = $wpdb->prefix . 'vikrentcar_cars';
            
            if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
                $results = $wpdb->get_results("SELECT `id`, `name` FROM `{$table_name}` WHERE `avail` = 1 ORDER BY `name` ASC");
                
                if ($results) {
                    foreach ($results as $car) {
                        $cars[] = array(
                            'id' => $car->id,
                            'name' => $car->name
                        );
                    }
                }
            }
        }
        
        return $cars;
    }
    
    /**
     * Get locations from VikRentCar database
     */
    protected function get_locations() {
        global $wpdb;
        
        $locations = array();
        
        if ($this->is_vikrentcar_available()) {
            $table_name = $wpdb->prefix . 'vikrentcar_places';
            
            if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
                $results = $wpdb->get_results("SELECT `id`, `name` FROM `{$table_name}` ORDER BY `ordering` ASC, `name` ASC");
                
                if ($results) {
                    foreach ($results as $location) {
                        $locations[] = array(
                            'id' => $location->id,
                            'name' => $location->name
                        );
                    }
                }
            }
        }
        
        return $locations;
    }
}
