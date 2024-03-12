# oik 
![banner](assets/oik-banner-772x250.jpg)
* Contributors: bobbingwide, vsgloik
* Donate link: https://www.oik-plugins.com/oik/oik-donate/
* Tags: blocks, shortcodes, shortcode, advanced
* Requires at least: 5.0.3
* Tested up to: 6.4.3
* Stable tag: 4.10.1
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Gutenberg compatible: Yes

Over 80 advanced, powerful shortcodes for displaying the content of your WordPress website.

Now with 9 blocks:

* Address - Displays your address from oik-options
* Contact form - Displays a contact form
* Contact field - Part of the Contact form
* Countdown - Countdown / count since timer
* Dynamic content - Dynamic content block
* Follow me - Displays your social media links
* Google Maps - Displays a Google Maps map using oik options
* PayPal button - PayPal button for: Pay Now, Buy Now, Donate, Add to Cart, and View Cart / Checkout
* Shortcode block for oik shortcodes - Expands oik shortcodes


## Description 
The *oik base* plugin provides a series of advanced WordPress shortcodes that help you display dynamic content from your website's pages, posts, attachments, links and custom post types.
The OIK Information Kit uses lazy smart shortcode technology to display your WordPress website's content including your often included key-information.
The functionality in the oik base plugin is used by over 40 other WordPress plugins, providing powerful facilities for an efficient, performant website.

oik now provides over 80 shortcodes including:

* [bw_pages], [bw_posts], [bw_thumbs], [bw_attachments], [bw_images], [bw_pdf] shortcodes to list subpages, posts, attachments or custom post types
* [bw_cycle] to display content using jQuery cycle.
* [bw_list], [bw_tree] to display lists of links to pages, posts, attachments or custom post types
* [bw_table] to tabulate pages, posts or custom post types
* [bw_bookmarks] to list links
* [paypal] shortcodes for PayPal buttons: Pay Now, Buy Now, Donate, Add to Cart, View Cart/Checkout
* [bw_block] & [bw_eblock], [div] & [ediv], [clear] - to create block structures within your pages, posts and even widgets
* [bw_button], [bw_contact_button] - to provide call-to-action button style links for Artisteer themes
* helper shortcodes for web designers and developers: [bw_editcss], [bwtrace], [bw_wpadmin], [bw_wtf], [stag] & [etag]

oik helps you to display information about you, your company, your social networking ids and your website using standard formats that search engines such as Google recognise.

* You enter your information once, then use oik shortcodes to display it wherever you want; in titles, post and page content, header, sidebar and footer widgets.
* Whenever you need to change a value you only need to update it in one place; and your website is updated instantly.
* Shortcodes to display often included key information include: [bw_contact], [bw_telephone], [bw_mobile], [bw_mailto], [bw_company], [bw_address], [bw_show_googlemap], [bw_directions], [bw_logo], [bw_qrcode], [bw_copyright]
* For your social networking use the [bw_follow_me] shortcode to display your links to Twitter, Facebook, LinkedIn, etcetera


Features:

* oik provides buttons to help you write the shortcodes, showing you the parameters you can choose and default values
* oik provides a shortcode discovery page where you can find out about every shortcode which is active in your site
* lazy programming means that code is only loaded when it's actually needed
* smart means that the shortcodes can recognise the content in which they're used and adjust their behaviour accordingly
* oik is extendable - plugin developers can build on the oik base functionality
* using the oik API: help, syntax information, examples AND HTML snippets can be produced for any plugin or theme that provides shortcodes
* oik is theme independent; meaning that you can change your theme without having to worry about whether or not the shortcodes will still work.
* oik uses microformats so that Google and other search engines can understand your content.

## Installation 
See Frequently Asked Questions

