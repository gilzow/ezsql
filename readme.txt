=== ELI's SQL Admin Reports with shortcode ===
Plugin URI: http://wordpress.ieonly.com/category/my-plugins/sql-reports/
Author: Eli Scheetz
Author URI: http://wordpress.ieonly.com/category/my-plugins/
Contributors: scheeeli
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7K3TSGPAENSGS
Tags: plugin, admin, reports, sql, mysql, query, custom, shortcode
Stable tag: 1.3.02.12
Version: 1.3.02.12
Requires at least: 2.6
Tested up to: 3.5.1

This plugin allows you to create reports simply by entering in the SQL.

== Description ==

Just place some SQL on in the box and save it as a report. You can save multiple reports and they will be listed on the Admin Menu so you can quickly run them again anytime with just one click. You can also put a report on a Page or Post using a shortcode like [SQLREPORT name="My Report" style="padding: 6px;" /]

Updated Feb-12th

== Installation ==

1. Download and unzip the plugin into your WordPress plugins directory (usually `/wp-content/plugins/`).
1. Activate the plugin through the 'Plugins' menu in your WordPress Admin.

== Frequently Asked Questions ==

= What do I do after I activate the Plugin? =

Start Creating and Saving Reports.

= How do I get one of my reports onto a Page or Post? =

Just use the shortcode SQLREPORT like this [SQLREPORT name="My Report" style="border: 2px solid #CCCCCC; padding: 6px;" /] but be sure the name attribute matches the exact name of a report you have already created.

= How do I use a global variable in one of my reports SQL? =

SELECT display_name FROM wp_users WHERE ID = '<?php $current_user->ID ?>'
(I know there are other ways to get the display name in WordPress, this is just a simple example to illustrate the proper syntax.)

SELECT * FROM wp_users WHERE user_registered > '<?php $_GET[thedate] ?>'
(note: this example assumes you are going to pass 'thedate' as a GET variable in the query string and, as this example shows, don't use quotes inside the PHP brackets.)

== Screenshots ==

1. This is a screen shot of the Admin Menu with some example reports.

== Changelog ==

= 1.3.02.12 =
* Added Menu Placement and sorting options.
* Expanded eval function to take multiple global variables.

= 1.3.01.28 =
* Added eval function to take PHP code in the SQL Statement.

= 1.2.09.23 =
* Added css classes to the Table and TRs for better style control.

= 1.2.09.02 =
* Fixed auto sort links and removed them from showing on finished reports.

= 1.2.04.16 =
* Added error message to the Edit Report Page if SQL statement fails.

= 1.2.04.06 =
* Added shortcode support so you can put your reports onto Pages and Posts.

= 1.2.03.16 =
* Added basic sort capability by linking column names.

= 1.1.12.16 =
* Added styled DIV around Reports with ID tag so that you can customize the style.

= 1.1.12.15 =
* Fixed aditional plugin links.
* Made the Save button label dynamic depending on the state of the report fields.

= 1.1.12.14 =
* First version uploaded to WordPress.

== Upgrade Notice ==

= 1.3.02.12 =
Added Menu Placement and sorting options and expanded eval function to take multiple global variables.

= 1.3.01.28 =
Added eval function to take PHP code in the SQL Statement.

= 1.2.09.23 =
Added css classes to the Table and TRs for better style control.

= 1.2.09.02 =
Fixed auto sort links and removed them from showing on finished reports.

= 1.2.04.06 =
Added shortcode support so you can put your reports onto Pages and Posts.

= 1.2.03.16 =
Added basic sort capability by linking column names.

= 1.1.12.16 =
Added styled DIV around Reports with ID tag so that you can customize the style.

= 1.1.12.15 =
Fixed the aditional plugin links and made the Save button label dynamic.

= 1.1.12.14 =
First version available through WordPress.

