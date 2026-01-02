![AI Prompt to CSS](https://github.com/emfluenceindia/smart-dependency-gurdian/blob/main/assets/smdepgur-logo-256x256.png?raw=true)

# AI Prompt to CSS
**Prevent site crashes by managing plugin dependencies intelligently.** AI Prompt to CSS ensures required plugins and environment specs are active before your code runs, eliminating the "White Screen of Death" (WSOD).

[![WordPress Version](https://img.shields.io/badge/WordPress-6.3+-0073AA.svg?style=flat-square&logo=wordpress)](https://wordpress.org)
[![PHP Version](https://img.shields.io/badge/PHP-7.4+-777BB4.svg?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-GPLv2+-blue.svg?style=flat-square)](https://www.gnu.org/licenses/gpl-2.0.html)

## 📖 Description
**AI Prompt to CSS** is a lightweight, developer-focused utility for the WordPress ecosystem. If your plugin relies on heavyweights like WooCommerce, ACF, or Elementor, this tool acts as a safety gate.

If requirements aren't met, the plugin gracefully halts execution and provides the administrator with clear, actionable feedback via the WordPress dashboard.

---

## ✨ Key Features
- ✅ **Automatic Detection**: Instantly scans for active plugins and minimum PHP versions.

- 🛡️ **Graceful Degradation**: Stops fatal errors by preventing code execution on incompatible environments.

- 🔔 **Smart Admin Notices**: Non-intrusive, dismissible alerts that tell users exactly what to install or update.

- 🪝 **Developer First**: Extensible via filters and hooks for custom dependency logic.

---

## 🚀 Installation

### Using Git
`cd wp-content/plugins`

`git clone https://github.com/emfluenceindia/ai-prompt-to-css`

### Manual
1. Download the latest release .zip.

2. Upload the smart-dependency-guard folder to your /wp-content/plugins/ directory.

3. Activate the plugin through the Plugins menu in WordPress.

## ❓ FAQ
Q: Does this plugin work with WooCommerce? A: Yes! It is perfect for WooCommerce extensions to ensure the core shop is active before running custom checkout logic.

Q: Can I check for specific PHP versions? A: Absolutely. You can define a minimum PHP version to prevent syntax errors (like trailing commas in calls) from crashing older servers.

## 📜 Changelog
1.0.0
Initial release.

Core dependency checking engine.

Admin notice system.

## 🛡️ License
Distributed under the GPLv2 or later. See LICENSE.txt for more information.

## 🤝 Contributing
Contributions, issues, and feature requests are welcome! Feel free to check the issues page.