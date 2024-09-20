<?php
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'rudr_button_variations', 20, 2 );
function rudr_button_variations( $html, $args ) {
    
    // Extract necessary variables from $args
    $options   = $args['options'];
    $product   = $args['product'];
    $attribute = $args['attribute'];
    $name      = $args['name'] ?: 'attribute_' . sanitize_title( $attribute );

    // Return original HTML if there are no options or no product
    if ( empty( $options ) || ! $product ) {
        return $html;
    }

    // Prepare an array to hold options along with their prices
    $options_with_prices = [];

    /**
     * @var WC_Product_Variable $product
     */
    // Get available variations for the product
    $variations = $product->get_available_variations();

    // Get the count of variation attributes
    $countAttributes = count($product->get_variation_attributes());

    if ($countAttributes > 1) {
        return $html;
    }

    // Loop through the variations to fetch prices and attribute values
    foreach ( $variations as $variation ) {
        $variation_id = $variation['variation_id'];
        $variation_obj = wc_get_product( $variation_id );

        // Ensure the product is a valid variation
        if ( $variation_obj && $variation_obj->is_type( 'variation' ) ) {
            $attributes = $variation_obj->get_attributes();
            $attribute_value = $attributes[strtolower($attribute)] ?? ''; // Get the specific attribute value
            $price = $variation_obj->get_price();

            // Check if the current attribute value is in the options list
            if ( in_array( $attribute_value, $options, true ) ) {
                $key = sanitize_title($attribute_value);
                $options_with_prices[$key] = [
                    'value' => $attribute_value,
                    'name'  => $attribute_value,
                    'price' => $price,
                ];
            }
        }
    }

    // Sort options by price if there's only one attribute
    usort( $options_with_prices, function ( $a, $b ) {
        return $a['price'] - $b['price'];
    });

    // Build the HTML for the variation buttons
    $buttons = '<div class="variation-buttons">';
    $buttons .= '<div class="title-variant" style="width: 100%; padding-left: 5px">Pilih ' . esc_html( $attribute ) . '</div>';

    foreach ( $options_with_prices as $item ) {
        $value = sanitize_text_field( $item['value'] );
        $label = esc_html( $item['name'] );
        $label .= ' - ' . wc_price( $item['price'] );

        // Create button HTML
        $buttons .= '<button type="button" class="variation-button" data-value="' . esc_attr( $value ) . '" data-name="' . esc_attr( $name ) . '">
                        <span>' . $label . '</span>
                     </button>';
    }

    $buttons .= '</div>';

    // Return original select HTML plus the custom buttons
    return $html . $buttons;
}


add_action('wp_head', function () {
   ?>
    <style>
        .variation-buttons {
            display: flex;
            flex-wrap: wrap;
        }

        .variation-button {
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin: 5px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            color: #0b0b0b;
        }

        .variation-button .variation-price {
            font-size: 0.9em;
            color: #555;
        }

        .variation-button.selected {
            background-color: #007cba;
            color: #fff;
        }

        table.variations .label,
        table.variations .value select {
            display: none !important;
        }

    </style>
    <?php
});


add_action('wp_footer', function () {
    ?>
    <script>
        jQuery(document).ready(function($) {
            const selection = $('select[data-attribute_name]');
            // When a variation button is clicked
            $(document).on('click', '.variation-button', function() {
                let $this = $(this);
                let value = $this.data('value');
                let name = $this.data('name');
                let parent = $this.closest('.variation-buttons');

                $('select[name="' + name + '"]').val(value).trigger('change');
                parent.find('.variation-button').removeClass('selected');
                $this.addClass('selected');
            });

            // Function to sync the buttons with the current dropdown selection
            function syncButtonWithSelection() {
                selection.each(function() {
                    let $select = $(this);
                    let name = $select.data('attribute_name');
                    let value = $select.val();

                    // Find the button that corresponds to the select's value and mark it as selected
                    let $button = $('.variation-button[data-value="' + value + '"][data-name="' + name + '"]');

                    // Remove the selected class from all buttons within this group
                    $button.closest('.variation-buttons').find('.variation-button').removeClass('selected');

                    // Add the selected class to the matched button
                    if ($button.length) {
                        $button.addClass('selected');
                    }
                });
            }

            // Trigger sync initially on page load
            syncButtonWithSelection();

            // Update buttons when the user changes the select manually
            selection.on('change', function() {
                syncButtonWithSelection();
            });

            // Handle the reset button to clear selections
            $('.reset_variations').on('click', function(e) {
                e.preventDefault();
                $('select[data-attribute_name]').each(function() {
                    $(this).val('').trigger('change');  // Clear select dropdown
                });
                $('.variation-button').removeClass('selected'); // Clear button selection
            });
        });
    </script>
    <?php
}, PHP_INT_MAX);
