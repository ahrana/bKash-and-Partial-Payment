<?php
/*
Plugin Name: BanglaPress Payment - bKash and Partial Payment
Description: One-click bKash payments on landing pages, accept partial payments, and provide checkout options with bKash, Nagad, Rocket, and Upay for WooCommerce.
Version: 1.0
Author: Anowar Hossain Rana
Author URI: https://cxrana.wordpress.com/
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the settings file
require_once plugin_dir_path(__FILE__) . 'bp-bkash-settings.php';
// Include the payment method class
function include_mobile_banking_gateway() {
    if (class_exists('WC_Payment_Gateway')) {
        include_once 'class-wc-gateway-mobile-banking.php';
    }
}
add_action('plugins_loaded', 'include_mobile_banking_gateway', 11);

// Enqueue Admin Scripts and Styles
function bkash_enqueue_admin_styles() {
    wp_enqueue_style('bkash-admin-style', plugin_dir_url(__FILE__) . 'assets/bkash-admin-style.css');
	 wp_enqueue_script('admin-scripts', plugin_dir_url(__FILE__) . 'assets/js/admin-scripts.js', array('jquery'), '1.0.0', true);
	    

}

add_action('admin_enqueue_scripts', 'bkash_enqueue_admin_styles');



class WC_BKASH_Manual_Payment {

    public function __construct() {
        add_shortcode('bkash_payment_button', array($this, 'bkash_payment_button'));
        add_action('wp_footer', array($this, 'bkash_payment_popup'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_process_bkash_payment', array($this, 'process_bkash_payment'));
        add_action('wp_ajax_nopriv_process_bkash_payment', array($this, 'process_bkash_payment'));
        add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'display_bkash_order_details'), 10, 1);
        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_bkash_payment_meta'));
		
    }

    // Enqueue Scripts and Styles
public function enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_style('bkash-style', plugin_dir_url(__FILE__) . 'assets/bkash-style.css', array(), '1.0.0');
	 // Enqueue the custom CSS file for the bKash payment
   wp_enqueue_style('mobile-banking-style', plugin_dir_url(__FILE__) . 'assets/mobile-banking.css');


}


       // Create Shortcode Button
public function bkash_payment_button($atts) {
    // Set default attributes
    $atts = shortcode_atts(array(
        'product_id' => '',
        'label' => '', // Add label attribute
        'style' => '' // Add style attribute
    ), $atts);

    if (empty($atts['product_id'])) {
        return '<div class="error">Please set a product ID!</div>';
    }

    // Retrieve the button label from the settings
    $options = get_option('bkash_options');
    $button_label = !empty($atts['label']) ? $atts['label'] : (!empty($options['button_label']) ? $options['button_label'] : 'Buy with bKash');

	// Fetch product details
    $product_id = intval($atts['product_id']);
    $product = wc_get_product($product_id);
    $product_price = $product ? $product->get_price() : 0;
	
	// Capture any custom styles from the style attribute
    $button_style = !empty($atts['style']) ? esc_attr($atts['style']) : '';

    ob_start();
    ?>
   <button class="bkash-payment-button" 
            data-product-id="<?php echo esc_attr($atts['product_id']); ?>" 
            data-product-price="<?php echo esc_attr($product_price); ?>" 
            style="<?php echo $button_style; ?>">
        <?php echo esc_html($button_label); ?>
    </button>
    

   
        
        <script>
jQuery(document).ready(function ($) {
    $('.bkash-payment-button').on('click', function () {
        var productId = $(this).data('product-id');
        $('#bkashPaymentModal').data('product-id', productId).fadeIn();
        $('#bkashCheckoutContainer').html('<div>Loading checkout...</div>');

        var billingEmail = '<?php echo esc_js(wp_get_current_user()->user_email); ?>';
        
        // Fetch labels from settings
        var checkoutHeaderLabel = '<?php echo esc_js($options['checkout_header_label'] ?? 'Checkout'); ?>';
        var firstNameLabel = '<?php echo esc_js($options['first_name_label'] ?? 'First Name'); ?>';
        var emailLabel = '<?php echo esc_js($options['email_label'] ?? 'Email'); ?>';
        var phoneLabel = '<?php echo esc_js($options['phone_label'] ?? 'Phone'); ?>';
        var transactionIdLabel = '<?php echo esc_js($options['transaction_id_label'] ?? 'Transaction ID'); ?>';
        var submitButtonLabel = '<?php echo esc_js($options['submit_button_label'] ?? 'Proceed to Payment'); ?>';
        var processingMessage = '<?php echo esc_js($options['processing_message'] ?? 'Please wait, processing your payment...'); ?>';
       
	   var formHtml = `
            <form id="bkashCheckoutForm">
                <h3>${checkoutHeaderLabel}</h3>
                <label for="bkash_first_name">${firstNameLabel}:</label>
                <input type="text" id="bkash_first_name" name="billing_first_name" required>
                <label for="bkash_email">${emailLabel}:</label>
                <input type="email" id="bkash_email" name="billing_email" value="${billingEmail}" required>
                <label for="bkash_phone">${phoneLabel}:</label>
                <input type="tel" id="bkash_phone" name="billing_phone" required>
                <label for="bkash_last_digit">${transactionIdLabel}:</label>
                <input type="text" id="bkash_last_digit" name="bkash_last_digit" required>
                <input type="hidden" name="product_id" value="${productId}">
                <input type="hidden" id="payment_method" name="payment_method" value="">

                <button type="submit">${submitButtonLabel}</button>
            </form>
            <div id="processingMessage" style="display: none; margin-top: 10px;">${processingMessage}</div>
        `;
        $('#bkashCheckoutContainer').html(formHtml);

        // Set the payment method based on the selected radio button
        $('input[name="operator"]').on('change', function () {
            var selectedPaymentMethod = $(this).val();
            $('#payment_method').val(selectedPaymentMethod);
        });
        // Trigger change event on page load to set default value
        $('input[name="operator"]:checked').trigger('change');
    });

    $(document).on('click', '.close', function () {
        $('#bkashPaymentModal').fadeOut();
    });

    $(document).on('click', function (event) {
        if ($(event.target).is('#bkashPaymentModal')) {
            $('#bkashPaymentModal').fadeOut();
        }
    });

    $(document).on('submit', '#bkashCheckoutForm', function (e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $('#processingMessage').fadeIn();

        $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
            action: 'process_bkash_payment',
            data: formData
        }, function (response) {
            $('#processingMessage').fadeOut();

            if (response.success) {
                window.location.href = response.data.redirect_url;
            } else {
                // Display error message instead of alert
                $('#bkashCheckoutContainer').prepend('<div class="error" style="color: red;">' + response.data.message + '</div>');
            }
        });
    });
});
</script>



        <?php
        return ob_get_clean();
    }


public function bkash_payment_popup() {
 $options = get_option('bkash_options');
    ?>
    <!-- Modal Structure, initially hidden -->
    <div id="bkashPaymentModal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
 
            <div class="modal-body" style="display: flex;">
                <div class="form-column" style="flex: 1; padding-right: 20px;">
                    <div id="bkashCheckoutContainer"></div>
                </div>
                <div class="logo-column" style="flex: 1; text-align: center;">
    <?php
$options = get_option('bkash_options');
?>

<div id="mobileOperator">
    <label>
        <input type="radio" name="operator" value="bkash" checked> <?php echo esc_html($options['bkash_label'] ?? 'bKash'); ?>
    </label>
    <label>
        <input type="radio" name="operator" value="nagad"> <?php echo esc_html($options['nagad_label'] ?? 'Nagad'); ?>
    </label>
    <label>
        <input type="radio" name="operator" value="rocket"> <?php echo esc_html($options['rocket_label'] ?? 'Rocket'); ?>
    </label>
    <label>
        <input type="radio" name="operator" value="upay"> <?php echo esc_html($options['upay_label'] ?? 'Upay'); ?>
    </label>
</div>

    <div id="mobileInfo" style="margin-top: 20px;">
        <p id="mobileNumber" style="font-weight: bold;"></p>
        <img id="operatorLogo" src="" alt="Mobile Operator Logo">
        <p id="instructions" style="font-style: italic;"></p>
		<?php
$options = get_option('bkash_options');
$pay_label = esc_html($options['pay_label'] ?? 'Pay');
?>

<p style="
    font-style: italic; 
    font-weight: normal; 
    margin-top: 5px; 
    font-size: 16px; 
    color: #555; 
    background-color: #f3f3f3; 
    padding: 8px; 
    border: 1px solid #ddd; 
    border-radius: 3px; 
    display: flex; 
    align-items: center; 
    justify-content: center;"> <!-- Centering the content -->
    <?php echo $pay_label; ?> 
    <span id="productPrice" style="
        color: #E2126E; 
        font-size: 18px; 
        font-weight: bold; 
        margin: 0 5px;"> 
    </span> 
    ৳
</p>


    
	</div>
	
</div>

<script>
    jQuery(document).ready(function ($) {
        // Define default logos
        var defaultLogos = {
            'bkash': '<?php echo plugin_dir_url(__FILE__) . 'assets/icons/bKash-logo.png'; ?>',
            'nagad': '<?php echo plugin_dir_url(__FILE__) . 'assets/icons/nagad.png'; ?>',
            'rocket': '<?php echo plugin_dir_url(__FILE__) . 'assets/icons/rocket.png'; ?>',
            'upay': '<?php echo plugin_dir_url(__FILE__) . 'assets/icons/upay.png'; ?>'
        };

        // Trigger change event on radio button change
        $('input[name="operator"]').on('change', function () {
            var operator = $(this).val();
            var mobileNumber = '';
            var logoSrc = '';
            var instructions = '';
            var productPrice = $('#bkashPaymentModal').data('product-price'); // Retrieve product price

            switch (operator) {
                case 'bkash':
                    mobileNumber = '<?php echo esc_js($options['bkash_number'] ?? '017XXXXXXX'); ?>';
                    logoSrc = '<?php echo esc_url($options['bkash_logo'] ?? ''); ?>' || defaultLogos['bkash'];
                    instructions = '<?php echo esc_js($options['bkash_instruction'] ?? 'Please send money to this number.'); ?>';
                    break;
                case 'nagad':
                    mobileNumber = '<?php echo esc_js($options['nagad_number'] ?? '018XXXXXXX'); ?>';
                    logoSrc = '<?php echo esc_url($options['nagad_logo'] ?? ''); ?>' || defaultLogos['nagad'];
                    instructions = '<?php echo esc_js($options['nagad_instruction'] ?? 'Please send money to this number.'); ?>';
                    break;
                case 'rocket':
                    mobileNumber = '<?php echo esc_js($options['rocket_number'] ?? '019XXXXXXX'); ?>';
                    logoSrc = '<?php echo esc_url($options['rocket_logo'] ?? ''); ?>' || defaultLogos['rocket'];
                    instructions = '<?php echo esc_js($options['rocket_instruction'] ?? 'Please send money to this number.'); ?>';
                    break;
                case 'upay':
                    mobileNumber = '<?php echo esc_js($options['upay_number'] ?? '016XXXXXXX'); ?>';
                    logoSrc = '<?php echo esc_url($options['upay_logo'] ?? ''); ?>' || defaultLogos['upay'];
                    instructions = '<?php echo esc_js($options['upay_instruction'] ?? 'Please send money to this number.'); ?>';
                    break;
            }

            // If no custom logo, use the default logo
            if (logoSrc === '') {
                logoSrc = defaultLogos[operator];
            }

            // Update the mobile info
            $('#mobileNumber').text(mobileNumber);
            $('#operatorLogo').attr('src', logoSrc).toggle(logoSrc !== '');
            $('#instructions').text(instructions);
            $('#productPrice').text(productPrice); // Display only the product price


        });

        // Trigger change event on page load to set default values
        $('input[name="operator"]:checked').trigger('change');

        // Update the modal with product price when the button is clicked
        $('.bkash-payment-button').on('click', function () {
            var productId = $(this).data('product-id');
            var productPrice = $(this).data('product-price'); // Fetch product price from button data attribute

            // Set product price in the modal
            $('#bkashPaymentModal').data('product-price', productPrice);

            // Trigger change to update the info
            $('input[name="operator"]:checked').trigger('change');
        });
    });
</script>





    <?php
	
}


 // In the process_bkash_payment method
public function process_bkash_payment() {
    if (!isset($_POST['data'])) {
        wp_send_json_error(array('message' => 'All fields are required.'));
        wp_die();
    }

    parse_str($_POST['data'], $form_data);

    // Sanitize input data
    $first_name = sanitize_text_field($form_data['billing_first_name']);
    $email = sanitize_email($form_data['billing_email']);
    $phone = sanitize_text_field($form_data['billing_phone']);
    $bkash_last_digit = sanitize_text_field($form_data['bkash_last_digit']);
    $product_id = intval($form_data['product_id']);
    $payment_method = sanitize_text_field($form_data['payment_method']); // Capture payment method

    $order = wc_create_order();
    $product = wc_get_product($product_id);

    if ($product) {
        $order->add_product($product, 1); 

        // Set billing address
        $order->set_address(array(
            'first_name' => $first_name,
            'email'      => $email,
            'phone'      => $phone,
        ), 'billing');

        // Set customer ID to 0 for guest orders
        $order->set_customer_id(0); // No associated user account

        // Set order status and add notes
		$order->set_status('on-hold'); // Set order status to on hold
        $order->add_order_note('Payment made through ' . $payment_method . '.'); // Add payment note
        $order->calculate_totals(); // Calculate the totals
        $order->save(); // Save the order

        // Save the bKash Last Digit
        update_post_meta($order->get_id(), '_bkash_last_digit', $bkash_last_digit);
        // Save the payment method to order meta
        update_post_meta($order->get_id(), '_payment_method', $payment_method);

        // Redirect to the WooCommerce order complete page
        wp_send_json_success(array('redirect_url' => $order->get_checkout_order_received_url())); // Redirect to order complete page
    } else {
        wp_send_json_error(array('message' => 'Invalid product.'));
    }

    wp_die();
}



    // Save Order Meta
	
    public function save_bkash_payment_meta($order_id) {
        if (isset($_POST['billing_first_name'])) {
            update_post_meta($order_id, '_billing_first_name', sanitize_text_field($_POST['billing_first_name']));
        }
        if (isset($_POST['billing_email'])) {
            update_post_meta($order_id, '_billing_email', sanitize_email($_POST['billing_email']));
        }
        if (isset($_POST['billing_phone'])) {
            update_post_meta($order_id, '_billing_phone', sanitize_text_field($_POST['billing_phone']));
        }
        if (isset($_POST['bkash_last_digit'])) {
            update_post_meta($order_id, '_bkash_last_digit', sanitize_text_field($_POST['bkash_last_digit']));
        }
    }
	
	
    public function display_bkash_order_details($order) {
    // Retrieve custom fields
    $first_name = get_post_meta($order->get_id(), '_billing_first_name', true);
    $email = get_post_meta($order->get_id(), '_billing_email', true);
    $phone = get_post_meta($order->get_id(), '_billing_phone', true);
    $bkash_last_digit = get_post_meta($order->get_id(), '_bkash_last_digit', true);
    $payment_method = get_post_meta($order->get_id(), '_payment_method', true);

    echo '<div class="bkash-order-details">';
    echo '<h2>' . __('Payment Info', 'textdomain') . '</h4>';
    if (!empty($first_name)) {
        echo '<p><strong>' . __('Name:', 'textdomain') . '</strong> ' . esc_html($first_name) . '</p>';
    }
    if (!empty($email)) {
        echo '<p><strong>' . __('Email:', 'textdomain') . '</strong> ' . esc_html($email) . '</p>';
    }
    if (!empty($phone)) {
        echo '<p><strong>' . __('Phone:', 'textdomain') . '</strong> ' . esc_html($phone) . '</p>';
    }
    if (!empty($bkash_last_digit)) {
        echo '<p><strong>' . __('Transaction ID:', 'textdomain') . '</strong> ' . esc_html($bkash_last_digit) . '</p>';
    }
    if (!empty($payment_method)) {
        echo '<p><strong>' . __('Payment Method:', 'textdomain') . '</strong> ' . esc_html(ucfirst($payment_method)) . '</p>';
    }
    echo '</div>';
}

}

new WC_BKASH_Manual_Payment();
