# School Fee Calculator WordPress Plugin

A comprehensive school fee calculator plugin for WordPress with advanced features including discount systems, payment plans, and tax calculations.

## Features

- 🎯 Multiple student types (Day Scholar, Boarder, International)
- 💰 Comprehensive fee structure management
- 🎁 Advanced discount system (sibling, early payment, scholarships)
- 📅 Flexible payment plans (Annual, Semi-Annual, Quarterly, Monthly)
- 🧮 Tax calculations
- 📊 Detailed fee breakdown
- 📱 Responsive design
- 🔌 Shortcode support
- 📈 Reporting and analytics
- 🌍 Multi-language ready

## Installation

1. Upload the plugin files to `/wp-content/plugins/school-fee-calc`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the `[school_fee_calculator]` shortcode to display the calculator

## Usage

### Basic Shortcode
[school_fee_calculator]
[fee_calculator_results]


### Admin Setup
1. Go to WordPress Admin → Fee Calculator
2. Set up fee structures for different grades
3. Configure discounts and payment plans
4. Add shortcode to any page/post

## 📁 File Structure
school-fee-calc/
├── school-fee-calc.php # Main plugin file
├── includes/ # Core classes
│ ├── class-admin.php
│ ├── class-calculator.php
│ ├── class-shortcodes.php
│ ├── class-ajax.php
│ └── class-database.php
├── templates/ # Admin templates
│ ├── admin-dashboard.php
│ ├── fee-structures.php
│ ├── discounts.php
│ ├── payment-plans.php
│ └── settings.php
├── assets/ # CSS & JS
│ ├── css/
│ │ ├── admin.css
│ │ └── frontend.css
│ └── js/
│ ├── admin.js
│ └── frontend.js
└── languages/ # Translation files


## 🔧 Requirements

- WordPress 5.0+
- PHP 7.4+
- MySQL 5.6+

## 📝 Changelog

### 1.0.0
- Initial release
- Complete fee calculation system
- Admin management interface
- Responsive frontend calculator

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## 🆕 Support

- Create an [issue](https://github.com/yourusername/school-fee-calc/issues) for bugs or feature requests
- Documentation available in the [wiki](https://github.com/yourusername/school-fee-calc/wiki)

## 🎯 Demo

[Live Demo](https://yourdemo-site.com) | [Screenshots](#screenshots)
