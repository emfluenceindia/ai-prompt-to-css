=== AI Prompt to CSS ===
Contributors: subrataemfluence
Tags: cusotm css, ai powered, css generator, developer, text to css 
Requires at least: 6.3
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

AI Prompt to CSS WordPress plugin generates clean, modern CSS rules from a natural text.

== Description ==

**AI Prompt to CSS** is a lightweight, developer-friendly tool. CSS is complex!

This plugin takes a natural text/paragraph (prompt) and converts it into a clean and modern CSS rules.

Tell the plugin what you want and AI Prompt to CSS does the rest!

= Key Features =
* **Automatic Generation:** Analyzes the prompt and outpus the generated CSS.
* **Storage:** These generated CSS rules never gets added to your main CSS files. Rather it are stored as post type in wp_posts table.
* **Multiple Rules:** You can generate as many CSS rules as you want. The database will keep updating.
* **Visibility:** All generated custom CSS rules are displayed just below your prompt area.
* **Admin Notifications:** The plugin displays proper notices on success and failures.

== Installation ==

1. Upload the `ai-prompt-to-css` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Setup and Security ==

This plugin requires an OpenAI Key. Please add and validate your key in the settings page.

== Frequently Asked Questions ==

= Does this plugin work with all themes? =
Yes! This is a theme-independent plugin and work the same way with all themes.

= Will all the generated CSS rules work for all themes? =
No. It is designed to work in a way that the CSS you generate will only work for the theme active at that time. For example, CSS generated with twentytwentyfive theme activated will not work if you switch to twentytwentythree theme.

= Can I check for specific PHP versions? =
Absolutely. The plugin includes a check to ensure the server meets your minimum PHP requirements.

== Screenshots ==

1. The admin notice displayed when a dependency is missing.
2. The settings dashboard showing the status of all guarded dependencies.

== Changelog ==

= 1.0.0 =
* Initial release.
* AI Powered CSS generator from input text.
* Admin notice system.

== Upgrade Notice ==

= 1.0.0 =
This is the first stable version. Enjoy a crash-free WordPress experience!