<?php
/**
 * VikRentCar Availability Widget
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Availability Widget Class
 */
class VRC_Availability_Widget extends VRC_Base_Widget {
    
    /**
     * Get widget name
     */
    public function get_name() {
        return 'vrc-availability';
    }
    
    /**
     * Get widget title
     */
    public function get_title() {
        return __('Car Availability', 'vik-car-booking-elementor-widgets');
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-calendar';
    }
    
    /**
     * Get widget description
     */
    public function get_description() {
        return __('Display car availability calendar from VikRentCar.', 'vik-car-booking-elementor-widgets');
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
        
        // Car IDs
        $cars = $this->get_cars();
        $car_options = array();
        foreach ($cars as $car) {
            $car_options[$car['id']] = $car['name'];
        }
        
        $this->add_control(
            'car_ids',
            array(
                'label' => __('Select Cars', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $car_options,
                'multiple' => true,
                'description' => __('Select one or more cars to show availability for.', 'vik-car-booking-elementor-widgets'),
            )
        );
        
        // Show Type
        $this->add_control(
            'showtype',
            array(
                'label' => __('Show Type', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '1' => __('None', 'vik-car-booking-elementor-widgets'),
                    '2' => __('Remaining', 'vik-car-booking-elementor-widgets'),
                    '3' => __('Booked', 'vik-car-booking-elementor-widgets'),
                ),
                'default' => '2',
                'description' => __('What to show in the availability calendar.', 'vik-car-booking-elementor-widgets'),
            )
        );
        
        // Sort By
        $this->add_control(
            'sortby',
            array(
                'label' => __('Sort By', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => __('Price', 'vik-car-booking-elementor-widgets'),
                    'name' => __('Name', 'vik-car-booking-elementor-widgets'),
                    'id' => __('ID', 'vik-car-booking-elementor-widgets'),
                ),
                'default' => '',
            )
        );
        
        // Sort Type
        $this->add_control(
            'sorttype',
            array(
                'label' => __('Sort Type', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'asc' => __('Ascending', 'vik-car-booking-elementor-widgets'),
                    'desc' => __('Descending', 'vik-car-booking-elementor-widgets'),
                ),
                'default' => 'asc',
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
                'description' => __('Add custom CSS to style the availability calendar.', 'vik-car-booking-elementor-widgets'),
            )
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Check if car IDs are selected
        if (empty($settings['car_ids'])) {
            echo '<div class="vrc-error">Please select at least one car to show availability.</div>';
            return;
        }
        
        // Build shortcode
        $shortcode = '[vikrentcar view=availability lang=*';
        
        if (!empty($settings['car_ids'])) {
            $car_ids = is_array($settings['car_ids']) ? implode(',', $settings['car_ids']) : $settings['car_ids'];
            $shortcode .= ' car_ids=' . esc_attr($car_ids);
        }
        
        if (!empty($settings['showtype'])) {
            $shortcode .= ' showtype=' . esc_attr($settings['showtype']);
        }
        
        if (!empty($settings['sortby'])) {
            $shortcode .= ' sortby=' . esc_attr($settings['sortby']);
        }
        
        if (!empty($settings['sorttype'])) {
            $shortcode .= ' sorttype=' . esc_attr($settings['sorttype']);
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
                <?php _e('The actual VikRentCar availability calendar will be displayed on the frontend.', 'vik-car-booking-elementor-widgets'); ?>
            </div>
        </div>
        <?php
    }
}
