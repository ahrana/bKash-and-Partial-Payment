<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register settings
function bkash_register_settings() {
    register_setting('bkash_options_group', 'bkash_options');
}

add_action('admin_init', 'bkash_register_settings');

// Create settings page
function bkash_settings_page() {
    $options = get_option('bkash_options');
    ?>
    <div class="wrap bkash-settings-page">
        <h1 class="bkash-title">BanglaPress Payment - bKash and More Settings: Button</h1>
        
       <!-- Link Button to WooCommerce Settings Checkout Page -->
<a href="admin.php?page=wc-settings&tab=checkout" class="flat-bkash-link-button">WooCommerce Payment Settings</a>

<!-- Another Link Button (if needed) -->
<a href="javascript:void(0);" class="flat-bkash-link-button" onclick="showHelpPopup()">Help</a>

<div id="help-popup-overlay" class="help-popup-overlay" onclick="closeHelpPopup()">
    <div id="help-popup-content" class="help-popup-content" onclick="event.stopPropagation()">
        <div class="popup-header">
            <span class="dashicons dashicons-editor-help"></span>
            <h2>Plugin Instructions</h2>
            <button class="popup-close-button" onclick="closeHelpPopup()"><span class="dashicons dashicons-no"></span></button>
        </div>
        <div class="help-popup-content-area">
            <p><strong><span class="dashicons dashicons-admin-generic icon-blue"></span> 1. Adding Button on Any Page Using Shortcode</strong><br>
                Use the shortcode <code>[bkash_payment_button product_id="YOUR_PRODUCT_ID"]</code> to add a payment button on any page.</p>
            <p><strong><span class="dashicons dashicons-cart icon-green"></span> 2. Use in Checkout Page</strong><br>
                Ensure the button is enabled on the WooCommerce checkout page for seamless transactions.</p>
            <p><strong><span class="dashicons dashicons-admin-settings icon-orange"></span> 3. Enable Partial Payment in WooCommerce Settings</strong><br>
                Go to WooCommerce settings and enable the partial payment option for split payments.</p>
            <p><strong><span class="dashicons dashicons-edit icon-purple"></span> 4. Customize Shortcode in Settings Page</strong><br>
                You can edit button styles and label text in the settings page.</p>
            <p><strong><span class="dashicons dashicons-editor-removeformatting icon-red"></span> 5. Fix Checkout Page Issues</strong><br>
                If the checkout page is unresponsive, try switching to the Block Editor or Classic Editor, removing the default checkout, and adding the shortcode <code>[woocommerce_checkout]</code></p>
        </div>
        <div class="popup-footer">
            <p>If you feel this plugin is helpful, please consider a small donation to support further development:</p>
            <a href="https://learnwithrana.com/buy-me-a-coffee/" target="_blank" class="donation-button"><span class="dashicons dashicons-heart"></span> Donate</a>
        </div>
    </div>
</div>


        <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated']) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e('Settings saved successfully!', 'bkash'); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="options.php" class="bkash-form">
            <?php settings_fields('bkash_options_group'); ?>
            
            <div class="bkash-section">
                <h3 class="bkash-heading">Pay Button Settings</h3>
                <table class="form-table bkash-form-table">
                    <tr valign="top">
                        <th scope="row">Button Label</th>
                        <td><input type="text" name="bkash_options[button_label]" value="<?php echo esc_attr($options['button_label'] ?? 'Buy with bKash'); ?>" /></td>
                    </tr>
					<tr valign="top">
    <th scope="row">Product Amount Label</th>
    <td><input type="text" name="bkash_options[pay_label]" value="<?php echo esc_attr($options['pay_label'] ?? 'Pay'); ?>" /></td>
</tr>


                </table>
            </div>
			

            <div class="bkash-separator"></div> <!-- Separator -->

            <div class="bkash-section">
                <h3 class="bkash-heading">Checkout Form Settings</h3>
                <table class="form-table bkash-form-table">
                    <tr valign="top">
                        <th scope="row">Checkout Header Label</th>
                        <td><input type="text" name="bkash_options[checkout_header_label]" value="<?php echo esc_attr($options['checkout_header_label'] ?? 'Checkout'); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Name Label</th>
                        <td><input type="text" name="bkash_options[first_name_label]" value="<?php echo esc_attr($options['first_name_label'] ?? 'Name'); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Email Label</th>
                        <td><input type="text" name="bkash_options[email_label]" value="<?php echo esc_attr($options['email_label'] ?? 'Email'); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Phone Label</th>
                        <td><input type="text" name="bkash_options[phone_label]" value="<?php echo esc_attr($options['phone_label'] ?? 'Phone'); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Transaction ID Label</th>
                        <td><input type="text" name="bkash_options[transaction_id_label]" value="<?php echo esc_attr($options['transaction_id_label'] ?? 'Transaction ID'); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Submit Button Label</th>
                        <td><input type="text" name="bkash_options[submit_button_label]" value="<?php echo esc_attr($options['submit_button_label'] ?? 'Proceed to Payment'); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Processing Message</th>
                        <td><input type="text" name="bkash_options[processing_message]" value="<?php echo esc_attr($options['processing_message'] ?? 'Please wait, processing your payment...'); ?>" /></td>
                    </tr>
                </table>
            </div>

            <div class="bkash-separator"></div> <!-- Separator -->

            <div class="bkash-section">
                <h3 class="bkash-heading">bKash Settings</h3>
                <table class="form-table bkash-form-table">
                    <tr valign="top">
                        <th scope="row">bKash Label</th>
                        <td><input type="text" name="bkash_options[bkash_label]" value="<?php echo esc_attr($options['bkash_label'] ?? 'bKash'); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">bKash Number</th>
                        <td><input type="text" name="bkash_options[bkash_number]" value="<?php echo esc_attr($options['bkash_number'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">bKash Logo URL</th>
                        <td><input type="text" name="bkash_options[bkash_logo]" value="<?php echo esc_attr($options['bkash_logo'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">bKash Instruction</th>
                        <td><textarea name="bkash_options[bkash_instruction]"><?php echo esc_textarea($options['bkash_instruction'] ?? ''); ?></textarea></td>
                    </tr>
                </table>
            </div>

            <div class="bkash-separator"></div> <!-- Separator -->

            <div class="bkash-section">
                <h3 class="bkash-heading">Nagad Settings</h3>
                <table class="form-table bkash-form-table">
                    <tr valign="top">
                        <th scope="row">Nagad Label</th>
                        <td><input type="text" name="bkash_options[nagad_label]" value="<?php echo esc_attr($options['nagad_label'] ?? 'Nagad'); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Nagad Number</th>
                        <td><input type="text" name="bkash_options[nagad_number]" value="<?php echo esc_attr($options['nagad_number'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Nagad Logo URL</th>
                        <td><input type="text" name="bkash_options[nagad_logo]" value="<?php echo esc_attr($options['nagad_logo'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Nagad Instruction</th>
                        <td><textarea name="bkash_options[nagad_instruction]"><?php echo esc_textarea($options['nagad_instruction'] ?? ''); ?></textarea></td>
                    </tr>
                </table>
            </div>

            <div class="bkash-separator"></div> <!-- Separator -->

            <div class="bkash-section">
                <h3 class="bkash-heading">Rocket Settings</h3>
                <table class="form-table bkash-form-table">
                    <tr valign="top">
                        <th scope="row">Rocket Label</th>
                        <td><input type="text" name="bkash_options[rocket_label]" value="<?php echo esc_attr($options['rocket_label'] ?? 'Rocket'); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Rocket Number</th>
                        <td><input type="text" name="bkash_options[rocket_number]" value="<?php echo esc_attr($options['rocket_number'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Rocket Logo URL</th>
                        <td><input type="text" name="bkash_options[rocket_logo]" value="<?php echo esc_attr($options['rocket_logo'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Rocket Instruction</th>
                        <td><textarea name="bkash_options[rocket_instruction]"><?php echo esc_textarea($options['rocket_instruction'] ?? ''); ?></textarea></td>
                    </tr>
                </table>
            </div>

            <div class="bkash-separator"></div> <!-- Separator -->

            <div class="bkash-section">
                <h3 class="bkash-heading">Upay Settings</h3>
                <table class="form-table bkash-form-table">
                    <tr valign="top">
                        <th scope="row">Upay Label</th>
                        <td><input type="text" name="bkash_options[upay_label]" value="<?php echo esc_attr($options['upay_label'] ?? 'Upay'); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Upay Number</th>
                        <td><input type="text" name="bkash_options[upay_number]" value="<?php echo esc_attr($options['upay_number'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Upay Logo URL</th>
                        <td><input type="text" name="bkash_options[upay_logo]" value="<?php echo esc_attr($options['upay_logo'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Upay Instruction</th>
                        <td><textarea name="bkash_options[upay_instruction]"><?php echo esc_textarea($options['upay_instruction'] ?? ''); ?></textarea></td>
                    </tr>
                </table>
            </div>

            <div class="bkash-separator"></div> <!-- Separator -->

		<!-- Shortcode Section -->
<div class="bkash-shortcode-section">
    <h3 class="bkash-heading">Payment Button Shortcode</h3>
    <p class="bkash-description">To create a payment button for a specific product, replace the WooCommerce <code>product_id</code> in the shortcode below with the ID of the desired product.</p>
    
    <div class="bkash-shortcodes">
        <!-- First Shortcode -->
        <input type="text" value="[bkash_payment_button product_id='86']" id="bkash-shortcode-1" readonly />
        <button type="button" class="bkash-copy-button" onclick="copyShortcode('bkash-shortcode-1')">Copy Shortcode</button>
        
        <!-- Second Shortcode -->
        <p class="bkash-description">You can create a payment button with a custom Button Text - Example: Pay, Admission, Buy The Product etc. If you want to keep the default, use the 1st shortcode.</p>
        <input type="text" value="[bkash_payment_button product_id='101' label='Pay with bKash']" id="bkash-shortcode-2" readonly />
        <button type="button" class="bkash-copy-button" onclick="copyShortcode('bkash-shortcode-2')">Copy Shortcode</button>

        <!-- Editable Third Shortcode -->
        <p class="bkash-description">To use a custom payment button with style options, you can edit and save the shortcode below:</p>
        <textarea id="bkash-shortcode-3" class="bkash-long-shortcode"></textarea>
        <div class="bkash-shortcode-buttons">
            <button type="button" class="bkash-copy-button" onclick="copyShortcode('bkash-shortcode-3')">Copy Shortcode</button>
            <button type="button" class="bkash-save-button" onclick="saveShortcode()">Save</button>
            <button type="button" class="bkash-reset-button" onclick="resetShortcode()">Reset</button>
        </div>
    </div>

    <!-- Save Button Only -->
    <?php submit_button(); ?>
</div>

<div class="plugin-developer-credit">
    <p>Made with <span class="dashicons dashicons-heart"></span> by <strong>Rana</strong></p>
    <div class="developer-social-links">
        <a href="https://github.com/ahrana/" target="_blank" class="social-link github">
            <span class="dashicons dashicons-admin-plugins"></span>
        </a>
        <a href="https://facebook.com/cxrana" target="_blank" class="social-link facebook">
            <span class="dashicons dashicons-facebook"></span>
        </a>
        <a href="https://cxrana.wordpress.com/" target="_blank" class="social-link wordpress">
            <span class="dashicons dashicons-wordpress"></span>
        </a>
        <a href="https://www.linkedin.com/in/ahrana/" target="_blank" class="social-link linkedin">
            <span class="dashicons dashicons-linkedin"></span>
        </a>
        <a href="https://learnwithrana.com/" target="_blank" class="social-link website">
            <span class="dashicons dashicons-admin-site"></span>
        </a>
    </div>
</div>


    <?php
}

// Add settings page to admin menu
function bkash_add_settings_page() {
    add_menu_page(
        'bKash Settings',
        'bKash Settings',
        'manage_options',
        'bkash-settings',
        'bkash_settings_page',
        'dashicons-money-alt',
        55
    );
}

add_action('admin_menu', 'bkash_add_settings_page');
?>
