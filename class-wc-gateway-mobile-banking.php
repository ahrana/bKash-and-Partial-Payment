<?php
if (!defined('ABSPATH')) {
    exit;
}

class WC_Gateway_Mobile_Banking extends WC_Payment_Gateway {

    public function __construct() {
        $this->id = 'mobile_banking';
        $this->method_title = __('bKash and More-Partial Payment System', 'woocommerce');
$this->method_description = __(
    'Mobile banking payment gateway integration for WooCommerce. bKash, Nagad, Rocket, and Upay. 
    <div style="margin-top: 10px;">
        <a href="' . admin_url('admin.php?page=bkash-settings') . '" style="display: inline-block; padding: 8px 12px; background-color: #0073aa; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold;" onmouseover="this.style.backgroundColor=\'#005177\'" onmouseout="this.style.backgroundColor=\'#0073aa\'">Landing Page Button</a>
    </div>',
    'woocommerce'
);

        $this->has_fields = true;

        // Load the settings
        $this->init_form_fields();
        $this->init_settings();

        // Settings
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');

        // Save admin options
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

   
// Admin fields for the settings page

	public function init_form_fields() {
    $this->form_fields = array(
        'enabled' => array(
            'title'       => __('Enable/Disable', 'woocommerce'),
            'type'        => 'checkbox',
            'label'       => __('Enable Mobile Banking Payment', 'woocommerce'),
            'default'     => 'yes',
        ),
        'title' => array(
            'title'       => __('Title', 'woocommerce'),
            'type'        => 'text',
            'description' => __('Payment method title that customers will see at checkout.', 'woocommerce'),
            'default'     => __('Mobile Banking', 'woocommerce'),
            'desc_tip'    => true,
        ),
        'description' => array(
            'title'       => __('Description', 'woocommerce'),
            'type'        => 'textarea',
            'description' => __('Payment method description that customers will see at checkout.', 'woocommerce'),
            'default'     => __('Pay using bKash, Nagad, Rocket, or Upay. Take Full Payment of Partial Payment.', 'woocommerce'),
            'desc_tip'    => true,
        ),
 // Separator for Payment Method Settings
        'payment_method_separator' => array(
            'type' => 'title',
            'title' => __('Payment Method Settings', 'woocommerce'), // Title for the section
            'description' => '<hr>', // Horizontal line separator
        ),
        // Group for bKash
        'bkash_enabled' => array(
            'title'       => __('Enable bKash', 'woocommerce'),
            'type'        => 'checkbox',
            'label'       => __('Enable bKash payment method', 'woocommerce'),
            'default'     => 'yes',
        ),
        'bkash_number' => array(
            'title'       => __('bKash Number', 'woocommerce'),
            'type'        => 'text',
            'description' => __('Enter the bKash number to be displayed for payments.', 'woocommerce'),
            'default'     => '',
            'desc_tip'    => true,
        ),
        'bkash_label' => array(
            'title'       => __('bKash Button Label', 'woocommerce'),
            'type'        => 'text',
            'description' => __('Enter the label for the bKash button.', 'woocommerce'),
            'default'     => __('bKash', 'woocommerce'),
            'desc_tip'    => true,
        ),
        // Separator for bKash
        'bkash_separator' => array(
            'type' => 'title',
            'title' => '', // Empty title for the separator
            'description' => '<hr>', // Horizontal line separator
        ),

        // Group for Nagad
        'nagad_enabled' => array(
            'title'       => __('Enable Nagad', 'woocommerce'),
            'type'        => 'checkbox',
            'label'       => __('Enable Nagad payment method', 'woocommerce'),
            'default'     => 'yes',
        ),
        'nagad_number' => array(
            'title'       => __('Nagad Number', 'woocommerce'),
            'type'        => 'text',
            'description' => __('Enter the Nagad number to be displayed for payments.', 'woocommerce'),
            'default'     => '',
            'desc_tip'    => true,
        ),
        'nagad_label' => array(
            'title'       => __('Nagad Button Label', 'woocommerce'),
            'type'        => 'text',
            'description' => __('Enter the label for the Nagad button.', 'woocommerce'),
            'default'     => __('Nagad', 'woocommerce'),
            'desc_tip'    => true,
        ),
        // Separator for Nagad
        'nagad_separator' => array(
            'type' => 'title',
            'title' => '', // Empty title for the separator
            'description' => '<hr>', // Horizontal line separator
        ),

        // Group for Rocket
        'rocket_enabled' => array(
            'title'       => __('Enable Rocket', 'woocommerce'),
            'type'        => 'checkbox',
            'label'       => __('Enable Rocket payment method', 'woocommerce'),
            'default'     => 'yes',
        ),
        'rocket_number' => array(
            'title'       => __('Rocket Number', 'woocommerce'),
            'type'        => 'text',
            'description' => __('Enter the Rocket number to be displayed for payments.', 'woocommerce'),
            'default'     => '',
            'desc_tip'    => true,
        ),
        'rocket_label' => array(
            'title'       => __('Rocket Button Label', 'woocommerce'),
            'type'        => 'text',
            'description' => __('Enter the label for the Rocket button.', 'woocommerce'),
            'default'     => __('Rocket', 'woocommerce'),
            'desc_tip'    => true,
        ),
        // Separator for Rocket
        'rocket_separator' => array(
            'type' => 'title',
            'title' => '', // Empty title for the separator
            'description' => '<hr>', // Horizontal line separator
        ),

		
		// Group for Upay
        'upay_enabled' => array(
            'title'       => __('Enable Upay', 'woocommerce'),
            'type'        => 'checkbox',
            'label'       => __('Enable Upay payment method', 'woocommerce'),
            'default'     => 'yes',
        ),
        'upay_number' => array(
            'title'       => __('Upay Number', 'woocommerce'),
            'type'        => 'text',
            'description' => __('Enter the Upay number to be displayed for payments.', 'woocommerce'),
            'default'     => '',
            'desc_tip'    => true,
        ),
        'upay_label' => array(
            'title'       => __('Upay Button Label', 'woocommerce'),
            'type'        => 'text',
            'description' => __('Enter the label for the Upay button.', 'woocommerce'),
            'default'     => __('Upay', 'woocommerce'),
            'desc_tip'    => true,
        ),
		
        // Separator for Payer Number and Transaction ID
        'payer_transaction_separator' => array(
            'type' => 'title',
            'title' => __('Payer Information', 'woocommerce'), // Title for the new section
            'description' => '<hr>', // Horizontal line separator
        ),

        'payer_number_label' => array(
            'title'       => __('Payer Number Label', 'woocommerce'),
            'type'        => 'text',
            'description' => __('Enter the label for the Payer Number field.', 'woocommerce'),
            'default'     => __('Payer Number:', 'woocommerce'),
            'desc_tip'    => true,
        ),
        'transaction_id_label' => array(
            'title'       => __('Transaction ID Label', 'woocommerce'),
            'type'        => 'text',
            'description' => __('Enter the label for the Transaction ID field.', 'woocommerce'),
            'default'     => __('Transaction ID:', 'woocommerce'),
            'desc_tip'    => true,
        ),
// Separator for Partial and Full Payment Settings
        'partial_full_payment_separator' => array(
            'type' => 'title',
            'title' => __('Partial and Full Payment Options', 'woocommerce'),
            'description' => '<hr>', // Horizontal line separator
        ),
		
        'partial_payment_enabled' => array(
    'title'       => __('Enable Advanced Payment', 'woocommerce'),
    'type'        => 'checkbox',
    'label'       => __('Enable partial payment option to receive advanced or shipping cost', 'woocommerce'),
    'default'     => 'no',
),
'full_payment_label' => array(
    'title'       => __('Full Payment Label', 'woocommerce'),
    'type'        => 'text',
    'description' => __('Enter the label for the full payment option displayed on the checkout page.', 'woocommerce'),
    'default'     => __('Full Payment', 'woocommerce'),
    'desc_tip'    => true,
),
'partial_payment_label' => array(
    'title'       => __('Partial Payment Label', 'woocommerce'),
    'type'        => 'text',
    'description' => __('Enter the label for the partial payment option displayed on the checkout page.', 'woocommerce'),
    'default'     => __('Partial Payment', 'woocommerce'),
    'desc_tip'    => true,
),

		
		
    );
}



  public function payment_fields() {
    // Get admin numbers, labels, and enable settings from settings
    $bkash_enabled = $this->get_option('bkash_enabled') === 'yes';
    $nagad_enabled = $this->get_option('nagad_enabled') === 'yes';
    $rocket_enabled = $this->get_option('rocket_enabled') === 'yes';
    $upay_enabled = $this->get_option('upay_enabled') === 'yes';

    $bkash_number = $this->get_option('bkash_number');
    $nagad_number = $this->get_option('nagad_number');
    $rocket_number = $this->get_option('rocket_number');
    $upay_number = $this->get_option('upay_number');

    // Get button labels
    $bkash_label = $this->get_option('bkash_label', __('bKash', 'woocommerce'));
    $nagad_label = $this->get_option('nagad_label', __('Nagad', 'woocommerce'));
    $rocket_label = $this->get_option('rocket_label', __('Rocket', 'woocommerce'));
    $upay_label = $this->get_option('upay_label', __('Upay', 'woocommerce'));
	
	// Get payer number and transaction ID labels from settings
    $payer_number_label = $this->get_option('payer_number_label', __('Payer Number:', 'woocommerce'));
    $transaction_id_label = $this->get_option('transaction_id_label', __('Transaction ID:', 'woocommerce'));

	  // Check if partial payment is enabled
    $partial_payment_enabled = $this->get_option('partial_payment_enabled') === 'yes';
    $partial_payment_amount = floatval($this->get_option('partial_payment_amount'));


    ?>
	
    <div class="mobile-banking-container">
        <ul class="payment-method-list">
            <?php if ($bkash_enabled) : ?>
                <li class="payment-method-item">
                    <button class="button-39 payment-button" data-value="bkash"><?php echo esc_html($bkash_label); ?></button>
                    <img src="<?php echo plugins_url('assets/icons/bKash-logo.png', __FILE__); ?>" alt="bKash Icon" class="payment-icon">
                    <span id="bkash_number" class="payment-number"><?php echo esc_html($bkash_number); ?></span>
                </li>
            <?php endif; ?>

            <?php if ($nagad_enabled) : ?>
                <li class="payment-method-item">
                    <button class="button-40 payment-button" data-value="nagad"><?php echo esc_html($nagad_label); ?></button>
                    <img src="<?php echo plugins_url('assets/icons/nagad.png', __FILE__); ?>" alt="Nagad Icon" class="payment-icon">
                    <span id="nagad_number" class="payment-number"><?php echo esc_html($nagad_number); ?></span>
                </li>
            <?php endif; ?>

            <?php if ($rocket_enabled) : ?>
                <li class="payment-method-item">
                    <button class="button-41 payment-button" data-value="rocket"><?php echo esc_html($rocket_label); ?></button>
                    <img src="<?php echo plugins_url('assets/icons/rocket.png', __FILE__); ?>" alt="Rocket Icon" class="payment-icon">
                    <span id="rocket_number" class="payment-number"><?php echo esc_html($rocket_number); ?></span>
                </li>
            <?php endif; ?>

            <?php if ($upay_enabled) : ?>
                <li class="payment-method-item">
                    <button class="button-42 payment-button" data-value="upay"><?php echo esc_html($upay_label); ?></button>
                    <img src="<?php echo plugins_url('assets/icons/upay.png', __FILE__); ?>" alt="Upay Icon" class="payment-icon">
                    <span id="upay_number" class="payment-number"><?php echo esc_html($upay_number); ?></span>
                </li>
            <?php endif; ?>
        </ul>

        <input type="hidden" id="mobile_payment_method" name="mobile_payment_method" value="">

        <div class="mobile-banking-form">
            <label class="payer-number-label" for="payer_number"><?php echo esc_html($payer_number_label); ?></label>
<input type="tel" id="payer_number" name="payer_number" required>

<label class="transaction-id-label" for="transaction_id"><?php echo esc_html($transaction_id_label); ?></label>
<input type="text" id="transaction_id" name="transaction_id" required>


           <!-- Partial and Full Payment Options -->
<?php if ($partial_payment_enabled) : ?>
    <?php
    // Get the shipping total
    $shipping_total = WC()->cart->get_shipping_total();
    
    // Get the labels from the admin settings
    $full_payment_label = $this->get_option('full_payment_label');
    $partial_payment_label = $this->get_option('partial_payment_label');
    ?>
    <div class="payment-options">
        <input type="radio" id="full_payment" name="payment_type" value="full" checked>
        <label for="full_payment"><?php echo esc_html($full_payment_label); ?> (<?php echo wc_price(WC()->cart->total); ?>)</label>
        <br>

        <input type="radio" id="partial_payment" name="payment_type" value="partial">
        <label for="partial_payment"><?php echo esc_html($partial_payment_label); ?> (<?php echo wc_price($shipping_total); ?>)</label>
    </div>
<?php endif; ?>


        </div>
    </div>
	
	<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('input[name="payment_type"]').on('change', function() {
            var paymentType = $(this).val();
            var shippingAmount = <?php echo WC()->cart->get_shipping_total(); ?>; // Get shipping total
            var totalAmount = <?php echo WC()->cart->total; ?>; // Get full total amount

            if (paymentType === 'partial') {
                // Set the new total to the total amount minus shipping amount
                var newTotal = totalAmount - shippingAmount;

                // Update the total amount displayed on the checkout page
                $('body .order-total .woocommerce-Price-amount').text(wc_price(newTotal));
            } else {
                // Reset to the full total amount
                $('body .order-total .woocommerce-Price-amount').text(wc_price(totalAmount));
            }
        });
    });
</script>


    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Initially hide the form
        $('.mobile-banking-form').hide();