## Frequently Asked Questions 
# Installation 
1. Upload the contents of the oik plugin to the `/wp-content/plugins/oik' directory
1. Activate the oik base plugin through the 'Plugins' menu in WordPress
1. Go to **oik options** > **options** to fill in your **o**ften **i**ncluded **k**ey information
1. Use the blocks or shortcodes when writing your content

# Where is the FAQ? 
[oik FAQ](https://www.oik-plugins.com/oik/oik-faq)

# Is there a support forum? 
Yes - please use the [oik plugin forum](https://wordpress.org/support/plugin/oik)

# Can I get support? 
Yes - see above

# Where are the blocks documented? 
[oik blocks](https://www.oik-plugins.com/oik-plugins/oik)

For blocks of many other WordPress plugins ( including WordPress SEO, Jetpack, WooCommerce ) see [blocks.wp-a2z.org](https://blocks.wp-a2z.org)

# Where are the shortcodes documented? 

[oik shortcodes](https://www.oik-plugins.com/shortcodes)

The shortcode reference includes the standard WordPress shortcodes and help for shortcodes provided by other oik plugins.

For other popular WordPress plugins (e.g. Jetpack, Easy-Digital-Downloads, WooCommerce) see [WP-a2z.org](https://wp-a2z.org/sitemap/sites)


## Screenshots 
1. oik options - Options
2. Demonstrating [bw_pages] and [bw_thumbs]
3. [bw_contact_form] - Contact form and [bw_show_googlemap] - Google Map
4. Option to display post IDs on admin pages
5. Custom CSS button
6. oik button dialog - to create the [bw_button] shortcode
* 7. oik PayPal dialog - create PayPal buttons: Pay Now, Buy Now, Donate, Add to Cart and View Cart/Checkout
8. oik shortcodes dialog - showing syntax for [bw_block]
9. oik options - Shortcode help - lists ALL active shortcodes
10. oik options - Buttons

## Upgrade Notice 
# 4.10.2 
Update for a security fix to prevent JavaScript in URLs #224

# 4.10.1 
Update for basic spam checking on the contact form subject, and support for PHP 8.3

## Changelog 
# 4.10.2 
* Fixed: Escape the URL in links. #224  Props: Wordfence. Vulnerability Researcher: Francesco Carlucci

# 4.10.1 
* Changed: Support PHP 8.3 #220
* Changed: Spam check subject for #221
* Tested: With WordPress 6.4.3 and WordPress Multisite
* Tested: With PHP 8.3
* Tested: With PHPUnit 9.6

## Further reading 
If you want to read more about the oik plugins then please visit the
[oik plugin](https://www.oik-plugins.com/oik)
**"the oik plugin - for often included key-information"**

# Other plugins 

Other plugins which depend upon the oik API are available on WordPress.org:

* [bbboing](https://www.wordpress.org/extend/plugins/bbboing) - obfuscate text but leave it readable
* [cookie-cat](https://www.wordpress.org/extend/plugins/cookie-cat) - [cookies] shortcode to list the cookies your website may use
* [oik-batchmove](https://www.wordpress.org/extend/plugins/oik-batchmove) - batch change post categories or published date
* [oik-nivo-slider](https://wordpress.org/extend/plugins/oik-nivo-slider/) - [nivo] shortcode for the jQuery "Nivo slider" for posts, pages, attachments and custom post types
* [oik-privacy-policy](https://www.wordpress.org/extend/plugins/oik-privacy-policy) - generate a privacy policy page, compliant with UK cookie law (EU cookie directive)
* [oik-read-more](https://wordpress.org/plugins/oik-read-more) - progressively reveal content by clicking on "read more" buttons

Plugins which participate with oik shared libraries are:

* [oik-bwtrace](https://wordpress.org/plugins/oik-bwtrace/) - Debug trace for WordPress, including action and filter tracing
* [oik-css](https://www.wordpress.org/extend/plugins/oik-css) - [bw_css] for CSS styling per page
* [uk-tides](https://wordpress.org/extend/plugins/uk-tides/) - [bw_tides] shortcode for tide times and heights in the UK  (replaces oik-tides)

These plugins are not dependent upon oik:

* [allow-reinstalls](https://wordpress.org/plugins/allow-reinstalls/) - Allow re-installation of plugins and themes by upload
* [oik-weight-zone-shipping](https://wordpress.org/plugins/oik-weight-zone-shipping) - Weight Zone Shipping for WooCommerce
* [oik-weightcountry-shipping](https://wordpress.org/plugins/oik-weightcountry-shipping) - Weight/Country Shipping for WooCommerce


More FREE and Premium plugins are available from [oik-plugins.com](https://www.oik-plugins.com/wordpress-plugins-from-oik-plugins/) including:

* [diy-oik](https://www.oik-plugins.com/oik-plugins/diy-oik) - Do-It-Yourself shortcodes
* [oik-blocks](https://www.oik-plugins.com/oik-plugins/oik-blocks) - WordPress blocks for oik shortcodes
* [oik-External link warning jQuery](https://www.oik-plugins.com/oik-plugins/external-link-warning-jquery/) - Warns visitor about leaving your site
* [oik-fields](https://www.oik-plugins.com/oik-plugins/oik-fields-custom-post-type-field-apis) - custom post type field APIs
* [oik-ms](https://www.oik-plugins.com/oik-plugins/oik-ms-oik-multisite-shortcodes/) - oik MultiSite shortcodes
* [oik-mshot](https://www.oik-plugins.com/oik-plugins/oik-mshot) - Shortcode to display the "mshot" of an URL; oik-fields extension
* [oik-rating](https://www.oik-plugins.com/oik-plugins/oik-rating) - 5 star rating custom field; oik-fields extension
* [oik-testimonials](https://www.oik-plugins.com/oik-plugins/oik-testimonials) - Manage and display testimonials.
* [oik-todo](https://www.oik-plugins.com/oik-plugins/oik-todo-todo-list) - TO DO list
* [oik-types](https://www.oik-plugins.com/oik-plugins/oik-types) - custom content type, field and taxonomy manager
* [oik-user](https://www.oik-plugins.com/oik-plugins/oik-user) - display oik information for each site user


oik plugins are suitable for:

* WordPress site owners
* WordPress site administrators
* WordPress designers
* WordPress web site developers
* WordPress plugin developers

oik plugins are tested with:

* WordPress
* WordPress Multisite
* PHP 8.1, PHP 8.2 and PHP 8.3
* PHPUnit 9.6

All of the plugins are developed using a set of functions that can make PHP and HTML coding a bit easier.
These are known as the [OIK Application Programming Interface (OIK API)](https://www.oik-plugins.com/apis/oik-apis)
