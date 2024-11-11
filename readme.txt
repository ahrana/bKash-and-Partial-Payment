=== BanglaPress Payment - bKash and Partial Payment ===
Contributors: ahrana  
Tags: WooCommerce, bKash, Nagad, partial payment, Bangladesh  
Requires at least: 5.0  
Tested up to: 6.6.2  
Stable tag: trunk  
License: GPL-2.0+  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

One-click bKash, Nagad, Rocket, and Upay payments for WooCommerce with partial payments, quick checkout, and complete control over checkout options.

== Description ==

**BanglaPress Payment - bKash and Partial Payment** is a versatile and user-friendly WooCommerce plugin for Bangladeshi businesses that brings seamless mobile payment options using popular gateways like **bKash, Nagad, Rocket, and Upay**. The plugin supports **one-click payment**, **partial payments**, and offers a fully customizable checkout experience with direct admin controls for easy management.

### Key Capabilities
- **Instant Payment Button on Any Page**: Add payment buttons anywhere on your website using a shortcode. Customers can pay instantly without navigating to the cart or checkout page.
- **Partial Payment Support**: Accept partial payments for orders, allowing customers to reserve their order with an advance and pay the remainder on delivery.
- **Flexible Payment Gateway Options**: Enable or disable specific payment gateways (bKash, Nagad, Rocket, Upay) based on your store’s needs, with customization options for each.
- **Dynamic Order Summary**: Dues and balances are automatically recalculated and displayed for customers, with detailed records in the order summary.
- **Custom Checkout Form and Button Texts**: Customize checkout field labels, button titles, and payment instructions from the admin dashboard. Add unique branding with custom logos and QR codes.
- **Easy Setup & Integration**: With clear options in the WooCommerce settings page, set up and manage all features without any complex configuration.
- **Free Forever**: No hidden charges or subscription fees – this plugin is free to use.

== Video Walkthrough ==

Watch our **setup and usage guide** for BanglaPress - bKash & More on YouTube:

https://www.youtube.com/watch?v=G-bd01XoVCQ&feature=youtu.be


== Features ==

- 🚀 **One-Click Payment** - Quickly add payment buttons anywhere with a shortcode.
- 🔄 **Partial Payments** - Offer customers the flexibility of partial payments for orders.
- 💳 **Multiple Payment Gateways** - Supports bKash, Nagad, Rocket, and Upay.
- 🖋️ **Customizable Texts & Labels** - Personalize button labels, form fields, and instructions.
- 📊 **Order Summaries** - Dynamic calculation of dues and balances.
- 🎨 **Custom Branding Options** - Use custom logos or QR codes.
- 💸 **Completely Free** - Enjoy all features at no cost.

== Installation ==

1. Download the plugin and upload it to your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure your settings under **WooCommerce > Settings > BanglaPress Payment** to set up mobile payment options, customize labels, and more.

== Usage ==

**Fix Checkout Page Issues**
If the checkout page is unresponsive, try switching to the Block Editor or Classic Editor, removing the default checkout, and adding the shortcode `[woocommerce_checkout]`

To add a payment button to any page, use the following shortcode format:

`[bkash_payment_button product_id="123"]`

- `product_id` - Specify the WooCommerce product ID.
- `label` - (Optional) Customize the button label.
  
**Example**:  
`[bkash_payment_button product_id="101" label="Quick Pay with bKash"]`

== Screenshots ==

1. **One-Click Payment** - Add the payment button anywhere on your site.
2. **Custom Checkout** - Simplified checkout form with custom fields for mobile payments.
3. **Checkput page with partial payment** - accept delivery charge as advance payment.
4. **Settings Page** - Set up payment gateways, logos, and instructions easily.
5. **WC Order** - Verify Payment and Dues.


== Frequently Asked Questions ==

= How do I add a payment button to a page? =  
Use the shortcode `[bkash_payment_button product_id="123"]` and place it on any page or post. Replace `"123"` with the actual WooCommerce product ID.

= Can I change the button color and style? =  
Yes! You can customize the button style directly in the shortcode, for example:  
`[bkash_payment_button product_id="101" label="Pay Now" style="background-color: #E2126E; color: white;"]`

= Can I control which payment gateways are available? =  
Yes, you can enable or disable specific gateways from the settings under WooCommerce > BanglaPress Payment.

= Does this plugin support partial payments? =  
Absolutely. Partial payments can be set up so customers pay only a portion initially and the remainder upon delivery.

== Changelog ==

= 1.0 =  
* Initial release with one-click payment, partial payment support, multi-gateway integration, and customizable labels.

== Key Features ==

- 📱 **One-Click Mobile Payments**  
- 💰 **Flexible Partial Payment Options**  
- 💼 **Support for Popular Gateways: bKash, Nagad, Rocket, Upay**  
- 🔧 **Fully Customizable Checkout and Labels**  
- 📊 **Dynamic Dues & Order Summaries**  
- 🎨 **Add Custom Branding with Logo & QR Codes**  

== Additional Information ==

For more details and support, please visit our [plugin page](https://wordpress.org/plugins/bangla-press/).

== Upgrade Notice ==

= 1.0 =  
Requires WooCommerce version 5.0 or higher for full compatibility.