        // Show the admin number and form based on the selected payment method
        $('.payment-button').on('click', function(event) {
            event.preventDefault(); // Prevent default button behavior

            // Remove zoom and blur effects from all buttons
            $('.payment-button').removeClass('zoom');
            $('.payment-method-item').removeClass('blur');

            // Apply zoom effect to the clicked button
            $(this).addClass('zoom');

            // Blur other buttons
            $(this).closest('.payment-method-item').siblings().addClass('blur');

            // Show the form when any button is clicked
            $('.mobile-banking-form').slideDown();
            
            // Show the selected payment method's number
            var selectedValue = $(this).data('value');
            $('.payment-number').hide(); // Hide all payment numbers first
            $('#' + selectedValue + '_number').show(); // Show the selected payment number

            // Set the selected payment method in the hidden input
            $('#mobile_payment_method').val(selectedValue);
        });
    });
    </script>
    <?php
}



    // Validate payment fields on the checkout page
    public function validate_fields() {
        if (empty($_POST['payer_number']) || empty($_POST['transaction_id']) || empty($_POST['mobile_payment_method'])) {
            wc_add_notice(__('Please select a payment method and enter your payer number and transaction ID.', 'woocommerce'), 'error');
            return false;
        }
        return true;
    }

// Process the payment
public function process_payment($order_id) {
    $order = wc_get_order($order_id);
    $payer_number = sanitize_text_field($_POST['payer_number']);
    $transaction_id = sanitize_text_field($_POST['transaction_id']);
    $payment_method = sanitize_text_field($_POST['mobile_payment_method']);
    $payment_type = isset($_POST['payment_type']) ? sanitize_text_field($_POST['payment_type']) : 'full';
    $partial_payment_amount = floatval($this->get_option('partial_payment_amount'));
    $shipping_amount = floatval($order->get_shipping_total()); // Get shipping amount

    // Determine the total amount to process
    if ($payment_type === 'partial') {
        $amount_to_charge = $shipping_amount; // Charge only the shipping amount
        $remaining_due = $order->get_total() - $amount_to_charge; // Calculate remaining due
        $order->update_meta_data('_partial_payment_due', $remaining_due);
        $order->update_meta_data('_partial_payment_amount', $amount_to_charge); // Store charged amount
        $order->add_order_note(__('Partial payment of ' . wc_price($amount_to_charge) . ' received. Remaining due: ' . wc_price($remaining_due), 'woocommerce'));
    } else {
        $amount_to_charge = $order->get_total(); // Charge the full amount
        $order->update_meta_data('_partial_payment_due', 0);
        $order->add_order_note(__('Full payment received: ' . wc_price($amount_to_charge), 'woocommerce'));
    }

    // Update the order total based on the amount to charge
    $order->set_total($amount_to_charge);

    // Assume payment is completed successfully
    $order->payment_complete();
    $order->add_order_note(__('Payment completed using ' . $payment_method . ' with transaction ID: ' . $transaction_id . ' and payer number: ' . $payer_number, 'woocommerce'));

    // Add payer number and transaction ID to order meta
    $order->update_meta_data('_payer_number', $payer_number);
    $order->update_meta_data('_transaction_id', $transaction_id);
    $order->update_meta_data('_mobile_payment_method', $payment_method);
    $order->save();

	// Mark the order as on-hold (or whatever status you want)
    $order->update_status('on-hold', __('Awaiting mobile banking payment.', 'woocommerce'));

	
    // Return thank you page redirect
    return array(
        'result'   => 'success',
        'redirect' => $this->get_return_url($order),
    );
}

}


