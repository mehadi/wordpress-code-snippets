<?php

/**
 * Custom Flat Rate Shipping Method for WooCommerce
 */

// Check if the class doesn't already exist
if (!class_exists('WC_Custom_Flat_Rate_Shipping_Method')) {
    // Hook into WooCommerce shipping initialization
    add_action('woocommerce_shipping_init', 'custom_flat_rate_shipping_method_init');

    // Initialize custom flat rate shipping method
    function custom_flat_rate_shipping_method_init(): void
    {
        // Define custom shipping method class
        class WC_Custom_Flat_Rate_Shipping_Method extends WC_Shipping_Method
        {
            // Constructor method
            public function __construct()
            {
                // Initialize properties of the shipping method
                $this->id                 = 'flat_rate';
                $this->method_title       = __('Custom Flat Rate', 'woocommerce');
                $this->method_description = __('Custom Flat Rate Shipping Method', 'woocommerce');
                $this->enabled            = 'yes';
                $this->title              = "Custom Flat Rate";
                $this->init();
            }

            // Initialize the shipping method
            function init(): void
            {
                // Initialize form fields and settings
                $this->init_form_fields();
                $this->init_settings();
                // Hook into shipping method settings update
                add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
            }

            // Initialize form fields for settings
            function init_form_fields(): void
            {
                $this->form_fields = array(
                    // Base cost for all of Bangladesh
                    'base_cost_all_bangladesh' => array(
                        'title'       => __('Base Cost for All Bangladesh', 'woocommerce'),
                        'type'        => 'text',
                        'description' => __('Set the base cost for shipping to all Bangladesh.', 'woocommerce'),
                        'default'     => 100,
                    ),
                    // Base cost for Dhaka
                    'base_cost_dhaka'          => array(
                        'title'       => __('Base Cost for Dhaka', 'woocommerce'),
                        'type'        => 'text',
                        'description' => __('Set the base cost for shipping to Dhaka.', 'woocommerce'),
                        'default'     => 80,
                    ),
                    // Additional cost per kilogram
                    'additional_cost_per_kg'  => array(
                        'title'       => __('Additional Cost per Kilogram', 'woocommerce'),
                        'type'        => 'text',
                        'description' => __('Set the additional cost per kilogram beyond the first kilogram.', 'woocommerce'),
                        'default'     => 20,
                    ),
                );
            }

            // Calculate shipping cost
            public function calculate_shipping($package = array())
            {
                // Ensure destination country and state are set
                if (empty($package['destination']['country']) || empty($package['destination']['state'])) {
                    return;
                }

                // Get total weight of cart contents
                $total_weight = WC()->cart->get_cart_contents_weight();
                $shipping_cost = 0;
                $country       = $package['destination']['country'];
                $state         = $package['destination']['state'];

                // Get base cost based on location
                if ($country === 'BD') {
                    if ($state === 'Dhaka' || $state === 'BD-13') {
                        $shipping_cost = isset($this->settings['base_cost_dhaka']) ? wc_format_decimal($this->settings['base_cost_dhaka']) : 0;
                    } else {
                        $shipping_cost = isset($this->settings['base_cost_all_bangladesh']) ? wc_format_decimal($this->settings['base_cost_all_bangladesh']) : 0;
                    }
                }

                // Get additional cost per kilogram
                $additional_cost_per_kg = isset($this->settings['additional_cost_per_kg']) ? wc_format_decimal($this->settings['additional_cost_per_kg']) : 0;

                // Ensure positive values for settings
                $shipping_cost = max(0, $shipping_cost);
                $additional_cost_per_kg = max(0, $additional_cost_per_kg);

                // Calculate additional cost based on weight
                $additional_shipping_cost = max(0, $total_weight - 1) * $additional_cost_per_kg;

                // Calculate total shipping cost
                $shipping_cost += $additional_shipping_cost;

                // Apply filter for custom shipping cost
                $shipping_cost = apply_filters('woocommerce_custom_shipping_cost', $shipping_cost);

                // Set the shipping rate
                $rate = array(
                    'id'    => $this->id,
                    'label' => "Total Shipping Cost",
                    'cost'  => $shipping_cost,
                );
                $this->add_rate($rate);
            }
        }
    }
}

// Function to add custom flat rate shipping method to WooCommerce
function add_custom_flat_rate_shipping_method($methods)
{
    $methods['custom_flat_rate'] = 'WC_Custom_Flat_Rate_Shipping_Method';
    return $methods;
}
// Hook into WooCommerce shipping methods
add_filter('woocommerce_shipping_methods', 'add_custom_flat_rate_shipping_method');
