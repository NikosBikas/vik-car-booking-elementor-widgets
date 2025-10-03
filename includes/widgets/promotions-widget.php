<?php
/**
 * VikRentCar Promotions Widget
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Promotions Widget Class
 */
class VRC_Promotions_Widget extends VRC_Base_Widget {
    
    /**
     * Get widget name
     */
    public function get_name() {
        return 'vrc-promotions';
    }
    
    /**
     * Get widget title
     */
    public function get_title() {
        return __('Car Promotions', 'vik-car-booking-elementor-widgets');
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-price-tag';
    }
    
    /**
     * Get widget description
     */
    public function get_description() {
        return __('Display active promotions and special offers from VikRentCar.', 'vik-car-booking-elementor-widgets');
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
        
        // Show Cars
        $this->add_control(
            'showcars',
            array(
                'label' => __('Show Cars', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'vik-car-booking-elementor-widgets'),
                'label_off' => __('No', 'vik-car-booking-elementor-widgets'),
                'return_value' => '1',
                'default' => '1',
                'description' => __('Show cars associated with promotions.', 'vik-car-booking-elementor-widgets'),
            )
        );
        
        // Max Date
        $this->add_control(
            'maxdate',
            array(
                'label' => __('Max Date Range', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '3' => __('3 Months', 'vik-car-booking-elementor-widgets'),
                    '6' => __('6 Months', 'vik-car-booking-elementor-widgets'),
                    '12' => __('1 Year', 'vik-car-booking-elementor-widgets'),
                ),
                'default' => '6',
                'description' => __('Maximum date range to show promotions for.', 'vik-car-booking-elementor-widgets'),
            )
        );
        
        // Limit
        $this->add_control(
            'lim',
            array(
                'label' => __('Number of Promotions', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 1,
                'max' => 50,
                'description' => __('Maximum number of promotions to display.', 'vik-car-booking-elementor-widgets'),
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
                'description' => __('Add custom CSS to style the promotions.', 'vik-car-booking-elementor-widgets'),
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
        $shortcode = '[vikrentcar view=promotions lang=*';
        
        if (!empty($settings['showcars'])) {
            $shortcode .= ' showcars=' . esc_attr($settings['showcars']);
        }
        
        if (!empty($settings['maxdate'])) {
            $shortcode .= ' maxdate=' . esc_attr($settings['maxdate']);
        }
        
        if (!empty($settings['lim'])) {
            $shortcode .= ' lim=' . esc_attr($settings['lim']);
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
                <?php _e('The actual VikRentCar promotions will be displayed on the frontend.', 'vik-car-booking-elementor-widgets'); ?>
            </div>
        </div>
        <?php
    }
}
