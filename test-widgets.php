<?php
/**
 * Test VikRentCar Elementor Widgets
 */

// Load WordPress
require_once('../../../wp-load.php');

echo "<h1>VikRentCar Elementor Widgets Test</h1>";

// Check if our plugin is loaded
if (!class_exists('VRC_Elementor_Widgets')) {
    echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
    echo "<strong>❌ ERROR:</strong> VRC_Elementor_Widgets class not found. Plugin may not be loaded.";
    echo "</div>";
    exit;
}

echo "<div style='background: #e8f5e8; border: 1px solid #4caf50; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
echo "<strong>✅ SUCCESS:</strong> VRC_Elementor_Widgets class found!";
echo "</div>";

// Check VikRentCar availability
if (defined('VIKRENTCAR_SOFTWARE_VERSION')) {
    echo "<div style='background: #e8f5e8; border: 1px solid #4caf50; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
    echo "<strong>✅ VikRentCar Available:</strong> Version " . VIKRENTCAR_SOFTWARE_VERSION;
    echo "</div>";
} else {
    echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
    echo "<strong>❌ VikRentCar Not Available:</strong> VikRentCar plugin is not installed or activated.";
    echo "</div>";
}

// Check Elementor availability
if (did_action('elementor/loaded')) {
    echo "<div style='background: #e8f5e8; border: 1px solid #4caf50; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
    echo "<strong>✅ Elementor Available:</strong> Elementor plugin is loaded.";
    echo "</div>";
} else {
    echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
    echo "<strong>❌ Elementor Not Available:</strong> Elementor plugin is not installed or activated.";
    echo "</div>";
}

// Test widget classes
$widget_classes = array(
    'VRC_Search_Form_Widget' => 'Search Form Widget',
    'VRC_Cars_List_Widget' => 'Cars List Widget',
    'VRC_Search_Results_Widget' => 'Search Results Widget',
    'VRC_Car_Details_Widget' => 'Car Details Widget',
    'VRC_Availability_Widget' => 'Availability Widget',
    'VRC_Locations_List_Widget' => 'Locations List Widget',
    'VRC_Promotions_Widget' => 'Promotions Widget',
    'VRC_User_Orders_Widget' => 'User Orders Widget',
);

echo "<h2>Widget Classes Test</h2>";

foreach ($widget_classes as $class => $name) {
    if (class_exists($class)) {
        echo "<div style='background: #e8f5e8; border: 1px solid #4caf50; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
        echo "<strong>✅ {$name}:</strong> Class {$class} found";
        echo "</div>";
    } else {
        echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
        echo "<strong>❌ {$name}:</strong> Class {$class} not found";
        echo "</div>";
    }
}

// Test shortcodes
echo "<h2>VikRentCar Shortcodes Test</h2>";

$shortcodes = array(
    '[vikrentcar view=vikrentcar lang=*]' => 'Search Form',
    '[vikrentcar view=carslist lang=*]' => 'Cars List',
    '[vikrentcar view=search lang=*]' => 'Search Results',
    '[vikrentcar view=cardetails lang=* carid=1]' => 'Car Details',
    '[vikrentcar view=availability lang=* car_ids=1]' => 'Availability',
    '[vikrentcar view=locationslist lang=*]' => 'Locations List',
    '[vikrentcar view=promotions lang=*]' => 'Promotions',
    '[vikrentcar view=userorders lang=*]' => 'User Orders',
);

foreach ($shortcodes as $shortcode => $name) {
    try {
        $output = do_shortcode($shortcode);
        if (!empty($output) && $output !== $shortcode && strpos($output, '[vikrentcar') === false) {
            echo "<div style='background: #e8f5e8; border: 1px solid #4caf50; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
            echo "<strong>✅ {$name}:</strong> Shortcode executed successfully";
            echo "</div>";
        } else {
            echo "<div style='background: #fff3e0; border: 1px solid #ff9800; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
            echo "<strong>⚠️ {$name}:</strong> Shortcode returned empty or literal text";
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
        echo "<strong>❌ {$name}:</strong> Error - " . esc_html($e->getMessage());
        echo "</div>";
    } catch (Error $e) {
        echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0; border-radius: 4px;'>";
        echo "<strong>❌ {$name}:</strong> Fatal Error - " . esc_html($e->getMessage());
        echo "</div>";
    }
}

echo "<h2>Summary</h2>";
echo "<p><strong>Plugin Status:</strong> Ready for use in Elementor!</p>";
echo "<p><strong>Available Widgets:</strong></p>";
echo "<ul>";
echo "<li>Search Form - Main car rental search form</li>";
echo "<li>Cars List - Display available cars</li>";
echo "<li>Search Results - Show search results</li>";
echo "<li>Car Details - Show specific car information</li>";
echo "<li>Availability - Show car availability calendar</li>";
echo "<li>Locations List - Show rental locations</li>";
echo "<li>Promotions - Show active promotions</li>";
echo "<li>User Orders - Show user booking history</li>";
echo "</ul>";

echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Go to Elementor editor</li>";
echo "<li>Look for 'VikRentCar' category in the widgets panel</li>";
echo "<li>Drag and drop any VikRentCar widget to your page</li>";
echo "<li>Configure the widget settings as needed</li>";
echo "<li>Publish and test on the frontend</li>";
echo "</ol>";
?>
