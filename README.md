# Wiki Prayer Times Widget

A responsive prayer times widget that displays Islamic prayer times with a countdown timer. This plugin is compatible with all WordPress page builders and can be used via shortcode or widget.

## Features

- Displays prayer times for any city and country
- Shows Hijri date
- Highlights the next prayer
- Countdown timer to next prayer
- Responsive design
- Caching for better performance
- Multiple calculation methods
- Customizable through WordPress admin
- Compatible with all page builders
- Local storage caching for faster loading
- Mobile-friendly design
- Real-time countdown updates

## Installation

1. Download the plugin zip file
2. Go to WordPress admin panel > Plugins > Add New
3. Click "Upload Plugin" and select the downloaded zip file
4. Click "Install Now" and then "Activate"

## Usage

### Using Shortcode

You can add the prayer times widget to any page or post using the shortcode:

```
[prayer_times]
```

You can also customize the city, country, and calculation method:

```
[prayer_times city="London" country="United Kingdom" method="5"]
```

### Using in Page Builders

The shortcode can be used in any page builder's shortcode element or HTML widget. The widget is fully responsive and will adapt to any container size.

### Default Settings

You can set default values for city, country, and calculation method in:
WordPress Admin > Settings > Prayer Times Widget

## Calculation Methods

The plugin supports various calculation methods:

1. Egyptian General Authority
2. Islamic Society of North America
3. Muslim World League
4. University of Islamic Sciences, Karachi
5. Islamic University of Karachi
6. Institute of Geophysics, University of Tehran
7. Shia Ithna-Ashari, Leva Institute, Qum
8. Gulf Region
9. Kuwait
10. Qatar
11. Singapore
12. Tehran
13. Custom

## Features in Detail

### Prayer Times Display

- Shows all five daily prayers (Fajr, Dhuhr, Asr, Maghrib, Isha)
- Includes sunrise time
- Displays Hijri date
- Highlights the next prayer
- Shows countdown timer to next prayer

### Caching System

- Uses browser's localStorage for caching prayer times
- Cache duration: 1 hour
- Automatically updates when cache expires
- Reduces API calls for better performance

### Responsive Design

- Adapts to all screen sizes
- Mobile-first approach
- Optimized for readability on small devices
- Maintains functionality across all devices

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- Modern web browser with JavaScript enabled
- Active internet connection for API calls

## Support

For support, please create an issue in the [GitHub repository](https://github.com/wikiwyrhead/wiki-islamic-prayer-times) or contact the plugin author.

## License

This plugin is licensed under the GPL v2 or later. See the [license file](http://www.gnu.org/licenses/gpl-2.0.html) for details.

## Credits

Created by [Arnel Go](https://arnelgo.info/)

## Changelog

### 1.1.1

- Initial release
- Added responsive design
- Implemented caching system
- Added multiple calculation methods
- Added shortcode support
- Added admin settings page
