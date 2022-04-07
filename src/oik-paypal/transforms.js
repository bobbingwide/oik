/* Transformation of [paypal] to oik/paypal and acf/paypal to oik/paypal
 *
 * This was copied and not fully cobbled from oik-address.
 * where the tag attribute was div/span
 *
 * I'll have to read up on the stuff to copy attributes
 * and apply it for: type, amount, product_name, product_sku and shipping_address_required
 */
;
import { createBlock } from '@wordpress/blocks';

const transforms = {
    from: [
        {
            type: 'block',
            blocks: ['acf/paypal'],
            transform: function (attributes) {
                return createBlock('oik/paypal', {
                    type: attributes.type,
                    amount: attributes.amount,
                    productname: attributes.product_name,
                    sku: attributes.product_sku,
                    shipadd: attributes.shipping_address_required

                });
            },
        },
        {
            type: 'shortcode',
            tag: 'paypal',
            attributes: {
                type: {
                    type: 'string',
                    shortcode: ( { named: { type } } ) => {
                        return type;
                    },
                },
                amount: {
                    type: 'number',
                    shortcode: ( { named: { amount } } ) => {
                        return amount;
                    },
                },
                productname: {
                    type: 'string',
                    shortcode: ( { named: { productname } } ) => {
                        return productname;
                    },
                },
                sku: {
                    type: 'string',
                    shortcode: ( { named: { sku } } ) => {
                        return sku;
                    },
                },
                shipadd: {
                    type: 'string',
                    shortcode: ( { named: { shipadd } } ) => {
                        return shipadd;
                    },
                },
                email: {
                    type: 'string',
                    shortcode: ( { named: { email } } ) => {
                        return email;
                    },
                },
                currency: {
                    type: 'string',
                    shortcode: ( { named: { currency } } ) => {
                        return currency;
                    },
                },
                location: {
                    type: 'string',
                    shortcode: ( { named: { location } } ) => {
                        return location;
                    },
                },
               shipcost: {
                    type: 'number',
                    shortcode: ( { named: { shipcost } } ) => {
                        return shipcost;
                    },
                },
            },
        },
    ]
};

export { transforms } ;