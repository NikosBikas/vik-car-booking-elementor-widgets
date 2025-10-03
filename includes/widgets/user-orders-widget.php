<?php
/**
 * VikRentCar User Orders Widget
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * User Orders Widget Class
 */
class VRC_User_Orders_Widget extends VRC_Base_Widget {
    
    /**
     * Get widget name
     */
    public function get_name() {
        return 'vrc-user-orders';
    }
    
    /**
     * Get widget title
     */
    public function get_title() {
        return __('My Bookings', 'vik-car-booking-elementor-widgets');
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-user';
    }
    
    /**
     * Get widget description
     */
    public function get_description() {
        return __('Display user orders and booking history from VikRentCar.', 'vik-car-booking-elementor-widgets');
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
        
        // Search Order
        $this->add_control(
            'searchorder',
            array(
                'label' => __('Enable Order Search', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'vik-car-booking-elementor-widgets'),
                'label_off' => __('No', 'vik-car-booking-elementor-widgets'),
                'return_value' => '1',
                'default' => '1',
                'description' => __('Allow users to search for their orders by order ID.', 'vik-car-booking-elementor-widgets'),
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
                'description' => __('Add custom CSS to style the user orders.', 'vik-car-booking-elementor-widgets'),
            )
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Build shortcode
        $shortcode = '[vikrentcar view=userorders lang=*';
        
        if (!empty($settings['searchorder'])) {
            $shortcode .= ' searchorder=' . esc_attr($settings['searchorder']);
        }
        
        $shortcode .= ']';
        
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
                <?php _e('User orders will be displayed when users are logged in.', 'vik-car-booking-elementor-widgets'); ?>
            </div>
        </div>
        <?php
    }
}
