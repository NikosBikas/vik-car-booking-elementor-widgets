<?php
/**
 * VikRentCar Cars List Widget
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cars List Widget Class
 */
class VRC_Cars_List_Widget extends VRC_Base_Widget {
    
    /**
     * Get widget name
     */
    public function get_name() {
        return 'vrc-cars-list';
    }
    
    /**
     * Get widget title
     */
    public function get_title() {
        return __('Cars List', 'vik-car-booking-elementor-widgets');
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-car';
    }
    
    /**
     * Get widget description
     */
    public function get_description() {
        return __('Display a list of available cars from VikRentCar.', 'vik-car-booking-elementor-widgets');
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
        
        // Layout Style
        $this->add_control(
            'layoutstyle',
            array(
                'label' => __('Layout Style', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'list' => __('List', 'vik-car-booking-elementor-widgets'),
                    'grid' => __('Grid', 'vik-car-booking-elementor-widgets'),
                ),
                'default' => 'list',
            )
        );
        
        // Category ID
        $categories = $this->get_vrc_categories();
        $category_options = array('0' => __('All Categories', 'vik-car-booking-elementor-widgets'));
        foreach ($categories as $category) {
            $category_options[$category['id']] = $category['name'];
        }
        
        $this->add_control(
            'category_id',
            array(
                'label' => __('Category', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $category_options,
                'default' => '0',
            )
        );
        
        // Order By
        $this->add_control(
            'orderby',
            array(
                'label' => __('Order By', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'price' => __('Price', 'vik-car-booking-elementor-widgets'),
                    'customprice' => __('Custom Price', 'vik-car-booking-elementor-widgets'),
                    'name' => __('Name', 'vik-car-booking-elementor-widgets'),
                ),
                'default' => 'price',
            )
        );
        
        // Order Type
        $this->add_control(
            'ordertype',
            array(
                'label' => __('Order Type', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'asc' => __('Ascending', 'vik-car-booking-elementor-widgets'),
                    'desc' => __('Descending', 'vik-car-booking-elementor-widgets'),
                ),
                'default' => 'asc',
            )
        );
        
        // Limit
        $this->add_control(
            'lim',
            array(
                'label' => __('Number of Cars', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 1,
                'max' => 100,
            )
        );
        
        // Debug Mode
        $this->add_control(
            'debug_mode',
            array(
                'label' => __('Debug Mode', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'vik-car-booking-elementor-widgets'),
                'label_off' => __('Off', 'vik-car-booking-elementor-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Enable debug information for administrators.', 'vik-car-booking-elementor-widgets'),
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
                'description' => __('Add custom CSS to style the cars list.', 'vik-car-booking-elementor-widgets'),
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
        $shortcode = '[vikrentcar view=carslist lang=*';
        
        if (!empty($settings['layoutstyle'])) {
            $shortcode .= ' layoutstyle=' . esc_attr($settings['layoutstyle']);
        }
        
        if (!empty($settings['category_id']) && $settings['category_id'] !== '0') {
            $shortcode .= ' category_id=' . esc_attr($settings['category_id']);
        }
        
        if (!empty($settings['orderby'])) {
            $shortcode .= ' orderby=' . esc_attr($settings['orderby']);
        }
        
        if (!empty($settings['ordertype'])) {
            $shortcode .= ' ordertype=' . esc_attr($settings['ordertype']);
        }
        
        if (!empty($settings['lim'])) {
            $shortcode .= ' lim=' . esc_attr($settings['lim']);
        }
        
        $shortcode .= ']';
        
        // Add debug info for admins only if debug mode is enabled
        if (current_user_can('manage_options') && $settings['debug_mode'] === 'yes') {
            $this->add_debug_info($shortcode, $settings);
        }
        
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
                <?php _e('The actual VikRentCar cars list will be displayed on the frontend.', 'vik-car-booking-elementor-widgets'); ?>
            </div>
        </div>
        <?php
    }
}
