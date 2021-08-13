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
 * @copyright (C) Copyright Bobbing Wide 2018-2021
 * @author Herb Miller @bobbingwide
 */
import './style.scss';
import './editor.scss';

import { __ } from '@wordpress/i18n';
import classnames from 'classnames';

import { registerBlockType, createBlock } from '@wordpress/blocks';
import {AlignmentControl, BlockControls, InspectorControls, useBlockProps, PlainText, BlockIcon} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import {
	Toolbar,
	PanelBody,
	PanelRow,
	FormToggle,
	TextControl,
	TextareaControl,
	SelectControl } from '@wordpress/components';
import { Fragment} from '@wordpress/element';
import { map, partial } from 'lodash';

import { bw_shortcodes, getAttributes, bw_shortcodes_attrs } from './bw_shortcodes';
//import GenericAttrs from './GenericAttrs';

/**
 * Register the oik/shortcode-block block
 * 
 * registerBlockType is a function which takes the name of the block to register
 * and an object that contains the properties of the block.
 * Some of these properties are objects and others are functions
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
		'oik/shortcode-block',
    {
		edit: props => {
				const { attributes, setAttributes, instanceId, focus, isSelected } = props;
				const { textAlign, label } = props.attributes;
				const blockProps = useBlockProps( {
					className: classnames( {
						[ `has-text-align-${ textAlign }` ]: textAlign,
					} ),
				} );

				const onChangeContent = ( value ) => {
					setAttributes( { content: value } );
				};

				const onChangeParameters = ( value ) => {
					setAttributes( { parameters: value } );
				}
				
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
				return (
					<Fragment>
				
  					  <InspectorControls>
								<PanelBody>
									<SelectControl label="Shortcode" value={attributes.shortcode}
										options={ map( bw_shortcodes, ( key, label ) => ( { value: label, label: label + ' - ' + key } ) ) }
										onChange={partial( onChangeAttr, 'shortcode' )}
									/>
									<TextareaControl label="Parameters"
													 value={ attributes.parameters }
													 placeholder={ __( 'Enter your shortcode parameters' ) }
													 onChange={onChangeParameters}
													 rows="1"
									/>
									<TextareaControl label="Content"
													 value={ attributes.content }
													 placeholder={ __( 'Enter your shortcode content' ) }
													 onChange={onChangeContent}
									/>

									
								</PanelBody>
              </InspectorControls>
									

					<div className="wp-block-oik-block-shortcode wp-block-shortcode">
						<SelectControl label="Shortcode" value={attributes.shortcode}
										options={ map( bw_shortcodes, ( key, label ) => ( { value: label, label: label + ' - ' + key } ) ) }
										onChange={partial( onChangeAttr, 'shortcode' )}
									/>



					</div>
					<div {...blockProps}>
						<ServerSideRender
							block="oik/shortcode-block" attributes={ attributes }
						/>
					</div>
					</Fragment>
				 					
			);
			},
				

		/**
		 * We intend to render this dynamically. The content created by the user
		 * is stored in the content attribute. 
		 * 
		 */
		save( { attributes } ) {
			return null;
		}
	}
);