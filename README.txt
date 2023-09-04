=== Addonify - Compare Products For WooCommerce ===

Contributors: addonify
Tags: compare, woocommerce compare, products comparison, compare products, compare woocommerce, addonify, woocommerce
Requires at least: 5.9
Tested up to: 6.3.1
Stable tag: 1.1.11
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Addonify Compare Products is a WooCommerce extension that allows website visitors to compare multiple products on your online store.

== Description ==

Addonify Compare Products is a WooCommerce extension that allows website visitors to compare multiple products on your online store. It enables functionality to let visitors add products to a comparison table which consists of product information like name, image, price, rating, description, attributes etc. 

👉 [Demo one](https://demo.addonify.com/woo/01/compare/) 
👉 [Documentation guide](https://docs.addonify.com/kb/woocommerce-compare-products/) 

Addonify Compare Products helps your online visitors to make better buying decisions that will result in a better online shopping experience.

#### ⏳ FEATURES: 

- Light-weight & optimized.
- Enable or disable product comparison.
- Display the comparison table either in a modal window or a page.
- Save the compare cookie in the visitor's web browser for specified days.
- Add to compare button position.
- Custom add to compare button label.
- Hide/show icon in add to compare button.
- Add to compare button icon position.
- Contents to display in the comparison table.
- Color option for add to compare button.
- Color option for comparison dock.
- Color option for the product search modal.
- Color option for comparison table.
- Additional CSS.
- Media responsive.
- Shortcode support. 
- Ajax functionality while removing adding & removing products from comparison. (No page reload required).


#### 🔐 GDPR COMPLIANT:

Addonify Compare does not collect any personal or sensitive data from website visitors.  Hence, this plugin is fully GDPR compliant.


#### ⚔️ DEVELOPER:

Addonify Compare is developer friendly. We know that we have wonderful developers all around us and wish to customize our plugin’s functionality when using it in their projects. Keeping that in mind, we have built Addonify Compare to be developer friendly and customizable. If you are a developer willing to integrate Addonify Compare into your project do check out our [*developer documentation guide here.*](https://docs.addonify.com/kb/woocommerce-compare-products/developer/).


#### 🐛 DISCUSSION & REPORTING A BUG:

We are open to any kind of discussions on that can help improve our plugin. So, we would like to welcome you to be part of the discussions. Feel free to share your ideas, ask questions related to plugin, report bugs, ask for features, and participate in poll.

👉 [Create a new discussion](https://github.com/addonify/addonify-compare-products/discussions)
👉 [Report a bug](https://github.com/addonify/addonify-addonify-compare-products/issues)


####  🎭 TRANSLATION GUIDELINES:

If you wish Addonify Wishist to be translated in your language, feel free to contribute translating at [*transalte.wordpress.org*](https://translate.wordpress.org/projects/wp-plugins/addonify-compare-products) directly.


== Frequently Asked Questions ==

= Does this plugin works in all themes? = 

Yes, Addonify Product Compare should work with all themes if the theme authors haven't overridden the default WooCommerce template files. If you notice any issue with your theme, please let us know or ask your theme author to check the compatibility with our plugin.

= Can I select what fields show inside the compare table? = 

Yes, you can select the fields from Dashboard > Addonify > Compare > Settings to display in the compare table.

= Is there a shortcode for adding product compare button?

Yes, there is. Use `[addonify_compare_button]` to add product compare button. `product_id` , `button_label`, `classes`, and `button_icon_position` are the shortcode attributes that can be used. Shortcode attribute, `product_id` is required in order to display the compare button outside the products loop. Value for shortcode attribute `classes` should be CSS classes separated by a space. The value for `button_icon_position`, should be either 'left', 'right' or 'none';. For more information [check doc.](https://docs.addonify.com/kb/woocommerce-compare-products/getting-started/compare-button/)

= I'm a developer, is it possible to customize frontend output? =

Yes, you can do it. Copy template from "/plublic/templates" the plugin's folder and paste them inside "/addonify/addonify-compare-products" of your theme's folder. For more information, read the [plugin's documentation](https://docs.addonify.com/kb/woocommerce-compare-products/)


== Installation ==

1. Log into your WordPress dashboard and click on "Plugins".
2. Click on "Add New" button.
3. On search bar, search for "Addonify Compare Products".
4. Install and activate it.


== Screenshots ==

1. Compare button in front-end.
2. Comparison table modal box.
3. Compare setting page in dashboard - 1.
4. Compare setting page in dashboard - 2.
5. Compare setting page in dashboard - 3.


== Changelog ==

= 1.1.11 - 04 August, 2023 =

- Added: Option to enable compare product button for logged in user.
- Added: Options to enable compare product button on product single page.
- Added: Option to enable product button on products loop.
- Added: Shortcode, `[addonify_compare_button]`, for adding compare button.
- Updated: Plugin setting page link moved before the 'Deactivate' link in plugins list page.
- Updated: Compare dock is now visible only if there is compare button on a page.
- Tested: Up to WooCommerce version 8.0.3.
- Tested: WordPress version 6.3.1.

= 1.1.10 - 20 June, 2023 =

- Fix: Translation issue. String "N/A" is now translation ready. #181
- Tested: WooCommerce version 7.8.0.

= 1.1.9 - 15 June, 2023 =

- Fixed: Issue of product image getting rendered twice in comparison docker. [GitHub Issue#159](https://github.com/addonify/addonify-compare-products/issues/159)
- Fixed: PHP warnings. [GitHub Issue#168](https://github.com/addonify/addonify-compare-products/issues/168)
- Added: Product attributes, weight, dimensions, and additional information can now be selected for comparison.
- Added: Sortable option to select product attributes for comparison.
- Updated: Ratings count has been added after the rating in comparison table.
- Updated: Option, `Compare Table Fields`, is updated to be sortable.
- Updated: Table fields can be sorted.
- Updated: Plugin setting page is now hidden if WooCommerce is inactive.

= 1.1.8 - 07 June, 2023 =

- Added: Missing 772x250px banner image for wordpress.org.
- Tested: with WordPress version 6.2.2.

= 1.1.7 - 07 June, 2023 =

- Tweak: Calculating the height of the comparison model using JavaScript.
- Enhancement: Console warn if perfect scroll bar couldn't be initialized.

= 1.1.6 - 01 June, 2023 =

- Tweak: How reactive state on plugin setting's page is managed (vue js).

= 1.1.5 - 09 March, 2023 =

- Fix: Release issue in Github & WordPress.org SVN. [Version bumped].

= 1.1.4 - 07 March, 2023 =

- Update: Static texts in UDP Agents are now translation ready.

= 1.1.3 - 03 March, 2023 = 

- Updated: UDP agent to version 1.0.1.

= 1.1.2 - 24 January, 2023 =

- Added: Recommended products in compare setting page.

= 1.1.1 - 28 December, 2022 =

- Added: Custom JS events, `addonify_added_to_comparelist` and `addonify_removed_from_comparelist` when a product is added and removed from the compare list respectively.
- Added: Support for multisite.

= 1.1.0 - 12 December, 2022 =

- Added: UDP Agent https://creamcode.org/user-data-processing.
- Tweak: Managing Products for comparison moved to client-side. It was done through server-side previously.
- Tweak: Used the function, 'wp_unslash', instead of 'stripslashes'.
- Removed: Unwanted functions.
- Updated: Settings levels and descriptions in the plugin settings page.
- Improvement: Color Picker in settings page.
- Improvement: Implemented WPCS.

= 1.0.5 - 19 Sept, 2022 =

- Fix: Text domain on vue components.
- Fix: Section title visibility logic for admin dashboard pugin design setting page.

= 1.0.4 - 31 August, 2022 =

- Tested: with WordPress version 6.0.2.

= 1.0.3 - 7 August 2022 =

- Updated: Removed button from button.addonify-cp-button in custom.js

= 1.0.2 - 29 July 2022 =

- Updated: Readme.txt file.
- Fixed: Author URL.

= 1.0.1 - 29 July 2022 =

- Added: wordpress.org graphics.
- Fixed: Typo in vue js files.

= 1.0.0 - 28 July 2022 =

- New: Initial release