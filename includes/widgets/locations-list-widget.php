<?php
/**
 * VikRentCar Locations List Widget
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Locations List Widget Class
 */
class VRC_Locations_List_Widget extends VRC_Base_Widget {
    
    /**
     * Get widget name
     */
    public function get_name() {
        return 'vrc-locations-list';
    }
    
    /**
     * Get widget title
     */
    public function get_title() {
        return __('Rental Locations', 'vik-car-booking-elementor-widgets');
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-google-maps';
    }
    
    /**
     * Get widget description
     */
    public function get_description() {
        return __('Display a list of rental locations from VikRentCar.', 'vik-car-booking-elementor-widgets');
    }
    
    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            array(
                'label' => __('Content', 'vik-car-booking-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );
        
        $this->add_control(
            'info_note',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="background: #e7f3ff; border: 1px solid #b3d9ff; padding: 10px; border-radius: 4px;"><strong>Note:</strong> This widget displays all rental locations configured in VikRentCar. No additional parameters are needed.</div>',
            )
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            array(
                'label' => __('Style', 'vik-car-booking-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
        $this->add_control(
            'custom_css',
            array(
                'label' => __('Custom CSS', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'description' => __('Add custom CSS to style the locations list.', 'vik-car-booking-elementor-widgets'),
            )
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Build shortcode - locations list doesn't need parameters
        $shortcode = '[vikrentcar view=locationslist lang=*]';
        
        // Add debug info for admins
        $this->add_debug_info($shortcode, $settings);
        
        // Execute shortcode
        $output = $this->execute_vrc_shortcode($shortcode);
        
        // Add custom CSS if provided
        if (!empty($settings['custom_css'])) {
            echo '<style>' . $settings['custom_css'] . '</style>';
        }
        
        echo $output;
    }
    
    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <div class="vrc-widget-preview">
            <h3><?php echo esc_html($this->get_title()); ?></h3>
            <p><?php echo esc_html($this->get_description()); ?></p>
            <div class="vrc-preview-note">
                <strong><?php _e('Preview:', 'vik-car-booking-elementor-widgets'); ?></strong>
                <?php _e('The actual VikRentCar locations list will be displayed on the frontend.', 'vik-car-booking-elementor-widgets'); ?>
            </div>
        </div>
        <?php
    }
}