// Display mobile banking payment details in admin order details
add_action('woocommerce_admin_order_data_after_order_details', 'display_mobile_banking_order_info', 10, 1);
function display_mobile_banking_order_info($order) {
    $payer_number = $order->get_meta('_payer_number');
    $transaction_id = $order->get_meta('_transaction_id');
    $payment_method = $order->get_meta('_mobile_payment_method');
    $partial_payment_amount = $order->get_meta('_partial_payment_amount');
    $remaining_due = $order->get_meta('_partial_payment_due');

    if ($payer_number || $transaction_id || $payment_method) {
        echo '<div style="margin-top: 20px;">'; // Add margin top to the container

        // Heading with color and size
        echo '<h3 style="color: #007cba; font-size: 16px;">' . __('Mobile Banking Payment Details', 'woocommerce') . '</h3>';
        
        // Details container with padding and border
        echo '<div style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;">';

        // Information label with font size 20px
        echo '<p style="font-size: 16px; margin: 0 0 10px 0; font-weight: bold;">' . __('Please Verify below payment', 'woocommerce') . '</p>';
        
        echo '<p style="margin: 5px 0;"><strong>' . __('Payment Method:', 'woocommerce') . '</strong> ' . esc_html($payment_method) . '</p>';
        echo '<p style="margin: 5px 0;"><strong>' . __('Payer Number:', 'woocommerce') . '</strong> ' . esc_html($payer_number) . '</p>';
        
        // Transaction ID in red color
        echo '<p style="margin: 5px 0; color: red;"><strong>' . __('Transaction ID:', 'woocommerce') . '</strong> ' . esc_html($transaction_id) . '</p>';
        
        // Display partial payment and remaining due if partial payment was selected
        if ($partial_payment_amount) {
            echo '<p style="margin: 5px 0;"><strong>' . __('Partial Payment Amount:', 'woocommerce') . '</strong> ' . wc_price($partial_payment_amount) . '</p>';
            echo '<p style="margin: 5px 0; color: orange;"><strong>' . __('Remaining Due Amount:', 'woocommerce') . '</strong> ' . wc_price($remaining_due) . '</p>';
        }

        echo '</div>'; // Close details container
        echo '</div>'; // Close outer div
    }
}


// Display remaining due amount on the thank you page
add_action('woocommerce_thankyou', 'display_remaining_due_on_thank_you_page', 10, 1);

function display_remaining_due_on_thank_you_page($order_id) {
    $order = wc_get_order($order_id);
    
    // Get the remaining due amount from order meta
    $remaining_due = $order->get_meta('_partial_payment_due');

    // Check if there is a remaining due amount
    if ($remaining_due > 0) {
        echo '<h2>' . __('Remaining Due Amount', 'woocommerce') . '</h2>';
        echo '<p>' . __('You still have a remaining due amount of:', 'woocommerce') . ' ' . wc_price($remaining_due) . '</p>';
    }
}


// Register the gateway
function add_wc_gateway_mobile_banking($methods) {
    $methods[] = 'WC_Gateway_Mobile_Banking';
    return $methods;
}
add_filter('woocommerce_payment_gateways', 'add_wc_gateway_mobile_banking');
