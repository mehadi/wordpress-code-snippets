# Custom Flat Rate Shipping Method Based On Weight for WooCommerce

This code snippet implements a custom flat rate shipping method for WooCommerce, allowing for flexible shipping cost calculation based on destination and weight.

## Setu

1. Copy the provided code into your WordPress theme's `functions.php` file or create a new plugin file.
2. Ensure WooCommerce is installed and activated on your WordPress site.

## Configuration

After adding the code to theme's `functions.php` file, you can configure the following settings in the WooCommerce settings panel:

- **Base Cost for All Bangladesh**: Set the base cost for shipping to all locations in Bangladesh.
- **Base Cost for Dhaka**: Set the base cost for shipping to Dhaka specifically.
- **Additional Cost per Kilogram**: Set the additional cost per kilogram beyond the first kilogram.

## Usage

Once configured, the shipping cost will be calculated dynamically based on the destination and the total weight of the cart contents. When the total weight is over 1kg, it will add the additional per kg cost to the base shipping cost. The shipping cost is calculated according to the provided settings and added to the checkout page.

## Filters

This code provides a filter `woocommerce_custom_shipping_cost` which allows developers to modify the shipping cost before it's applied.

## Customization

Developers can further customize this shipping method by modifying the code according to specific business requirements, such as adding additional conditions or adjusting cost calculation logic.

## Compatibility

This code is compatible with WooCommerce and can be used with any WordPress theme that supports WooCommerce.

## Note

Ensure that the code is properly tested in a staging environment before deploying it to a live site to avoid any unexpected behavior.
