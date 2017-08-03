=== MN Client for FormSping.me ===
Contributors: marcosjr
Tags: formspring, updates, questions, answers, widget, plugin, sidebar
Requires at least: 2.8
Tested up to: 2.9
Stable tag: 2.1

This plugin adds a widget to show the lastest formspring updates based on username

== Description ==

This plugin adds a widget to show the lastest formspring updates based on username

For better compatibility, this plugin must be used with PHP5 or higher

This plugin allows to set 3 options:

*   Username: Formspring.me username to show updates
*   Title: The title that is showed in your wordpress site
*   Number: The quantity of updates to show

And is totally customizable.

= Customization =

The updates are wrapped by these CSS classes:

* ul.formspringme-updates : The list of updates
* li.formspringme-update : The list item that contains the update, this is child of ul.formspringme-updates
* span.formspringme-question : The text of question
* span.formspringme-answer : The text of answer
* p.formspringme-askme-link : The text of user profile link



Note: This plugin doesn't uses the API of Formspring.me because it isn't avaiable yet. So, this may still unavailable at any time.


== Installation ==

To install this plugin is simple.

1. Upload `mnformspringclient.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Show updates on sidebar: Drag and add the MN Client for FormSpring.me widget on your theme sidebar throught 'Widgets' menu in wordpress
4. Show updates on post/page content: Just add [formspringme-updates] on your post/page where you want to show
5. To show answers from another user independent the configured username, just add [formspringme-updates username]

After activated your plugin, you can configure it on menu "MN Client updates options" or on widgets page

== Screenshots ==

1. Control panel

== Changelog ==

= 2.0 =
* Plugin changed to work with the new formspring.me site

= 1.3 =
* Added feature to show updates on post/page content just adding [formspringme-updates]
* You can show answers from diferent users for each tag, just adding [formspringme-updates username]

= 1.2 =
* Fix compatibility with other servers where file_get_contents was disable

= 1.1 =
* Added link to user profile with text "Ask me anything"
* Added message "Any questions answered yet" when any question isn't found
* New CSS class added to customize style of profile link

= 1.0.2 =
* Fixed bug in generation of direct links to questions.

== Upgrade Notice ==

= 2.0 =
Adapted to new formspring.me interface

= 1.3 =
Now you can show your updates on post/page content, update and just add [formspringme-updates] to post/page and the plugin will show your updates

= 1.2 =
If the plugin just shows a message "Service unavailable", this update will try to fix the compatibility problem

= 1.1 =
Update your plugin and add a link to your profile and show message when any questions was answered yet

= 1.0.2 =
Update your formspring.me plugin to fix the direct link to your questions.
