# VikRentCar Elementor Widgets

Elementor widgets for VikRentCar plugin - providing easy-to-use widgets that integrate seamlessly with Elementor page builder.

## Features

This plugin provides 8 Elementor widgets that use the original VikRentCar shortcodes:

### Available Widgets

1. **Search Form Widget** - Main car rental search form
   - Shortcode: `[vikrentcar view="vikrentcar" lang="*"]`
   - Parameters: `category_id` (optional)

2. **Cars List Widget** - Display available cars
   - Shortcode: `[vikrentcar view="carslist" lang="*"]`
   - Parameters: `layoutstyle`, `category_id`, `orderby`, `ordertype`, `lim`

3. **Search Results Widget** - Show search results
   - Shortcode: `[vikrentcar view="search" lang="*"]`
   - Parameters: None (uses search form data)

4. **Car Details Widget** - Show specific car information
   - Shortcode: `[vikrentcar view="cardetails" lang="*"]`
   - Parameters: `carid` (required)

5. **Availability Widget** - Show car availability calendar
   - Shortcode: `[vikrentcar view="availability" lang="*"]`
   - Parameters: `car_ids`, `showtype`, `sortby`, `sorttype`

6. **Locations List Widget** - Show rental locations
   - Shortcode: `[vikrentcar view="locationslist" lang="*"]`
   - Parameters: None

7. **Promotions Widget** - Show active promotions
   - Shortcode: `[vikrentcar view="promotions" lang="*"]`
   - Parameters: `showcars`, `maxdate`, `lim`

8. **User Orders Widget** - Show user booking history
   - Shortcode: `[vikrentcar view="userorders" lang="*"]`
   - Parameters: `searchorder`

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Elementor plugin (free or pro)
- VikRentCar plugin (installed and activated)

## Installation

1. Upload the plugin files to `/wp-content/plugins/vik-car-booking-elementor-widgets/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Make sure VikRentCar and Elementor are installed and activated
4. Go to Elementor editor and look for 'VikRentCar' category in the widgets panel

## Usage

1. **In Elementor Editor:**
   - Drag any VikRentCar widget to your page
   - Configure the widget settings in the panel
   - Publish the page

2. **Widget Settings:**
   - Each widget has its own specific settings
   - All widgets include a custom CSS field for styling
   - Debug information is shown for administrators

3. **Styling:**
   - Use the custom CSS field in each widget's style tab
   - Widgets inherit VikRentCar's default styling
   - Additional CSS can be added for custom appearance

## Technical Details

### How It Works

This plugin creates Elementor widgets that execute VikRentCar shortcodes directly. This approach:

- ✅ Uses the original VikRentCar functionality
- ✅ Maintains all existing features and styling
- ✅ Provides the same design as default VikRentCar
- ✅ No conflicts with VikRentCar's JavaScript or CSS
- ✅ Easy to maintain and update

### Widget Structure

Each widget:
1. Extends `VRC_Base_Widget` class
2. Registers Elementor controls for configuration
3. Builds the appropriate VikRentCar shortcode
4. Executes the shortcode safely with error handling
5. Displays debug information for administrators

### Error Handling

- Safe shortcode execution with try-catch blocks
- Fallback error messages if shortcodes fail
- Debug information for administrators
- Graceful degradation if VikRentCar is not available

## Support

For support and documentation:
- TheCreators: https://thecreators.gr
- VikRentCar: https://vikwp.com
- Elementor: https://elementor.com

## Changelog

### Version 1.0.0
- Initial release
- 8 Elementor widgets for VikRentCar
- Full integration with original VikRentCar shortcodes
- Debug information for administrators
- Custom CSS support for each widget

## License

GPL v2 or later - same as VikRentCar and Elementor
