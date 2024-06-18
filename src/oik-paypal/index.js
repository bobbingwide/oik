/**
 * Implements [paypal] as a dynamic block.
 * In the editor only the button image is displayed.
 *
 * - Depends on oik for the server rendering.
 *
 * @copyright (C) Copyright Bobbing Wide 2022
 * @author Herb Miller @bobbingwide
 */

import './style.scss';
import './editor.scss';
import { paypal } from './paypal-icon';

import { __ } from '@wordpress/i18n';
import clsx from 'clsx';

import { registerBlockType, createBlock } from '@wordpress/blocks';
import {AlignmentControl, BlockControls, InspectorControls, useBlockProps, PlainText, BlockIcon} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import {
    Toolbar,
    PanelBody,
    PanelRow,
    FormToggle,
    TextControl,
    __experimentalNumberControl as NumberControl,
    TextareaControl,
    SelectControl } from '@wordpress/components';
import { Fragment} from '@wordpress/element';
import { map, partial } from 'lodash';
import { transforms } from './transforms.js';

/**
 * These are the different options for the type attr
 */
const typeOptions =
    {
            "pay": "Pay Now - show debit and credit card logos",
            "pay-noCC": "Pay Now",
            "buy": "Buy Now - show debit and credit card logos",
            "buy-noCC": "Buy Now",
            "donate": "Donate",
            "add": "Add to Cart",
            "view": "View Cart / Checkout"
    };

const currencyOptions =
    { "AUD": "Australian Dollar",
        "CAD": "Canadian Dollar",
        "EUR": "Euro",
        "GBP": "Pound Sterling",
        "JPY": "Japanese Yen",
        "USD": "U.S. Dollar"
    };

// $paypal_currency_list = array("GBP", "USD", "EUR", "AUD", "BRL", "CAD", "CHF", "CZK", "DKK", "HKD", "HUF", "ILS", "JPY", "MXN", "MYR", "NOK", "NZD", "PHP", "PLN", "SEK", "SGD", "THB", "TRY", "TWD");


// The CC suffix will cause the button image to "display debit and credit card logos".
// LG is for large
// and everything is en_GB
const imageOptions =
    { "pay": "https://www.paypal.com/en_GB/i/btn/btn_paynowCC_LG.gif",
      "pay-noCC" : "https://www.paypalobjects.com/en_GB/i/btn/btn_paynow_LG.gif",
      "buy": "https://www.paypal.com/en_GB/i/btn/btn_buynowCC_LG.gif",
      "buy-noCC": "https://www.paypal.com/en_GB/i/btn/btn_buynow_LG.gif",
      "donate": "https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif",
      "add": "https://www.paypal.com/en_GB/i/btn/btn_cart_LG.gif",
      "view": "https://www.paypal.com/en_GB/i/btn/btn_viewcart_LG.gif"
    };

// https://www.paypalobjects.com/en_GB/i/btn/btn_paynow_LG.gif

const shipAddOptions =
    { "0":  "Prompt for an address, but do not require one",
      "1":  "Do not prompt for an address",
      "2":  "Prompt for an address, and require one"
    };

/**
 * Register the block
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik/paypal',

    {
        icon: paypal,
        example: {
            type: 'donate',
            amount: "5.00"
        },
        transforms,

        edit: props => {
            const { attributes, setAttributes, instanceId, focus, isSelected } = props;
            const { textAlign, label } = props.attributes;
            const blockProps = useBlockProps( {
                className: clsx( {
                    [ `has-text-align-${ textAlign }` ]: textAlign,
                } ),
            } );
            function onChangeAttr( key, value ) {
                props.setAttributes( { [key] : value } );
            };

            const image = imageOptions[props.attributes.type];

            return (
                <Fragment>
                    <InspectorControls >
                        <PanelBody>
                            <PanelRow>
                                <SelectControl label={__("Type","oik")} value={props.attributes.type}
                                               options={ map( typeOptions, ( key, label ) => ( { value: label, label: key } ) ) }
                                               onChange={partial( onChangeAttr, 'type' )}
                                />
                            </PanelRow>
                            <PanelRow>
                                <NumberControl label={__("Amount","oik")} value={props.attributes.amount}
                                               isShiftStepEnabled={ true } shiftStep={ 10 }
                                              onChange={partial( onChangeAttr, 'amount' )}
                                />
                            </PanelRow>
                            <PanelRow>
                                <NumberControl label={__("Shipping cost","oik")} value={props.attributes.shipcost}
                                             onChange={partial( onChangeAttr, 'shipcost' )}
                                />
                            </PanelRow>

                            <PanelRow>
                                <SelectControl label={__("Currency","oik")} value={props.attributes.currency}
                                               options={ map( currencyOptions, ( key, label ) => ( { value: label, label: key } ) ) }
                                             onChange={partial( onChangeAttr, 'currency' )}
                                />
                            </PanelRow>

                            <PanelRow>
                                <TextControl label={__("Product Name","oik")} value={props.attributes.productname}
                                             onChange={partial( onChangeAttr, 'productname' )}
                                />
                            </PanelRow>
                            <PanelRow>
                                <TextControl label={__("Product SKU","oik")} value={props.attributes.sku}
                                             onChange={partial( onChangeAttr, 'sku' )}
                                />
                            </PanelRow>
                            <PanelRow>
                                <TextControl label={__("Email","oik")} value={props.attributes.email}
                                             onChange={partial( onChangeAttr, 'email' )}
                                />
                            </PanelRow>
                            <PanelRow>
                                <TextControl label={__("Location","oik")} value={props.attributes.location}
                                             onChange={partial( onChangeAttr, 'location' )}
                                />
                            </PanelRow>

                            <PanelRow>
                                <SelectControl label={__("Shipping Address Required","oik")} value={props.attributes.shipadd}
                                               options={ map( shipAddOptions, ( key, label ) => ( { value: label, label: key } ) ) }
                                               onChange={partial( onChangeAttr, 'shipadd' )}
                                />
                            </PanelRow>
                        </PanelBody>
                    </InspectorControls>
                    <div { ...blockProps}>
                        <img src={image} />

                    </div>
                </Fragment>
            );
        },

        save: props => {
            const image = imageOptions[props.attributes.type];
            const blockProps = useBlockProps.save();
            return (
                <div {...blockProps}>
                    <img src={image} />
                </div>
            );
        }
    },
);