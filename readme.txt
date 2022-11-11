=== World Population Counter ===
Contributors: algoritmika, anbinder
Tags: world, population, counter, clock
Requires at least: 4.4
Tested up to: 5.3
Stable tag: 1.2.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add live world population counter to your site.

== Description ==

**World Population Counter** plugin lets you add live world population counter (i.e. world population clock) to your site.

Counter can be added via:

* "World Population Counter" **widget**,
* `[alg_world_population_counter]` **shortcode**,
* `echo alg_world_population_counter();` **PHP function**.

= Feedback =

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "Settings > World Population Counter".

== Changelog ==

= 1.2.0 - 05/03/2020 =
* Dev - Admin settings descriptions updated.
* Donate link etc. removed.
* Tested up to: 5.3.

= 1.1.1 - 05/07/2017 =
* Fix - `alg_world_population_counter()` - `return` instead of `echo`.
* Dev - "CSS Class" option added.

= 1.1.0 - 04/07/2017 =
* Fix - `load_plugin_textdomain()` moved from `init` hook to constructor.
* Dev - Widget - Default title set to empty string.
* Dev - Plugin settings description updated.
* Dev - Version added to all `wp_enqueue_script()` calls.
* Dev - Action links on the plugin screen added.
* Dev - Plugin headers ("Text Domain", "Donate link" etc.) updated.
* Dev - Description (`readme.txt`) updated.
* Dev - Minor code refactoring and cleanup.

= 1.0.0 - 16/11/2016 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
