=== ELI's SQL Admin Reports Shortcode and DB Backup ===
Plugin URI: http://wordpress.ieonly.com/category/my-plugins/sql-reports/
Author: Eli Scheetz
Author URI: http://wordpress.ieonly.com/category/my-plugins/
Contributors: scheeeli
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7K3TSGPAENSGS
Tags: plugin, admin, reports, dashboard, sql, query, shortcode, mysql, cron, schedule, database, backup
Stable tag: 3.08.03
Version: 3.08.03
Requires at least: 2.6
Tested up to: 3.6

Create and save SQL Reports in your WP Admin and place them on pages and posts with a shortcode. Keep your database safe with automatic backups.

== Description ==

Just place some SQL on in the box and save it as a report. You can save multiple reports and they will be listed on the Admin Menu so you can quickly run them again anytime with just one click. You can place your reports on the User's Dashboard based on Roles. You can also put a report on a Page or Post using a shortcode like [SQLREPORT name="My Report" style="padding: 6px;" /]

There is also an shortcode for the wpdb::get_var function that you can use to display a single value from your database. For example, this will display the number of users on your site:
[sqlgetvar]SELECT COUNT(*) FROM wp_users[/sqlgetvar]

Now your data can be automatically saved and archived every hour and/or every day, and backups can be emailed to the address you specify. You can also restore the data to your WP DB or an external DB, which makes copying your database to another server and easy task.

Updated August-3rd

== Installation ==

1. Download and unzip the plugin into your WordPress plugins directory (usually `/wp-content/plugins/`).
1. Activate the plugin through the 'Plugins' menu in your WordPress Admin.

== Frequently Asked Questions ==

= What do I do after I activate the Plugin? =

Start Creating and Saving Reports.

= How do I get one of my reports onto a Page or Post? =

Just use the shortcode SQLREPORT like this [SQLREPORT name="My Report" style="border: 2px solid #CCCCCC; padding: 6px;" /] but be sure the name attribute matches the exact name of a report you have already created.

= How do I use a global variable in one of my SQL queries? =

Note: This < does not display properly on web pages so I used the HTML code &lt; in this example, > works...

SELECT display_name FROM wp_users WHERE ID = '&lt;?php $current_user->ID ?>'</textarea>
(I know there are other ways to get the display name in WordPress, this is just a simple example to illustrate the proper syntax.)

<textarea>SELECT * FROM wp_users WHERE user_registered > '&lt;?php $_GET[thedate] ?>'</textarea>
(note: this example assumes you are going to pass 'thedate' as a GET variable in the query string and, as this example shows, don't use quotes inside the PHP brackets.)

== Screenshots ==

1. This is a screenshot of the Plugin Settings and the Admin Menu with some example reports.

== Changelog ==

= 3.08.03 =
* Improved the compatibility and reliability of the restore process.

= 3.06.29 =
* Add ability to place reports on the dashboard for a given Role.

= 3.06.24 =
* Created a second method for backups that uses the command line mysql.
* Made compression optional and backup location changable.
* Made restore function able to connect to external an DB.
* Fixed shortcode to work with sanitize_title.

= 3.06.14 =
* Added a WP cron scheduler for hourly and daily backups.
* Upgraded the Backup process to ZIP and email all backups.
* Added a shortcode for wpdb::get_var

= 1.3.03.24 =
* Fixed the Backup process to capture VIEW definitions properly.

= 1.3.03.02 =
* Added database backup feature.
* Fixed Report name issue when using apostrophes.

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

= 3.08.03 =
Improved the compatibility and reliability of the restore process.

= 3.06.29 =
Add ability to place reports on the dashboard for a given Role.

= 3.06.24 =
Created a second method for backups that uses the command line mysql, made compression optional and backup location changable, restore function able to connect to an external DB, and fixed shortcode to work with sanitize_title.

= 3.06.14 =
Added scheduler for hourly and daily backups, upgraded the Backup process to ZIP and email all backups, and added a shortcode for get_var.

= 1.3.03.24 =
Fixed the Backup process to capture VIEW definitions properly.

= 1.3.03.02 =
Added database backup feature and fixed Report name issue when using apostrophes.

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

