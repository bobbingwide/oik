/**
 * Implements Google Maps shortcode block
 * 
 * Uses [bw_show_googlemap] shortcode.
 *
 * @copyright (C) Copyright Bobbing Wide 2018-2020
 * @author Herb Miller @bobbingwide
 */

import './style.scss';
import './editor.scss';
import { transforms } from './transforms.js';

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;
// Get registerBlockType and Editable from wp.blocks
const { registerBlockType, Editable } = wp.blocks;
// Set the header for the block since it is reused
const blockHeader = <h3>{ __( 'Map' ) }</h3>;

//var TextControl = wp.blocks.InspectorControls.TextControl;

/**
 * Register e
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik/googlemap',
    {
        // Localize title using wp.i18n.__()
        title: __( 'Google Maps' ),
				
		description: 'Displays a Google Maps map using oik options',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'location',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Google Maps' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: {
					
        },
        transforms,


        edit: props => {
          const onChangeInput = ( event ) => {
            props.setAttributes( { issue: event.target.value } );
						bit = 'bit'; 
						props.setAttributes( { bit: bit } );
          };
					
					//const focus = ( focus ) => {
					 	//props.setAttributes( { issue: 'fred' } );
					//};
					
          return (
            <div className={ props.className }>
							{blockHeader}
							<p>This is where the map will appear</p>
            </div>
          );
        },
        save: props => {
					// console.log( props );
					//var shortcode =  {props.attributes.issue} ;
					var lsb = '[';
					var rsb = ']';
          return (
            <div>
						{blockHeader}
						{lsb}
						bw_show_googlemap
						{rsb}
            </div>
          );
        },
    },
);
