/**
 * Implements the Countdown block
 *
 * Equivalent to [bw_countdown]
 * The block is dynamic, but cannot be Server Side Rendered since
 * the original implementation generates inline jQuery and CSS which isn't enqueued in REST processing.
 *
 * @copyright (C) Copyright Bobbing Wide 2019, 2020, 2021
 * @author Herb Miller @bobbingwide
 */
import './style.scss';
import './editor.scss';

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
	TextareaControl,
	SelectControl } from '@wordpress/components';
import { Fragment} from '@wordpress/element';
import { map, partial } from 'lodash';

import { transforms } from './transforms.js';

/**
 * Register the Countdown timer
 */
registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik/countdown',
    {
		transforms,

        edit: props => {
			const { attributes, setAttributes, instanceId, focus, isSelected } = props;
			const { textAlign, label } = props.attributes;
			const blockProps = useBlockProps( {
				className: clsx( {
					[ `has-text-align-${ textAlign }` ]: textAlign,
				} ),
			} );
					
					const onChangeSince = ( event ) => {
						props.setAttributes( { since: event } );
					};
					
					const onChangeUntil = ( event ) => {
						props.setAttributes( { until: event } );
					};
					
					
					const onChangeURL = ( event ) => {
						props.setAttributes( { url: event } );
					};
					
					const onChangeDescription = ( event ) => {
						props.setAttributes( { description: event } );
					};
					
					const onChangeExpiryText = ( event ) => {
						props.setAttributes( { expirytext: event } );
					};
					
					const onChangeFormat = ( event ) => {
						props.setAttributes( { format: event } );
					};
					
					// For the time being we'll show the generated shortcode.
					var atts = props.attributes;
					var chatts = '[bw_countdown'; 	
					for (var key of Object.keys( atts )) {
						var value = atts[key];
						if ( value ) {
							chatts = chatts + " " + key + "=\"" + value + '"';
						}
					}
					chatts = chatts + ']';
					
          return (
          	<Fragment>
						
  					  <InspectorControls>
							<PanelBody>
								<PanelRow>
								<TextControl label={__("Since","oik")} value={props.attributes.since} onChange={onChangeSince} />
								</PanelRow>
								<PanelRow>
						  		<TextControl label={__("Until", "oik" )}value={props.attributes.until} onChange={onChangeUntil} />
								</PanelRow>
								<PanelRow>
								<TextControl label={__("URL", "oik" )}value={props.attributes.url} onChange={onChangeURL} />
								</PanelRow>
								<PanelRow>
                					<TextControl label={__("Description", "oik" )}value={props.attributes.description} onChange={onChangeDescription} />
								</PanelRow>
						  		<PanelRow>
								<TextControl label={__("Expiry Text", "oik" )}value={props.attributes.expirytext} onChange={onChangeExpiryText}  />
						  		</PanelRow>
						  		<PanelRow>
						 		<TextControl label={__("Format", "oik" )}value={props.attributes.format} onChange={onChangeFormat} />
						  		</PanelRow>
							 </PanelBody>

					
					

          	</InspectorControls>
				<div {...blockProps} >

					<p>{__("The Countdown block will be rendered on the front end.","oik" )} <br />{__("Shortcode equivalent:", "oik" )}
					<br />
				{chatts}
					</p>
				</div>

			</Fragment>
			);
		},

		save() {
			// Rendering in PHP
			return null;
		}
    }
);
