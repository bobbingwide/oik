/**
 * Implements [bw_address] as a server rendered block
 *
 * - Depends on oik
 * -
 *
 * @copyright (C) Copyright Bobbing Wide 2018,2019, 2020
 * @author Herb Miller @bobbingwide
 */

import './style.scss';
import './editor.scss';

//import Input from './input';
//import TextControl from '@wordpress/components-text

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;
// Get registerBlockType and Editable from wp.blocks
const { registerBlockType, Editable } = wp.blocks;

const {
    ServerSideRender,
} = wp.editor;
const {
    InspectorControls,
} = wp.blockEditor;

const {
    Toolbar,
    PanelBody,
    PanelRow,
    FormToggle,
    TextControl,
    SelectControl,
} = wp.components;
const Fragment = wp.element.Fragment;
import { map, partial } from 'lodash';
// Set the header for the block since it is reused
const blockHeader = <h3>{ __( 'Address' ) }</h3>;

//var TextControl = wp.blocks.InspectorControls.TextControl;
/**
 * These are the different options for the tag attr
 */
const tagOptions =
    { "div": "Block",
        "span": "Inline",

    };

/**
 * Register e
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik-block/address',
    {
        // Localize title using wp.i18n.__()
        title: __( 'Address' ),
				
		description: 'Displays your address',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'building',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Address' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: {
            tag: {
                type: 'string',
                default: 'div',
            }

					
        },
        example: {
        },

        supports: {
            customClassName: false,
            className: false,
            html: false,
        },

        edit: props => {
            function onChangeAttr( key, value ) {
                props.setAttributes( { [key] : value } );
            };


					
          return (

              <Fragment>
              <InspectorControls >
              <PanelBody>
            <PanelRow>
                <SelectControl label="Display" value={props.attributes.tag}
                                     options={ map( tagOptions, ( key, label ) => ( { value: label, label: key } ) ) }
                                     onChange={partial( onChangeAttr, 'tag' )}
            />

            </PanelRow>
                  <PanelRow>
                      Equivalent shortcode<br />
                      &#91;bw_address tag={props.attributes.tag}&#93;
                  </PanelRow>
            </PanelBody>

        </InspectorControls>

        <ServerSideRender
            block="oik-block/address" attributes={ props.attributes }
            />
        </Fragment>
          );
        },
        saver: props => {
					// console.log( props );
					//var shortcode =  {props.attributes.issue} ;
					var lsb = '[';
					var rsb = ']';
          return (
            <div>
						{blockHeader}
						{lsb}
						bw_address
						{rsb}
            </div>
          );
        },
        save() {
                  return null;
        }
    },
);
