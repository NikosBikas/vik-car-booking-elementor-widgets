<?php
/**
 * VikRentCar Car Details Widget
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Car Details Widget Class
 */
class VRC_Car_Details_Widget extends VRC_Base_Widget {
    
    /**
     * Get widget name
     */
    public function get_name() {
        return 'vrc-car-details';
    }
    
    /**
     * Get widget title
     */
    public function get_title() {
        return __('Car Details', 'vik-car-booking-elementor-widgets');
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-info-circle';
    }
    
    /**
     * Get widget description
     */
    public function get_description() {
        return __('Display detailed information about a specific car from VikRentCar.', 'vik-car-booking-elementor-widgets');
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
        
        // Car ID
        $cars = $this->get_cars();
        $car_options = array();
        foreach ($cars as $car) {
            $car_options[$car['id']] = $car['name'];
        }
        
        $this->add_control(
            'carid',
            array(
                'label' => __('Select Car', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $car_options,
                'default' => !empty($car_options) ? array_keys($car_options)[0] : '',
                'description' => __('Select the car to display details for.', 'vik-car-booking-elementor-widgets'),
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
                'description' => __('Add custom CSS to style the car details.', 'vik-car-booking-elementor-widgets'),
            )
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Check if car ID is selected
        if (empty($settings['carid'])) {
            echo '<div class="vrc-error">Please select a car to display details.</div>';
            return;
        }
        
        // Build shortcode
        $shortcode = '[vikrentcar view=cardetails lang=* carid=' . esc_attr($settings['carid']) . ']';
        
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
                <?php _e('The actual VikRentCar car details will be displayed on the frontend.', 'vik-car-booking-elementor-widgets'); ?>
            </div>
        </div>
        <?php
    }
}
