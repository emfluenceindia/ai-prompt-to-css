=== AI Prompt to CSS ===
Contributors: subrataemfluence
Tags: custom CSS, AI-powered CSS generator, Prompt to CSS, AI CSS generator 
Requires at least: 6.3
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
The AI Prompt to CSS WordPress plugin generates clean, modern CSS rules from natural text.

== Description ==
AI Prompt to CSS is a lightweight, developer-friendly tool. CSS is complex!
This plugin takes a natural text/paragraph (prompt) and converts it into clean and modern CSS rules.
Tell the plugin what you want, and AI Prompt to CSS does the rest!
= Key Features =
* Automatic Generation: Analyzes the prompt and outputs the generated CSS.
* Storage: No generated CSS is added in any existing CSS files. They are stored as custom_css post types in the wp_posts table.
* No restrictions: You can generate as many CSS rules as you want.
* Visibility: All generated custom CSS is displayed below the prompt area on the admin page.
* Admin Notifications: The plugin displays proper notices on success and failures.

== Installation ==
1. Upload the `ai-prompt-to-css` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Setup and Security ==
This plugin requires an OpenAI key. Please add and validate your key in the settings page.
This plugin never tracks or stores your location or any private data anywhere.

== External services ==
This plugin connects to an external API to generate custom CSS based on user-provided prompts. This connection is necessary to process natural language and return functional CSS code that is stored and applied within your WordPress site.

It sends a human-readable prompt to the OpenAI API service whenever a user initiates a request through the plugin interface. No data is sent without a user-triggered action.

This service is provided by OpenAI: Terms of Use (https://openai.com/policies/terms-of-use), Privacy Policy (https://openai.com/policies/privacy-policy).

== Frequently Asked Questions ==
= Does this plugin work with all themes? =
Yes! This is a theme-independent plugin and works the same way with all themes.
= Will all the generated CSS rules work for all themes? =
No. It is designed to work in a way that the CSS you generate will only work for the theme active at that time. For example, CSS generated with the twentytwentyfive theme activated will not work if you switch to the twentytwentythree theme.
= Can I check for specific PHP versions? =
Absolutely. The plugin includes a check to ensure the server meets your minimum PHP requirements.

== Screenshots ==
1. The admin notice displayed when a dependency is missing.
2. The settings dashboard showing the status of all guarded dependencies.

== Changelog ==
= 1.0.0 =
* Initial release.
* AI-powered CSS generator from input text.
* Admin notice system.

== Upgrade Notice ==
= 1.0.0 =
This is the first stable version. Enjoy a crash-free WordPress experience!