/**
 * Implements the oik shortcodes block
 *
 * A block that allows selection of a number of shortcodes
 * and behaves a little like the shortcodes dialog.
 *
 * Version 1 will just do the shortcodes that don't require parameters except possibly
 * - alt=0|1
 * - tag=div|span
 *
 *
 * @copyright (C) Copyright Bobbing Wide 2018-2020
 * @author Herb Miller @bobbingwide
 */
import './style.scss';
import './editor.scss';

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;
// Get registerBlockType and Editable from wp.blocks
const { 
	registerBlockType,
} = wp.blocks;

const { 
	Editable,
	PlainText,
  AlignmentToolbar,
  BlockControls,
 } = wp.editor;
const {
	InspectorControls,
} = wp.blockEditor;
	 
const {
  Toolbar,
  Button,
  Tooltip,
  PanelBody,
  PanelRow,
  FormToggle,
	TextControl,
	SelectControl,
} = wp.components;

const {
	withInstanceId,
} = wp.compose;	

const Fragment = wp.element.Fragment;
const RawHTML = wp.element.RawHTML;

//var TextControl = wp.blocks.InspectorControls.TextControl;

import { bw_shortcodes, getAttributes, bw_shortcodes_attrs } from './bw_shortcodes';
//import GenericAttrs from './GenericAttrs';

import { map, partial, has } from 'lodash';

const shortcode_attributes =
{
					shortcode: {
						type: 'string',
						default: '',
					},
					
					content: {
						type: 'string',
						default: '',
					},
					
					selector: {
						type: 'string',
						default: '',
					},
					
					post_type: {
						type: 'string',
						default: '',
						values: {},
					}
};	

 


/**
 * Register the oik-block/shortcode-block block
 * 
 * registerBlockType is a function which takes the name of the block to register
 * and an object that contains the properties of the block.
 * Some of these properties are objects and others are functions
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
		'oik/shortcode-block',
    {
        // Localize title using wp.i18n.__()
        title: __( 'Shortcode block for oik-shortcodes' ),
				
		description: 'Expands oik shortcodes',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'layout',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'shortcode',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Shortcode' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
				// The shortcode should be displayed as a select list 
				// with text override. a la? 
				
				// We can't set a default for the shortcode since the attribute is not created when it's the default value
				// This can probably be used to our advantage if we expect the default value to come from options.
				
        attributes: shortcode_attributes,
				
		supports: {
			customClassName: false,
			className: false,
			html: false,
		},
			
		edit: withInstanceId(
			( { attributes, setAttributes, instanceId, isSelected } ) => {
				const inputId = `blocks-shortcode-input-${ instanceId }`;
				
				
				const onChangeContent = ( value ) => {
					setAttributes( { content: value } );
				};
				
				const onChangeShortcode = ( value ) => {
					
					attributes = getAttributes( value ); 			
					setAttributes( { shortcode: value } );
				};
				
				
					function onChangeAttr( key, value ) {
						//var nextAttributes = {};
						//nextAttributes[ key ] = value;
						//setAttributes( nextAttributes );
						setAttributes( { [key] : value } );
					};
				

				 /*
									<GenericAttrs value={attributes.shortcode} />
				 */
				return [
				
  					  <InspectorControls>
								<PanelBody>
									<TextControl label="Shortcode" value={attributes.shortcode} onChange={onChangeShortcode} />
									
																	
									<SelectControl label="" value={attributes.shortcode}
										options={ map( bw_shortcodes, ( key, label ) => ( { value: label, label: key } ) ) }
										onChange={partial( onChangeAttr, 'shortcode' )}
									/>
									<SelectControl label="Post Type" value={attributes.post_type} 
										options={ map( bw_shortcodes_attrs.bw_posts.post_type, ( key, label ) => ( { value: label, label: key } ) ) }
										onChange={partial( onChangeAttr, 'post_type' )}
									/>
									
								</PanelBody>
              </InspectorControls>
									
						,
					<div className="wp-block-oik-block-shortcode wp-block-shortcode">
						<SelectControl label="Shortcode" value={attributes.shortcode}
										options={ map( bw_shortcodes, ( key, label ) => ( { value: label, label: key } ) ) }
										onChange={partial( onChangeAttr, 'shortcode' )}
									/>

						<PlainText
							id={ inputId }
							value={ attributes.content }
							placeholder={ __( 'Enter your shortcode content' ) }
							onChange={onChangeContent}
						/>
					</div>
				 					
				];
			}
		),
				

		/**
		 * We intend to render this dynamically. The content created by the user
		 * is stored in the content attribute. 
		 * 
		 */
		save( { attributes } ) {
			return null;
		},
	},
);

