# Wiki Prayer Times Widget

[![License: GPL v2](https://img.shields.io/badge/License-GPL_v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
[![GitHub release](https://img.shields.io/github/release/wikiwyrhead/wiki-islamic-prayer-times.svg)](https://github.com/wikiwyrhead/wiki-islamic-prayer-times/releases)
[![GitHub issues](https://img.shields.io/github/issues/wikiwyrhead/wiki-islamic-prayer-times)](https://github.com/wikiwyrhead/wiki-islamic-prayer-times/issues)
[![GitHub stars](https://img.shields.io/github/stars/wikiwyrhead/wiki-islamic-prayer-times)](https://github.com/wikiwyrhead/wiki-islamic-prayer-times/stargazers)

A responsive prayer times widget that displays Islamic prayer times with a countdown timer. This plugin is compatible with all WordPress page builders and can be used via shortcode or widget.

## Table of Contents
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Calculation Methods](#calculation-methods)
- [Features in Detail](#features-in-detail)
- [Caching System](#caching-system)
- [Responsive Design](#responsive-design)
- [Requirements](#requirements)
- [Support](#support)
- [License](#license)
- [Credits](#credits)
- [Changelog](#changelog)

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
1. Download the plugin zip file.
2. Go to WordPress admin panel > Plugins > Add New.
3. Click "Upload Plugin" and select the downloaded zip file.
4. Click "Install Now" and then "Activate".

## Usage
### Using Shortcode
Add the prayer times widget to any page or post using the shortcode:
```
[wiki_prayer_times]
```
Customize the city, country, and calculation method:
```
[wiki_prayer_times city="London" country="United Kingdom" method="5"]
```

### Using in Page Builders
The shortcode can be used in any page builder's shortcode element or HTML widget. The widget is fully responsive and will adapt to any container size.

### Default Settings
Set default values for city, country, and calculation method in:
WordPress Admin > Settings > Prayer Times Widget.

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
For support, please [create an issue](https://github.com/wikiwyrhead/wiki-islamic-prayer-times/issues) or contact the plugin author.

## License
This plugin is licensed under the [GPL v2 or later](http://www.gnu.org/licenses/gpl-2.0.html).

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
