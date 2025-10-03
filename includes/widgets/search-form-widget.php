<?php
/**
 * VikRentCar Search Form Widget
 * 
 * A simple Elementor widget that wraps the default VikRentCar search form shortcode
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class VRC_Search_Form_Widget extends VRC_Base_Widget {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'vrc-search-form';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return esc_html__('Car Search Form', 'vik-car-booking-elementor-widgets');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-search';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return array('vikrentcar');
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            array(
                'label' => esc_html__('Search Form Settings', 'vik-car-booking-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        // Show Results Toggle
        $this->add_control(
            'show_results',
            array(
                'label' => esc_html__('Show Search Results', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'vik-car-booking-elementor-widgets'),
                'label_off' => esc_html__('No', 'vik-car-booking-elementor-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('If enabled, search results will be displayed below the form. If disabled, only the search form will be shown.', 'vik-car-booking-elementor-widgets'),
            )
        );

        // Category Selection
        $this->add_control(
            'category_id',
            array(
                'label' => esc_html__('Car Category', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_vrc_categories_options(),
                'default' => '',
                'description' => esc_html__('Select a specific car category to filter the search form. Leave empty to show all categories.', 'vik-car-booking-elementor-widgets'),
            )
        );

        // Debug Mode
        $this->add_control(
            'debug_mode',
            array(
                'label' => esc_html__('Debug Mode', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'vik-car-booking-elementor-widgets'),
                'label_off' => esc_html__('Off', 'vik-car-booking-elementor-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('Enable debug information for administrators.', 'vik-car-booking-elementor-widgets'),
            )
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            array(
                'label' => esc_html__('Custom Styling', 'vik-car-booking-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        // Custom CSS
        $this->add_control(
            'custom_css',
            array(
                'label' => esc_html__('Custom CSS', 'vik-car-booking-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'rows' => 10,
                'description' => esc_html__('Add custom CSS to style the search form.', 'vik-car-booking-elementor-widgets'),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Get categories options for the select control
     */
    private function get_vrc_categories_options() {
        $options = array('' => esc_html__('All Categories', 'vik-car-booking-elementor-widgets'));
        
        $categories = $this->get_vrc_categories();
        foreach ($categories as $category) {
            $options[$category['id']] = $category['name'];
        }
        
        return $options;
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Simple debug info for admins
        if (current_user_can('manage_options') && $settings['debug_mode'] === 'yes') {
            echo '<div class="vrc-widget-debug" style="border: 2px dashed #ff6b6b; padding: 10px; margin: 10px 0; background: #fff5f5;">';
            echo '<strong>🔍 VikRentCar Search Form Widget</strong><br>';
            echo 'Show Results: ' . ($settings['show_results'] === 'yes' ? 'YES' : 'NO') . '<br>';
            echo 'Category: ' . (!empty($settings['category_id']) ? $settings['category_id'] : 'All') . '<br>';
            echo '</div>';
        }
        
        // Build the default VikRentCar shortcode
        $shortcode = '[vikrentcar view=vikrentcar lang=*';
        
        if (!empty($settings['category_id'])) {
            $shortcode .= ' category_id=' . esc_attr($settings['category_id']);
        }
        
        $shortcode .= ']';
        
        // Execute the shortcode
        $output = do_shortcode($shortcode);
        
        // If we don't want to show results, filter to show only the search form
        if ($settings['show_results'] !== 'yes') {
            $output = $this->filter_to_search_form_only($output);
            // Fix the form action to point to a search results page
            $output = $this->fix_form_action_for_search_results($output);
        }
        
        // Add custom CSS if provided
        if (!empty($settings['custom_css'])) {
            echo '<style>' . $settings['custom_css'] . '</style>';
        }
        
        echo $output;
    }
    
    /**
     * Simple filter to show only the search form (remove results)
     */
    private function filter_to_search_form_only($output) {
        // Remove search results sections but keep the form
        $patterns_to_remove = array(
            '/<div[^>]*class=["\'][^"\']*search[^"\']*results[^"\']*["\'][^>]*>.*?<\/div>/is',
            '/<div[^>]*class=["\'][^"\']*results[^"\']*["\'][^>]*>.*?<\/div>/is',
            '/<div[^>]*class=["\'][^"\']*cars[^"\']*found[^"\']*["\'][^>]*>.*?<\/div>/is',
        );
        
        $filtered_output = $output;
        foreach ($patterns_to_remove as $pattern) {
            $filtered_output = preg_replace($pattern, '', $filtered_output);
        }
        
        return $filtered_output;
    }
    
    /**
     * Fix the form action to point to a search results page
     */
    private function fix_form_action_for_search_results($output) {
        // Get the search results page URL
        $search_results_url = $this->get_search_results_page_url();
        
        if ($search_results_url) {
            // Replace the form action URL
            $output = preg_replace(
                '/<form([^>]*)\s+action=["\'][^"\']*["\']([^>]*)>/i',
                '<form$1 action="' . esc_url($search_results_url) . '"$2>',
                $output
            );
            
            // Also handle forms without action attribute
            $output = preg_replace(
                '/<form([^>]*)(?!\s+action=)([^>]*)>/i',
                '<form$1 action="' . esc_url($search_results_url) . '"$2>',
                $output
            );
        }
        
        return $output;
    }
    
    /**
     * Get the search results page URL
     */
    private function get_search_results_page_url() {
        // Try to find a page with the VikRentCar search results shortcode
        $search_results_page = get_posts(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_elementor_data',
                    'value' => 'vikrentcar.*search',
                    'compare' => 'REGEXP'
                )
            ),
            'numberposts' => 1
        ));
        
        if (!empty($search_results_page)) {
            return get_permalink($search_results_page[0]->ID);
        }
        
        // Fallback: try to find a page with search results shortcode in content
        $search_results_page = get_posts(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            's' => '[vikrentcar view=search]',
            'numberposts' => 1
        ));
        
        if (!empty($search_results_page)) {
            return get_permalink($search_results_page[0]->ID);
        }
        
        // If no search results page found, return the current page
        // This will show results on the same page
        return get_permalink();
    }
}